<?php
$escape = static fn($value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
$formProduct = $formData !== []
    ? $formData
    : ($editingProduct ?: ['name' => '', 'price' => '', 'quantity' => '', 'category_id' => '', 'description' => '']);

$totalQuantity = 0;
$inventoryValue = 0;
$categoryCounts = [];
foreach ($categories as $category) {
    $categoryCounts[(int) $category['id']] = [
        'id' => (int) $category['id'],
        'name' => $category['name'],
        'count' => 0,
    ];
}
foreach ($products as $product) {
    $quantity = (int) $product['quantity'];
    $totalQuantity += $quantity;
    $inventoryValue += (float) $product['price'] * $quantity;
    $categoryId = (int) $product['category_id'];
    if (isset($categoryCounts[$categoryId])) {
        $categoryCounts[$categoryId]['count']++;
    }
}
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
            color: #1f2937;
            background: #f1f5f9;
            font: 15px Arial, sans-serif;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button,
        input,
        select,
        textarea {
            font: inherit;
        }

        .admin-shell {
            display: grid;
            grid-template-columns: 210px minmax(0, 1fr);
            min-height: 100vh;
        }

        .sidebar {
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            padding: 18px 0;
            color: #e5e7eb;
            background: #1e293b;
        }

        .brand {
            display: block;
            padding: 0 18px 20px;
            color: #fff;
            font-size: 19px;
            font-weight: 700;
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 15px 18px;
            border-top: 1px solid #334155;
            border-bottom: 1px solid #334155;
        }

        .avatar {
            display: grid;
            width: 34px;
            height: 34px;
            place-items: center;
            border-radius: 50%;
            color: #fff;
            background: #6366f1;
            font-weight: 700;
        }

        .profile-name {
            overflow: hidden;
            font-size: 14px;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .side-label {
            margin: 20px 18px 8px;
            color: #94a3b8;
            font-size: 11px;
            text-transform: uppercase;
        }

        .category-toggle {
            display: flex;
            width: calc(100% - 36px);
            align-items: center;
            justify-content: space-between;
            padding: 0;
            border: 0;
            background: transparent;
            text-align: left;
            cursor: pointer;
        }

        .category-chevron {
            transition: transform 0.2s ease;
        }

        .category-toggle[aria-expanded="false"] .category-chevron {
            transform: rotate(-90deg);
        }

        #category-menu[hidden] {
            display: none;
        }

        .side-link,
        .category-filter {
            display: block;
            width: 100%;
            padding: 11px 18px;
            border: 0;
            color: #dbe3ee;
            background: transparent;
            text-align: left;
            cursor: pointer;
        }

        .side-link:hover,
        .category-filter:hover,
        .category-filter.active {
            color: #fff;
            background: #334155;
        }

        .category-filter {
            padding-left: 28px;
            font-size: 13px;
        }

        .admin-main {
            min-width: 0;
        }

        .topbar {
            display: flex;
            height: 58px;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            border-bottom: 1px solid #e2e8f0;
            background: #fff;
        }

        .topbar-title {
            font-weight: 600;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .home-link {
            padding: 8px 10px;
            border-radius: 5px;
            color: #176bce;
            background: #eff6ff;
        }

        .logout-form {
            margin: 0;
        }

        .logout-button {
            padding: 8px 10px;
            border: 0;
            color: #475569;
            background: transparent;
            cursor: pointer;
        }

        .content {
            padding: 24px;
        }

        h1 {
            margin: 0 0 6px;
            font-size: 26px;
        }

        h2 {
            margin: 0;
            font-size: 17px;
        }

        .muted {
            color: #64748b;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
            margin: 20px 0;
        }

        .stat-card,
        .panel {
            border: 1px solid #e2e8f0;
            border-radius: 9px;
            background: #fff;
        }

        .stat-card {
            padding: 17px;
        }

        .stat-label {
            color: #64748b;
            font-size: 12px;
            text-transform: uppercase;
        }

        .stat-value {
            margin-top: 8px;
            color: #0f172a;
            font-size: 21px;
            font-weight: 700;
        }

        .panel {
            padding: 18px;
        }

        .form-panel {
            margin-bottom: 18px;
        }

        .form-panel summary {
            color: #0758d6;
            cursor: pointer;
            font-weight: 700;
        }

        .product-form {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px 16px;
            margin-top: 16px;
        }

        .field label {
            display: block;
            margin-bottom: 6px;
            font-size: 13px;
            font-weight: 600;
        }

        .field input,
        .field select,
        .field textarea {
            width: 100%;
            padding: 9px 10px;
            border: 1px solid #cbd5e1;
            border-radius: 5px;
            background: #fff;
        }

        .field textarea {
            min-height: 72px;
            resize: vertical;
        }

        .field.full,
        .form-actions {
            grid-column: 1 / -1;
        }

        .checkbox {
            display: flex;
            align-items: center;
            gap: 7px;
            margin-top: 9px;
            font-size: 13px;
        }

        .checkbox input {
            width: auto;
        }

        .form-actions {
            display: flex;
            gap: 8px;
        }

        .button {
            display: inline-block;
            padding: 9px 12px;
            border: 0;
            border-radius: 5px;
            color: #fff;
            background: #176bce;
            cursor: pointer;
        }

        .button.secondary {
            color: #334155;
            background: #e2e8f0;
        }

        .button.danger {
            background: #b42318;
        }

        .notice {
            margin: 14px 0;
            padding: 11px 13px;
            border-radius: 5px;
            color: #176b48;
            background: #e8f5ed;
        }

        .notice.error {
            color: #9c2525;
            background: #fff0f0;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: minmax(0, 2fr) minmax(240px, 1fr);
            align-items: start;
            gap: 18px;
        }

        .panel-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 14px;
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        th,
        td {
            padding: 10px 8px;
            border-bottom: 1px solid #e8ecef;
            vertical-align: middle;
        }

        th {
            color: #64748b;
            font-size: 12px;
            white-space: nowrap;
        }

        td img {
            width: 52px;
            height: 40px;
            border-radius: 4px;
            object-fit: cover;
        }

        .row-actions {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .row-actions form {
            margin: 0;
        }

        .row-actions .button {
            padding: 7px 9px;
            font-size: 12px;
        }

        .category-stat {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid #e8ecef;
        }

        .category-actions {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .category-actions form {
            margin: 0;
        }

        .category-actions .button {
            padding: 6px 8px;
            font-size: 11px;
        }

        .category-stat:last-child {
            border-bottom: 0;
        }

        .badge {
            min-width: 25px;
            padding: 3px 7px;
            border-radius: 12px;
            color: #fff;
            background: #176bce;
            text-align: center;
            font-size: 12px;
        }

        .empty {
            padding: 20px;
            color: #64748b;
            text-align: center;
        }

        @media (max-width: 1000px) {
            .stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 700px) {
            .admin-shell {
                grid-template-columns: 1fr;
            }

            .sidebar {
                position: static;
                height: auto;
            }

            .side-link,
            .category-filter {
                display: inline-block;
                width: auto;
                padding: 9px 12px;
            }

            .side-label {
                margin-top: 12px;
            }

            .topbar {
                padding: 0 16px;
            }

            .content {
                padding: 18px 14px;
            }
        }

        @media (max-width: 480px) {
            .stats {
                grid-template-columns: 1fr 1fr;
                gap: 8px;
            }

            .stat-card {
                padding: 12px;
            }

            .stat-value {
                font-size: 17px;
            }

            .product-form {
                grid-template-columns: 1fr;
            }

            .field.full,
            .form-actions {
                grid-column: auto;
            }
        }
    </style>
</head>

<body>
    <div class="admin-shell">
        <aside class="sidebar">
            <a class="brand" href="index.php?act=admin">◆ MạnhDuy Admin</a>
            <div class="profile">
                <span class="avatar">A</span>
                <span class="profile-name"><?= $escape($_SESSION['user']['name'] ?? 'Admin') ?></span>
            </div>

            <p class="side-label">Tổng quan</p>
            <a class="side-link" href="#overview">▦ &nbsp; Dashboard</a>
            <p class="side-label">Quản lý</p>
            <a class="side-link" href="#products">▣ &nbsp; Sản phẩm</a>
            <a class="side-link" href="#product-form">＋ &nbsp; Thêm sản phẩm</a>
            <button class="side-label category-toggle" type="button" aria-expanded="true" aria-controls="category-menu">
                <span>Danh mục</span>
                <span class="category-chevron" aria-hidden="true">▼</span>
            </button>
            <div id="category-menu">
                <a class="side-link" href="#category-form">＋ &nbsp; Thêm danh mục</a>
                <button class="category-filter active" type="button" data-category="all" aria-pressed="true">Tất cả</button>
                <?php foreach ($categories as $category): ?>
                    <button class="category-filter" type="button" data-category="<?= (int) $category['id'] ?>"
                        aria-pressed="false"><?= $escape($category['name']) ?></button>
                <?php endforeach; ?>
            </div>

            <p class="side-label">Hệ thống</p>
            <a class="side-link" href="index.php?act=%2F">↗ &nbsp; Xem trang chủ</a>
            <form class="logout-form" method="post" action="index.php?act=logout">
                <input type="hidden" name="csrf_token" value="<?= $escape(csrfToken()) ?>">
                <button class="side-link" type="submit">⇥ &nbsp; Đăng xuất</button>
            </form>
        </aside>

        <main class="admin-main" id="overview">
            <header class="topbar">
                <span class="topbar-title">Dashboard</span>
                <div class="topbar-actions">
                    <a class="home-link" href="index.php?act=%2F">↗ Về trang chủ</a>
                    <form class="logout-form" method="post" action="index.php?act=logout">
                        <input type="hidden" name="csrf_token" value="<?= $escape(csrfToken()) ?>">
                        <button class="logout-button" type="submit">⇥ Đăng xuất</button>
                    </form>
                </div>
            </header>

            <div class="content">
                <h1>Hệ thống quản trị cửa hàng</h1>
                <p class="muted">Xin chào, <?= $escape($_SESSION['user']['name'] ?? 'Admin') ?>.</p>

                <?php if ($message !== ''): ?>
                    <p class="notice" role="status"><?= $escape($message) ?></p>
                <?php endif; ?>
                <?php if ($error !== ''): ?>
                    <p class="notice error" role="alert"><?= $escape($error) ?></p>
                <?php endif; ?>

                <section class="stats" aria-label="Thống kê cửa hàng">
                    <div class="stat-card">
                        <div class="stat-label">Tổng sản phẩm</div>
                        <div class="stat-value"><?= count($products) ?></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Danh mục</div>
                        <div class="stat-value"><?= count($categories) ?></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Sản phẩm trong kho</div>
                        <div class="stat-value"><?= number_format($totalQuantity) ?></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Giá trị tồn kho ước tính</div>
                        <div class="stat-value"><?= number_format($inventoryValue, 0, ',', '.') ?> ₫</div>
                    </div>
                </section>

                <details class="panel form-panel" id="category-form" <?= $categoryError !== '' || $editingCategory ? 'open' : '' ?>>
                    <summary><?= $editingCategory ? 'Chỉnh sửa danh mục' : '＋ Thêm danh mục' ?></summary>
                    <?php if ($categoryError !== ''): ?>
                        <p class="notice error" role="alert"><?= $escape($categoryError) ?></p>
                    <?php endif; ?>
                    <form class="product-form" method="post" action="index.php?act=admin-category-save">
                        <input type="hidden" name="csrf_token" value="<?= $escape(csrfToken()) ?>">
                        <?php if ($editingCategory): ?>
                            <input type="hidden" name="id" value="<?= (int) $editingCategory['id'] ?>">
                        <?php endif; ?>
                        <div class="field full">
                            <label for="category_name">Tên danh mục</label>
                            <input id="category_name" name="category_name" type="text" maxlength="100" required
                                value="<?= $escape($editingCategory ? $editingCategory['name'] : $categoryName) ?>">
                        </div>
                        <div class="form-actions">
                            <button class="button" type="submit"><?= $editingCategory ? 'Lưu thay đổi' : 'Thêm danh mục' ?></button>
                            <?php if ($editingCategory): ?>
                                <a class="button secondary" href="index.php?act=admin">Hủy</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </details>

                <details class="panel form-panel" id="product-form" <?= $editingProduct || $error !== '' ? 'open' : '' ?>>
                    <summary><?= $editingProduct ? 'Chỉnh sửa sản phẩm' : '＋ Thêm sản phẩm' ?></summary>
                    <form class="product-form" method="post" action="index.php?act=admin-save"
                        enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?= $escape(csrfToken()) ?>">
                        <?php if ($editingProduct): ?>
                            <input type="hidden" name="id" value="<?= (int) $editingProduct['id'] ?>">
                        <?php endif; ?>

                        <div class="field">
                            <label for="name">Tên sản phẩm</label>
                            <input id="name" name="name" type="text" maxlength="50" required
                                value="<?= $escape($formProduct['name'] ?? '') ?>">
                        </div>
                        <div class="field">
                            <label for="price">Giá (₫)</label>
                            <input id="price" name="price" type="number" min="0" step="0.01" required
                                value="<?= $escape($formProduct['price'] ?? '') ?>">
                        </div>
                        <div class="field">
                            <label for="quantity">Số lượng</label>
                            <input id="quantity" name="quantity" type="number" min="0" step="1" required
                                value="<?= $escape($formProduct['quantity'] ?? '') ?>">
                        </div>
                        <div class="field">
                            <label for="category_id">Danh mục</label>
                            <select id="category_id" name="category_id" required>
                                <option value="">-- Chọn danh mục --</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= (int) $category['id'] ?>" <?= (string) ($formProduct['category_id'] ?? '') === (string) $category['id'] ? 'selected' : '' ?>>
                                        <?= $escape($category['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="field">
                            <label for="image">Ảnh (JPG, PNG, WEBP; tối đa 5 MB)</label>
                            <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp">
                            <?php if ($editingProduct && !empty($editingProduct['image'])): ?>
                                <label class="checkbox">
                                    <input type="checkbox" name="remove_image" value="1"> Xóa ảnh hiện tại
                                </label>
                            <?php endif; ?>
                        </div>
                        <div class="field">
                            <label for="description">Mô tả</label>
                            <textarea id="description" name="description"
                                maxlength="500"><?= $escape($formProduct['description'] ?? '') ?></textarea>
                        </div>
                        <div class="form-actions">
                            <button class="button"
                                type="submit"><?= $editingProduct ? 'Lưu thay đổi' : 'Thêm sản phẩm' ?></button>
                            <?php if ($editingProduct): ?>
                                <a class="button secondary" href="index.php?act=admin">Hủy</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </details>

                <div class="dashboard-grid">
                    <section class="panel" id="products">
                        <div class="panel-heading">
                            <h2>Danh sách sản phẩm</h2>
                            <span class="muted"><?= count($products) ?> sản phẩm</span>
                        </div>
                        <?php if ($products === []): ?>
                            <p class="empty" id="filtered-empty">Chưa có sản phẩm nào.</p>
                        <?php else: ?>
                            <p class="empty" id="filtered-empty" hidden>Danh mục này chưa có sản phẩm.</p>
                            <div class="table-wrap">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Ảnh</th>
                                            <th>Sản phẩm</th>
                                            <th>Danh mục</th>
                                            <th>Giá</th>
                                            <th>Kho</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($products as $product): ?>
                                            <?php
                                            $imagePath = str_replace('\\', '/', trim((string) ($product['image'] ?? '')));
                                            $hasImage = $imagePath !== '' && !str_contains($imagePath, '..')
                                                && is_file(PATH_ROOT . ltrim($imagePath, '/'));
                                            $imageUrl = $hasImage
                                                ? implode('/', array_map('rawurlencode', explode('/', ltrim($imagePath, '/'))))
                                                : '';
                                            ?>
                                            <tr data-category="<?= (int) $product['category_id'] ?>">
                                                <td>
                                                    <?php if ($hasImage): ?>
                                                        <img src="<?= $escape($imageUrl) ?>" alt="">
                                                    <?php else: ?>
                                                        —
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= $escape($product['name']) ?></td>
                                                <td><?= $escape($product['category_name'] ?? 'Khác') ?></td>
                                                <td><?= number_format((float) $product['price'], 0, ',', '.') ?> ₫</td>
                                                <td><?= (int) $product['quantity'] ?></td>
                                                <td>
                                                    <div class="row-actions">
                                                        <a class="button secondary"
                                                            href="index.php?act=admin&amp;id=<?= (int) $product['id'] ?>">Sửa</a>
                                                        <form method="post" action="index.php?act=admin-delete"
                                                            onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">
                                                            <input type="hidden" name="csrf_token"
                                                                value="<?= $escape(csrfToken()) ?>">
                                                            <input type="hidden" name="id" value="<?= (int) $product['id'] ?>">
                                                            <button class="button danger" type="submit">Xóa</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </section>

                    <section class="panel">
                        <div class="panel-heading">
                            <h2>Danh mục nhanh</h2>
                        </div>
                        <?php if ($categoryCounts === []): ?>
                            <p class="muted">Chưa có danh mục.</p>
                        <?php else: ?>
                            <?php foreach ($categoryCounts as $category): ?>
                                <div class="category-stat">
                                    <span><?= $escape($category['name']) ?></span>
                                    <div class="category-actions">
                                        <span class="badge"><?= $category['count'] ?></span>
                                        <a class="button secondary"
                                            href="index.php?act=admin&amp;category_id=<?= (int) $category['id'] ?>">Sửa</a>
                                        <form method="post" action="index.php?act=admin-category-delete"
                                            onsubmit="return confirm('Bạn có chắc muốn xóa danh mục này?')">
                                            <input type="hidden" name="csrf_token" value="<?= $escape(csrfToken()) ?>">
                                            <input type="hidden" name="id" value="<?= (int) $category['id'] ?>">
                                            <button class="button danger" type="submit">Xóa</button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </section>
                </div>
            </div>
        </main>
    </div>
    <script>
        const categoryToggle = document.querySelector('.category-toggle');
        const categoryMenu = document.getElementById('category-menu');
        categoryToggle.addEventListener('click', () => {
            const expanded = categoryToggle.getAttribute('aria-expanded') === 'true';
            categoryToggle.setAttribute('aria-expanded', String(!expanded));
            categoryMenu.hidden = expanded;
        });

        const categoryButtons = [...document.querySelectorAll('.category-filter')];
        const productRows = [...document.querySelectorAll('tbody tr[data-category]')];
        const emptyState = document.getElementById('filtered-empty');

        categoryButtons.forEach((button) => {
            button.addEventListener('click', () => {
                const selectedCategory = button.dataset.category;
                let visibleCount = 0;

                categoryButtons.forEach((item) => {
                    const active = item === button;
                    item.classList.toggle('active', active);
                    item.setAttribute('aria-pressed', String(active));
                });
                productRows.forEach((row) => {
                    const visible = selectedCategory === 'all' || row.dataset.category === selectedCategory;
                    row.hidden = !visible;
                    if (visible) visibleCount++;
                });
                if (emptyState) emptyState.hidden = visibleCount > 0;
            });
        });
    </script>
</body>

</html>