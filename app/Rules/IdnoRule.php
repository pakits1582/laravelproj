<?php
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class IdnoRule implements Rule
{
    public function passes($attribute, $value)
    {
        // Check if the value is numeric and its length is between 8 and 10 characters
        return is_numeric($value) && strlen($value) >= 8 && strlen($value) <= 10;
    }

    public function message()
    {
        return 'The :attribute must be a numeric value minimum of 8 and maximum 10 characters.';
    }
}
