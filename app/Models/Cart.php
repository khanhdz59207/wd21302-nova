<?php

namespace App\Models;

use App\Core\Session;

class Cart {
    protected const SESSION_CART_KEY = '_shopping_cart';

    public static function get(): array {
        return Session::get(self::SESSION_CART_KEY, []);
    }

    public static function add(array $product, int $quantity = 1, ?array $variant = null): void {
        $cart = self::get();
        $id = (string)$product['id'];
        $cartKey = $id;
        $variantId = null;
        $variantName = '';
        $variantSku = '';
        $variantPrice = 0.0;

        if ($variant) {
            $variantId = (int)$variant['id'];
            $cartKey = $id . ':' . $variantId;
            $variantName = (string)($variant['name'] ?? '');
            $variantSku = (string)($variant['sku'] ?? '');
            $variantPrice = (float)($variant['price'] ?? 0);
        }

        $price = $variantPrice > 0
            ? $variantPrice
            : ((!empty($product['sale_price']) && $product['sale_price'] > 0)
                ? (float)$product['sale_price']
                : (float)$product['price']);

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $quantity;
        } else {
            $cart[$cartKey] = [
                'cart_key'     => $cartKey,
                'id'           => $product['id'],
                'name'         => $product['name'],
                'slug'         => $product['slug'] ?? '',
                'image'        => $product['image'] ?? '',
                'price'        => $price,
                'quantity'     => $quantity,
                'variant_id'   => $variantId,
                'variant_name' => $variantName,
                'variant_sku'  => $variantSku,
            ];
        }

        Session::set(self::SESSION_CART_KEY, $cart);
    }

    public static function update(string|int $cartKey, int $quantity): void {
        $cart = self::get();
        $cartKey = (string)$cartKey;
        if ($quantity <= 0) {
            unset($cart[$cartKey]);
        } elseif (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] = $quantity;
        }
        Session::set(self::SESSION_CART_KEY, $cart);
    }

    public static function remove(string|int $cartKey): void {
        $cart = self::get();
        unset($cart[(string)$cartKey]);
        Session::set(self::SESSION_CART_KEY, $cart);
    }

    public static function clear(): void {
        Session::remove(self::SESSION_CART_KEY);
    }

    public static function totalCount(): int {
        $cart = self::get();
        $count = 0;
        foreach ($cart as $item) {
            $count += (int)$item['quantity'];
        }
        return $count;
    }

    public static function totalPrice(): float {
        $cart = self::get();
        $total = 0;
        foreach ($cart as $item) {
            $total += (float)$item['price'] * (int)$item['quantity'];
        }
        return $total;
    }
}
