# 🎀 AURIS - Hệ thống Quản lý Shop Cho Thuê Quần Áo Nữ

Ứng dụng web được xây dựng bằng **Laravel 12** để quản lý shop cho thuê quần áo nữ với đầy đủ tính năng từ cơ bản đến nâng cao.

## 🎯 Tính năng chính

### 📦 1. Quản lý Sản phẩm
- **Thêm/Sửa/Xóa sản phẩm** với đầy đủ thông tin
- **Upload hình ảnh** sản phẩm với validation
- **Mã sản phẩm unique** để dễ quản lý
- **Thông tin giá**: Giá mua về, giá cho thuê, giá cọc
- **Trạng thái sản phẩm**: Có sẵn, đang thuê, bảo trì
- **Tìm kiếm sản phẩm** theo mã hoặc tên
- **Phân trang** danh sách sản phẩm

### 🛍️ 2. Quản lý Đơn thuê
- **Tạo đơn thuê mới** với nhiều sản phẩm
- **Tìm kiếm sản phẩm** bằng mã sản phẩm
- **Thông tin khách hàng**: Tên, số điện thoại, email, địa chỉ
- **Tính tiền thuê tự động** theo số ngày và quy tắc:
  - Ngày đầu: Giá thuê cơ bản
  - Ngày thứ 2: +20.000 VNĐ
  - Từ ngày thứ 3: +10.000 VNĐ/ngày
- **Hai loại cọc**:
  - **Cọc tiền**: Trừ trực tiếp vào tiền cọc khi gia hạn
  - **Cọc căn cước**: Thu thêm tiền khi gia hạn
- **Hình thức thanh toán cọc**: Tiền mặt, Momo, Techcombank
- **Gia hạn đơn thuê** với tính tiền tự động
- **Trả sản phẩm** với tính phạt trễ hạn
- **Theo dõi quá hạn** và cảnh báo

### 📊 3. Lịch sử Đơn thuê
- **Xem toàn bộ lịch sử** đơn thuê đã hoàn thành
- **Tìm kiếm** theo tên khách hàng hoặc số điện thoại
- **Phân trang** lịch sử
- **Thông tin chi tiết** từng đơn thuê

### 📈 4. Báo cáo & Thống kê
- **Dashboard tổng quan** với các chỉ số quan trọng
- **Doanh thu theo tháng** (12 tháng gần nhất)
- **Top sản phẩm** thuê nhiều nhất/ít nhất
- **Top khách hàng** thuê nhiều nhất
- **Đơn thuê đang hoạt động** và quá hạn
- **Tổng tiền phạt** trễ hạn
- **Bộ lọc theo thời gian** cho báo cáo

### 📝 5. Ghi chú & Quản lý
- **Thêm ghi chú** cho từng đơn thuê
- **Quản lý ghi chú** riêng biệt
- **Chỉnh sửa ghi chú** khi cần

### 🔐 6. Hệ thống Đăng nhập
- **Đăng nhập** bằng tên đăng nhập và mật khẩu
- **Bảo mật** với middleware auth
- **Đăng xuất** an toàn

## 🚀 Cài đặt và Chạy

### Yêu cầu hệ thống
- **PHP 8.2+**
- **Laravel 12.0+**
- **MySQL/PostgreSQL/SQLite**
- **Composer**
- **Node.js & NPM** (cho frontend assets)

### Bước 1: Clone và cài đặt
```bash
git clone <repository-url>
cd auris
composer install
npm install
```

### Bước 2: Cấu hình môi trường
```bash
cp .env.example .env
php artisan key:generate
```

Cấu hình database trong file `.env`:
```env
DB_CONNECTION=sqlite
DB_DATABASE=/path/to/database.sqlite
# Hoặc MySQL
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=auris
# DB_USERNAME=root
# DB_PASSWORD=
```

### Bước 3: Chạy migration và seeder
```bash
php artisan migrate:fresh --seed
php artisan storage:link
```

### Bước 4: Build assets (nếu cần)
```bash
npm run build
```

### Bước 5: Khởi chạy server
```bash
php artisan serve
```

Truy cập: `http://localhost:8000`

### 🔑 Tài khoản mặc định
- **Tên đăng nhập:** `admin`
- **Mật khẩu:** `1999`

## 📋 Cấu trúc Database

### Bảng `products`
- `id`: ID sản phẩm
- `product_code`: Mã sản phẩm (unique)
- `name`: Tên sản phẩm
- `description`: Mô tả
- `image`: Đường dẫn hình ảnh
- `purchase_price`: Giá mua về
- `rental_price`: Giá cho thuê
- `deposit_price`: Giá cọc
- `purchase_date`: Ngày mua về
- `status`: Trạng thái (available/rented/maintenance)

### Bảng `customers`
- `id`: ID khách hàng
- `name`: Tên khách hàng
- `phone`: Số điện thoại (unique)
- `email`: Email (optional)
- `address`: Địa chỉ (optional)

### Bảng `rentals`
- `id`: ID đơn thuê
- `customer_id`: ID khách hàng (foreign key)
- `rental_date`: Ngày thuê
- `expected_return_date`: Ngày trả dự kiến
- `actual_return_date`: Ngày trả thực tế
- `total_price`: Tổng giá sản phẩm
- `rental_fee`: Tiền thuê
- `deposit_amount`: Tiền cọc
- `deposit_type`: Loại cọc (money/idcard)
- `deposit_payment_method`: Hình thức thanh toán cọc
- `deposit_note`: Ghi chú cọc (số CMND)
- `total_paid`: Tổng tiền khách đã trả
- `refund_amount`: Số tiền hoàn lại
- `status`: Trạng thái (active/returned/overdue)
- `notes`: Ghi chú

