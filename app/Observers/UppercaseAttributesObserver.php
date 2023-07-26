<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;

class UppercaseAttributesObserver
{
    public function saving(Model $model)
    {
        foreach ($model->getAttributes() as $key => $value) {
            if (is_string($value) && !in_array($key, ['password', 'email', 'contact_email'])) {
                $model->{$key} = strtoupper($value);
            }
        }
    }
}
