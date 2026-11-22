<?php
class AuthController
{
    private AuthModel $authModel;

    public function __construct()
    {
        $this->authModel = new AuthModel();
    }

    public function login(): void
    {
        if ($this->isLoggedIn()) {
            $this->redirectHome();
        }

        $error = '';
        $email = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $this->postValue('email');
            $password = $this->postValue('password');

            if (!isValidCsrfToken($_POST['csrf_token'] ?? null)) {
                $error = 'Phiên làm việc không hợp lệ. Vui lòng tải lại trang.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
                $error = 'Vui lòng nhập email và mật khẩu hợp lệ.';
            } else {
                $user = $this->authModel->findByEmail(strtolower(trim($email)));

                if ($user !== false && password_verify($password, $user['password'])) {
                    $this->startUserSession($user);
                    $this->redirectHome();
                }

                $error = 'Email hoặc mật khẩu không chính xác.';
            }
        }

        $title = 'Đăng nhập';
        require './views/login.php';
    }

    public function register(): void
    {
        if ($this->isLoggedIn()) {
            $this->redirectHome();
        }

        $error = '';
        $name = '';
        $email = '';
        $phone = '';
        $address = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($this->postValue('name'));
            $email = strtolower(trim($this->postValue('email')));
            $phone = trim($this->postValue('phone'));
            $address = trim($this->postValue('address'));
            $password = $this->postValue('password');
            $confirmPassword = $this->postValue('confirm_password');

            if (!isValidCsrfToken($_POST['csrf_token'] ?? null)) {
                $error = 'Phiên làm việc không hợp lệ. Vui lòng tải lại trang.';
            } elseif ($name === '' || strlen($name) > 100) {
                $error = 'Tên không được để trống và tối đa 100 ký tự.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 100) {
                $error = 'Vui lòng nhập địa chỉ email hợp lệ.';
            } elseif ($phone === '' || strlen($phone) > 20) {
                $error = 'Số điện thoại không được để trống và tối đa 20 ký tự.';
            } elseif ($address === '' || strlen($address) > 255) {
                $error = 'Địa chỉ không được để trống và tối đa 255 ký tự.';
            } elseif (strlen($password) < 8) {
                $error = 'Mật khẩu cần có ít nhất 8 ký tự.';
            } elseif ($password !== $confirmPassword) {
                $error = 'Mật khẩu xác nhận không khớp.';
            } else {
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                if (!$this->authModel->createUser($name, $email, $passwordHash, $phone, $address)) {
                    $error = 'Email này đã được đăng ký.';
                } else {
                    $user = $this->authModel->findByEmail($email);
                    if ($user === false) {
                        throw new RuntimeException('Không thể tải thông tin tài khoản vừa tạo.');
                    }

                    $this->startUserSession($user);
                    $this->redirectHome();
                }
            }
        }

        $title = 'Đăng ký';
        require './views/register.php';
    }

    public function logout(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            header('Allow: POST');
            echo 'Phương thức không được hỗ trợ.';
            return;
        }

        if (!isValidCsrfToken($_POST['csrf_token'] ?? null)) {
            http_response_code(400);
            echo 'Phiên làm việc không hợp lệ. Vui lòng tải lại trang.';
            return;
        }

        $_SESSION = [];
        session_regenerate_id(true);
        $this->redirectHome();
    }

    private function isLoggedIn(): bool
    {
        return isset($_SESSION['user']['id']);
    }

    private function postValue(string $key): string
    {
        $value = $_POST[$key] ?? '';

        return is_string($value) ? $value : '';
    }

    private function startUserSession(array $user): void
    {
        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id' => (int) $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => (int) $user['role'],
        ];
    }

    private function redirectHome(): void
    {
        header('Location: index.php?act=%2F');
        exit;
    }
}
