<?php

namespace SavageDev\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class MatchesException extends ValidationException
{
    protected $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => "{{name}} does not match {{identifier}}.",
        ]
    ];
}
