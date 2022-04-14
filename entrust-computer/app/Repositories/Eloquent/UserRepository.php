<?php 
namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\IUser;
use App\Repositories\Eloquent\BaseRepository;

class UserRepository extends BaseRepository implements IUser 
{
    public function model()
    {
        return User::class;
    }

    public function checkUserPhone($userPhone)
    {
        return $this->model
                    ->where('phone_number', $userPhone)
                    ->pluck('isVerified');
    }

    public function checkPhoneAvailability($phoneNumber)
    {
        return (bool)$this->model
                    ->where('phone_number', $phoneNumber)
                    ->count();
    }

    public function checkUserNameAvailability($username)
    {
        return (bool)$this->model
                    ->where('username', $username)
                    ->count();
    }

    public function getUserPhoneNumber($username)
    {
        return $this->model
                    ->where('username', $username)
                    ->pluck('phone_number');
    }

}