@extends('layouts.app')

@section('title', 'Quản lý Đơn thuê')

@section('page-title', 'Quản lý Đơn thuê')

@push('styles')
<style>
    /* Hide table on mobile */
    @media (max-width: 991.98px) {
        .table-responsive {
            display: none;
        }
    }
    /* Show cards on mobile */
    .rental-cards {
        display: none;
    }
    @media (max-width: 991.98px) {
        .rental-cards {
            display: block;
        }
    }
    .rental-card {
        margin-bottom: 1rem;
        border-radius: .375rem;
    }

    /* Prevent text wrapping in desktop table view */
    .table-responsive .table th,
    .table-responsive .table td {
        white-space: nowrap;
        vertical-align: middle;
    }
</style>
@endpush

@section('content')
<!-- Overdue Rentals Alert -->
@if($overdueRentals->count() > 0)
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-triangle me-2"></i>
    <strong>{{ $overdueRentals->count() }}</strong> đơn thuê đã quá hạn!
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Active Rentals -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-handshake me-2"></i>Đơn thuê đang hoạt động
        </h6>
        <a href="{{ route('rentals.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tạo đơn thuê
        </a>
    </div>
    <div class="card-body">
        <!-- Mobile Card View -->
        <div class="rental-cards d-lg-none">
            @if($activeRentals->count() > 0)
                @foreach($activeRentals as $rental)
                    <div class="card rental-card {{ $rental->isOverdue() ? 'border-warning' : 'border-primary' }}">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Đơn #{{ $rental->id }}</strong>
                            </div>
                            <div>
                                @if($rental->isOverdue())
                                    <span class="badge bg-danger">Quá hạn</span>
                                @else
                                    <span class="badge bg-success">Đang thuê</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="mb-1"><strong>Khách hàng:</strong> {{ $rental->customer->name }} - {{ $rental->customer->phone }}</p>
                            <p class="mb-1">
                                <strong>Sản phẩm:</strong> 
                                @foreach($rental->products as $product)
                                    <span class="badge bg-light text-dark border">{{ $product->product_code }}</span>
                                @endforeach
                            </p>
                            <p class="mb-1"><strong>Ngày trả:</strong> {{ $rental->expected_return_date->format('d/m/Y') }}</p>
                            @if($rental->isOverdue())
                                <p class="mb-2 text-danger"><strong>Quá hạn:</strong> {{ $rental->getOverdueDays() }} ngày</p>
                            @endif
                            
                            <div class="d-grid gap-2 d-sm-flex justify-content-sm-start">
                                <a href="{{ route('rentals.show', $rental) }}" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-eye"></i> Chi tiết
                                </a>
                                <form action="{{ route('rentals.return', $rental) }}" method="POST" onsubmit="return confirm('Xác nhận trả toàn bộ sản phẩm trong đơn này?')" class="d-grid">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-undo"></i> Trả hàng
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <!-- No active rentals message for mobile -->
            @endif
        </div>

        <!-- Desktop Table View -->
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Mã đơn</th>
                        <th>Sản phẩm (SL)</th>
                        <th>Khách hàng</th>
                        <th>Ngày thuê</th>
                        <th>Ngày trả dự kiến</th>
                        <th>Tổng tiền</th>
                        <th>Loại cọc</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activeRentals as $rental)
                    <tr class="{{ $rental->isOverdue() ? 'table-warning' : '' }}">
                        <td>
                            <strong>#{{ $rental->id }}</strong>
                        </td>
                        <td>
                            @foreach($rental->products as $product)
                                <span class="badge bg-light text-dark border">{{ $product->product_code }}</span>
                            @endforeach
                            <span class="badge bg-primary rounded-pill">{{ $rental->products->count() }}</span>
                        </td>
                        <td>
                            <div>
                                <strong>{{ $rental->customer->name }}</strong><br>
                                <small class="text-muted">{{ $rental->customer->phone }}</small>
                            </div>
                        </td>
                        <td>{{ $rental->rental_date->format('d/m/Y') }}</td>
                        <td>
                            <span class="{{ $rental->isOverdue() ? 'text-danger fw-bold' : '' }}">
                                {{ $rental->expected_return_date->format('d/m/Y') }}
                            </span>
                            @if($rental->isOverdue())
                                <br><small class="text-danger">Quá {{ $rental->getOverdueDays() }} ngày</small>
                            @endif
                        </td>
                        <td>{{ number_format($rental->total_price) }} VNĐ</td>
                        <td>
                            @if($rental->deposit_type === 'money')
                                {{ number_format($rental->deposit_value) }} VNĐ
                            @elseif($rental->deposit_type === 'idcard')
                                <span class="badge bg-secondary">CCCD</span>
                            @else
                                0 VNĐ
                            @endif
                        </td>
                        <td>
                            @if($rental->isOverdue())
                                <span class="badge bg-danger">Quá hạn</span>
                            @else
                                <span class="badge bg-success">Đang thuê</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('rentals.show', $rental) }}" 
                                   class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="{{ route('rentals.return', $rental) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Xác nhận trả toàn bộ sản phẩm trong đơn này?')"
                                      style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-undo"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if($activeRentals->count() > 0)
            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $activeRentals->links() }}
            </div>
        @else
            <div class="text-center py-4">
                <i class="fas fa-handshake fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Chưa có đơn thuê nào</h5>
                <p class="text-muted">Bắt đầu bằng cách tạo đơn thuê đầu tiên</p>
                <a href="{{ route('rentals.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Tạo đơn thuê
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Overdue Rentals Details -->
@if($overdueRentals->count() > 0)
<div class="card shadow mb-4 border-left-warning">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>Đơn thuê quá hạn
        </h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-warning" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Mã đơn</th>
                        <th>Khách hàng</th>
                        <th>Ngày trả dự kiến</th>
                        <th>Số ngày quá hạn</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($overdueRentals as $rental)
                    <tr>
                        <td><strong>#{{ $rental->id }}</strong></td>
                        <td>
                            <strong>{{ $rental->customer->name }}</strong><br>
                            <small class="text-muted">{{ $rental->customer->phone }}</small>
                        </td>
                        <td>{{ $rental->expected_return_date->format('d/m/Y') }}</td>
                        <td>
                            <span class="badge bg-danger">{{ $rental->getOverdueDays() }} ngày</span>
                        </td>
                        <td>
                            <a href="{{ route('rentals.show', $rental) }}" 
                               class="btn btn-sm btn-warning">
                                <i class="fas fa-eye me-1"></i>Xem chi tiết
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection 