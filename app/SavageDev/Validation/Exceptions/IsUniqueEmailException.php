<?php

namespace SavageDev\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class IsUniqueEmailException extends ValidationException
{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => 'That {{name}} is already taken.',
        ]
    ];
}