### Bảng `rental_items`
- `id`: ID item
- `rental_id`: ID đơn thuê (foreign key)
- `product_id`: ID sản phẩm (foreign key)
- `price`: Giá thuê của sản phẩm

### Bảng `notes`
- `id`: ID ghi chú
- `content`: Nội dung ghi chú
- `note_date`: Ngày ghi chú

## 🎨 Giao diện

### Thiết kế
- **Responsive Design**: Tương thích mọi thiết bị (desktop, tablet, mobile)
- **Modern UI**: Bootstrap 5 + Font Awesome icons
- **User-friendly**: Giao diện thân thiện, dễ sử dụng
- **Color scheme**: Gradient tím-xanh sang trọng
- **Dark/Light mode**: Tùy chọn giao diện

### Layout
- **Sidebar Navigation**: Menu chính với các chức năng
- **Main Content**: Khu vực hiển thị nội dung
- **Cards Layout**: Hiển thị thông tin trong các khối đẹp mắt
- **Data Tables**: Danh sách dữ liệu có phân trang và tìm kiếm
- **Modal Dialogs**: Các form popup cho thao tác nhanh

## 🔧 Tính năng kỹ thuật

### Validation & Security
- **Form Validation**: Kiểm tra dữ liệu đầu vào chặt chẽ
- **CSRF Protection**: Bảo vệ chống tấn công CSRF
- **File Upload Security**: Validation hình ảnh an toàn
- **Input Sanitization**: Làm sạch dữ liệu đầu vào
- **SQL Injection Protection**: Bảo vệ database

### Business Logic
- **Product Availability Check**: Kiểm tra trạng thái sản phẩm trước khi cho thuê
- **Automatic Overdue Detection**: Tự động phát hiện đơn thuê quá hạn
- **Smart Pricing Calculation**: Tính tiền thuê theo quy tắc phức tạp
- **Deposit Management**: Quản lý cọc tiền và cọc căn cước
- **Extension Fee Calculation**: Tính tiền gia hạn tự động
- **Late Fee Calculation**: Tính phạt trễ hạn

### Performance & Optimization
- **Database Indexing**: Tối ưu truy vấn database
- **Eager Loading**: Giảm số lượng query
- **Pagination**: Phân trang dữ liệu lớn
- **Caching**: Cache các dữ liệu thường dùng
- **Image Optimization**: Tối ưu hình ảnh upload

## 📱 Hướng dẫn sử dụng

### 1. Thêm sản phẩm mới
1. Vào menu "Quản lý Sản phẩm"
2. Click "Thêm sản phẩm"
3. Điền đầy đủ thông tin (mã SP, tên, giá...)
4. Upload hình ảnh sản phẩm (tùy chọn)
5. Click "Lưu sản phẩm"

### 2. Tạo đơn thuê mới
1. Vào menu "Tạo đơn thuê"
2. Nhập mã sản phẩm và tìm kiếm
3. Thêm sản phẩm vào giỏ hàng
4. Điền thông tin khách hàng
5. Chọn ngày thuê và ngày trả
6. Chọn loại cọc (tiền/căn cước)
7. Nhập thông tin cọc
8. Click "Lưu đơn thuê"

### 3. Gia hạn đơn thuê
1. Vào "Quản lý Đơn thuê"
2. Click "Gia hạn" trên đơn cần gia hạn
3. Chọn số ngày gia hạn
4. Xem ước tính tiền gia hạn
5. Click "Xác nhận gia hạn"

### 4. Trả sản phẩm
1. Vào "Quản lý Đơn thuê"
2. Click "Đánh dấu đã trả"
3. Hệ thống tự động tính phạt nếu trễ hạn
4. Hiển thị thông tin hoàn lại cọc

### 5. Xem báo cáo
1. Vào menu "Báo cáo"
2. Chọn khoảng thời gian (tùy chọn)
3. Xem các chỉ số thống kê
4. Xuất báo cáo nếu cần

## 🧪 Dữ liệu mẫu

Sau khi chạy seeder, hệ thống sẽ có:
- **4 sản phẩm mẫu**: Váy dạ hội, váy cưới, váy cocktail, váy dự tiệc
- **Tài khoản admin**: admin/1999
- **Cấu trúc database** hoàn chỉnh

## 🔄 Bảo trì và Cập nhật

### Backup database
```bash
# SQLite
cp database/database.sqlite backup/database_backup.sqlite

# MySQL
mysqldump -u username -p database_name > backup.sql
```

### Clear cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

### Update dependencies
```bash
composer update
npm update
```

### Reset database (cẩn thận!)
```bash
php artisan migrate:fresh --seed
```

## 🐛 Troubleshooting

### Lỗi thường gặp

**1. Lỗi upload hình ảnh**
```bash
php artisan storage:link
chmod -R 775 storage/
```

**2. Lỗi database**
```bash
php artisan migrate:fresh --seed
```

**3. Lỗi cache**
```bash
php artisan cache:clear
php artisan config:clear
```

**4. Lỗi permission**
```bash
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
```

📞 Hỗ trợ

Nếu có vấn đề hoặc cần hỗ trợ:
- **Email**: vovantin522001@gmail.com
- **Phone**: 0967813250

---

**AURIS** - Hệ thống quản lý shop cho thuê quần áo nữ chuyên nghiệp! 🎀✨
