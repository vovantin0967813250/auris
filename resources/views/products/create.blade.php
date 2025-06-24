@extends('layouts.app')

@section('title', 'Thêm Sản phẩm')

@section('page-title', 'Thêm Sản phẩm')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-12">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-plus me-2"></i>Thông tin sản phẩm
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="product_code" class="form-label">Mã sản phẩm <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('product_code') is-invalid @enderror" 
                                       id="product_code" name="product_code" 
                                       value="{{ old('product_code') }}" required>
                                @error('product_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" 
                                       value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Hình ảnh</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                               id="image" name="image" accept="image/*">
                        <div class="form-text">Chấp nhận: JPG, PNG, GIF (tối đa 2MB)</div>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="purchase_price" class="form-label">Giá mua về <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control @error('purchase_price') is-invalid @enderror" 
                                           id="purchase_price_display" 
                                           value="{{ old('purchase_price') }}" required>
                                    <input type="hidden" name="purchase_price" id="purchase_price" value="{{ old('purchase_price') }}">
                                    <span class="input-group-text">VNĐ</span>
                                </div>
                                @error('purchase_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="rental_price" class="form-label">Giá cho thuê <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control @error('rental_price') is-invalid @enderror" 
                                           id="rental_price_display" 
                                           value="{{ old('rental_price') }}" required>
                                    <input type="hidden" name="rental_price" id="rental_price" value="{{ old('rental_price') }}">
                                    <span class="input-group-text">VNĐ</span>
                                </div>
                                @error('rental_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="deposit_price" class="form-label">Giá cọc <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control @error('deposit_price') is-invalid @enderror" 
                                           id="deposit_price_display" 
                                           value="{{ old('deposit_price') }}" required>
                                    <input type="hidden" name="deposit_price" id="deposit_price" value="{{ old('deposit_price') }}">
                                    <span class="input-group-text">VNĐ</span>
                                </div>
                                @error('deposit_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="purchase_date" class="form-label">Ngày mua về <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('purchase_date') is-invalid @enderror" 
                                       id="purchase_date" name="purchase_date" 
                                       value="{{ old('purchase_date') }}" required>
                                @error('purchase_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Quay lại
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Lưu sản phẩm
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const purchasePriceDisplay = document.getElementById('purchase_price_display');
    const purchasePriceHidden = document.getElementById('purchase_price');
    const rentalPriceDisplay = document.getElementById('rental_price_display');
    const rentalPriceHidden = document.getElementById('rental_price');
    const depositPriceDisplay = document.getElementById('deposit_price_display');
    const depositPriceHidden = document.getElementById('deposit_price');

    const formatter = new Intl.NumberFormat('vi-VN');

    function formatAndSet(displayInput, hiddenInput) {
        // Format initial value if it exists
        if (displayInput.value) {
            const rawValue = displayInput.value.replace(/[^0-9]/g, '');
            hiddenInput.value = rawValue;
            displayInput.value = formatter.format(rawValue);
        }

        displayInput.addEventListener('input', function (e) {
            // Get raw number value by removing non-digit characters
            const rawValue = e.target.value.replace(/[^0-9]/g, '');
            
            // Update hidden input
            hiddenInput.value = rawValue;
            
            // Format for display and update visible input
            if (rawValue) {
                displayInput.value = formatter.format(rawValue);
            } else {
                displayInput.value = '';
            }
        });
    }

    formatAndSet(purchasePriceDisplay, purchasePriceHidden);
    formatAndSet(rentalPriceDisplay, rentalPriceHidden);
    formatAndSet(depositPriceDisplay, depositPriceHidden);
});
</script>
@endpush 