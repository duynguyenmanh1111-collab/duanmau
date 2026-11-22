<?php
class AdminController
{
    private ProductModel $productModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
    }

    public function index(): void
    {
        if (!$this->requireAdmin()) {
            return;
        }

        $message = (string) ($_SESSION['admin_message'] ?? '');
        unset($_SESSION['admin_message']);

        $editingProduct = null;
        $productId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if ($productId !== null && $productId !== false && $productId > 0) {
            $editingProduct = $this->productModel->findProduct($productId);
            if ($editingProduct === false) {
                http_response_code(404);
                $message = 'Không tìm thấy sản phẩm cần chỉnh sửa.';
            }
        }

        $editingCategory = null;
        $categoryId = filter_input(INPUT_GET, 'category_id', FILTER_VALIDATE_INT);
        if ($categoryId !== null && $categoryId !== false && $categoryId > 0) {
            $editingCategory = $this->productModel->findCategory($categoryId);
            if ($editingCategory === false) {
                http_response_code(404);
                $message = 'Không tìm thấy danh mục cần chỉnh sửa.';
            }
        }

        $this->renderIndex('', [], $editingProduct, $message, '', '', $editingCategory);
    }

    public function save(): void
    {
        if (!$this->requireAdmin()) {
            return;
        }
        if (!$this->requirePostAndCsrf()) {
            return;
        }

        $id = null;
        $isUpdate = array_key_exists('id', $_POST);
        if ($isUpdate) {
            $idValue = $_POST['id'];
            $id = is_string($idValue) ? filter_var($idValue, FILTER_VALIDATE_INT) : false;
            if ($id === false || $id < 1) {
                http_response_code(400);
                echo 'Mã sản phẩm không hợp lệ.';
                return;
            }
        }
        $existingProduct = $isUpdate ? $this->productModel->findProduct($id) : false;
        if ($isUpdate && $existingProduct === false) {
            http_response_code(404);
            $this->renderIndex('Không tìm thấy sản phẩm cần cập nhật.', [], null);
            return;
        }

        $formData = [
            'name' => $this->postValue('name'),
            'price' => $this->postValue('price'),
            'quantity' => $this->postValue('quantity'),
            'category_id' => $this->postValue('category_id'),
            'description' => $this->postValue('description'),
        ];
        $name = trim($formData['name']);
        $price = filter_var($formData['price'], FILTER_VALIDATE_FLOAT);
        $quantity = filter_var($formData['quantity'], FILTER_VALIDATE_INT);
        $categoryId = filter_var($formData['category_id'], FILTER_VALIDATE_INT);
        $categories = $this->productModel->getAllCategories();
        $categoryIds = array_map(static fn(array $category): int => (int) $category['id'], $categories);
        $error = '';

        if ($name === '' || strlen($name) > 50) {
            $error = 'Tên sản phẩm không được để trống và tối đa 50 ký tự.';
        } elseif ($price === false || $price < 0) {
            $error = 'Giá sản phẩm phải là số không âm.';
        } elseif (!is_finite($price) || $price > 9999999999999.99) {
            $error = 'Giá sản phẩm không được vượt quá 9.999.999.999.999,99 ₫.';
        } elseif ($quantity === false || $quantity < 0) {
            $error = 'Số lượng phải là số nguyên không âm.';
        } elseif ($categoryId === false || !in_array($categoryId, $categoryIds, true)) {
            $error = 'Vui lòng chọn danh mục hợp lệ.';
        } elseif (strlen($formData['description']) > 500) {
            $error = 'Mô tả sản phẩm quá dài.';
        }

        $newImage = null;
        if ($error === '') {
            $newImage = $this->uploadProductImage();
            if ($this->uploadError !== '') {
                $error = $this->uploadError;
            }
        }

        if ($error !== '') {
            $this->renderIndex($error, $formData, $existingProduct);
            return;
        }

        $image = $newImage
            ?? (!empty($_POST['remove_image']) ? '' : (string) ($existingProduct['image'] ?? ''));

        try {
            if ($isUpdate) {
                $this->productModel->updateProduct(
                    $id,
                    $name,
                    (float) $price,
                    $quantity,
                    $categoryId,
                    $image,
                    trim($formData['description'])
                );
            } else {
                $this->productModel->createProduct(
                    $name,
                    (float) $price,
                    $quantity,
                    $categoryId,
                    $image ?? '',
                    trim($formData['description'])
                );
            }
        } catch (Throwable $exception) {
            if ($newImage !== null) {
                $this->deleteProductImage($newImage);
            }
            throw $exception;
        }

        if ($isUpdate && $newImage !== null && !empty($existingProduct['image'])) {
            $this->deleteProductImage((string) $existingProduct['image']);
        } elseif ($isUpdate && !empty($_POST['remove_image']) && !empty($existingProduct['image'])) {
            $this->deleteProductImage((string) $existingProduct['image']);
        }

        $_SESSION['admin_message'] = $isUpdate
            ? 'Đã cập nhật sản phẩm.'
            : 'Đã thêm sản phẩm.';
        $this->redirectToAdmin();
    }

    public function saveCategory(): void
    {
        if (!$this->requireAdmin()) {
            return;
        }
        if (!$this->requirePostAndCsrf()) {
            return;
        }

        $name = trim($this->postValue('category_name'));
        $idValue = $_POST['id'] ?? null;
        $isUpdate = $idValue !== null;
        $id = $isUpdate
            ? (is_string($idValue) ? filter_var($idValue, FILTER_VALIDATE_INT) : false)
            : null;
        $existingCategory = $isUpdate && $id !== false
            ? $this->productModel->findCategory($id)
            : false;

        if ($isUpdate && ($id === false || $id < 1)) {
            http_response_code(400);
            echo 'Mã danh mục không hợp lệ.';
            return;
        }
        if ($isUpdate && $existingCategory === false) {
            http_response_code(404);
            $this->renderIndex('', [], null, '', 'Không tìm thấy danh mục cần cập nhật.', $name);
            return;
        }

        if ($name === '' || strlen($name) > 100) {
            $this->renderIndex('', [], null, '', 'Tên danh mục không được để trống và tối đa 100 ký tự.', $name, $existingCategory);
            return;
        }
        $duplicate = $isUpdate
            ? $this->productModel->categoryExistsExcept($name, $id)
            : $this->productModel->categoryExists($name);
        if ($duplicate) {
            $this->renderIndex('', [], null, '', 'Danh mục này đã tồn tại.', $name, $existingCategory);
            return;
        }

        if ($isUpdate) {
            $this->productModel->updateCategory($id, $name);
            $_SESSION['admin_message'] = 'Đã cập nhật danh mục.';
        } else {
            $this->productModel->createCategory($name);
            $_SESSION['admin_message'] = 'Đã thêm danh mục.';
        }
        $this->redirectToAdmin();
    }

    public function deleteCategory(): void
    {
        if (!$this->requireAdmin()) {
            return;
        }
        if (!$this->requirePostAndCsrf()) {
            return;
        }

        $idValue = $_POST['id'] ?? null;
        $id = is_string($idValue) ? filter_var($idValue, FILTER_VALIDATE_INT) : false;
        if ($id === false || $id < 1) {
            http_response_code(400);
            echo 'Mã danh mục không hợp lệ.';
            return;
        }

        $category = $this->productModel->findCategory($id);
        if ($category === false) {
            http_response_code(404);
            echo 'Không tìm thấy danh mục cần xóa.';
            return;
        }

        $productCount = $this->productModel->countCategoryProducts($id);
        if ($productCount > 0) {
            $_SESSION['admin_message'] = 'Không thể xóa danh mục đang có sản phẩm.';
            $this->redirectToAdmin();
            return;
        }

        $this->productModel->deleteCategory($id);
        $_SESSION['admin_message'] = 'Đã xóa danh mục.';
        $this->redirectToAdmin();
    }

    public function delete(): void
    {
        if (!$this->requireAdmin()) {
            return;
        }
        if (!$this->requirePostAndCsrf()) {
            return;
        }

        $idValue = $_POST['id'] ?? null;
        $id = is_string($idValue) ? filter_var($idValue, FILTER_VALIDATE_INT) : false;
        if ($id === false || $id === null || $id < 1) {
            http_response_code(400);
            echo 'Mã sản phẩm không hợp lệ.';
            return;
        }

        $product = $this->productModel->findProduct($id);
        if ($product !== false) {
            $this->productModel->deleteProduct($id);
            if (!empty($product['image'])) {
                $this->deleteProductImage((string) $product['image']);
            }
        }

        $_SESSION['admin_message'] = 'Đã xóa sản phẩm.';
        $this->redirectToAdmin();
    }

    private string $uploadError = '';

    private function requireAdmin(): bool
    {
        if (!isset($_SESSION['user']['id'])) {
            header('Location: index.php?act=login');
            exit;
        }

        if ((int) ($_SESSION['user']['role'] ?? 0) !== 1) {
            http_response_code(403);
            echo 'Bạn không có quyền truy cập trang quản trị.';
            return false;
        }

        return true;
    }

    private function requirePostAndCsrf(): bool
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            header('Allow: POST');
            echo 'Phương thức không được hỗ trợ.';
            return false;
        }

        if (!isValidCsrfToken($_POST['csrf_token'] ?? null)) {
            http_response_code(400);
            echo 'Phiên làm việc không hợp lệ. Vui lòng tải lại trang.';
            return false;
        }

        return true;
    }

    private function renderIndex(
        string $error,
        array $formData,
        array|false|null $editingProduct,
        string $message = '',
        string $categoryError = '',
        string $categoryName = '',
        array|false|null $editingCategory = null
    ): void {
        $title = 'Quản trị sản phẩm';
        $products = $this->productModel->getAllProduct();
        $categories = $this->productModel->getAllCategories();
        require __DIR__ . '/../views/admin/admin.php';
    }

    private function postValue(string $key): string
    {
        $value = $_POST[$key] ?? '';

        return is_string($value) ? $value : '';
    }

    private function uploadProductImage(): ?string
    {
        $file = $_FILES['image'] ?? null;
        if (!is_array($file) || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
            $this->uploadError = 'Không thể tải ảnh lên. Vui lòng thử lại.';
            return null;
        }
        if (!isset($file['tmp_name'], $file['size']) || !is_string($file['tmp_name'])
            || !is_uploaded_file($file['tmp_name'])) {
            $this->uploadError = 'Tệp ảnh tải lên không hợp lệ.';
            return null;
        }
        if ((int) $file['size'] > 5 * 1024 * 1024) {
            $this->uploadError = 'Ảnh không được vượt quá 5 MB.';
            return null;
        }

        $imageInfo = getimagesize($file['tmp_name']);
        $extensions = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
        ];
        $mimeType = is_array($imageInfo) ? ($imageInfo['mime'] ?? '') : '';
        if (!isset($extensions[$mimeType])) {
            $this->uploadError = 'Chỉ chấp nhận ảnh JPG, PNG hoặc WEBP.';
            return null;
        }

        $filename = bin2hex(random_bytes(16)) . '.' . $extensions[$mimeType];
        $relativePath = 'uploads/imgproduct/' . $filename;
        $destination = PATH_ROOT . $relativePath;
        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            $this->uploadError = 'Không thể lưu ảnh. Hãy kiểm tra quyền ghi thư mục tải lên.';
            return null;
        }

        return $relativePath;
    }

    private function deleteProductImage(string $image): void
    {
        $normalized = str_replace('\\', '/', $image);
        if (!str_starts_with($normalized, 'uploads/imgproduct/')) {
            return;
        }

        $filename = basename($normalized);
        if ($filename === '' || $filename === '.' || $filename === '..') {
            return;
        }

        $path = PATH_ROOT . 'uploads/imgproduct/' . $filename;
        if (is_file($path)) {
            unlink($path);
        }
    }

    private function redirectToAdmin(): void
    {
        header('Location: index.php?act=admin');
        exit;
    }
}
