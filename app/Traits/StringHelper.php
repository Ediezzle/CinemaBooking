<?php

namespace App\Traits;

trait StringHelper
{
    public function generateRandomAlphaNumericString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomAlphaNumericString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomAlphaNumericString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomAlphaNumericString;
    }
}
