<?php

namespace PlicniTeplice\Recipes\Api\Core\Validations;

class RecipeRequestValidator extends BaseValidator
{

    public function isValid(object $data): ValidationResult {
        if($this->isEmpty($data)){
            return ValidationResult::invalid('Nebyla zadána žádná data');
        }

        $validationResult = $this->isRequiredAll($data, [
            'name' => 'Nezadal jste jméno',
            'phone' => 'Nezadal jste telefonní číslo',
            'birthDate' => 'Nezadal jste datum narození',
            'items' => 'Nezadal jste léky'
        ]);

        if(!$validationResult->isValid())
            return $validationResult;

        if(!$this->isDateInPast($data->birthDate))
            $validationResult->addError('Chybně zadané datum narození');

        if(!$this->isPhoneValid($data->phone))
            $validationResult->addError('Chybně zadané telefonní číslo');

        return $validationResult;
    }
}