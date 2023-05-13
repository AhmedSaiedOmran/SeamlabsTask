<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CalculateStepsArrayValidation implements ValidationRule
{
    public $NSize;

    public function __construct($NSize)
    {
        $this->NSize = $NSize;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $valuesArray = explode(",", $value);
        if (count($valuesArray) != $this->NSize) {
            $fail('Size of Array Q not Match N');
        }
        if (!(count($valuesArray) >= 1 &&  count($valuesArray) <= 99999)) {
            $fail('The allowed elements count from: 1, to: 99999');
        }
        for ($i = 0; $i < count($valuesArray); $i++) {
            if (!($valuesArray[$i] >= 0 &&  $valuesArray[$i] <= 99999)) {
                $fail('The allowed elements range from: 1, to: 99999, element with index [' . $i . '] exceed the range');
            }
        }
    }
}
