

<?php $__env->startSection('title', 'Tạo Đơn thuê Mới'); ?>

<?php $__env->startSection('page-title', 'Tạo Đơn thuê Mới'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .product-cart-table th, .product-cart-table td {
        vertical-align: middle;
    }
    .product-cart-table .form-control {
        min-width: 100px;
    }
    #productSearchResult .list-group-item {
        cursor: pointer;
    }
    #productSearchResult {
        position: absolute;
        z-index: 1000;
        width: 97%;
    }
    .summary-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
    }
    .summary-total {
        font-weight: bold;
        font-size: 1.1em;
        border-top: 2px solid #dee2e6;
        padding-top: 0.5rem;
        margin-top: 0.5rem;
    }
    .rental-fee-display {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        padding: 0.375rem 0.75rem;
        font-weight: bold;
        color: #495057;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<form action="<?php echo e(route('rentals.store')); ?>" method="POST" id="rentalForm">
    <?php echo csrf_field(); ?>
    <div class="row">
        <!-- Left Side: Rental & Customer Info -->
        <div class="col-lg-8">
            <!-- Product Cart -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-shopping-cart me-2"></i>Chi tiết Đơn thuê</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="product_search" class="form-label">Tìm và thêm sản phẩm</label>
                        <div class="input-group">
                            <input type="text" id="product_search" class="form-control" placeholder="Nhập mã hoặc tên sản phẩm...">
                             <button type="button" class="btn btn-outline-secondary" id="clearSearch">Xóa</button>
                        </div>
                        <div id="productSearchResult" class="list-group"></div>
                    </div>
                    <div class="table-responsive">
                        <table class="table product-cart-table">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th style="width: 120px;">Giá thuê</th>
                                    <th style="width: 120px;">Giá cọc</th>
                                    <th style="width: 50px;"></th>
                                </tr>
                            </thead>
                            <tbody id="cart-items">
                                <!-- Cart items will be appended here by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                     <div id="cart-empty-message" class="text-center text-muted py-3">
                        <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                        <p>Chưa có sản phẩm nào trong đơn.</p>
                    </div>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="card shadow mb-4">
                 <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-user me-2"></i>Thông tin Khách hàng</h6>
                </div>
                <div class="card-body">
                     <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="customer_name" class="form-label">Tên khách hàng <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="customer_phone" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="customer_phone" name="customer_phone" required>
                            <div id="customer-info-hint" class="mt-2"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Summary and Actions -->
        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-file-invoice-dollar me-2"></i>Tổng hợp</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="rental_date" class="form-label">Ngày thuê <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="rental_date" name="rental_date" value="<?php echo e(date('Y-m-d')); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="expected_return_date" class="form-label">Ngày trả dự kiến <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="expected_return_date" name="expected_return_date" required>
                    </div>
                    <hr>
                    
                    <!-- Tổng tiền thuê -->
                    <div class="summary-item">
                        <span>Giá thuê:</span>
                        <strong id="total-rental-display">0 VNĐ</strong>
                    </div>
                    <input type="hidden" name="rental_fee" id="rental_fee_hidden" value="0">
                    
                    <!-- Loại cọc -->
                    <div class="mb-3">
                        <label for="deposit_type" class="form-label">Loại cọc</label>
                        <select class="form-select" id="deposit_type" name="deposit_type">
                            <option value="money" selected>Cọc tiền</option>
                            <option value="idcard">Cọc căn cước công dân</option>
                        </select>
                    </div>
                    
                    <!-- Tiền cọc -->
                    <div class="mb-3" id="deposit_money_group">
                        <label for="deposit_amount_display" class="form-label">Số tiền cọc</label>
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" id="deposit_amount_display" value="0">
                            <input type="hidden" name="deposit_amount" id="deposit_amount_hidden" value="0">
                            <span class="input-group-text">VNĐ</span>
                        </div>
                        <small class="form-text text-muted">Có thể chỉnh sửa giá cọc mặc định từ sản phẩm</small>
                        <div class="mt-2">
                            <label class="form-label mb-1">Hình thức thanh toán:</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="deposit_payment_method" id="deposit_momo" value="momo" checked>
                                <label class="form-check-label" for="deposit_momo">Chuyển khoản Momo</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="deposit_payment_method" id="deposit_techcom" value="techcombank">
                                <label class="form-check-label" for="deposit_techcom">Chuyển khoản Techcombank</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="deposit_payment_method" id="deposit_cash" value="cash">
                                <label class="form-check-label" for="deposit_cash">Tiền mặt</label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- CMND cọc -->
                    <div class="mb-3 d-none" id="deposit_idcard_group">
                        <label for="deposit_note" class="form-label">Tên căn cước công dân</label>
                        <input type="text" class="form-control" id="deposit_note" name="deposit_note" placeholder="Nhập tên căn cước">
                    </div>
                    
                    <!-- Tổng tiền phải trả -->
                    <div class="summary-item summary-total">
                        <span>Tổng tiền phải trả:</span>
                        <strong id="total-paid-display">0 VNĐ</strong>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Ghi chú</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i>Lưu đơn thuê
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('product_search');
    const searchResult = document.getElementById('productSearchResult');
    const cartItems = document.getElementById('cart-items');
    const cartEmptyMsg = document.getElementById('cart-empty-message');
    const totalRentalDisplay = document.getElementById('total-rental-display');
    const rentalFeeHidden = document.getElementById('rental_fee_hidden');
    const totalPaidDisplay = document.getElementById('total-paid-display');
    const rentalForm = document.getElementById('rentalForm');
    const clearSearchBtn = document.getElementById('clearSearch');
    const rentalDateInput = document.getElementById('rental_date');
    const returnDateInput = document.getElementById('expected_return_date');
    const customerPhoneInput = document.getElementById('customer_phone');
    const customerNameInput = document.getElementById('customer_name');
    const customerInfoHint = document.getElementById('customer-info-hint');
    let lastPhone = '';

    let cart = []; // Array to store product objects in the cart
    let debounceTimeout;

    // DEBOUNCE SEARCH
    searchInput.addEventListener('input', () => {
        clearTimeout(debounceTimeout);
        const query = searchInput.value;
        if (query.length < 2) {
            searchResult.innerHTML = '';
            return;
        }
        debounceTimeout = setTimeout(() => performSearch(query), 500);
    });

    clearSearchBtn.addEventListener('click', () => {
        searchInput.value = '';
        searchResult.innerHTML = '';
    });

    // PERFORM SEARCH
    async function performSearch(query) {
        try {
            const url = `<?php echo e(route('products.searchByCode')); ?>?search=${encodeURIComponent(query)}`;
            const response = await fetch(url);
            if (!response.ok) throw new Error('Network response was not ok.');
            
            const products = await response.json();
            searchResult.innerHTML = '';
            if (products.length > 0) {
                products.forEach(product => {
                    const item = document.createElement('a');
                    item.href = '#';
                    item.classList.add('list-group-item', 'list-group-item-action');
                    item.innerHTML = `
                        <strong>${product.product_code}</strong> - ${product.name}<br>
                        <small class="text-muted">
                            Thuê: ${formatCurrency(product.rental_price)} | 
                            Cọc: ${formatCurrency(product.deposit_price)}
                        </small>
                    `;
                    if(product.status !== 'available' || cart.some(p => p.id === product.id)) {
                        item.classList.add('disabled');
                        item.innerHTML += ` <span class="badge bg-danger float-end">Đã thuê/Trong giỏ</span>`;
                    } else {
                         item.addEventListener('click', (e) => {
                            e.preventDefault();
                            addToCart(product);
                        });
                    }
                    searchResult.appendChild(item);
                });
            } else {
                searchResult.innerHTML = '<span class="list-group-item">Không tìm thấy sản phẩm</span>';
            }
        } catch (error) {
            console.error("Search failed:", error);
            searchResult.innerHTML = '<span class="list-group-item text-danger">Lỗi khi tìm kiếm</span>';
        }
    }

    // CART MANAGEMENT
    function addToCart(product) {
        if (cart.some(p => p.id === product.id)) {
            alert('Sản phẩm đã có trong đơn thuê.');
            return;
        }
        cart.push(product);
        searchInput.value = '';
        searchResult.innerHTML = '';
        renderCart();
    }

    function removeFromCart(productId) {
        cart = cart.filter(p => p.id !== productId);
        renderCart();
    }

    // RENDER UI
    function renderCart() {
        cartItems.innerHTML = ''; // Clear current cart items
        if (cart.length === 0) {
            cartEmptyMsg.style.display = 'block';
        } else {
            cartEmptyMsg.style.display = 'none';
            cart.forEach(product => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>
                        <strong>${product.name}</strong><br>
                        <small class="text-muted">Mã: ${product.product_code}</small>
                        <input type="hidden" name="products[]" value="${product.id}">
                    </td>
                    <td>
                        <div class="rental-fee-display">${formatCurrency(product.rental_price)}</div>
                    </td>
                    <td>
                        <div class="rental-fee-display">${formatCurrency(product.deposit_price)}</div>
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-outline-danger remove-item-btn" data-id="${product.id}">&times;</button>
                    </td>
                `;
                cartItems.appendChild(row);
            });
        }
        updateTotals();
        attachRemoveListeners();
    }
    
    function attachRemoveListeners() {
        document.querySelectorAll('.remove-item-btn').forEach(button => {
            button.addEventListener('click', function() {
                const productId = parseInt(this.getAttribute('data-id'));
                removeFromCart(productId);
            });
        });
    }

    // UTILITY
    function formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
    }

    // --- Deposit Type Logic ---
    const depositType = document.getElementById('deposit_type');
    const depositMoneyGroup = document.getElementById('deposit_money_group');
    const depositIdCardGroup = document.getElementById('deposit_idcard_group');
    const depositAmountDisplay = document.getElementById('deposit_amount_display');
    const depositAmountHidden = document.getElementById('deposit_amount_hidden');

    // Định dạng số tiền cọc
    const formatter = new Intl.NumberFormat('vi-VN');
    function formatDepositAmount() {
        const rawValue = depositAmountDisplay.value.replace(/[^0-9]/g, '');
        depositAmountHidden.value = rawValue;
        depositAmountDisplay.value = rawValue ? formatter.format(rawValue) : '';
    }
    depositAmountDisplay.addEventListener('input', function() {
        formatDepositAmount();
        updateTotals();
    });
    // Format initial value
    formatDepositAmount();

    // Ẩn/hiện input theo loại cọc
    function updateDepositInput() {
        if (depositType.value === 'money') {
            depositMoneyGroup.classList.remove('d-none');
            depositIdCardGroup.classList.add('d-none');
            depositAmountDisplay.required = false; // Không bắt buộc phải có tiền cọc
            document.getElementById('deposit_note').required = false;
            // Nếu chuyển từ cọc căn cước sang cọc tiền, tự động lấy tổng giá cọc mặc định nếu chưa nhập
            if (cart.length > 0 && (!depositAmountHidden.value || depositAmountHidden.value == '0')) {
                const totalDeposit = cart.reduce((sum, product) => sum + parseFloat(product.deposit_price), 0);
                if (totalDeposit > 0) {
                    depositAmountHidden.value = totalDeposit;
                    depositAmountDisplay.value = formatter.format(totalDeposit);
                }
            }
        } else {
            depositMoneyGroup.classList.add('d-none');
            depositIdCardGroup.classList.remove('d-none');
            depositAmountDisplay.required = false;
            document.getElementById('deposit_note').required = true;
            // Khi chọn cọc căn cước thì set tiền cọc về 0
            depositAmountHidden.value = 0;
            depositAmountDisplay.value = '';
        }
        updateTotals();
    }
    depositType.addEventListener('change', updateDepositInput);
    updateDepositInput();

    // TÍNH TIỀN THUÊ THEO SỐ NGÀY
    function calculateRentalFee() {
        if (cart.length === 0) return 0;
        const rentalDate = rentalDateInput.value;
        const returnDate = returnDateInput.value;
        if (!rentalDate || !returnDate) return cart.reduce((sum, p) => sum + parseFloat(p.rental_price), 0);
        const start = new Date(rentalDate);
        const end = new Date(returnDate);
        let days = Math.floor((end - start) / (1000 * 60 * 60 * 24));
        if (isNaN(days) || days < 1) days = 1;
        let baseFee = cart.reduce((sum, p) => sum + parseFloat(p.rental_price), 0);
        const productCount = cart.length;
        if (days === 1) {
            return baseFee;
        } else if (days === 2) {
            return baseFee + 20000 * productCount;
        } else if (days > 2) {
            return baseFee + (20000 + (days - 2) * 10000) * productCount;
        }
        return baseFee;
    }

    // TÍNH TỔNG TIỀN PHẢI TRẢ
    function updateTotals() {
        const rentalFee = calculateRentalFee();
        let depositAmount = parseFloat(depositAmountHidden.value) || 0;
        const type = depositType.value;
        if (type === 'idcard') {
            depositAmount = 0;
        }
        const totalPaid = rentalFee + depositAmount;
        totalRentalDisplay.textContent = formatCurrency(rentalFee);
        rentalFeeHidden.value = rentalFee;
        totalPaidDisplay.textContent = formatCurrency(totalPaid);
    }

    // Khi thêm/xóa sản phẩm thì cập nhật lại tổng tiền và giá cọc mặc định nếu là cọc tiền
    const originalAddToCart = addToCart;
    addToCart = function(product) {
        originalAddToCart(product);
        if (depositType.value === 'money') {
            const totalDeposit = cart.reduce((sum, p) => sum + parseFloat(p.deposit_price), 0);
            if (totalDeposit > 0) {
                depositAmountHidden.value = totalDeposit;
                depositAmountDisplay.value = formatter.format(totalDeposit);
            }
        }
        updateTotals();
    };
    const originalRemoveFromCart = removeFromCart;
    removeFromCart = function(productId) {
        originalRemoveFromCart(productId);
        if (depositType.value === 'money') {
            const totalDeposit = cart.reduce((sum, p) => sum + parseFloat(p.deposit_price), 0);
            depositAmountHidden.value = totalDeposit;
            depositAmountDisplay.value = totalDeposit ? formatter.format(totalDeposit) : '';
        }
        updateTotals();
    };

    // Khi thay đổi ngày thuê/ngày trả thì cập nhật lại tổng tiền
    rentalDateInput.addEventListener('change', updateTotals);
    returnDateInput.addEventListener('change', updateTotals);

    // FORM VALIDATION
    rentalForm.addEventListener('submit', function(e) {
        if (cart.length === 0) {
            e.preventDefault();
            alert('Vui lòng thêm ít nhất một sản phẩm vào đơn thuê.');
        }
    });

    customerPhoneInput.addEventListener('input', function() {
        const phone = this.value.trim();
        if (phone.length < 6) {
            customerInfoHint.innerHTML = '';
            return;
        }
        if (phone === lastPhone) return;
        lastPhone = phone;
        fetch(`/customers/info?phone=${encodeURIComponent(phone)}`)
            .then(res => res.json())
            .then(data => {
                if (data.exists) {
                    customerNameInput.value = data.name;
                    customerInfoHint.innerHTML = `<span class='text-success'><i class='fas fa-user-check me-1'></i>Khách quen: <b>${data.name}</b> - Đã thuê <b>${data.rental_count}</b> lần</span>`;
                } else {
                    customerInfoHint.innerHTML = `<span class='text-muted'><i class='fas fa-user-plus me-1'></i>Khách mới, chưa từng thuê</span>`;
                }
            });
    });
});
</script>
<?php $__env->stopPush(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\auris\resources\views/rentals/create.blade.php ENDPATH**/ ?>