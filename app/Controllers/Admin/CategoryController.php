<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Flash;
use App\Models\Category;

class CategoryController extends Controller {
    public function index(): void {
        $categoryModel = new Category();
        $categories = $categoryModel->getWithProductCount();

        $this->render('admin/category/index', [
            'title'      => 'Quản lý Danh mục - Quản trị',
            'categories' => $categories,
        ], 'admin');
    }

    public function create(): void {
        $categoryModel = new Category();
        $parentCategories = $categoryModel->all();

        $this->render('admin/category/form', [
            'title'            => 'Thêm Danh mục mới - Quản trị',
            'category'         => null,
            'parentCategories' => $parentCategories,
        ], 'admin');
    }

    public function store(): void {
        $this->validate([
            'name' => 'required|min:2|max:100',
        ]);

        $categoryModel = new Category();
        $slug = $this->slugify($this->request->post('name'));

        $categoryModel->create([
            'name'        => $this->request->post('name'),
            'slug'        => $slug,
            'description' => $this->request->post('description', ''),
            'status'      => (int)$this->request->post('status', 1),
            'order_index' => (int)$this->request->post('order_index', 0),
        ]);

        Flash::success('Thêm danh mục mới thành công!');
        $this->redirect('admin/categories');
    }

    public function edit(string|int $id): void {
        $categoryModel = new Category();
        $category = $categoryModel->find((int)$id);

        if (!$category) {
            Flash::error('Danh mục không tồn tại.');
            $this->redirect('admin/categories');
        }

        $this->render('admin/category/form', [
            'title'    => 'Chỉnh sửa Danh mục - Quản trị',
            'category' => $category,
        ], 'admin');
    }

    public function update(string|int $id): void {
        $categoryModel = new Category();
        $this->validate([
            'name' => 'required|min:2|max:100',
        ]);

        $categoryModel->update((int)$id, [
            'name'        => $this->request->post('name'),
            'description' => $this->request->post('description', ''),
            'status'      => (int)$this->request->post('status', 1),
            'order_index' => (int)$this->request->post('order_index', 0),
        ]);

        Flash::success('Cập nhật danh mục thành công!');
        $this->redirect('admin/categories');
    }

    public function delete(string|int $id): void {
        $categoryModel = new Category();
        $categoryModel->delete((int)$id);
        Flash::success('Đã xóa danh mục thành công!');
        $this->redirect('admin/categories');
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

