

<?php $__env->startSection('title', 'HOME - AURIS'); ?>

<?php $__env->startSection('page-title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>

<?php $__env->startPush('styles'); ?>
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
<?php $__env->stopPush(); ?>

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
                        <div class="h5 mb-0 font-weight-bold"><?php echo e($totalProducts); ?></div>
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
                        <div class="h5 mb-0 font-weight-bold"><?php echo e($availableProducts); ?></div>
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
                        <div class="h5 mb-0 font-weight-bold"><?php echo e($activeRentals); ?></div>
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
                        <div class="h5 mb-0 font-weight-bold"><?php echo e(number_format($thisMonthRevenue)); ?> VNĐ</div>
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
                <a href="<?php echo e(route('rentals.index')); ?>" class="btn btn-sm btn-primary">
                    Xem tất cả
                </a>
            </div>
            <div class="card-body">
                <?php if($recentRentals->count() > 0): ?>
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
                                <?php $__currentLoopData = $recentRentals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rental): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <?php $__currentLoopData = $rental->products->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span class="badge bg-light text-dark border"><?php echo e($product->product_code); ?></span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($rental->products->count() > 2): ?>
                                            <span class="badge bg-secondary">+<?php echo e($rental->products->count() - 2); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($rental->customer->name); ?></td>
                                    <td>
                                        <?php if($rental->status === 'returned'): ?>
                                            <span class="badge bg-info">Đã trả</span>
                                        <?php elseif($rental->isOverdue()): ?>
                                            <span class="badge bg-danger">Quá hạn</span>
                                        <?php else: ?>
                                            <span class="badge bg-success">Đang thuê</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center">Chưa có đơn thuê nào</p>
                <?php endif; ?>
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
                    <a href="<?php echo e(route('products.create')); ?>" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Thêm sản phẩm
                    </a>
                    <a href="<?php echo e(route('rentals.create')); ?>" class="btn btn-success">
                        <i class="fas fa-handshake me-2"></i>Tạo đơn thuê
                    </a>
                    <a href="<?php echo e(route('products.index')); ?>" class="btn btn-info">
                        <i class="fas fa-box me-2"></i>Quản lý sản phẩm
                    </a>
                </div>
            </div>
        </div>

        <!-- Alerts -->
        <?php if($overdueRentals > 0): ?>
        <div class="card shadow mb-4 border-left-warning">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>Cảnh báo
                </h6>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <strong><?php echo e($overdueRentals); ?></strong> đơn thuê đã quá hạn!
                    <a href="<?php echo e(route('rentals.index')); ?>" class="alert-link">Xem chi tiết</a>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Recent Products -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-box me-2"></i>Sản phẩm mới
                </h6>
            </div>
            <div class="card-body">
                <?php if($recentProducts->count() > 0): ?>
                    <div class="list-group list-group-flush">
                        <?php $__currentLoopData = $recentProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong><?php echo e($product->product_code); ?></strong><br>
                                <small class="text-muted"><?php echo e($product->name); ?></small>
                            </div>
                            <span class="badge bg-primary rounded-pill"><?php echo e(number_format($product->rental_price)); ?> VNĐ</span>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center">Chưa có sản phẩm nào</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\auris\resources\views/dashboard.blade.php ENDPATH**/ ?>