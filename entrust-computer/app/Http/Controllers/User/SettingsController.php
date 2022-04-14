<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Twilio\Rest\Client;
use Illuminate\Http\Request;
use App\Rules\CheckOldPassword;
use App\Rules\MatchOldPassword;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Repositories\Contracts\IUser;

class SettingsController extends Controller
{
    protected $users;

    public function __construct(IUser $users)
    {
        $this->users = $users;   
    }

    public function updateProfile(Request $request)
    {      
        $this->validate($request, [
            'first_name' => ['required', 'string', 'max:25'],
            'last_name' => ['required', 'string', 'max:25'],
            'username' => ['required', 'string', 'max:15', 'alpha_dash'],
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);
       
        $user = $this->users->update(auth()->id(),[
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
       ]);

       return new UserResource($user);
    }

    public function updatePassword(Request $request)
    {
        // current password
        // new password
        // password confirmation
            $this->validate($request, [
            'current_password' => ['required', new MatchOldPassword],
            'password' => ['required', 'confirmed', 'min:8', new CheckOldPassword],
        ]);

        $request->user()->update([
            'password' => bcrypt($request->password)
        ]);

        return response()->json(['message' => 'Password updated'], 200);
    }

    public function addProfilePhoto(Request $request)
    {
        $user = auth()->user();

        // validate the request
        $this->validate($request, [
            'profile_photo'=>['required', 'mimes:jpeg,gif,bmp,png', 'max:2048'],
        ]);

        // get the image 
        $profile_photo = $request->file('profile_photo');

        // get the original file name and replace any spaces with _
        $filename = time()."_". preg_replace('/\s+/', '_', strtolower($profile_photo->getClientOriginalName()));
        $tmp = $profile_photo->storeAs('uploads'. DIRECTORY_SEPARATOR . 'users', $filename, 'public');
       
        $user = $this->users->update(auth()->id(),[
            'profile_photo' => $filename
        ]);

        return new UserResource($user);
    }

    public function sendResetPasswordCode(Request $request)
    {
        $data = $request->validate([
            'phone_number' => ['required', 'string'],
        ]);

        // Check if phone number is available in database
        $user = User::where('phone_number', $request->phone_number)->first();

        if(! $user){
            return response()->json(["errors" => [
                "phone" => "No user could be found with this phone number"
            ]], 422);
        }

        /* Get credentials from .env */ 
        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_sid = getenv("TWILIO_SID");
        $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
        $twilio = new Client($twilio_sid, $token);
        $twilio->verify->v2->services($twilio_verify_sid)
            ->verifications
            ->create($data['phone_number'], "sms");

        return response()->json(['message' => 'Verification code successfully resent'],
        200);
    }


    public function resetPassword(Request $request)
    {
        $this->validate($request, [
            'verification_code' => ['required', 'numeric'],
            'phone_number' => ['required', 'string'],
            'password' => ['required', 'confirmed', 'min:8']
        ]);

        $phoneNumberAvailability = $this->users->checkPhoneAvailability($request->phone_number);
        $phoneVerification = $this->users->checkUserPhone($request->phone_number);

        $user = User::where('phone_number', $request->phone_number)->first();

        if(! $user){
            return response()->json([
                'message' => 'Number not available in database'
            ], 404);
        }

          if($phoneNumberAvailability){
            $token = getenv("TWILIO_AUTH_TOKEN");
            $twilio_sid = getenv("TWILIO_SID");
            $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
            $twilio = new Client($twilio_sid, $token);
            $verification = $twilio->verify->v2->services($twilio_verify_sid)
                ->verificationChecks
                ->create($request['verification_code'], array('to' => $request['phone_number']));

                if ($verification->valid) {
                    $user = tap(User::where('phone_number', $request['phone_number']))->update(['password' => bcrypt($request->password)]);
                    
                    return response()->json(['message' => 'Password reset proceed to login'],
                    200);
                }
                return response()->json(["errors" => [
                    "message" => "Invalid Verification code entered!"
                    ]], 422);
        } else {
            return response()->json([
                'message' => 'Phone Number not available in our database'
            ], 422);
        }
    }

}
