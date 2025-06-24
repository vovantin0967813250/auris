@extends('layouts.app')

@section('title', 'Báo cáo thống kê')
@section('page-title', 'Báo cáo thống kê tổng hợp')

@section('content')
<div class="container-fluid">
    <form class="row g-3 align-items-end mb-4" method="GET" action="{{ route('reports.index') }}">
        <div class="col-md-3">
            <label for="from" class="form-label">Từ ngày</label>
            <input type="date" id="from" name="from" class="form-control" value="{{ request('from', $from ? $from->format('Y-m-d') : '') }}">
        </div>
        <div class="col-md-3">
            <label for="to" class="form-label">Đến ngày</label>
            <input type="date" id="to" name="to" class="form-control" value="{{ request('to', $to ? $to->format('Y-m-d') : '') }}">
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter me-1"></i>Lọc</button>
        </div>
        <div class="col-md-3">
            <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary w-100"><i class="fas fa-times me-1"></i>Đặt lại</a>
        </div>
    </form>
    <h4 class="mb-4">Báo cáo tổng hợp hoạt động kinh doanh</h4>
    <!-- Tổng quan số liệu -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <div class="h5">Doanh thu</div>
                    <div class="h3 text-success" id="revenue-summary">{{ number_format($revenue) }} VNĐ</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <div class="h5">Số đơn thuê</div>
                    <div class="h3" id="rental-count">{{ $rentalCount }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <div class="h5">Số sản phẩm</div>
                    <div class="h3" id="product-count">{{ $productCount }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <div class="h5">Số khách hàng</div>
                    <div class="h3" id="customer-count">{{ $customerCount }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-chart-line me-2"></i>Biểu đồ doanh thu 12 tháng gần nhất</h6>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4 g-4">
        <div class="col-lg-6">
            <div class="card shadow rounded-4 h-100">
                <div class="card-header bg-primary text-white rounded-top-4 d-flex align-items-center">
                    <i class="fas fa-crown me-2"></i>
                    <span class="fw-bold">Top 5 sản phẩm thuê nhiều nhất</span>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">Mã SP</th>
                                <th>Tên sản phẩm</th>
                                <th class="text-center">Lượt thuê</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topProducts as $product)
                            <tr>
                                <td class="text-center fw-bold">{{ $product->product_code }}</td>
                                <td>{{ $product->name }}</td>
                                <td class="text-center">
                                    <span class="badge bg-gradient bg-primary fs-6 px-3 py-2">{{ $product->rental_items_count }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow rounded-4 h-100">
                <div class="card-header bg-secondary text-white rounded-top-4 d-flex align-items-center">
                    <i class="fas fa-arrow-down me-2"></i>
                    <span class="fw-bold">Top 5 sản phẩm thuê ít nhất</span>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">Mã SP</th>
                                <th>Tên sản phẩm</th>
                                <th class="text-center">Lượt thuê</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leastProducts as $product)
                            <tr>
                                <td class="text-center fw-bold">{{ $product->product_code }}</td>
                                <td>{{ $product->name }}</td>
                                <td class="text-center">
                                    <span class="badge bg-gradient bg-secondary fs-6 px-3 py-2">{{ $product->rental_items_count }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4 g-4">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow rounded-4">
                <div class="card-header bg-success text-white rounded-top-4 d-flex align-items-center">
                    <i class="fas fa-user-friends me-2"></i>
                    <span class="fw-bold">Top 5 khách hàng thuê nhiều nhất</span>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tên khách</th>
                                <th>SĐT</th>
                                <th class="text-center">Lượt thuê</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topCustomers as $customer)
                            <tr>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->phone }}</td>
                                <td class="text-center">
                                    <span class="badge bg-gradient bg-success fs-6 px-3 py-2">{{ $customer->rentals_count }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="m-0 font-weight-bold text-info"><i class="fas fa-list me-2"></i>Đơn thuê đang hoạt động</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Khách hàng</th>
                                    <th>SĐT</th>
                                    <th>Ngày thuê</th>
                                    <th>Ngày trả dự kiến</th>
                                    <th>Sản phẩm</th>
                                    <th>Tiền thuê</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activeRentals as $rental)
                                <tr>
                                    <td>{{ $rental->id }}</td>
                                    <td>{{ $rental->customer->name }}</td>
                                    <td>{{ $rental->customer->phone }}</td>
                                    <td>{{ $rental->rental_date->format('d/m/Y') }}</td>
                                    <td>{{ $rental->expected_return_date->format('d/m/Y') }}</td>
                                    <td>
                                        @foreach($rental->products as $product)
                                            <span class="badge bg-secondary">{{ $product->name }}</span>
                                        @endforeach
                                    </td>
                                    <td>{{ number_format($rental->rental_fee) }} VNĐ</td>
                                    <td>
                                        @if($rental->isOverdue())
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
                </div>
            </div>
        </div>
        <div class="col-md-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="m-0 font-weight-bold text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Đơn thuê quá hạn</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Khách hàng</th>
                                    <th>SĐT</th>
                                    <th>Ngày thuê</th>
                                    <th>Ngày trả dự kiến</th>
                                    <th>Sản phẩm</th>
                                    <th>Tiền thuê</th>
                                    <th>Số ngày trễ</th>
                                    <th>Tiền phạt</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($overdueRentals as $rental)
                                <tr>
                                    <td>{{ $rental->id }}</td>
                                    <td>{{ $rental->customer->name }}</td>
                                    <td>{{ $rental->customer->phone }}</td>
                                    <td>{{ $rental->rental_date->format('d/m/Y') }}</td>
                                    <td>{{ $rental->expected_return_date->format('d/m/Y') }}</td>
                                    <td>
                                        @foreach($rental->products as $product)
                                            <span class="badge bg-secondary">{{ $product->name }}</span>
                                        @endforeach
                                    </td>
                                    <td>{{ number_format($rental->rental_fee) }} VNĐ</td>
                                    <td class="text-danger fw-bold">{{ $rental->getOverdueDays() }}</td>
                                    <td class="text-danger">{{ number_format($rental->getLateFee()) }} VNĐ</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const monthlyRevenue = @json($monthlyRevenue);
    const labels = monthlyRevenue.map(item => `${item.month}/${item.year}`);
    const data = monthlyRevenue.map(item => item.total);
    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: data,
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('vi-VN');
                        }
                    }
                }
            }
        }
    });
</script>
<style>
    .card-header {
        font-size: 1.1rem;
        font-weight: 500;
        letter-spacing: 0.5px;
    }
    .badge.fs-6 {
        font-size: 1.1rem;
    }
    @media (max-width: 991.98px) {
        .col-lg-8.mx-auto { max-width: 100% !important; }
    }
</style>
@endpush 