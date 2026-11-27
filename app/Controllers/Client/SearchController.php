<?php

namespace App\Controllers\Client;

use App\Core\Controller;
use App\Models\Category;
use App\Models\Product;

class SearchController extends Controller {
    public function index(): void {
        $rawKeyword = $this->request->query('q', '');
        $keyword = is_scalar($rawKeyword) ? trim((string)$rawKeyword) : '';
        $page = (int)$this->request->query('page', 1);

        $productModel = new Product();
        $categoryModel = new Category();

        $filters = Product::filtersFromQuery($_GET);
        $filters['keyword'] = $keyword;

        $products = !empty($keyword)
            ? $productModel->getListing($filters, $page, DEFAULT_PAGE_SIZE)
            : ['data' => [], 'total' => 0, 'current_page' => 1, 'total_pages' => 0];

        $categories = $categoryModel->getActive();

        $this->render('client/product/list', [
            'title'            => 'Kết quả tìm kiếm cho: "' . e($keyword) . '" - Nova Shop',
            'products'         => $products,
            'keyword'          => $keyword,
            'categories'       => $categories,
            'filters'          => $filters,
            'brandFacets'      => !empty($keyword) ? $productModel->getBrandFacets($filters) : [],
            'priceFacets'      => !empty($keyword) ? $productModel->getPriceFacets($filters) : [],
            'filterAction'     => base_url('search'),
            'hiddenFilters'    => !empty($keyword) ? ['q' => $keyword] : [],
            'activeCategoryId' => null,
            'isSearch'         => true,
        ], 'client');
    }
}

