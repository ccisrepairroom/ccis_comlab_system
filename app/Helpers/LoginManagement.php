<?php

namespace App\Helpers;

class LoginManagement
{
    public static function rememberUser($email, $password, $remember)
    {
        if ($remember) {
            // Store credentials in cookies for 30 days
            cookie()->queue('remember_email', $email, 43200); 
            cookie()->queue('remember_password', $password, 43200); 
        } else {
            // Forget the cookies
            cookie()->queue(cookie()->forget('remember_email'));
            cookie()->queue(cookie()->forget('remember_password'));
        }
    }

    public static function getRememberedCredentials()
    {
        return [
            'email' => request()->cookie('remember_email', ''),
            'password' => request()->cookie('remember_password', '')
        ];
    }
}
