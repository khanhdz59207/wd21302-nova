<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Flash;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller {
    public function index(): void {
        $productModel = new Product();
        $page = (int)$this->request->query('page', 1);
        $products = $productModel->paginate($page, ADMIN_PAGE_SIZE, '', [], 'id DESC');

        $this->render('admin/product/index', [
            'title'    => 'Quản lý Sản phẩm - Quản trị',
            'products' => $products,
        ], 'admin');
    }

    public function create(): void {
        $categoryModel = new Category();
        $categories = $categoryModel->getActive();

        $this->render('admin/product/form', [
            'title'      => 'Thêm mới Sản phẩm - Quản trị',
            'product'    => null,
            'categories' => $categories,
        ], 'admin');
    }

    public function store(): void {
        $this->validate([
            'name'        => 'required|min:3|max:200',
            'category_id' => 'required|numeric',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|numeric|min:0',
        ]);

        $imagePath = $this->handleUpload('image');

        $productModel = new Product();
        $slug = $this->slugify($this->request->post('name'));

        $productModel->create([
            'category_id' => (int)$this->request->post('category_id'),
            'name'        => $this->request->post('name'),
            'slug'        => $slug,
            'sku'         => $this->request->post('sku') ?: strtoupper(substr(md5(uniqid()), 0, 8)),
            'price'       => (float)$this->request->post('price'),
            'sale_price'  => !empty($this->request->post('sale_price')) ? (float)$this->request->post('sale_price') : null,
            'stock'       => (int)$this->request->post('stock', 0),
            'image'       => $imagePath,
            'description' => $this->request->post('description', ''),
            'specs'       => $this->request->post('specs', ''),
            'status'      => (int)$this->request->post('status', 1),
            'is_featured' => (int)$this->request->post('is_featured', 0),
        ]);

        Flash::success('Thêm sản phẩm thành công!');
        $this->redirect('admin/products');
    }

    public function edit(string|int $id): void {
        $productModel = new Product();
        $product = $productModel->find((int)$id);

        if (!$product) {
            Flash::error('Sản phẩm không tồn tại.');
            $this->redirect('admin/products');
        }

        $categoryModel = new Category();
        $categories = $categoryModel->getActive();

        $this->render('admin/product/form', [
            'title'      => 'Chỉnh sửa Sản phẩm - Quản trị',
            'product'    => $product,
            'categories' => $categories,
        ], 'admin');
    }

    public function update(string|int $id): void {
        $productModel = new Product();
        $product = $productModel->find((int)$id);

        if (!$product) {
            Flash::error('Sản phẩm không tồn tại.');
            $this->redirect('admin/products');
        }

        $this->validate([
            'name'        => 'required|min:3|max:200',
            'category_id' => 'required|numeric',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|numeric|min:0',
        ]);

        $data = [
            'category_id' => (int)$this->request->post('category_id'),
            'name'        => $this->request->post('name'),
            'price'       => (float)$this->request->post('price'),
            'sale_price'  => !empty($this->request->post('sale_price')) ? (float)$this->request->post('sale_price') : null,
            'stock'       => (int)$this->request->post('stock', 0),
            'description' => $this->request->post('description', ''),
            'specs'       => $this->request->post('specs', ''),
            'status'      => (int)$this->request->post('status', 1),
            'is_featured' => (int)$this->request->post('is_featured', 0),
        ];

        $uploaded = $this->handleUpload('image');
        if ($uploaded) {
            $data['image'] = $uploaded;
        }

        $productModel->update((int)$id, $data);
        Flash::success('Cập nhật sản phẩm thành công!');
        $this->redirect('admin/products');
    }

    public function delete(string|int $id): void {
        $productModel = new Product();
        $productModel->delete((int)$id);
        Flash::success('Đã xóa sản phẩm thành công!');
        $this->redirect('admin/products');
    }

    protected function handleUpload(string $inputName): ?string {
        $file = $this->request->file($inputName);
        if (!$file || empty($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        if (!in_array($ext, $allowed)) {
            Flash::error('Định dạng ảnh không hợp lệ.');
            return null;
        }

        $newFileName = 'prod_' . time() . '_' . rand(100, 999) . '.' . $ext;
        $destPath = UPLOAD_PATH . '/products/' . $newFileName;

        if (move_uploaded_file($file['tmp_name'], $destPath)) {
            return 'products/' . $newFileName;
        }

        return null;
    }

    protected function slugify(string $text): string {
        $text = mb_strtolower($text, 'UTF-8');
        $text = preg_replace('/[áàảãạăắằẳẵặâấầẩẫậ]/u', 'a', $text);
        $text = preg_replace('/[éèẻẽẹêếềểễệ]/u', 'e', $text);
        $text = preg_replace('/[íìỉĩị]/u', 'i', $text);
        $text = preg_replace('/[óòỏõọôốồổỗộơớờởỡợ]/u', 'o', $text);
        $text = preg_replace('/[úùủũụưứừửữự]/u', 'u', $text);
        $text = preg_replace('/[ýỳỷỹỵ]/u', 'y', $text);
        $text = preg_replace('/[đ]/u', 'd', $text);
        $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
        $text = preg_replace('/[\s-]+/', '-', $text);
        return trim($text, '-');
    }
}

