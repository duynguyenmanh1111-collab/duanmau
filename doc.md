### Cấu trúc thư mục

1. commons // File dùng chung cả dự án
2. uploads // Folder lưu trữ file upload

3. controllers // Xử lý logic
4. models // Thao tác cơ sở dữ liệu
5. views // Hiển thị
6. index.php // Điều hướng


// Không sử dụng đường dẫn tuyệt đối(file.php)
localhost/mvc-basic/trang-chu.php - Không dùng cách này nữa
-> Dùng route: Gọi đến hàm trong controller để thực thi(hàm không trả về)
- Hàm không trả về: không có return - Chỉ dùng để thực thi đoạn mã, và hàm này không trả về giá trị
- Hàm có trả về: Có return - Thực thi đoạn mã và trả về giá trị

Class - object - Hàm - Mảng


Mô hình MVC 
- Model: Dùng để tương tác với cơ sở dữ liệu
- View: Hiển trị, nhận yêu cầu người dùng
- Controller: Dùng để thực thi các đoạn mã xử lý logic


- Cấu hình DB trong file commons/env.php

### Tài khoản

Chọn database `duanmau` trong phpMyAdmin và import file `users.sql` để tạo bảng tài khoản. Form đăng ký lưu họ tên, email, mật khẩu dạng hash, số điện thoại và địa chỉ; người dùng có thể đăng ký, đăng nhập và đăng xuất từ trang chủ.

### Trang quản trị

Tài khoản đăng ký mới mặc định là người dùng thường. Để cấp quyền quản trị cho tài khoản, đăng nhập phpMyAdmin vào database `duanmau` và chạy (thay email bằng email tài khoản admin):

```sql
UPDATE users SET role = 1 WHERE email = 'admin@example.com';
```

Sau đó đăng xuất rồi đăng nhập lại để áp dụng quyền mới. Tài khoản admin có thể vào `index.php?act=admin` để thêm danh mục, thêm, sửa và xóa sản phẩm; các thao tác thay đổi dữ liệu chỉ được chấp nhận bằng POST có CSRF token hợp lệ.

Trước khi lưu sản phẩm có giá lớn hơn 2.147.483.647 ₫, chạy nội dung `migrations/20261118_expand_product_price.sql` trên database `duanmau` bằng phpMyAdmin để đổi cột giá sang kiểu số thập phân.