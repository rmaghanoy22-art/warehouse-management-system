<?php

namespace App\Controllers\Products;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\ProductCategoryModel;
use App\Libraries\AuditService;

/**
 * ProductController
 * Full CRUD for products and product categories.
 */
class ProductController extends BaseController
{
    protected ProductModel $productModel;
    protected ProductCategoryModel $categoryModel;
    protected AuditService $auditService;

    public function __construct()
    {
        $this->productModel  = new ProductModel();
        $this->categoryModel = new ProductCategoryModel();
        $this->auditService  = new AuditService();
        helper(['form', 'permission', 'format']);
    }

    /**
     * List all products.
     */
    public function index()
    {
        $search   = $this->request->getGet('search');
        $category = $this->request->getGet('category');
        $status   = $this->request->getGet('status');

        $data = [
            'title'      => 'Products',
            'products'   => $this->productModel->getProductsWithCategory($search, $category, $status),
            'categories' => $this->categoryModel->findAll(),
            'search'     => $search,
            'currentCategory' => $category,
            'currentStatus'   => $status,
        ];

        return view('products/index', $data);
    }

    /**
     * Show create product form.
     */
    public function create()
    {
        $data = [
            'title'      => 'Add Product',
            'categories' => $this->categoryModel->findAll(),
        ];

        return view('products/create', $data);
    }

    /**
     * Store a new product.
     */
    public function store()
    {
        $rules = [
            'code'                => 'required|max_length[50]|is_unique[products.code]',
            'name'                => 'required|max_length[200]',
            'category_id'         => 'required',
            'unit_of_measurement' => 'required',
            'quantity_in_stock'   => 'required|integer|greater_than_equal_to[0]',
            'reorder_level'       => 'required|integer|greater_than_equal_to[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $categoryId = $this->request->getPost('category_id');
        if ($categoryId === 'other') {
            $customCategory = trim($this->request->getPost('custom_category'));
            if (!empty($customCategory)) {
                $existing = $this->categoryModel->where('name', $customCategory)->first();
                if ($existing) {
                    $categoryId = $existing['id'];
                } else {
                    $this->categoryModel->insert([
                        'name'        => $customCategory,
                        'description' => 'Automatically created custom category'
                    ]);
                    $categoryId = $this->categoryModel->getInsertID();
                }
            }
        }

        $unitOfMeasurement = $this->request->getPost('unit_of_measurement');
        if ($unitOfMeasurement === 'other') {
            $unitOfMeasurement = strtolower(trim($this->request->getPost('custom_unit')));
        }

        $productData = [
            'code'                => $this->request->getPost('code'),
            'name'                => $this->request->getPost('name'),
            'category_id'         => $categoryId,
            'unit_of_measurement' => $unitOfMeasurement,
            'quantity_in_stock'   => $this->request->getPost('quantity_in_stock'),
            'reorder_level'       => $this->request->getPost('reorder_level'),
            'expiration_date'     => $this->request->getPost('expiration_date') ?: null,
            'status'              => $this->request->getPost('status') ?? 'active',
            'description'         => $this->request->getPost('description'),
        ];

        $this->productModel->insert($productData);
        $this->auditService->logCreate('product', $this->productModel->getInsertID(), $productData);

        return redirect()->to('/products')->with('success', 'Product created successfully.');
    }

    /**
     * Show edit product form.
     */
    public function edit(int $id)
    {
        $product = $this->productModel->find($id);
        if (!$product) {
            return redirect()->to('/products')->with('error', 'Product not found.');
        }

        $data = [
            'title'      => 'Edit Product',
            'product'    => $product,
            'categories' => $this->categoryModel->findAll(),
        ];

        return view('products/edit', $data);
    }

    /**
     * Update a product.
     */
    public function update(int $id)
    {
        $product = $this->productModel->find($id);
        if (!$product) {
            return redirect()->to('/products')->with('error', 'Product not found.');
        }

        $rules = [
            'code' => "required|max_length[50]|is_unique[products.code,id,{$id}]",
            'name' => 'required|max_length[200]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $categoryId = $this->request->getPost('category_id');
        if ($categoryId === 'other') {
            $customCategory = trim($this->request->getPost('custom_category'));
            if (!empty($customCategory)) {
                $existing = $this->categoryModel->where('name', $customCategory)->first();
                if ($existing) {
                    $categoryId = $existing['id'];
                } else {
                    $this->categoryModel->insert([
                        'name'        => $customCategory,
                        'description' => 'Automatically created custom category'
                    ]);
                    $categoryId = $this->categoryModel->getInsertID();
                }
            }
        }

        $unitOfMeasurement = $this->request->getPost('unit_of_measurement');
        if ($unitOfMeasurement === 'other') {
            $unitOfMeasurement = strtolower(trim($this->request->getPost('custom_unit')));
        }

        $updateData = [
            'code'                => $this->request->getPost('code'),
            'name'                => $this->request->getPost('name'),
            'category_id'         => $categoryId,
            'unit_of_measurement' => $unitOfMeasurement,
            'quantity_in_stock'   => $this->request->getPost('quantity_in_stock'),
            'reorder_level'       => $this->request->getPost('reorder_level'),
            'expiration_date'     => $this->request->getPost('expiration_date') ?: null,
            'status'              => $this->request->getPost('status'),
            'description'         => $this->request->getPost('description'),
        ];

        $this->auditService->logUpdate('product', $id, $product, $updateData);
        $this->productModel->update($id, $updateData);

        return redirect()->to('/products')->with('success', 'Product updated successfully.');
    }

    /**
     * Soft delete a product.
     */
    public function delete(int $id)
    {
        $product = $this->productModel->find($id);
        if (!$product) {
            return redirect()->to('/products')->with('error', 'Product not found.');
        }

        $this->auditService->logDelete('product', $id, $product);
        $this->productModel->delete($id);

        return redirect()->to('/products')->with('success', 'Product deleted successfully.');
    }

    /**
     * Categories management page.
     */
    public function categories()
    {
        $data = [
            'title'      => 'Product Categories',
            'categories' => $this->categoryModel->getCategoriesWithProductCount(),
        ];

        return view('products/categories', $data);
    }

    /**
     * Store a new category.
     */
    public function storeCategory()
    {
        $rules = ['name' => 'required|max_length[100]'];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->categoryModel->insert([
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
        ]);

        return redirect()->to('/categories')->with('success', 'Category created.');
    }

    /**
     * Update a category.
     */
    public function updateCategory(int $id)
    {
        $this->categoryModel->update($id, [
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
        ]);

        return redirect()->to('/categories')->with('success', 'Category updated.');
    }

    /**
     * Delete a category.
     */
    public function deleteCategory(int $id)
    {
        $this->categoryModel->delete($id);
        return redirect()->to('/categories')->with('success', 'Category deleted.');
    }
}
