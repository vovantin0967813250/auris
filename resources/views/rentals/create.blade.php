@extends('layouts.app')

@section('title', 'Tạo Đơn thuê Mới')

@section('page-title', 'Tạo Đơn thuê Mới')

@push('styles')
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
        width: 100%;
    }
</style>
@endpush

@section('content')
<form action="{{ route('rentals.store') }}" method="POST" id="rentalForm">
    @csrf
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
                                    <th style="width: 150px;">Giá thuê</th>
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
                        <input type="date" class="form-control" id="rental_date" name="rental_date" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="expected_return_date" class="form-label">Ngày trả dự kiến <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="expected_return_date" name="expected_return_date" required>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tổng tiền thuê:</span>
                        <strong id="total-price-display">0 VNĐ</strong>
                    </div>
                    <div class="mb-3">
                        <label for="deposit_type" class="form-label">Loại cọc</label>
                        <select class="form-select" id="deposit_type" name="deposit_type">
                            <option value="money" selected>Cọc tiền</option>
                            <option value="idcard">Cọc căn cước công dân</option>
                        </select>
                    </div>
                    <div class="mb-3" id="deposit_money_group">
                        <label for="deposit_money_display" class="form-label">Số tiền cọc</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="deposit_money_display" value="0">
                            <input type="hidden" name="deposit_money" id="deposit_money" value="0">
                            <span class="input-group-text">VNĐ</span>
                        </div>
                    </div>
                    <div class="mb-3 d-none" id="deposit_idcard_group">
                        <label for="deposit_idcard" class="form-label">Tên/ID căn cước công dân</label>
                        <input type="text" class="form-control" id="deposit_idcard" name="deposit_idcard" placeholder="Nhập tên hoặc số căn cước">
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Ghi chú</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>
                    <input type="hidden" name="total_price" id="total_price_hidden">
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
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('product_search');
    const searchResult = document.getElementById('productSearchResult');
    const cartItems = document.getElementById('cart-items');
    const cartEmptyMsg = document.getElementById('cart-empty-message');
    const totalPriceDisplay = document.getElementById('total-price-display');
    const totalPriceHidden = document.getElementById('total_price_hidden');
    const rentalForm = document.getElementById('rentalForm');
    const clearSearchBtn = document.getElementById('clearSearch');

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
            const url = `{{ route('products.searchByCode') }}?search=${encodeURIComponent(query)}`;
            const response = await fetch(url);
            if (!response.ok) throw new Error('Network response was not ok.');
            
            const products = await response.json();
            searchResult.innerHTML = '';
            if (products.length > 0) {
                products.forEach(product => {
                    const item = document.createElement('a');
                    item.href = '#';
                    item.classList.add('list-group-item', 'list-group-item-action');
                    item.innerHTML = `<strong>${product.product_code}</strong> - ${product.name}`;
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
                    <td>${formatCurrency(product.rental_price)}</td>
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

    // UPDATE TOTALS
    function updateTotals() {
        const total = cart.reduce((sum, product) => sum + parseFloat(product.rental_price), 0);
        totalPriceDisplay.textContent = formatCurrency(total);
        totalPriceHidden.value = total;
    }
    
    // UTILITY
    function formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
    }
    
    // FORM VALIDATION
    rentalForm.addEventListener('submit', function(e) {
        if (cart.length === 0) {
            e.preventDefault();
            alert('Vui lòng thêm ít nhất một sản phẩm vào đơn thuê.');
        }
    });

    // --- Deposit Type Logic ---
    const depositType = document.getElementById('deposit_type');
    const depositMoneyGroup = document.getElementById('deposit_money_group');
    const depositIdCardGroup = document.getElementById('deposit_idcard_group');
    const depositMoneyDisplay = document.getElementById('deposit_money_display');
    const depositMoneyHidden = document.getElementById('deposit_money');

    // Định dạng số tiền cọc
    const formatter = new Intl.NumberFormat('vi-VN');
    function formatDepositMoney() {
        const rawValue = depositMoneyDisplay.value.replace(/[^0-9]/g, '');
        depositMoneyHidden.value = rawValue;
        depositMoneyDisplay.value = rawValue ? formatter.format(rawValue) : '';
    }
    depositMoneyDisplay.addEventListener('input', formatDepositMoney);
    // Format initial value
    formatDepositMoney();

    // Ẩn/hiện input theo loại cọc
    function updateDepositInput() {
        if (depositType.value === 'money') {
            depositMoneyGroup.classList.remove('d-none');
            depositIdCardGroup.classList.add('d-none');
            depositMoneyDisplay.required = true;
            document.getElementById('deposit_idcard').required = false;
        } else {
            depositMoneyGroup.classList.add('d-none');
            depositIdCardGroup.classList.remove('d-none');
            depositMoneyDisplay.required = false;
            document.getElementById('deposit_idcard').required = true;
        }
    }
    depositType.addEventListener('change', updateDepositInput);
    updateDepositInput();

});
</script>
@endpush 