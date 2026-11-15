<?php
$escape = static fn($value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Khám phá các mẫu xe chất lượng với mức giá tốt tại Autohaus.">
    <title><?= $escape($title) ?> | Autohaus</title>
    <style>
        :root {
            color-scheme: light;
            --ink: #17211f;
            --muted: #737c79;
            --line: #e8ece9;
            --green: #1d5b48;
            --green-dark: #143f34;
            --cream: #f5f5ef;
            --white: #fff;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            color: var(--ink);
            background: #fff;
            font-family: Inter, "Segoe UI", Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button,
        input {
            font: inherit;
        }

        .container {
            width: min(1160px, calc(100% - 48px));
            margin: 0 auto;
        }

        .topbar {
            background: var(--green-dark);
            color: #e8f1ec;
            font-size: 12px;
        }

        .topbar-inner {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            padding: 10px 0;
        }

        .topbar-note {
            color: #c2d5cb;
        }

        .navbar {
            border-bottom: 1px solid var(--line);
            background: #fff;
        }

        .nav-inner {
            min-height: 78px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 32px;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -.8px;
        }

        .brand-mark {
            width: 34px;
            height: 34px;
            display: grid;
            place-items: center;
            border-radius: 11px;
            color: white;
            background: var(--green);
        }

        .brand-mark svg {
            width: 19px;
            height: 19px;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 32px;
            color: #59615e;
            font-size: 14px;
        }

        .nav-links a:hover {
            color: var(--green);
        }

        .nav-cta,
        .button-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            border: 0;
            border-radius: 7px;
            color: white;
            background: var(--green);
            font-size: 13px;
            font-weight: 700;
            transition: background .2s, transform .2s;
        }

        .nav-cta {
            padding: 12px 17px;
        }

        .button-primary {
            padding: 15px 20px;
        }

        .nav-cta:hover,
        .button-primary:hover {
            background: var(--green-dark);
            transform: translateY(-1px);
        }

        .button-arrow {
            font-size: 18px;
            line-height: 12px;
        }

        .hero {
            overflow: hidden;
            background: var(--cream);
        }

        .hero-inner {
            min-height: 450px;
            display: grid;
            grid-template-columns: 1fr 1.05fr;
            align-items: center;
            gap: 32px;
            padding-top: 40px;
            padding-bottom: 40px;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            color: var(--green);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .eyebrow::before {
            width: 24px;
            height: 2px;
            content: "";
            background: #87a898;
        }

        h1 {
            max-width: 500px;
            margin: 19px 0 16px;
            font-size: clamp(42px, 5vw, 64px);
            line-height: 1.02;
            letter-spacing: -3.2px;
        }

        h1 span {
            color: var(--green);
        }

        .hero-copy {
            max-width: 425px;
            margin: 0 0 27px;
            color: var(--muted);
            font-size: 15px;
            line-height: 1.8;
        }

        .hero-actions {
            display: flex;
            align-items: center;
            gap: 19px;
        }

        .text-link {
            color: var(--green);
            font-size: 13px;
            font-weight: 700;
        }

        .hero-trust {
            display: flex;
            align-items: center;
            gap: 11px;
            margin-top: 32px;
            color: var(--muted);
            font-size: 12px;
        }

        .trust-dots {
            display: flex;
            padding-left: 4px;
        }

        .trust-dots span {
            width: 24px;
            height: 24px;
            margin-left: -4px;
            border: 2px solid var(--cream);
            border-radius: 50%;
            background: #adc2b4;
        }

        .trust-dots span:nth-child(2) {
            background: #d3b39a;
        }

        .trust-dots span:nth-child(3) {
            background: #6c837b;
        }

        .trust-dots span:nth-child(4) {
            background: #d4d7c7;
        }

        .hero-visual {
            position: relative;
            min-height: 345px;
            display: grid;
            place-items: center;
        }

        .visual-card {
            position: relative;
            width: min(100%, 520px);
            height: 318px;
            overflow: hidden;
            border-radius: 16px;
            background: #e3e7df;
        }

        .visual-sun {
            position: absolute;
            top: 37px;
            right: 68px;
            width: 74px;
            height: 74px;
            border-radius: 50%;
            background: #d1d9ce;
        }

        .visual-hill {
            position: absolute;
            right: -40px;
            bottom: 86px;
            width: 390px;
            height: 130px;
            border-radius: 50% 50% 0 0;
            background: #c6d0c3;
            transform: rotate(-9deg);
        }

        .visual-hill.back {
            right: 185px;
            bottom: 80px;
            width: 280px;
            height: 110px;
            background: #d1d9ce;
            transform: rotate(12deg);
        }

        .road {
            position: absolute;
            right: -28px;
            bottom: -96px;
            left: -28px;
            height: 170px;
            border-radius: 50% 50% 0 0;
            background: #858e85;
            transform: rotate(-4deg);
        }

        .road::after {
            position: absolute;
            top: 75px;
            right: 15%;
            left: 15%;
            height: 3px;
            content: "";
            border-top: 3px dashed #e2dfd3;
        }

        .hero-car {
            position: absolute;
            z-index: 1;
            right: 50%;
            bottom: 73px;
            width: min(78%, 390px);
            transform: translateX(50%);
            filter: drop-shadow(0 16px 13px #34453f38);
        }

        .hero-label {
            position: absolute;
            z-index: 2;
            right: 18px;
            bottom: 18px;
            padding: 10px 13px;
            border: 1px solid #ffffff99;
            border-radius: 8px;
            color: var(--ink);
            background: #ffffffd9;
            font-size: 11px;
            font-weight: 700;
        }

        .features {
            border-bottom: 1px solid var(--line);
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            padding: 24px 0;
        }

        .feature {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            border-right: 1px solid var(--line);
        }

        .feature:last-child {
            border-right: 0;
        }

        .feature-icon {
            width: 38px;
            height: 38px;
            display: grid;
            place-items: center;
            border-radius: 10px;
            color: var(--green);
            background: #edf3ee;
            font-size: 17px;
        }

        .feature strong {
            display: block;
            font-size: 12px;
        }

        .feature small {
            display: block;
            margin-top: 4px;
            color: var(--muted);
            font-size: 11px;
        }

        .products-section {
            padding: 76px 0 86px;
        }

        .section-heading {
            display: flex;
            align-items: end;
            justify-content: space-between;
            gap: 25px;
            margin-bottom: 28px;
        }

        .section-heading h2 {
            margin: 11px 0 0;
            font-size: 34px;
            letter-spacing: -1.4px;
        }

        .section-subtitle {
            margin: 10px 0 0;
            color: var(--muted);
            font-size: 13px;
        }

        .all-link {
            padding-bottom: 5px;
            color: var(--green);
            font-size: 13px;
            font-weight: 700;
        }

        .catalog-tools {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 24px;
        }

        .filters {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .filter-button {
            padding: 9px 14px;
            border: 1px solid var(--line);
            border-radius: 6px;
            color: #606966;
            background: #fff;
            cursor: pointer;
            font-size: 12px;
            transition: .2s;
        }

        .filter-button:hover,
        .filter-button.active {
            border-color: var(--green);
            color: #fff;
            background: var(--green);
        }

        .search-box {
            position: relative;
            width: 240px;
        }

        .search-box input {
            width: 100%;
            padding: 11px 13px 11px 36px;
            border: 1px solid var(--line);
            border-radius: 6px;
            outline: 0;
            font-size: 12px;
        }

        .search-box input:focus {
            border-color: #83a593;
            box-shadow: 0 0 0 3px #1d5b4812;
        }

        .search-icon {
            position: absolute;
            top: 9px;
            left: 12px;
            color: #808984;
            font-size: 15px;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 22px;
        }

        .product-card {
            overflow: hidden;
            border: 1px solid var(--line);
            border-radius: 10px;
            background: white;
            transition: box-shadow .2s, transform .2s;
        }

        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 35px #18272012;
        }

        .product-image {
            position: relative;
            height: 205px;
            display: grid;
            place-items: center;
            overflow: hidden;
            background: #f2f4f0;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-art {
            width: 75%;
            color: #58776a;
            filter: drop-shadow(0 10px 7px #17211f20);
        }

        .product-art svg {
            width: 100%;
            height: auto;
        }

        .product-category {
            position: absolute;
            top: 13px;
            left: 13px;
            padding: 6px 9px;
            border-radius: 4px;
            color: #355747;
            background: #ffffffd9;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .stock-status {
            position: absolute;
            right: 12px;
            bottom: 12px;
            padding: 6px 9px;
            border-radius: 4px;
            color: #355747;
            background: #ffffffdf;
            font-size: 10px;
        }

        .stock-status.out {
            color: #8c4d42;
        }

        .product-info {
            padding: 17px 17px 16px;
        }

        .product-name {
            min-height: 22px;
            margin: 0;
            font-size: 16px;
            font-weight: 700;
        }

        .product-description {
            display: -webkit-box;
            min-height: 38px;
            overflow: hidden;
            margin: 8px 0 15px;
            color: var(--muted);
            font-size: 11px;
            line-height: 1.65;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
        }

        .product-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding-top: 13px;
            border-top: 1px solid var(--line);
        }

        .product-price {
            color: var(--green);
            font-size: 17px;
            font-weight: 800;
        }

        .detail-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #59615e;
            font-size: 11px;
            font-weight: 700;
        }

        .detail-link:hover {
            color: var(--green);
        }

        .empty-state {
            display: none;
            padding: 44px 20px;
            border: 1px dashed #d6ddd8;
            border-radius: 9px;
            color: var(--muted);
            text-align: center;
            font-size: 14px;
        }

        .empty-state.visible {
            display: block;
        }

        .footer {
            padding: 27px 0;
            color: #c8d5ce;
            background: var(--green-dark);
        }

        .footer-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            font-size: 11px;
        }

        .footer .brand {
            color: #fff;
            font-size: 16px;
        }

        .footer .brand-mark {
            color: var(--green-dark);
            background: #fff;
        }

        @media (max-width: 800px) {
            .nav-links {
                gap: 17px;
            }

            .nav-cta {
                display: none;
            }

            .hero-inner {
                grid-template-columns: 1fr;
                gap: 16px;
                padding-top: 58px;
            }

            .hero-visual {
                min-height: 300px;
            }

            .visual-card {
                height: 285px;
            }

            .product-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 560px) {
            .container {
                width: min(100% - 32px, 440px);
            }

            .topbar-inner {
                justify-content: center;
                text-align: center;
            }

            .topbar-note {
                display: none;
            }

            .nav-inner {
                min-height: 66px;
            }

            .brand {
                font-size: 18px;
            }

            .nav-links {
                gap: 15px;
                font-size: 12px;
            }

            .nav-links a:first-child {
                display: none;
            }

            .hero-inner {
                padding-top: 43px;
                padding-bottom: 28px;
            }

            h1 {
                font-size: 46px;
                letter-spacing: -2.5px;
            }

            .hero-copy {
                font-size: 14px;
            }

            .hero-visual {
                min-height: 255px;
            }

            .visual-card {
                height: 240px;
            }

            .hero-car {
                bottom: 58px;
            }

            .feature-grid {
                grid-template-columns: 1fr;
                gap: 17px;
                padding: 19px 0;
            }

            .feature {
                justify-content: flex-start;
                border: 0;
            }

            .products-section {
                padding: 56px 0;
            }

            .section-heading {
                align-items: flex-start;
            }

            .section-heading h2 {
                font-size: 28px;
            }

            .all-link {
                display: none;
            }

            .catalog-tools {
                align-items: stretch;
                flex-direction: column;
                gap: 13px;
            }

            .search-box {
                width: 100%;
            }

            .product-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .product-image {
                height: 220px;
            }

            .footer-inner {
                align-items: flex-start;
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <div class="topbar">
        <div class="container topbar-inner">
            <span>Không gian chọn xe dành riêng cho bạn</span>
            <span class="topbar-note">Tư vấn tận tâm · Thông tin minh bạch</span>
        </div>
    </div>
    <header class="navbar">
        <div class="container nav-inner">
            <a class="brand" href="#home" aria-label="Autohaus - Trang chủ">
                <span class="brand-mark" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M3 14.5h18l-1.6-5a2 2 0 0 0-1.9-1.4H7.3a2 2 0 0 0-1.9 1.4L3 14.5Z"
                            stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                        <path d="M4 14.5v3h2m12 0h2v-3M7 17.5h10M7 11h10" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" />
                        <circle cx="7" cy="14.5" r="1" fill="currentColor" />
                        <circle cx="17" cy="14.5" r="1" fill="currentColor" />
                    </svg>
                </span>
                MạnhDuyAuto06
            </a>
            <nav class="nav-links" aria-label="Điều hướng chính">
                <a href="#home">Trang chủ</a>
                <a href="#products">Sản phẩm</a>
                <a href="#about">Về chúng tôi</a>
            </nav>
            <a class="nav-cta" href="#products">Khám phá xe <span class="button-arrow" aria-hidden="true">→</span></a>
        </div>
    </header>

    <main>
        <section class="hero" id="home">
            <div class="container hero-inner">
                <div>
                    <span class="eyebrow">Chọn xe thật dễ dàng</span>
                    <h1>Tìm chiếc xe<br><span>của riêng bạn.</span></h1>
                    <p class="hero-copy">Khám phá những mẫu xe được tuyển chọn kỹ lưỡng, phù hợp với phong cách và nhu
                        cầu di chuyển của bạn.</p>
                    <div class="hero-actions">
                        <a class="button-primary" href="#products">Xem bộ sưu tập <span class="button-arrow"
                                aria-hidden="true">→</span></a>
                        <a class="text-link" href="#about">Tìm hiểu thêm</a>
                    </div>
                    <div class="hero-trust">
                        <span class="trust-dots"
                            aria-hidden="true"><span></span><span></span><span></span><span></span></span>
                        <span>Đồng hành cùng bạn trên mọi hành trình</span>
                    </div>
                </div>
                <div class="hero-visual" aria-label="Minh họa xe trên cung đường xanh">
                    <div class="visual-card">
                        <div class="visual-sun"></div>
                        <div class="visual-hill back"></div>
                        <div class="visual-hill"></div>
                        <div class="road"></div>
                        <svg class="hero-car" viewBox="0 0 520 230" role="img" aria-label="Minh họa ô tô">
                            <path
                                d="M65 150 88 112c8-13 22-21 38-23l72-8 43-46c10-11 24-17 39-17h77c18 0 35 9 44 24l34 54 34 12c18 6 31 22 31 41v35H53v-16c0-9 4-16 12-18Z"
                                fill="#315c4d" />
                            <path d="m210 89 40-43c8-9 18-13 30-13h34v56H210Zm87-56h60c13 0 25 7 32 18l25 38H297V33Z"
                                fill="#cbd9ce" />
                            <path d="M64 144h439v35H64z" fill="#25483c" />
                            <path d="M78 132h22M440 132h40" stroke="#e9d8a8" stroke-width="7" stroke-linecap="round" />
                            <circle cx="155" cy="178" r="34" fill="#26302d" />
                            <circle cx="155" cy="178" r="17" fill="#bbc4bc" />
                            <circle cx="155" cy="178" r="7" fill="#65736b" />
                            <circle cx="423" cy="178" r="34" fill="#26302d" />
                            <circle cx="423" cy="178" r="17" fill="#bbc4bc" />
                            <circle cx="423" cy="178" r="7" fill="#65736b" />
                        </svg>
                        <span class="hero-label">Lựa chọn phù hợp, hành trình trọn vẹn</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="features" id="about" aria-label="Cam kết dịch vụ">
            <div class="container feature-grid">
                <div class="feature"><span class="feature-icon" aria-hidden="true">✓</span><span><strong>Thông tin rõ
                            ràng</strong><small>Chi tiết sản phẩm minh bạch</small></span></div>
                <div class="feature"><span class="feature-icon" aria-hidden="true">⌖</span><span><strong>Tư vấn tận
                            tâm</strong><small>Hỗ trợ chọn xe phù hợp</small></span></div>
                <div class="feature"><span class="feature-icon" aria-hidden="true">↗</span><span><strong>Sẵn sàng đồng
                            hành</strong><small>Bắt đầu hành trình của bạn</small></span></div>
            </div>
        </section>

        <section class="products-section" id="products">
            <div class="container">
                <div class="section-heading">
                    <div>
                        <span class="eyebrow">Bộ sưu tập</span>
                        <h2>Những mẫu xe nổi bật</h2>
                        <p class="section-subtitle">Tìm cảm hứng cho hành trình tiếp theo của bạn.</p>
                    </div>
                    <a class="all-link" href="#products">Xem tất cả <span aria-hidden="true">→</span></a>
                </div>
                <div class="catalog-tools">
                    <div class="filters" aria-label="Lọc theo hãng xe">
                        <button class="filter-button active" type="button" data-category="all" aria-pressed="true">Tất
                            cả</button>
                        <?php foreach ($categories as $category): ?>
                            <button class="filter-button" type="button" data-category="<?= $escape($category['id']) ?>"
                                aria-pressed="false"><?= $escape($category['name']) ?></button>
                        <?php endforeach; ?>
                    </div>
                    <label class="search-box">
                        <span class="search-icon" aria-hidden="true">⌕</span>
                        <input id="product-search" type="search" placeholder="Tìm tên xe..." aria-label="Tìm sản phẩm">
                    </label>
                </div>

                <div class="product-grid" id="product-grid">
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
                        <article class="product-card" data-category="<?= $escape($product['category_id']) ?>">
                            <div class="product-image">
                                <?php if ($hasLocalImage): ?>
                                    <img src="<?= $escape($imageUrl) ?>" alt="<?= $escape($product['name']) ?>" loading="lazy">
                                <?php else: ?>
                                    <div class="product-art" aria-hidden="true">
                                        <svg viewBox="0 0 340 150" fill="none">
                                            <path
                                                d="m36 93 15-28c5-9 14-15 24-16l48-5 30-29c6-6 15-10 24-10h48c12 0 23 6 29 16l22 33 23 8c11 4 18 14 18 25v17H28v-4c0-3 3-6 8-7Z"
                                                fill="currentColor" />
                                            <path
                                                d="m135 43 23-22c4-4 9-6 15-6h23v28h-61Zm69-28h36c8 0 15 4 20 11l12 17h-68V15Z"
                                                fill="#d9e1d8" />
                                            <path d="M34 91h303v18H34z" fill="#36594c" />
                                            <circle cx="91" cy="108" r="19" fill="#27332f" />
                                            <circle cx="91" cy="108" r="9" fill="#b9c3bb" />
                                            <circle cx="279" cy="108" r="19" fill="#27332f" />
                                            <circle cx="279" cy="108" r="9" fill="#b9c3bb" />
                                        </svg>
                                    </div>
                                <?php endif; ?>
                                <span class="product-category"><?= $escape($product['category_name'] ?? 'Khác') ?></span>
                                <span class="stock-status<?= (int) $product['quantity'] < 1 ? ' out' : '' ?>">
                                    <?= (int) $product['quantity'] > 0 ? 'Còn hàng' : 'Tạm hết hàng' ?>
                                </span>
                            </div>
                            <div class="product-info">
                                <h3 class="product-name"><?= $escape($product['name']) ?></h3>
                                <p class="product-description">
                                    <?= $escape($description !== '' ? $description : 'Liên hệ để biết thêm thông tin về mẫu xe này.') ?>
                                </p>
                                <div class="product-bottom">
                                    <span class="product-price"><?= number_format((float) $product['price'], 0, ',', '.') ?>
                                        ₫</span>
                                    <a class="detail-link" href="#about">Tìm hiểu <span aria-hidden="true">→</span></a>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
                <div class="empty-state<?= $products === [] ? ' visible' : '' ?>" id="empty-state">
                    Chưa có sản phẩm phù hợp. Hãy thử chọn hãng xe khác hoặc tìm kiếm lại.
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container footer-inner">
            <a class="brand" href="#home"><span class="brand-mark" aria-hidden="true">D</span>MạnhDuyAuto06</a>
            <span>© <?= date('Y') ?> Autohaus. Khởi đầu hành trình theo cách của bạn.</span>
        </div>
    </footer>
    <script>
        (() => {
            const cards = [...document.querySelectorAll('.product-card')];
            const buttons = [...document.querySelectorAll('.filter-button')];
            const search = document.getElementById('product-search');
            const emptyState = document.getElementById('empty-state');
            let selectedCategory = 'all';

            const updateProducts = () => {
                const query = search.value.trim().toLocaleLowerCase('vi');
                let visibleCount = 0;
                cards.forEach((card) => {
                    const categoryMatches = selectedCategory === 'all' || card.dataset.category === selectedCategory;
                    const searchMatches = card.textContent.toLocaleLowerCase('vi').includes(query);
                    const visible = categoryMatches && searchMatches;
                    card.hidden = !visible;
                    if (visible) visibleCount += 1;
                });
                emptyState.classList.toggle('visible', visibleCount === 0);
            };

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
        })();
    </script>
</body>

</html>