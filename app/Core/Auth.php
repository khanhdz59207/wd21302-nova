<?php

namespace App\Core;

class Auth {
    protected const SESSION_USER_KEY = '_auth_user';

    public static function login(array $user): void {
        Session::regenerate();
        // Không lưu password hash vào session
        unset($user['password']);
        Session::set(self::SESSION_USER_KEY, $user);
    }

    public static function logout(): void {
        Session::remove(self::SESSION_USER_KEY);
        Session::regenerate();
    }

    public static function check(): bool {
        return Session::has(self::SESSION_USER_KEY);
    }

    public static function user(): ?array {
        return Session::get(self::SESSION_USER_KEY);
    }

    public static function id(): ?int {
        $user = self::user();
        return $user['id'] ?? null;
    }

    public static function role(): ?string {
        $user = self::user();
        return $user['role'] ?? null;
    }

    public static function isAdmin(): bool {
        return self::check() && self::role() === ROLE_ADMIN;
    }

    public static function isCustomer(): bool {
        return self::check() && self::role() === ROLE_CUSTOMER;
    }
}

