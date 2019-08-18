<?php

namespace App\Helpers;

class ErrorHelper {

    static function formatErrors($validator)
    {
        $errors = [];
        foreach($validator->errors()->messages() as $key => $value) {
            array_push($errors, $value[0]);
        }
        return $errors;
    }
}
