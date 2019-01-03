<?php

namespace SavageDev\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class MatchesException extends ValidationException
{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => '{{name}} does not match {{identifier}}.',
        ]
    ];
}
