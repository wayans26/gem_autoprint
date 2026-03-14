<?php

namespace App\Http\Utils;

use Ramsey\Uuid\Uuid;
use Hash;

class makeid
{
    public static function createUuid()
    {
        return Uuid::uuid4()->getHex();
    }
    public static function createToken()
    {
        return Hash::make(Uuid::uuid4()->getHex());
    }

    public static function createId($length)
    {
        return str()->random($length);
    }
    public static function createNumber($length)
    {
        $number = "0123456789";
        $random = "";
        for ($i = 1; $i <= $length; $i++) {
            $random .= substr($number, rand(0, 10), 1);
        }
        return $random;
    }
}
