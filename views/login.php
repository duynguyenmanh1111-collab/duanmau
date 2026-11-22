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
        * { box-sizing: border-box; }
        body { margin: 0; padding: 32px 16px; color: #222; background: #f6f7f8; font: 16px Arial, sans-serif; }
        .form-card { width: min(420px, 100%); margin: 6vh auto; padding: 28px; border: 1px solid #e5e7eb; border-radius: 8px; background: #fff; }
        a { color: #176b48; text-decoration: none; }
        h1 { margin: 0 0 8px; font-size: 26px; }
        .hint { margin: 0 0 22px; color: #666; }
        label { display: block; margin: 14px 0 6px; font-size: 14px; }
        input { width: 100%; padding: 11px; border: 1px solid #ccc; border-radius: 5px; font: inherit; }
        button { width: 100%; margin-top: 20px; padding: 12px; border: 0; border-radius: 5px; color: #fff; background: #176b48; cursor: pointer; font: inherit; }
        .error { padding: 10px; border-radius: 5px; color: #9c2525; background: #fff0f0; font-size: 14px; }
        .switch { margin: 20px 0 0; color: #666; text-align: center; font-size: 14px; }
    </style>
</head>
<body>
    <main class="form-card">
        <h1>Đăng nhập</h1>
        <p class="hint">Đăng nhập vào tài khoản của bạn.</p>
        <?php if ($error !== ''): ?>
            <p class="error" role="alert"><?= $escape($error) ?></p>
        <?php endif; ?>
        <form method="post" action="index.php?act=login">
            <input type="hidden" name="csrf_token" value="<?= $escape(csrfToken()) ?>">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="<?= $escape($email) ?>" maxlength="100"
                autocomplete="email" required>
            <label for="password">Mật khẩu</label>
            <input id="password" name="password" type="password" autocomplete="current-password" required>
            <button type="submit">Đăng nhập</button>
        </form>
        <p class="switch">Chưa có tài khoản? <a href="index.php?act=register">Đăng ký</a></p>
        <p class="switch"><a href="index.php?act=%2F">Quay về trang chủ</a></p>
    </main>
</body>
</html>
