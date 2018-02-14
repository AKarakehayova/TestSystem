<?php

namespace App\Exceptions;

class Exceptions
{
    const DUPLICATE_ENTRY_CODE = 1062;
    const PDO_CODE_INDEX = 1;

    const DUPLICATE_ENTRY_ERROR_MESSAGE = 'There is already such entry in the database';

    public static function handle($exception) {

        if(!empty($exception->errorInfo[self::PDO_CODE_INDEX]) && $exception->errorInfo[self::PDO_CODE_INDEX] == self::DUPLICATE_ENTRY_CODE) {
            return ['error' => true, 'code' => self::DUPLICATE_ENTRY_CODE];
        }
    }
}
