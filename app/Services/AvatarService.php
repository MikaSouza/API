<?php

namespace App\Services;

use Laravolt\Avatar\Avatar;




class AvatarService
{

    public function getAvatar()
    {
        $name = auth()->user()->name;
        $surname = auth()->user()->surname;
        $avatar = new Avatar();
        
        return $avatar->create($name.' '.$surname)->toBase64();    
    }
}
