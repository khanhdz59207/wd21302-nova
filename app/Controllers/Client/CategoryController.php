<?php

namespace App\Controllers\Client;

use App\Core\Controller;
use App\Models\Category;
use App\Models\Product;
use Exception;

class CategoryController extends Controller {
    public function show(string|int $id): void {
        $categoryModel = new Category();
        $productModel = new Product();

        $category = is_numeric($id)
            ? $categoryModel->find((int)$id)
            : $categoryModel->findBy('slug', $id);

        if (!$category || $category['status'] != 1) {
            throw new Exception("Danh mục không tồn tại hoặc đã bị ẩn.", 404);
        }

        $page = (int)$this->request->query('page', 1);
        $filters = Product::filtersFromQuery($_GET);
        $filters['category_id'] = (int)$category['id'];
        $products = $productModel->getListing($filters, $page, DEFAULT_PAGE_SIZE);
        $categories = $categoryModel->getActive();

        $this->render('client/category/show', [
            'title'            => $category['name'] . ' - Nova Shop',
            'category'         => $category,
            'products'         => $products,
            'categories'       => $categories,
            'filters'          => $filters,
            'brandFacets'      => $productModel->getBrandFacets($filters),
            'priceFacets'      => $productModel->getPriceFacets($filters),
            'filterAction'     => base_url('category/' . $category['id']),
            'hiddenFilters'    => [],
            'activeCategoryId' => (int)$category['id'],
            'isSearch'         => false,
        ], 'client');
    }
}

