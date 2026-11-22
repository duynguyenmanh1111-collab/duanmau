<?php
$escape = static fn($value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $escape($title) ?> | MạnhDuyAuto06</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            color: #222;
            background: #f6f7f8;
            font: 16px Arial, sans-serif;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .container {
            width: min(1100px, calc(100% - 32px));
            margin: 0 auto;
        }

        header {
            padding: 18px 0;
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
        }

        .header-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .brand {
            color: #176b48;
            font-size: 21px;
            font-weight: 700;
        }

        nav {
            display: flex;
            align-items: center;
            gap: 22px;
            color: #555;
            font-size: 14px;
        }

        .account-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .account-actions form {
            margin: 0;
        }

        .account-button {
            padding: 8px 12px;
            border: 1px solid #176b48;
            border-radius: 5px;
            color: #176b48;
            background: #fff;
            cursor: pointer;
            font-size: 14px;
        }

        .account-button.primary {
            color: #fff;
            background: #176b48;
        }

        main {
            padding: 42px 0 56px;
        }

        .intro {
            margin-bottom: 28px;
        }

        h1 {
            margin: 0 0 8px;
            font-size: 30px;
        }

        .intro p {
            margin: 0;
            color: #666;
        }

        .tools {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 20px;
        }

        .filters {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        button,
        input {
            font: inherit;
        }

        .filter {
            padding: 9px 13px;
            border: 1px solid #ddd;
            border-radius: 5px;
            color: #444;
            background: #fff;
            cursor: pointer;
        }

        .filter.active,
        .filter:hover {
            border-color: #176b48;
            color: #fff;
            background: #176b48;
        }

        .search {
            width: 230px;
            padding: 9px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .products {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
        }

        .product {
            overflow: hidden;
            border: 1px solid #e5e7eb;
            border-radius: 7px;
            background: #fff;
        }

        .product-image {
            height: 190px;
            background: #eef1ef;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .no-image {
            display: grid;
            height: 100%;
            place-items: center;
            color: #888;
            font-size: 14px;
        }

        .product-info {
            padding: 15px;
        }

        .category {
            color: #176b48;
            font-size: 12px;
        }

        h2 {
            margin: 7px 0;
            font-size: 18px;
        }

        .description {
            min-height: 36px;
            margin: 0 0 13px;
            color: #666;
            font-size: 14px;
            line-height: 1.4;
        }

        .product-footer {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            padding-top: 12px;
            border-top: 1px solid #eee;
        }

        .price {
            color: #176b48;
            font-weight: 700;
        }

        .stock {
            color: #666;
            font-size: 13px;
        }

        .empty {
            display: none;
            padding: 28px;
            color: #666;
            text-align: center;
        }

        .empty.visible {
            display: block;
        }

        footer {
            padding: 18px 0;
            color: #666;
            background: #fff;
            text-align: center;
            font-size: 13px;
        }

        @media (max-width: 760px) {
            .products {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .tools {
                align-items: stretch;
                flex-direction: column;
            }

            .search {
                width: 100%;
            }
        }

        @media (max-width: 520px) {
            .header-inner {
                align-items: flex-start;
                flex-direction: column;
                gap: 12px;
            }

            nav {
                gap: 16px;
            }

            main {
                padding-top: 30px;
            }

            h1 {
                font-size: 25px;
            }

            .products {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <header>
        <div class="container header-inner">
            <a class="brand" href="#home">MạnhDuyAuto06</a>
            <nav aria-label="Điều hướng">
                <a href="#home">Trang chủ</a>
                <a href="#products">Sản phẩm</a>
                <?php if ((int) ($_SESSION['user']['role'] ?? 0) === 1): ?>
                    <a href="index.php?act=admin">Quản trị</a>
                <?php endif; ?>
            </nav>
            <div class="account-actions">
                <?php if (isset($_SESSION['user'])): ?>
                    <span>Xin chào, <?= $escape($_SESSION['user']['name'] ?? 'bạn') ?></span>
                    <form method="post" action="index.php?act=logout">
                        <input type="hidden" name="csrf_token" value="<?= $escape(csrfToken()) ?>">
                        <button class="account-button" type="submit">Đăng xuất</button>
                    </form>
                <?php else: ?>
                    <a class="account-button" href="index.php?act=login">Đăng nhập</a>
                    <a class="account-button primary" href="index.php?act=register">Đăng ký</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main class="container" id="home">
        <section class="intro">
            <h1>Danh sách xe</h1>
            <p>Chọn mẫu xe phù hợp với nhu cầu của bạn.</p>
        </section>

        <section id="products" aria-label="Sản phẩm">
            <div class="tools">
                <div class="filters" aria-label="Lọc theo hãng xe">
                    <button class="filter active" type="button" data-category="all" aria-pressed="true">Tất cả</button>
                    <?php foreach ($categories as $category): ?>
                        <button class="filter" type="button" data-category="<?= $escape($category['id']) ?>"
                            aria-pressed="false"><?= $escape($category['name']) ?></button>
                    <?php endforeach; ?>
                </div>
                <input class="search" id="product-search" type="search" placeholder="Tìm tên xe..."
                    aria-label="Tìm sản phẩm">
            </div>

            <div class="products" id="product-list">
                <?php foreach ($products as $product): ?>
                    <?php
                    $imagePath = str_replace('\\', '/', trim((string) ($product['image'] ?? '')));
                    $hasLocalImage = $imagePath !== ''
                        && !str_contains($imagePath, '..')
                        && is_file(PATH_ROOT . ltrim($imagePath, '/'));
                    $imageUrl = $hasLocalImage
                        ? implode('/', array_map('rawurlencode', explode('/', ltrim($imagePath, '/'))))
                        : '';
                    $description = trim((string) ($product['description'] ?? ''));
                    ?>
                    <article class="product" data-category="<?= $escape($product['category_id']) ?>">
                        <div class="product-image">
                            <?php if ($hasLocalImage): ?>
                                <img src="<?= $escape($imageUrl) ?>" alt="<?= $escape($product['name']) ?>" loading="lazy">
                            <?php else: ?>
                                <div class="no-image">Chưa có hình ảnh</div>
                            <?php endif; ?>
                        </div>
                        <div class="product-info">
                            <span class="category"><?= $escape($product['category_name'] ?? 'Khác') ?></span>
                            <h2><?= $escape($product['name']) ?></h2>
                            <p class="description">
                                <?= $escape($description !== '' ? $description : 'Liên hệ để biết thêm thông tin.') ?>
                            </p>
                            <div class="product-footer">
                                <span class="price"><?= number_format((float) $product['price'], 0, ',', '.') ?> ₫</span>
                                <span class="stock">
                                    <?= (int) $product['quantity'] > 0 ? 'Còn hàng' : 'Tạm hết hàng' ?>
                                </span>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
            <p class="empty<?= $products === [] ? ' visible' : '' ?>" id="empty-state">
                Không tìm thấy sản phẩm phù hợp.
            </p>
        </section>
    </main>

    <footer>
        <div class="container">© <?= date('Y') ?> MạnhDuyAuto06</div>
        <div class="container">Địa chỉ: Đường Mỹ Đình, phường Từ Liêm, TP Hà Nội</div>
        <div class="container">Hotline: 0367104330</div>
    </footer>

    <script>
        const cards = [...document.querySelectorAll('.product')];
        const buttons = [...document.querySelectorAll('.filter')];
        const search = document.getElementById('product-search');
        const emptyState = document.getElementById('empty-state');
        let selectedCategory = 'all';

        function updateProducts() {
            const query = search.value.trim().toLocaleLowerCase('vi');
            let visibleCount = 0;

            cards.forEach((card) => {
                const matchesCategory = selectedCategory === 'all' || card.dataset.category === selectedCategory;
                const matchesSearch = card.textContent.toLocaleLowerCase('vi').includes(query);
                card.hidden = !matchesCategory || !matchesSearch;
                if (!card.hidden) visibleCount++;
            });

            emptyState.classList.toggle('visible', visibleCount === 0);
        }

        buttons.forEach((button) => {
            button.addEventListener('click', () => {
                selectedCategory = button.dataset.category;
                buttons.forEach((item) => {
                    const active = item === button;
                    item.classList.toggle('active', active);
                    item.setAttribute('aria-pressed', String(active));
                });
                updateProducts();
            });
        });
        search.addEventListener('input', updateProducts);
    </script>
</body>

</html>