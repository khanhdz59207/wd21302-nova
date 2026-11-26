<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Product extends Model {
    protected string $table = 'products';
    protected array $fillable = [
        'category_id', 'name', 'brand', 'slug', 'sku', 'price', 'sale_price',
        'stock', 'image', 'description', 'description_html', 'specs',
        'rating', 'rating_count', 'warranty', 'promotions', 'source_url',
        'status', 'is_featured', 'views'
    ];

    public const SORT_OPTIONS = [
        'popular' => 'Phổ biến',
        'price-asc' => 'Giá thấp → cao',
        'price-desc' => 'Giá cao → thấp',
        'name' => 'Tên A → Z',
        'newest' => 'Mới nhất',
    ];

    public const PRICE_RANGES = [
        'under-5' => ['label' => 'Dưới 5 triệu', 'min' => 0, 'max' => 5000000],
        '5-10' => ['label' => '5 - 10 triệu', 'min' => 5000000, 'max' => 10000000],
        '10-20' => ['label' => '10 - 20 triệu', 'min' => 10000000, 'max' => 20000000],
        'over-20' => ['label' => 'Trên 20 triệu', 'min' => 20000000, 'max' => null],
    ];

    public static function filtersFromQuery(array $query): array {
        $brands = $query['brand'] ?? [];
        if (!is_array($brands)) {
            $brands = [$brands];
        }
        $brands = array_values(array_unique(array_filter(
            array_map(
                static fn(mixed $brand): string => is_scalar($brand) ? trim((string)$brand) : '',
                $brands
            ),
            static fn(string $brand): bool => $brand !== ''
        )));
        $brands = array_slice($brands, 0, 20);

        $price = is_scalar($query['price'] ?? null) ? (string)$query['price'] : '';
        if (!array_key_exists($price, self::PRICE_RANGES)) {
            $price = '';
        }

        $sort = is_scalar($query['sort'] ?? null) ? (string)$query['sort'] : 'popular';
        if (!array_key_exists($sort, self::SORT_OPTIONS)) {
            $sort = 'popular';
        }

        $stock = is_scalar($query['stock'] ?? null) ? (string)$query['stock'] : '';
        $sale = is_scalar($query['sale'] ?? null) ? (string)$query['sale'] : '';

        return [
            'brands' => $brands,
            'price' => $price,
            'sort' => $sort,
            'stock' => $stock === '1',
            'sale' => $sale === '1',
            'category_id' => null,
            'keyword' => '',
        ];
    }

    public function getListing(array $filters, int $page = 1, int $perPage = 12): array {
        [$where, $params] = $this->buildListingWhere($filters);
        return $this->paginate(
            $page,
            $perPage,
            $where,
            $params,
            $this->resolveOrderBy((string)($filters['sort'] ?? 'popular'))
        );
    }

    public function getBrandFacets(array $filters): array {
        $facetFilters = $filters;
        $facetFilters['brands'] = [];
        [$where, $params] = $this->buildListingWhere($facetFilters);

        $stmt = $this->db()->prepare("
            SELECT brand, COUNT(*) AS total
            FROM `products`
            WHERE {$where}
              AND brand IS NOT NULL
              AND brand <> ''
            GROUP BY brand
            ORDER BY total DESC, brand ASC
        ");
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getPriceFacets(array $filters): array {
        $facetFilters = $filters;
        $facetFilters['price'] = '';
        [$where, $params] = $this->buildListingWhere($facetFilters);
        $price = $this->effectivePriceSql();

        $stmt = $this->db()->prepare("
            SELECT
                SUM(CASE WHEN {$price} < 5000000 THEN 1 ELSE 0 END) AS `under-5`,
                SUM(CASE WHEN {$price} >= 5000000 AND {$price} < 10000000 THEN 1 ELSE 0 END) AS `5-10`,
                SUM(CASE WHEN {$price} >= 10000000 AND {$price} < 20000000 THEN 1 ELSE 0 END) AS `10-20`,
                SUM(CASE WHEN {$price} >= 20000000 THEN 1 ELSE 0 END) AS `over-20`
            FROM `products`
            WHERE {$where}
        ");
        $stmt->execute($params);
        $row = $stmt->fetch() ?: [];

        $facets = [];
        foreach (array_keys(self::PRICE_RANGES) as $key) {
            $facets[$key] = (int)($row[$key] ?? 0);
        }
        return $facets;
    }

    protected function buildListingWhere(array $filters): array {
        $where = ['status = 1'];
        $params = [];

        if (!empty($filters['category_id'])) {
            $where[] = 'category_id = :category_id';
            $params[':category_id'] = (int)$filters['category_id'];
        }

        $keyword = trim((string)($filters['keyword'] ?? ''));
        if ($keyword !== '') {
            $like = '%' . $keyword . '%';
            $where[] = '(name LIKE :kw_name OR description LIKE :kw_description OR brand LIKE :kw_brand OR sku LIKE :kw_sku)';
            $params[':kw_name'] = $like;
            $params[':kw_description'] = $like;
            $params[':kw_brand'] = $like;
            $params[':kw_sku'] = $like;
        }

        $brands = $filters['brands'] ?? [];
        if (!empty($brands)) {
            $placeholders = [];
            foreach (array_values($brands) as $index => $brand) {
                $placeholder = ':brand_' . $index;
                $placeholders[] = $placeholder;
                $params[$placeholder] = (string)$brand;
            }
            $where[] = 'brand IN (' . implode(', ', $placeholders) . ')';
        }

        $price = (string)($filters['price'] ?? '');
        if ($price !== '' && isset(self::PRICE_RANGES[$price])) {
            $range = self::PRICE_RANGES[$price];
            $priceSql = $this->effectivePriceSql();
            if ($range['min'] > 0) {
                $where[] = "{$priceSql} >= :price_min";
                $params[':price_min'] = (int)$range['min'];
            }
            if ($range['max'] !== null) {
                $where[] = "{$priceSql} < :price_max";
                $params[':price_max'] = (int)$range['max'];
            }
        }

        if (!empty($filters['stock'])) {
            $where[] = 'stock > 0';
        }

        if (!empty($filters['sale'])) {
            $where[] = 'sale_price IS NOT NULL AND sale_price > 0 AND sale_price < price';
        }

        return [implode(' AND ', $where), $params];
    }

    protected function effectivePriceSql(): string {
        return 'COALESCE(NULLIF(sale_price, 0), price)';
    }

    protected function resolveOrderBy(string $sort): string {
        return match ($sort) {
            'price-asc' => $this->effectivePriceSql() . ' ASC, id DESC',
            'price-desc' => $this->effectivePriceSql() . ' DESC, id DESC',
            'name' => 'name ASC, id DESC',
            'newest' => 'id DESC',
            default => 'is_featured DESC, views DESC, id DESC',
        };
    }

    public function getFeatured(int $limit = 8): array {
        $stmt = $this->db()->prepare("
            SELECT p.*, c.name AS category_name 
            FROM `products` p
            LEFT JOIN `categories` c ON p.category_id = c.id
            WHERE p.status = 1 AND p.is_featured = 1
            ORDER BY p.id DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getLatest(int $limit = 8): array {
        $stmt = $this->db()->prepare("
            SELECT p.*, c.name AS category_name 
            FROM `products` p
            LEFT JOIN `categories` c ON p.category_id = c.id
            WHERE p.status = 1
            ORDER BY p.id DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByCategory(int $categoryId, int $page = 1, int $perPage = 12): array {
        return $this->paginate($page, $perPage, 'category_id = :cat_id AND status = 1', [':cat_id' => $categoryId]);
    }

    public function search(string $keyword, int $page = 1, int $perPage = 12): array {
        $like = "%{$keyword}%";
        return $this->paginate(
            $page,
            $perPage,
            '(name LIKE :kw OR description LIKE :kw2) AND status = 1',
            [':kw' => $like, ':kw2' => $like]
        );
    }

    public function incrementViews(int $id): void {
        $stmt = $this->db()->prepare("UPDATE `products` SET views = views + 1 WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }

    public function getWithCategory(int $id): ?array {
        $stmt = $this->db()->prepare("
            SELECT p.*, c.name AS category_name, c.slug AS category_slug
            FROM `products` p
            LEFT JOIN `categories` c ON p.category_id = c.id
            WHERE p.id = :id
            LIMIT 1
        ");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function getGalleryImages(int $productId): array {
        $stmt = $this->db()->prepare("
            SELECT image, is_main, sort_order
            FROM `product_images`
            WHERE product_id = :product_id
            ORDER BY is_main DESC, sort_order ASC, id ASC
        ");
        $stmt->execute([':product_id' => $productId]);
        return $stmt->fetchAll();
    }

    public function getSpecs(int $productId): array {
        $stmt = $this->db()->prepare("
            SELECT label, value
            FROM `product_specs`
            WHERE product_id = :product_id
            ORDER BY sort_order ASC, id ASC
        ");
        $stmt->execute([':product_id' => $productId]);
        return $stmt->fetchAll();
    }

    public function getHighlights(int $productId): array {
        $stmt = $this->db()->prepare("
            SELECT content
            FROM `product_highlights`
            WHERE product_id = :product_id
            ORDER BY sort_order ASC, id ASC
        ");
        $stmt->execute([':product_id' => $productId]);
        return $stmt->fetchAll();
    }

    public function getVariants(int $productId): array {
        $stmt = $this->db()->prepare("
            SELECT id, name, sku, price
            FROM `product_variants`
            WHERE product_id = :product_id
            ORDER BY sort_order ASC, id ASC
        ");
        $stmt->execute([':product_id' => $productId]);
        return $stmt->fetchAll();
    }

    public function getVariant(int $variantId, int $productId): ?array {
        $stmt = $this->db()->prepare("
            SELECT id, product_id, name, sku, price
            FROM `product_variants`
            WHERE id = :id AND product_id = :product_id
            LIMIT 1
        ");
        $stmt->execute([':id' => $variantId, ':product_id' => $productId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function getRelated(int $productId, int $categoryId, int $limit = 4): array {
        $stmt = $this->db()->prepare("
            SELECT *
            FROM `products`
            WHERE category_id = :category_id
              AND id <> :product_id
              AND status = 1
            ORDER BY is_featured DESC, id DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':product_id', $productId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}

