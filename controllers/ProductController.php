<?php
// có class chứa các function thực thi xử lý logic 
class ProductController
{
    public $modelProduct;

    public function __construct()
    {
        $this->modelProduct = new ProductModel();
    }

    public function Home()
    {
        $title = 'Xe chính hãng, giá tốt';
        $products = $this->modelProduct->getAllProduct();
        $categories = $this->modelProduct->getAllCategories();
        require_once './views/trangchu.php';
    }
}
