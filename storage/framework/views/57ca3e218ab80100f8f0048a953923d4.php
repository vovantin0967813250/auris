

<?php $__env->startSection('title', 'Quản lý Đơn thuê'); ?>

<?php $__env->startSection('page-title', 'Quản lý Đơn thuê'); ?>

<?php $__env->startPush('styles'); ?>
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
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<!-- Overdue Rentals Alert -->
<?php if($overdueRentals->count() > 0): ?>
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-triangle me-2"></i>
    <strong><?php echo e($overdueRentals->count()); ?></strong> đơn thuê đã quá hạn!
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- Active Rentals -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-handshake me-2"></i>Đơn thuê đang hoạt động
        </h6>
        <a href="<?php echo e(route('rentals.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tạo đơn thuê
        </a>
    </div>
    <div class="card-body">
        <!-- Mobile Card View -->
        <div class="rental-cards d-lg-none">
            <?php if($activeRentals->count() > 0): ?>
                <?php $__currentLoopData = $activeRentals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rental): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="card rental-card <?php echo e($rental->isOverdue() ? 'border-warning' : 'border-primary'); ?>">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Đơn #<?php echo e($rental->id); ?></strong>
                            </div>
                            <div>
                                <?php if($rental->isOverdue()): ?>
                                    <span class="badge bg-danger">Quá hạn</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Đang thuê</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="mb-1"><strong>Khách hàng:</strong> <?php echo e($rental->customer->name); ?> - <?php echo e($rental->customer->phone); ?></p>
                            <p class="mb-1">
                                <strong>Sản phẩm:</strong> 
                                <?php $__currentLoopData = $rental->products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="badge bg-light text-dark border"><?php echo e($product->product_code); ?></span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </p>
                            <p class="mb-1"><strong>Ngày trả:</strong> <?php echo e($rental->expected_return_date->format('d/m/Y')); ?></p>
                            <p class="mb-1"><strong>Tiền thuê:</strong> <?php echo e(number_format($rental->rental_fee)); ?> VNĐ</p>
                            <p class="mb-1"><strong>Cọc:</strong> <?php echo e($rental->getDepositInfo()); ?></p>
                            <p class="mb-1"><strong>Tổng trả:</strong> <?php echo e(number_format($rental->total_paid)); ?> VNĐ</p>
                            <?php if($rental->isOverdue()): ?>
                                <p class="mb-2 text-danger"><strong>Quá hạn:</strong> <?php echo e($rental->getOverdueDays()); ?> ngày</p>
                            <?php endif; ?>
                            
                            <div class="d-grid gap-2 d-sm-flex justify-content-sm-start">
                                <a href="<?php echo e(route('rentals.show', $rental)); ?>" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-eye"></i> Chi tiết
                                </a>
                                <form action="<?php echo e(route('rentals.return', $rental)); ?>" method="POST" onsubmit="return confirm('Xác nhận trả toàn bộ sản phẩm trong đơn này?')" class="d-grid">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-undo"></i> Trả hàng
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <!-- No active rentals message for mobile -->
            <?php endif; ?>
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
                        <th>Tiền thuê</th>
                        <th>Hình thức cọc</th>
                        <th>Hình thức thanh toán</th>
                        <th>Tổng trả</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $activeRentals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rental): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="<?php echo e($rental->isOverdue() ? 'table-warning' : ''); ?>">
                        <td>
                            <strong>#<?php echo e($rental->id); ?></strong>
                        </td>
                        <td>
                            <?php $__currentLoopData = $rental->products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="badge bg-light text-dark border"><?php echo e($product->product_code); ?></span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <span class="badge bg-primary rounded-pill"><?php echo e($rental->products->count()); ?></span>
                        </td>
                        <td>
                            <div>
                                <strong><?php echo e($rental->customer->name); ?></strong><br>
                                <small class="text-muted"><?php echo e($rental->customer->phone); ?></small>
                            </div>
                        </td>
                        <td><?php echo e($rental->rental_date->format('d/m/Y')); ?></td>
                        <td>
                            <span class="<?php echo e($rental->isOverdue() ? 'text-danger fw-bold' : ''); ?>">
                                <?php echo e($rental->expected_return_date->format('d/m/Y')); ?>

                            </span>
                            <?php if($rental->isOverdue()): ?>
                                <br><small class="text-danger">Quá <?php echo e($rental->getOverdueDays()); ?> ngày</small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?php echo e(number_format($rental->rental_fee)); ?> VNĐ</strong>
                        </td>
                        <td>
                            <?php if($rental->hasMoneyDeposit()): ?>
                                <span class="badge bg-info"><?php echo e(number_format($rental->deposit_amount)); ?> VNĐ</span>
                            <?php elseif($rental->hasIdCardDeposit()): ?>
                                <span class="badge bg-secondary">CCCD: <?php echo e($rental->deposit_note); ?></span>
                            <?php else: ?>
                                <span class="text-muted">Không có</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($rental->deposit_payment_method === 'momo'): ?>
                                <span class="badge bg-warning text-dark">Momo</span>
                            <?php elseif($rental->deposit_payment_method === 'techcombank'): ?>
                                <span class="badge bg-primary">Techcombank</span>
                            <?php elseif($rental->deposit_payment_method === 'cash'): ?>
                                <span class="badge bg-success">Tiền mặt</span>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong class="text-primary"><?php echo e(number_format($rental->total_paid)); ?> VNĐ</strong>
                        </td>
                        <td>
                            <?php if($rental->isOverdue()): ?>
                                <span class="badge bg-danger">Quá hạn</span>
                            <?php else: ?>
                                <span class="badge bg-success">Đang thuê</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="<?php echo e(route('rentals.show', $rental)); ?>" 
                                   class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="<?php echo e(route('rentals.return', $rental)); ?>" 
                                      method="POST" 
                                      onsubmit="return confirm('Xác nhận trả toàn bộ sản phẩm trong đơn này?')"
                                      style="display: inline;">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-undo"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        
        <?php if($activeRentals->count() > 0): ?>
            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                <?php echo e($activeRentals->links()); ?>

            </div>
        <?php else: ?>
            <div class="text-center py-4">
                <i class="fas fa-handshake fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Chưa có đơn thuê nào</h5>
                <p class="text-muted">Bắt đầu bằng cách tạo đơn thuê đầu tiên</p>
                <a href="<?php echo e(route('rentals.create')); ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Tạo đơn thuê
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Overdue Rentals Details -->
<?php if($overdueRentals->count() > 0): ?>
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
                    <?php $__currentLoopData = $overdueRentals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rental): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><strong>#<?php echo e($rental->id); ?></strong></td>
                        <td>
                            <strong><?php echo e($rental->customer->name); ?></strong><br>
                            <small class="text-muted"><?php echo e($rental->customer->phone); ?></small>
                        </td>
                        <td><?php echo e($rental->expected_return_date->format('d/m/Y')); ?></td>
                        <td>
                            <span class="badge bg-danger"><?php echo e($rental->getOverdueDays()); ?> ngày</span>
                        </td>
                        <td>
                            <a href="<?php echo e(route('rentals.show', $rental)); ?>" 
                               class="btn btn-sm btn-warning">
                                <i class="fas fa-eye me-1"></i>Xem chi tiết
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\auris\resources\views/rentals/index.blade.php ENDPATH**/ ?>