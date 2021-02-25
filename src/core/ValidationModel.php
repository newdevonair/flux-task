<?php

declare(strict_types=1);

namespace Core;

class ValidationModel
{
    private array $validations;
    private array $error_messages;
    private array $request_data;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @return bool
     */
    public function validate(): bool
    {
        foreach ($this->validations as $field_name => $validations) {
            if (isset($validations['required']) && $validations['required'] === true && empty($this->request_data[$field_name])) {
                $this->error_messages[$field_name][] = "Value is required";
                continue;
            }
            $this->validateNumbers($field_name, $this->request_data[$field_name], $validations['min'], $validations['max']);
        }

        return !isset($this->error_messages);
    }

    /**
     * @param array $validations
     */
    public function setValidations(array $validations): void
    {
        $this->validations = $validations;
    }


    private function validateNumbers(string $field_name, $values, int $min, int $max): void
    {
        if (!is_int($values)) {
            $this->error_messages[$field_name][] = "Value should be integer";
        }
        if ($values > $max) {
            $this->error_messages[$field_name][] = "Value can not be grather then {$max}";
        }
        if ($values < $max) {
            $this->error_messages[$field_name][] = "Value can not be smaller then {$min}";
        }
    }

    /**
     * @param array $request_data
     */
    public function setRequestData(array $request_data): void
    {
        $this->request_data = $request_data;
    }

    /**
     * @return array
     */
    public function getErrorMessages(): array
    {
        return $this->error_messages;
    }
}
