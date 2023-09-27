<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class AllChoicesSelected implements Rule
{
    public function passes($attribute, $value)
    {
        foreach ($value as $groupId => $choices) {
            // Check if at least one choice in this group is selected
            if (!is_array($choices) || empty(array_filter($choices))) {
                return false;
            }
        }

        return true;
    }

    public function message()
    {
        return 'Each group of radio buttons must have at least one option selected.';
    }
}
