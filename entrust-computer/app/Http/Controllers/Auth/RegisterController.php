<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Twilio\Rest\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Contracts\IUser;

class RegisterController extends Controller
{
    protected $users;

    public function __construct(IUser $users)
    {
        $this->users = $users;
    }
    public function register(Request $request)
    {
        $this->validate($request, [
            'first_name' => ['required', 'string', 'max:25'],
            'last_name' => ['required', 'string', 'max:25'],
            'username' => ['required', 'string', 'max:15', 'alpha_dash', 'unique:users,username'],
            'phone_number' => ['required', 'string', 'string', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            
        ]);

        /* Get credentials from .env */ 
        // $tokens = getenv("TWILIO_AUTH_TOKEN");
        // $twilio_sid = getenv("TWILIO_SID");
        // $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
        // $twilio = new Client($twilio_sid, $tokens);
        // $twilio->verify->v2->services($twilio_verify_sid)
        //     ->verifications
        //     ->create($request['phone_number'], "sms");

        $user = $this->users->create([
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'username' => $request['username'],
            'phone_number' => $request['phone_number'],
            'password' => Hash::make($request['password'])
        ]); 

        return new UserResource($user);
    }

    public function verifyPhone(Request $request)
    {
        $this->validate($request, [
            'verification_code' => ['required', 'numeric'],
            'phone_number' => ['required', 'string'],
        ]);

        $phoneNumberAvailability = $this->users->checkPhoneAvailability($request->phone_number);
        $phoneVerification = $this->users->checkUserPhone($request->phone_number);

        $user = User::where('phone_number', $request->phone_number)->first();

        if(! $user){
            return response()->json([
                'message' => 'Number not available in database'
            ], 404);
        }

        if($user->isVerified == 1) {
            return response()->json([
                'message' => 'Phone Number Already Verified!'
            ], 422);
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
                    $user = tap(User::where('phone_number', $request['phone_number']))->update(['isVerified' => true]);
                    
                    return response()->json(['message' => 'Phone number successfully verified'],
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

    public function resendCode(Request $request, User $user)
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

        if($user->isVerified == 1) {
            return response()->json([
                'message' => 'Phone Number Already Verified!'
            ], 422);
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
}