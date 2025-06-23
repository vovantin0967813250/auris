@extends('layouts.app')

@section('title', 'Chi tiết Đơn thuê')
@section('page-title', 'Chi tiết Đơn thuê #' . $rental->id)

@section('content')
<div class="row">
    <!-- Rental Info & Customer -->
    <div class="col-lg-4">
        <!-- Rental Details -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-file-invoice me-2"></i>Thông tin đơn thuê</h6>
            </div>
            <div class="card-body">
                <p><strong>Trạng thái:</strong>
                    @if($rental->status === 'returned')
                        <span class="badge bg-info">Đã trả</span>
                    @elseif($rental->isOverdue())
                        <span class="badge bg-danger">Quá hạn</span>
                    @else
                        <span class="badge bg-success">Đang thuê</span>
                    @endif
                </p>
                <p><strong>Ngày thuê:</strong> {{ $rental->rental_date->format('d/m/Y') }}</p>
                <p><strong>Ngày trả dự kiến:</strong> {{ $rental->expected_return_date->format('d/m/Y') }}</p>
                @if($rental->actual_return_date)
                <p><strong>Ngày trả thực tế:</strong> {{ $rental->actual_return_date->format('d/m/Y') }}</p>
                @endif
                 @if($rental->isOverdue())
                    <p class="text-danger"><strong>Quá hạn:</strong> {{ $rental->getOverdueDays() }} ngày</p>
                @endif
                <hr>
                <p><strong>Tổng tiền thuê:</strong> {{ number_format($rental->total_price) }} VNĐ</p>
                <p><strong>Tiền cọc:</strong> 
                    @if($rental->deposit_type === 'money')
                        {{ number_format($rental->deposit_value) }} VNĐ
                    @elseif($rental->deposit_type === 'idcard')
                        {{ $rental->deposit_value }} (CCCD)
                    @else
                        Không có
                    @endif
                </p>
                @if($rental->notes)
                <p><strong>Ghi chú:</strong> {{ $rental->notes }}</p>
                @endif
            </div>
        </div>

        <!-- Customer Information -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-user me-2"></i>Thông tin khách hàng</h6>
            </div>
             <div class="card-body">
                <p><strong>Tên:</strong> {{ $rental->customer->name }}</p>
                <p><strong>SĐT:</strong> {{ $rental->customer->phone }}</p>
                @if($rental->customer->email)
                <p><strong>Email:</strong> {{ $rental->customer->email }}</p>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Rented Products -->
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-box me-2"></i>Các sản phẩm đã thuê ({{$rental->products->count()}})
                </h6>
                <div>
                     @if($rental->status === 'active')
                        <form action="{{ route('rentals.return', $rental) }}" method="POST" onsubmit="return confirm('Xác nhận trả toàn bộ sản phẩm trong đơn này?')" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success"><i class="fas fa-undo me-1"></i>Đánh dấu đã trả</button>
                        </form>
                    @endif
                    <a href="{{ route('rentals.index') }}" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-left me-1"></i>Quay lại</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Mã SP</th>
                                <th>Hình ảnh</th>
                                <th>Tên sản phẩm</th>
                                <th>Giá thuê</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rental->products as $product)
                            <tr>
                                <td><strong>{{ $product->product_code }}</strong></td>
                                <td>
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $product->name }}</td>
                                <td>{{ number_format($product->rental_price) }} VNĐ</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 