@extends('layouts.app')

@section('title', 'Dashboard - Quản lý Shop Cho Thuê')

@section('page-title', 'Dashboard')

@section('content')

@push('styles')
<style>
    .stat-card {
        color: white;
        background: linear-gradient(135deg, #667eea, #764ba2);
    }
    .stat-card .text-xs {
        color: rgba(255,255,255,0.8);
    }
    .border-left-warning {
        border-left: .25rem solid #f6c23e !important;
    }
    /* Prevent text wrapping in recent rentals table */
    .recent-rentals-table td,
    .recent-rentals-table th {
        white-space: nowrap;
        vertical-align: middle;
    }
</style>
@endpush

<div class="row">
    <!-- Statistics Cards -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                            Tổng sản phẩm
                        </div>
                        <div class="h5 mb-0 font-weight-bold">{{ $totalProducts }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-box fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                            Sản phẩm có sẵn
                        </div>
                        <div class="h5 mb-0 font-weight-bold">{{ $availableProducts }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                            Đang thuê
                        </div>
                        <div class="h5 mb-0 font-weight-bold">{{ $activeRentals }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-handshake fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                            Doanh thu tháng này
                        </div>
                        <div class="h5 mb-0 font-weight-bold">{{ number_format($thisMonthRevenue) }} VNĐ</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Rentals -->
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-history me-2"></i>Đơn thuê gần đây
                </h6>
                <a href="{{ route('rentals.index') }}" class="btn btn-sm btn-primary">
                    Xem tất cả
                </a>
            </div>
            <div class="card-body">
                @if($recentRentals->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered recent-rentals-table" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Khách hàng</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentRentals as $rental)
                                <tr>
                                    <td>
                                        @foreach($rental->products->take(2) as $product)
                                            <span class="badge bg-light text-dark border">{{ $product->product_code }}</span>
                                        @endforeach
                                        @if($rental->products->count() > 2)
                                            <span class="badge bg-secondary">+{{ $rental->products->count() - 2 }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $rental->customer->name }}</td>
                                    <td>
                                        @if($rental->status === 'returned')
                                            <span class="badge bg-info">Đã trả</span>
                                        @elseif($rental->isOverdue())
                                            <span class="badge bg-danger">Quá hạn</span>
                                        @else
                                            <span class="badge bg-success">Đang thuê</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center">Chưa có đơn thuê nào</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions & Alerts -->
    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-bolt me-2"></i>Thao tác nhanh
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('products.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Thêm sản phẩm
                    </a>
                    <a href="{{ route('rentals.create') }}" class="btn btn-success">
                        <i class="fas fa-handshake me-2"></i>Tạo đơn thuê
                    </a>
                    <a href="{{ route('products.index') }}" class="btn btn-info">
                        <i class="fas fa-box me-2"></i>Quản lý sản phẩm
                    </a>
                </div>
            </div>
        </div>

        <!-- Alerts -->
        @if($overdueRentals > 0)
        <div class="card shadow mb-4 border-left-warning">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>Cảnh báo
                </h6>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <strong>{{ $overdueRentals }}</strong> đơn thuê đã quá hạn!
                    <a href="{{ route('rentals.index') }}" class="alert-link">Xem chi tiết</a>
                </div>
            </div>
        </div>
        @endif

        <!-- Recent Products -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-box me-2"></i>Sản phẩm mới
                </h6>
            </div>
            <div class="card-body">
                @if($recentProducts->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentProducts as $product)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $product->product_code }}</strong><br>
                                <small class="text-muted">{{ $product->name }}</small>
                            </div>
                            <span class="badge bg-primary rounded-pill">{{ number_format($product->rental_price) }} VNĐ</span>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center">Chưa có sản phẩm nào</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 