<?php

namespace App\Models;

use App\Core\Model;

class User extends Model {
    protected string $table = 'users';
    protected array $fillable = [
        'name', 'email', 'password', 'phone', 'address', 'role', 'status', 'avatar'
    ];

    public function findByEmail(string $email): ?array {
        return $this->findBy('email', $email);
    }

    public function createWithHash(array $data): int {
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }
        return $this->create($data);
    }

    public function verifyPassword(string $inputPassword, string $hash): bool {
        return password_verify($inputPassword, $hash);
    }
}

