<?php
namespace SavageDev\Validation;

use Respect\Validation\Exceptions\NestedValidationException;

use SavageDev\Lib\Session;

class Validator
{
    protected $errors = [];

    public function validate($request, array $rules)
    {
        foreach ($rules as $field => $rule) {
            try {
                $rule->setName(ucfirst($field))->assert($request->getParsedBody()[$field]);
            } catch (NestedValidationException $e) {
                foreach($e->getIterator() as $message) {
                    $this->errors[$field] = $message->getMessage();
                }
            }
        }
        
        Session::set("errors", $this->errors());

        return $this;
    }

    public function failed()
    {
        return !empty($this->errors);
    }

    public function errors()
    {
        return $this->errors;
    }
}
