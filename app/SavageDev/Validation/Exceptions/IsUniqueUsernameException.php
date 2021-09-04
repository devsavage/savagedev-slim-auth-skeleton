<?php
namespace SavageDev\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class IsUniqueUsernameException extends ValidationException
{
    protected $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => "That {{name}} is already taken.",
        ]
    ];
}
