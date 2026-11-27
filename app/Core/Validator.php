<?php

namespace App\Core;

use PDO;

class Validator {
    protected array $errors = [];
    protected array $data = [];

    public function __construct(array $data) {
        $this->data = $data;
    }

    public static function make(array $data, array $rules, array $messages = []): self {
        $validator = new self($data);
        $validator->validate($rules, $messages);
        return $validator;
    }

    public function validate(array $rules, array $messages = []): void {
        foreach ($rules as $field => $ruleString) {
            $ruleList = is_array($ruleString) ? $ruleString : explode('|', $ruleString);
            $value = $this->data[$field] ?? null;
            $isNumericField = in_array('numeric', $ruleList, true);

            foreach ($ruleList as $ruleItem) {
                $params = [];
                if (str_contains($ruleItem, ':')) {
                    [$ruleName, $paramStr] = explode(':', $ruleItem, 2);
                    $params = explode(',', $paramStr);
                } else {
                    $ruleName = $ruleItem;
                }

                $this->applyRule($field, $value, $ruleName, $params, $messages, $isNumericField);
            }
        }
    }

    protected function applyRule(string $field, mixed $value, string $rule, array $params, array $messages, bool $isNumericField = false): void {
        $hasValue = $value !== null && $value !== '';

        switch ($rule) {
            case 'required':
                if (!$hasValue) {
                    $this->addError($field, $messages["$field.required"] ?? "Trường {$field} là bắt buộc.");
                }
                break;

            case 'email':
                if ($hasValue && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($field, $messages["$field.email"] ?? "Trường {$field} phải là email hợp lệ.");
                }
                break;

            case 'min':
                $min = (int)($params[0] ?? 0);
                if ($hasValue) {
                    if ($isNumericField) {
                        if (is_numeric($value) && (float)$value < $min) {
                            $this->addError($field, $messages["$field.min"] ?? "Trường {$field} tối thiểu là {$min}.");
                        }
                    } elseif (is_string($value) && mb_strlen($value) < $min) {
                        $this->addError($field, $messages["$field.min"] ?? "Trường {$field} phải có ít nhất {$min} ký tự.");
                    }
                }
                break;

            case 'max':
                $max = (int)($params[0] ?? 0);
                if ($hasValue) {
                    if ($isNumericField) {
                        if (is_numeric($value) && (float)$value > $max) {
                            $this->addError($field, $messages["$field.max"] ?? "Trường {$field} tối đa là {$max}.");
                        }
                    } elseif (is_string($value) && mb_strlen($value) > $max) {
                        $this->addError($field, $messages["$field.max"] ?? "Trường {$field} không được vượt quá {$max} ký tự.");
                    }
                }
                break;

            case 'numeric':
                if ($hasValue && !is_numeric($value)) {
                    $this->addError($field, $messages["$field.numeric"] ?? "Trường {$field} phải là một số.");
                }
                break;

            case 'confirmed':
                $confirmField = $field . '_confirmation';
                $confirmVal = $this->data[$confirmField] ?? null;
                if ($value !== $confirmVal) {
                    $this->addError($field, $messages["$field.confirmed"] ?? "Xác nhận {$field} không khớp.");
                }
                break;

            case 'unique':
                // unique:table,column,exceptId
                $table = $params[0] ?? '';
                $column = $params[1] ?? $field;
                $exceptId = $params[2] ?? null;

                if ($hasValue && $table) {
                    $db = Database::getInstance();
                    $sql = "SELECT COUNT(*) FROM `{$table}` WHERE `{$column}` = :val";
                    $binds = [':val' => $value];

                    if ($exceptId) {
                        $sql .= " AND `id` != :except_id";
                        $binds[':except_id'] = $exceptId;
                    }

                    $stmt = $db->prepare($sql);
                    $stmt->execute($binds);
                    if ((int)$stmt->fetchColumn() > 0) {
                        $this->addError($field, $messages["$field.unique"] ?? "Giá trị {$field} đã tồn tại trong hệ thống.");
                    }
                }
                break;
        }
    }

    protected function addError(string $field, string $message): void {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = [];
        }
        $this->errors[$field][] = $message;
    }

    public function fails(): bool {
        return !empty($this->errors);
    }

    public function errors(): array {
        return $this->errors;
    }

    public function first(string $field): ?string {
        return $this->errors[$field][0] ?? null;
    }
}

