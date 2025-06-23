# Ứng dụng Quản lý Shop Cho Thuê Quần Áo Nữ

Ứng dụng web được xây dựng bằng Laravel để quản lý shop cho thuê quần áo nữ với các tính năng chính:

## 🎯 Tính năng chính

### 1. Quản lý Sản phẩm (Tab 1)
- **Thêm sản phẩm mới**: Nhập mã sản phẩm, tên, mô tả, hình ảnh
- **Thông tin giá**: Giá mua về và giá cho thuê
- **Ngày mua**: Theo dõi ngày mua về sản phẩm
- **Trạng thái**: Có sẵn, đang thuê, bảo trì
- **Chỉnh sửa/Xóa**: Quản lý thông tin sản phẩm

### 2. Quản lý Thuê (Tab 2)
- **Tìm kiếm sản phẩm**: Nhập mã sản phẩm để tìm và hiển thị thông tin
- **Thông tin khách hàng**: Tên, số điện thoại, email, địa chỉ
- **Tạo đơn thuê**: Ngày thuê, ngày trả dự kiến, giá thuê, tiền cọc
- **Trả sản phẩm**: Cập nhật trạng thái khi khách trả
- **Theo dõi quá hạn**: Cảnh báo đơn thuê quá hạn

### 3. Dashboard
- **Thống kê tổng quan**: Tổng sản phẩm, đang thuê, doanh thu
- **Hoạt động gần đây**: Đơn thuê và sản phẩm mới
- **Cảnh báo**: Đơn thuê quá hạn
- **Thao tác nhanh**: Liên kết đến các chức năng chính

## 🚀 Cài đặt và Chạy

### Yêu cầu hệ thống
- PHP 8.2+
- Laravel 12.0+
- MySQL/PostgreSQL
- Composer

### Bước 1: Clone và cài đặt
```bash
git clone <repository-url>
cd auris
composer install
```

### Bước 2: Cấu hình môi trường
```bash
cp .env.example .env
php artisan key:generate
```

Cấu hình database trong file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=auris
DB_USERNAME=root
DB_PASSWORD=
```

### Bước 3: Chạy migration và seeder
```bash
php artisan migrate
php artisan db:seed
php artisan storage:link
```

### Bước 4: Khởi chạy server
```bash
php artisan serve
```

Truy cập: `http://localhost:8000`

## 📋 Cấu trúc Database

### Bảng `products`
- `id`: ID sản phẩm
- `product_code`: Mã sản phẩm (unique)
- `name`: Tên sản phẩm
- `description`: Mô tả
- `image`: Đường dẫn hình ảnh
- `purchase_price`: Giá mua về
- `rental_price`: Giá cho thuê
- `purchase_date`: Ngày mua về
- `status`: Trạng thái (available/rented/maintenance)

### Bảng `customers`
- `id`: ID khách hàng
- `name`: Tên khách hàng
- `phone`: Số điện thoại
- `email`: Email (optional)
- `address`: Địa chỉ (optional)

### Bảng `rentals`
- `id`: ID đơn thuê
- `product_id`: ID sản phẩm (foreign key)
- `customer_id`: ID khách hàng (foreign key)
- `rental_date`: Ngày thuê
- `expected_return_date`: Ngày trả dự kiến
- `actual_return_date`: Ngày trả thực tế
- `rental_price`: Giá thuê
- `deposit`: Tiền cọc
- `status`: Trạng thái (active/returned/overdue)
- `notes`: Ghi chú

## 🎨 Giao diện

### Thiết kế
- **Responsive**: Tương thích với mọi thiết bị
- **Modern UI**: Sử dụng Bootstrap 5 và Font Awesome
- **User-friendly**: Giao diện thân thiện, dễ sử dụng
- **Color scheme**: Gradient tím-xanh sang trọng

### Layout
- **Sidebar**: Navigation chính với các menu
- **Main content**: Khu vực hiển thị nội dung
- **Cards**: Hiển thị thông tin trong các khối
- **Tables**: Danh sách dữ liệu có phân trang

## 🔧 Tính năng kỹ thuật

### Validation
- Kiểm tra dữ liệu đầu vào
- Thông báo lỗi rõ ràng
- Xác thực tính duy nhất của mã sản phẩm

### Business Logic
- Kiểm tra trạng thái sản phẩm trước khi cho thuê
- Tính toán ngày quá hạn tự động
- Cập nhật trạng thái sản phẩm khi thuê/trả

### Security
- CSRF protection
- Input sanitization
- File upload validation

## 📱 Sử dụng

### 1. Thêm sản phẩm
1. Vào menu "Quản lý Sản phẩm"
2. Click "Thêm sản phẩm"
3. Điền đầy đủ thông tin
4. Upload hình ảnh (tùy chọn)
5. Lưu sản phẩm

### 2. Tạo đơn thuê
1. Vào menu "Tạo đơn thuê"
2. Nhập mã sản phẩm và tìm kiếm
3. Điền thông tin khách hàng
4. Chọn ngày thuê và ngày trả
5. Nhập giá thuê và tiền cọc
6. Tạo đơn thuê

### 3. Quản lý đơn thuê
1. Vào menu "Quản lý Thuê"
2. Xem danh sách đơn thuê đang hoạt động
3. Click "Trả sản phẩm" khi khách trả
4. Theo dõi đơn thuê quá hạn

## 🧪 Dữ liệu mẫu

Sau khi chạy seeder, hệ thống sẽ có:
- 5 sản phẩm mẫu (váy dạ hội, váy cưới, váy cocktail...)
- 3 khách hàng mẫu
- 2 đơn thuê mẫu (1 đang thuê, 1 đã trả)

## 🔄 Cập nhật và Bảo trì

### Backup database
```bash
php artisan backup:run
```

### Clear cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Update dependencies
```bash
composer update
```

## 📞 Hỗ trợ

Nếu có vấn đề hoặc cần hỗ trợ, vui lòng liên hệ:
- Email: support@example.com
- Phone: 0123-456-789

## 📄 License

MIT License - Xem file LICENSE để biết thêm chi tiết.
