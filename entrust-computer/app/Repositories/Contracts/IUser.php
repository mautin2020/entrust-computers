<?php 

namespace App\Repositories\Contracts;

interface IUser 
{
    public function checkUserPhone($userPhone);
    public function checkPhoneAvailability($phoneNumber);
    public function getUserPhoneNumber($username);
    public function checkUserNameAvailability($username);
}