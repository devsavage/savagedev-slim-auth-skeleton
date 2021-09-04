<?php

namespace SavageDev\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class MatchesCurrentPasswordException extends ValidationException
{
    protected $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => "Password does not match your current password.",
        ]
    ];
}
