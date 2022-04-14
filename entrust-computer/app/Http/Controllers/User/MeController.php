<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

class MeController extends Controller
{
    public function getMe()
    {
        if(Auth::check()){
            $user = auth()->user();
            return new UserResource($user);
            //return response()->json(["user" => auth()->user()], 200);
        }
        return response()->json(null, 401);
    }
    
}
