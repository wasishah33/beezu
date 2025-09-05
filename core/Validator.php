<?php

namespace Core;

class ValidationException extends \Exception
{
    private array $errors;

    public function __construct(array $errors)
    {
        parent::__construct('Validation failed');
        $this->errors = $errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}

class Validator
{
    private array $data;
    private array $errors = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Validate input against rules. Returns validated data or throws on failure.
     */
    public function validate(array $rules): array
    {
        foreach ($rules as $field => $ruleString) {
            $value = $this->data[$field] ?? null;
            $rulesArr = is_array($ruleString) ? $ruleString : explode('|', $ruleString);

            foreach ($rulesArr as $rule) {
                $this->applyRule($field, $value, $rule);
            }
        }

        if (!empty($this->errors)) {
            throw new ValidationException($this->errors);
        }

        // Return only validated keys
        $validated = [];
        foreach (array_keys($rules) as $key) {
            if (array_key_exists($key, $this->data)) {
                $validated[$key] = $this->data[$key];
            }
        }
        return $validated;
    }

    private function applyRule(string $field, $value, string $rule): void
    {
        $name = $rule;
        $param = null;

        if (strpos($rule, ':') !== false) {
            [$name, $param] = explode(':', $rule, 2);
        }

        switch ($name) {
            case 'required':
                if ($value === null || $value === '') {
                    $this->addError($field, 'The ' . $field . ' field is required.');
                }
                break;

            case 'email':
                if ($value !== null && $value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($field, 'The ' . $field . ' must be a valid email address.');
                }
                break;

            case 'min':
                $min = (int) $param;
                if (is_string($value) && mb_strlen($value) < $min) {
                    $this->addError($field, 'The ' . $field . ' must be at least ' . $min . ' characters.');
                }
                break;

            case 'max':
                $max = (int) $param;
                if (is_string($value) && mb_strlen($value) > $max) {
                    $this->addError($field, 'The ' . $field . ' may not be greater than ' . $max . ' characters.');
                }
                break;

            case 'confirmed':
                $confirmation = $this->data[$field . '_confirmation'] ?? null;
                if ($value !== $confirmation) {
                    $this->addError($field, 'The ' . $field . ' confirmation does not match.');
                }
                break;

            case 'in':
                $options = array_map('trim', explode(',', (string) $param));
                if ($value !== null && !in_array($value, $options, true)) {
                    $this->addError($field, 'The selected ' . $field . ' is invalid.');
                }
                break;

            case 'numeric':
                if ($value !== null && !is_numeric($value)) {
                    $this->addError($field, 'The ' . $field . ' must be a number.');
                }
                break;

            case 'boolean':
                if ($value !== null && !in_array($value, [true, false, 0, 1, '0', '1'], true)) {
                    $this->addError($field, 'The ' . $field . ' must be a boolean.');
                }
                break;

            // Placeholder for unique rule (requires DB). Users can extend to implement.
            case 'unique':
                // Format: unique:table,column
                // Implement in application-specific validator if desired.
                break;
        }
    }

    private function addError(string $field, string $message): void
    {
        $this->errors[$field][] = $message;
    }
}


