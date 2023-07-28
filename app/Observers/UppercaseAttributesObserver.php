<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;

class UppercaseAttributesObserver
{
    public function saving(Model $model)
    {
        foreach ($model->getAttributes() as $key => $value) {
            if (is_string($value) && !in_array($key, [
                'password', 
                'email', 
                'contact_email',
                'current_region',
                'current_province',
                'current_municipality',
                'current_barangay',
                'permanent_region',
                'permanent_province',
                'permanent_municipality',
                'permanent_barangay',
                ])) 
            {
                $model->{$key} = strtoupper($value);
            }
        }
    }
}
