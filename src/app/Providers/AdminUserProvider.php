<?php

namespace erfan_kateb_saber\admin_panel\app\Providers;

use Illuminate\Auth\GenericUser;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Str;

class AdminUserProvider implements UserProvider
{
    const DATA_LOGIN = [
        1=>[
            'username' => 'admin',
            'password' => 'admin'
        ]
    ];
    public function retrieveById($identifier)
    {
        // This method is called from subsequent calls until the session expires.
        //
        // As you don't have a local users database we are going
        // to assume the identifier saved into the session is fine.
        //
        // Session cookies are encrypted by default
        //
        // This avoid calling the external service on every navigation.
        //
        // The downside is that if the user is not authorized anymore
        // in the external service, you won't know until their session expires.
        //
        // Ideally you should set a lower session duration so user
        // gets logged out quickier.
        //
        // An alternative is to save encrypted the user's credentials
        // and call the external service every time.
        //
        // But that would make a external API call on every request,
        // making your app slower. But is the most secure way.
        //
        // If you want I can make an modified version exemplifying
        // how you could do this.
        return new GenericUser([
            'id' => $identifier,
            'username' => $identifier,
            'remember_token' => $identifier
        ]);
    }

    public function retrieveByToken($identifier, $token)
    {
        //unused
//        dd("retrieveByToken");
        return null;
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        //unused
//        dd("updateRememberToken");
    }

    public function retrieveByCredentials(array $credentials)
    {
//        dd("retrieveByCredentials");
        foreach (self::DATA_LOGIN as $id => $property){
            if($credentials["username"] == $property["username"] &&
                $credentials["password"]==$property["password"]){
                // GenericUser is a class from Laravel Auth System
                return new GenericUser([
                    'id' => $id,
                    'username' => $credentials["username"],
                    'password' => $credentials["password"],
                ]);
            }
        }
        return null;


    }

    public function validateCredentials($user, array $credentials)
    {
//        dd("validateCredentials");
        foreach (self::DATA_LOGIN as $id => $property){
            if($credentials["username"] == $property["username"] &&
                $credentials["password"]==$property["password"]){
                return true;
            }
        }
        return false;

        // This is a simplified usage of Laravel's HTTP Client to call the external API
        // You might need to send more info to the external service.
        // Please refer to the HTTP Client docs to learn how to use it properly.
        /*$response = Http::post('https://example.com/authenticate', [
            // $user is the GenericUser instance created in
            // the retrieveByCredentials() method above.
            'email' => $user->username,
            'password' => $credentials['password'],
        ]);*/

    }
}
