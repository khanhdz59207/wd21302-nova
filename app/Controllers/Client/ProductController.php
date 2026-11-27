<?php

namespace App\Controllers\Client;

use App\Core\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Review;
use Exception;

class ProductController extends Controller {
    public function list(): void {
        $productModel = new Product();
        $categoryModel = new Category();

        $page = (int)$this->request->query('page', 1);
        $filters = Product::filtersFromQuery($_GET);
        $products = $productModel->getListing($filters, $page, DEFAULT_PAGE_SIZE);
        $categories = $categoryModel->getActive();

        $this->render('client/product/list', [
            'title'        => 'Tất cả sản phẩm - Nova Shop',
            'products'     => $products,
            'categories'   => $categories,
            'filters'      => $filters,
            'brandFacets'  => $productModel->getBrandFacets($filters),
            'priceFacets'  => $productModel->getPriceFacets($filters),
            'filterAction' => base_url('products'),
            'hiddenFilters' => [],
            'activeCategoryId' => null,
            'isSearch'     => false,
        ], 'client');
    }

    public function detail(string|int $id): void {
        $productModel = new Product();
        $reviewModel = new Review();

        $product = $productModel->getWithCategory((int)$id);
        if (!$product || $product['status'] != 1) {
            throw new Exception("Sản phẩm không tồn tại hoặc đã ngừng kinh doanh.", 404);
        }

        $productModel->incrementViews((int)$id);
        $reviews = $reviewModel->getByProduct((int)$id);

        $galleryImages = $productModel->getGalleryImages((int)$id);
        if (empty($galleryImages)) {
            $galleryImages = [[
                'image' => $product['image'],
                'is_main' => 1,
                'sort_order' => 0,
            ]];
        }

        $this->render('client/product/detail', [
            'title'           => $product['name'] . ' - Nova Shop',
            'product'         => $product,
            'reviews'         => $reviews,
            'galleryImages'   => $galleryImages,
            'specs'           => $productModel->getSpecs((int)$id),
            'highlights'      => $productModel->getHighlights((int)$id),
            'variants'        => $productModel->getVariants((int)$id),
            'relatedProducts' => $productModel->getRelated((int)$id, (int)$product['category_id'], 4),
        ], 'client');
    }
}

