<?php

namespace App\Controllers\Client;

use App\Core\Controller;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller {
    public function index(): void {
        $productModel = new Product();
        $categoryModel = new Category();

        $featuredProducts = $productModel->getFeatured(8);
        $latestProducts = $productModel->getLatest(8);
        $categories = $categoryModel->getWithProductCount();

        $bestDeal = null;
        $bestDiscount = 0;
        foreach ($featuredProducts as $product) {
            $hasSale = !empty($product['sale_price'])
                && (float)$product['sale_price'] > 0
                && (float)$product['sale_price'] < (float)$product['price'];
            if (!$hasSale) {
                continue;
            }
            $discount = calculate_discount_percent((float)$product['price'], (float)$product['sale_price']);
            if ($discount > $bestDiscount) {
                $bestDiscount = $discount;
                $bestDeal = $product;
            }
        }
        if ($bestDeal === null && $featuredProducts !== []) {
            $bestDeal = $featuredProducts[0];
        }

        $this->render('client/home/index', [
            'title'            => 'Nova Shop - Laptop, PC & linh kiện chính hãng',
            'featuredProducts' => $featuredProducts,
            'latestProducts'   => $latestProducts,
            'categories'       => $categories,
            'bestDeal'         => $bestDeal,
            'bestDiscount'     => $bestDiscount,
        ], 'client');
    }
}
