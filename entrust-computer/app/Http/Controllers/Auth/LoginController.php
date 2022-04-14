<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Contracts\IUser;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    protected $users;

    public function __construct(IUser $users)
    {
        $this->users = $users;
    }

    protected function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        // $getUserPhoneNumber = $this->users->getUserPhoneNumber($request->username); 

        // $phoneNumberAvailability = $this->users->checkPhoneAvailability($getUserPhoneNumber);
        
        if(!$this->users->checkUserNameAvailability($request->username)){
            return response()->json([
                'username' => 'Invalid credentials!'
            ], 422);
        }
        
        $user = $this->users->findWhereFirst('username', $request['username']);
        
        if($user->isVerified == 0) {
            return response()->json([
                'message' => 'You need to verify your phone!'
            ], 422);
        }

        if(! auth()->attempt($credentials)){
            throw ValidationException::withMessages([
                'username' => 'Invalid credentials'
            ]);
        }

        // $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ],201);

    }

    public function logout(Request $request)
    {
       // Get user who requested the logout
        $user = Auth::user();
        // Revoke current user token
        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();

        return response()->json([
            'message' => 'User Successfully Logged out!'
        ], 200);
    }
}
