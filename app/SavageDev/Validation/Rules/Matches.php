<?php
namespace SavageDev\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;

class Matches extends AbstractRule
{
    protected $comparison;
    protected $identifier;

    public function __construct($comparison, $identifier)
    {
        $this->comparison = $comparison;
        $this->identifier = $identifier;
    }

    public function validate($input)
    {
        return $input === $this->comparison;
    }
}
