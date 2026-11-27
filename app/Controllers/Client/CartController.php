<?php

namespace App\Controllers\Client;

use App\Core\Controller;
use App\Core\Flash;
use App\Models\Cart;
use App\Models\Product;

class CartController extends Controller {
    public function index(): void {
        $cartItems = Cart::get();
        $totalPrice = Cart::totalPrice();
        $totalCount = Cart::totalCount();

        $this->render('client/cart/index', [
            'title'      => 'Giỏ hàng - Nova Shop',
            'cartItems'  => $cartItems,
            'totalPrice' => $totalPrice,
            'totalCount' => $totalCount,
        ], 'client');
    }

    public function add(): void {
        $productId = (int)$this->request->post('product_id');
        $quantity = max(1, (int)$this->request->post('quantity', 1));
        $variantId = (int)$this->request->post('variant_id', 0);

        $productModel = new Product();
        $product = $productModel->find($productId);

        if (!$product || $product['status'] != 1) {
            Flash::error('Sản phẩm không hợp lệ.');
            $this->back();
        }

        $variant = null;
        if ($variantId > 0) {
            $variant = $productModel->getVariant($variantId, $productId);
            if (!$variant) {
                Flash::error('Phiên bản sản phẩm không hợp lệ.');
                $this->back();
            }
        }

        Cart::add($product, $quantity, $variant);
        Flash::success("Đã thêm '{$product['name']}' vào giỏ hàng!");

        if ($this->request->isAjax()) {
            $this->json([
                'success'    => true,
                'message'    => "Đã thêm '{$product['name']}' vào giỏ hàng!",
                'totalCount' => Cart::totalCount(),
                'totalPrice' => Cart::totalPrice()
            ]);
        }

        $this->redirect('cart');
    }

    public function update(): void {
        $cartKey = (string)$this->request->post('cart_key', $this->request->post('product_id', ''));
        $quantity = (int)$this->request->post('quantity', 1);

        Cart::update($cartKey, $quantity);
        Flash::success('Giỏ hàng đã được cập nhật.');

        if ($this->request->isAjax()) {
            $this->json([
                'success'    => true,
                'totalCount' => Cart::totalCount(),
                'totalPrice' => Cart::totalPrice()
            ]);
        }

        $this->redirect('cart');
    }

    public function remove(): void {
        $cartKey = (string)$this->request->post('cart_key', $this->request->post('product_id', ''));
        Cart::remove($cartKey);
        Flash::info('Đã xóa sản phẩm khỏi giỏ hàng.');

        $this->redirect('cart');
    }

    public function clear(): void {
        Cart::clear();
        Flash::info('Đã làm trống giỏ hàng.');
        $this->redirect('cart');
    }
}

