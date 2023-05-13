<?php

namespace App\Rules;

use App\Models\Order;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class OrderTypeExist implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!in_array($value, Order::getTypesOfOrder())) {
            $fail('Type is not defined');
        }

    }
}
