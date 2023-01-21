<?php

namespace PlicniTeplice\Recipes\Api\Core\Validations;


use DateTime;

abstract class BaseValidator
{
    public abstract function isValid(object $data): ValidationResult;

    protected function isEmpty(object $data){
        return empty((array) $data);
    }

    protected function isRequired(object $data, array $requiredProperties) : ValidationResult{
        foreach ($requiredProperties as $property => $errorMessage){
            if(!property_exists($data, $property) || empty($data->$property)){
                return ValidationResult::invalid($errorMessage);
            }
        }
        return ValidationResult::valid();
    }

    protected function isRequiredAll(object $data, array $requiredProperties): ValidationResult{
        $result = ValidationResult::valid();
        foreach ($requiredProperties as $property => $errorMessage){
            if(!property_exists($data, $property) || empty($data->$property)){
                $result->addError($errorMessage);
            }
        }
        return $result;
    }

    protected function isValidDate($date, $format = 'Y-m-d'): bool {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    protected function isDateInFuture($date, $format = 'Y-m-d'): bool{
        $opening_date = DateTime::createFromFormat($format, $date);
        return $opening_date > new DateTime();
    }

    protected function isDateInPast($date, $format = 'Y-m-d'): bool{
        $opening_date = DateTime::createFromFormat($format, $date);
        return $opening_date < (new DateTime())->modify('-1 day');
    }

    protected function isPhoneValid(string $phoneNumber): bool{
        $phoneNumber = str_replace(' ', '', $phoneNumber);
        if(strlen($phoneNumber) > 9)
            return false;
        return preg_match('/^(\d{3})(\d{3})(\d{3})$/', $phoneNumber) === 1;
    }

    protected function getPropertyValueSafe(object $object, string $property): ?string{
        return $object->$property ?? '';
    }
}