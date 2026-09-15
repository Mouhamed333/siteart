<?php

declare(strict_types=1);

class Validator
{
    private array $errors = [];
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function required(string $field, string $message = 'Ce champ est requis.'): self
    {
        if (empty(trim($this->data[$field] ?? ''))) {
            $this->errors[$field] = $message;
        }
        return $this;
    }

    public function email(string $field, string $message = 'Email invalide.'): self
    {
        if (!empty($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = $message;
        }
        return $this;
    }

    public function min(string $field, int $min, string $message = ''): self
    {
        $val = $this->data[$field] ?? '';
        if (strlen($val) < $min) {
            $this->errors[$field] = $message ?: "Minimum {$min} caractères requis.";
        }
        return $this;
    }

    public function match(string $field, string $otherField, string $message = 'Les champs ne correspondent pas.'): self
    {
        if (($this->data[$field] ?? '') !== ($this->data[$otherField] ?? '')) {
            $this->errors[$field] = $message;
        }
        return $this;
    }

    public function numeric(string $field, string $message = 'Valeur numérique requise.'): self
    {
        if (!empty($this->data[$field]) && !is_numeric($this->data[$field])) {
            $this->errors[$field] = $message;
        }
        return $this;
    }

    public function passes(): bool
    {
        return empty($this->errors);
    }

    public function fails(): bool
    {
        return !$this->passes();
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function firstError(): ?string
    {
        return $this->errors[array_key_first($this->errors)] ?? null;
    }

    public static function sanitize(string $input): string
    {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
}
