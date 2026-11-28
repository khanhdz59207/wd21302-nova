<?php

namespace App\Core;

class Csrf {
    public static function getToken(): string {
        if (!Session::has('_csrf_token')) {
            Session::set('_csrf_token', bin2hex(random_bytes(32)));
            Session::set('_csrf_token_time', time());
        }
        return Session::get('_csrf_token');
    }

    public static function validate(?string $token): bool {
        $sessionToken = Session::get('_csrf_token');
        if (!$sessionToken || !$token) {
            return false;
        }

        $lifetime = (int)App::config('app.csrf.lifetime', 3600);
        $tokenTime = (int)Session::get('_csrf_token_time', 0);
        if (time() - $tokenTime > $lifetime) {
            self::regenerate();
            return false;
        }

        return hash_equals($sessionToken, $token);
    }

    public static function regenerate(): string {
        $token = bin2hex(random_bytes(32));
        Session::set('_csrf_token', $token);
        Session::set('_csrf_token_time', time());
        return $token;
    }
}

