<?php 
// Require toàn bộ các file khai báo môi trường, thực thi,...(không require view)

// Require file Common
require_once './commons/env.php'; // Khai báo biến môi trường
require_once './commons/function.php'; // Hàm hỗ trợ

session_set_cookie_params([
    'httponly' => true,
    'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    'samesite' => 'Lax',
]);
session_start();

// Require toàn bộ file Controllers
require_once './controllers/ProductController.php';
require_once './controllers/AuthController.php';
require_once './controllers/AdminController.php';

// Require toàn bộ file Models
require_once './models/ProductModel.php';
require_once './models/AuthModel.php';

// Route
$act = $_GET['act'] ?? '/';


// Để bảo bảo tính chất chỉ gọi 1 hàm Controller để xử lý request thì mình sử dụng match

match ($act) {
    // Trang chủ
    '/'=>(new ProductController())->Home(),
    'login'=>(new AuthController())->login(),
    'register'=>(new AuthController())->register(),
    'logout'=>(new AuthController())->logout(),
    'admin'=>(new AdminController())->index(),
    'admin-save'=>(new AdminController())->save(),
    'admin-category-save'=>(new AdminController())->saveCategory(),
    'admin-category-delete'=>(new AdminController())->deleteCategory(),
    'admin-delete'=>(new AdminController())->delete(),

};