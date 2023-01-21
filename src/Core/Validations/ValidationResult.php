<?php

namespace PlicniTeplice\Recipes\Api\Core\Validations;

final class ValidationResult
{
    private bool $isValid;
    private array $errors;

    public function __construct(bool $isValid, array $errors) {
        $this->isValid = $isValid;
        $this->errors = $errors;
    }

    public function isValid(): bool {
        return $this->isValid;
    }

    public function getErrors(): array {
        return $this->errors;
    }

    public function addError(string $error){
        $this->isValid = false;
        $this->errors[] = $error;
    }

    public static function invalid(string $errors): ValidationResult{
        return new ValidationResult(false, [$errors]);
    }

    public static function valid(): ValidationResult{
        return new ValidationResult(true, []);
    }
}