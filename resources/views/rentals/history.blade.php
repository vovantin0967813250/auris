@extends('layouts.app')

@section('title', 'Lịch sử Thuê')

@section('page-title', 'Lịch sử Thuê')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-history me-2"></i>Toàn bộ lịch sử đơn thuê
        </h6>
    </div>
    <div class="card-body">
        <!-- Search Form -->
        <div class="mb-3">
            <form action="{{ route('rentals.history') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Tìm theo tên hoặc SĐT khách hàng..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>

        @push('styles')
        <style>
            /* Hide table on mobile */
            @media (max-width: 991.98px) {
                .table-responsive {
                    display: none;
                }
            }
            /* Show cards on mobile */
            .history-cards {
                display: none;
            }
            @media (max-width: 991.98px) {
                .history-cards {
                    display: block;
                }
            }
            .history-card {
                margin-bottom: 1rem;
                border: 1px solid #dee2e6;
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

        <!-- Mobile Card View -->
        <div class="history-cards d-lg-none">
            @if($rentals->count() > 0)
                @foreach($rentals as $rental)
                    <div class="card history-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Đơn #{{ $rental->id }}</strong>
                            </div>
                            <div>
                                @if($rental->status === 'returned')
                                    <span class="badge bg-info">Đã trả</span>
                                @elseif($rental->isOverdue())
                                    <span class="badge bg-danger">Quá hạn</span>
                                @else
                                    <span class="badge bg-success">Đang thuê</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="mb-1"><strong>Sản phẩm:</strong> 
                                @foreach($rental->products as $product)
                                    <span class="badge bg-light text-dark border">{{ $product->product_code }}</span>
                                @endforeach
                            </p>
                            <p class="mb-1"><strong>Khách hàng:</strong> {{ $rental->customer->name }} - {{ $rental->customer->phone }}</p>
                            <p class="mb-1"><strong>Ngày thuê:</strong> {{ $rental->rental_date->format('d/m/Y') }}</p>
                            <p class="mb-1"><strong>Tiền thuê:</strong> {{ number_format($rental->rental_fee) }} VNĐ</p>
                            <p class="mb-1"><strong>Cọc:</strong> {{ $rental->getDepositInfo() }}</p>
                            <p class="mb-1"><strong>Tổng trả:</strong> {{ number_format($rental->total_paid) }} VNĐ</p>
                            
                            <a href="{{ route('rentals.show', $rental) }}" class="btn btn-sm btn-outline-info w-100">
                                <i class="fas fa-eye"></i> Xem chi tiết
                            </a>
                        </div>
                    </div>
                @endforeach
            @else
                <!-- No rentals message for mobile -->
            @endif
        </div>

        <!-- Desktop Table View -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                <thead class="table-light">
                    <tr>
                        <th>Mã đơn</th>
                        <th>Sản phẩm (SL)</th>
                        <th>Khách hàng</th>
                        <th>Ngày thuê</th>
                        <th>Ngày trả (Thực tế)</th>
                        <th>Tiền thuê</th>
                        <th>Cọc</th>
                        <th>Tổng trả</th>
                        <th>Trạng thái</th>
                        <th class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rentals as $rental)
                    <tr>
                        <td>#{{ $rental->id }}</td>
                        <td>
                            @foreach($rental->products as $product)
                                <span class="badge bg-light text-dark border">{{ $product->product_code }}</span>
                            @endforeach
                            <span class="badge bg-primary rounded-pill">{{ $rental->products->count() }}</span>
                        </td>
                        <td>
                            <strong>{{ $rental->customer->name }}</strong><br>
                            <small>{{ $rental->customer->phone }}</small>
                        </td>
                        <td>{{ $rental->rental_date->format('d/m/Y') }}</td>
                        <td>
                            @if($rental->actual_return_date)
                                {{ $rental->actual_return_date->format('d/m/Y') }}
                            @else
                                <span class="text-muted">Chưa trả</span>
                            @endif
                        </td>
                        <td>{{ number_format($rental->rental_fee) }} VNĐ</td>
                        <td>
                            @if($rental->hasMoneyDeposit())
                                <span class="badge bg-info">{{ number_format($rental->deposit_amount) }} VNĐ</span>
                            @elseif($rental->hasIdCardDeposit())
                                <span class="badge bg-secondary">CCCD: {{ $rental->deposit_note }}</span>
                            @else
                                <span class="text-muted">Không có</span>
                            @endif
                        </td>
                        <td>
                            <strong class="text-primary">{{ number_format($rental->total_paid) }} VNĐ</strong>
                        </td>
                        <td>
                            @if($rental->status === 'returned')
                                <span class="badge bg-info">Đã trả</span>
                            @elseif($rental->isOverdue())
                                <span class="badge bg-danger">Quá hạn</span>
                            @else
                                <span class="badge bg-success">Đang thuê</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('rentals.show', $rental) }}" 
                               class="btn btn-sm btn-outline-info"
                               data-bs-toggle="tooltip" title="Xem chi tiết">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if($rentals->count() > 0)
            <div class="d-flex justify-content-center mt-3">
                {{ $rentals->links() }}
            </div>
        @else
            <div class="text-center py-4">
                <i class="fas fa-history fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Chưa có lịch sử thuê nào</h5>
                <p class="text-muted">Khi có đơn thuê, lịch sử sẽ được ghi nhận tại đây.</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Enable Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
@endpush 