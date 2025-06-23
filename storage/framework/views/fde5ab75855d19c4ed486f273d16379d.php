

<?php $__env->startSection('title', 'Lịch sử Thuê'); ?>

<?php $__env->startSection('page-title', 'Lịch sử Thuê'); ?>

<?php $__env->startSection('content'); ?>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-history me-2"></i>Toàn bộ lịch sử đơn thuê
        </h6>
    </div>
    <div class="card-body">
        <!-- Search Form -->
        <div class="mb-3">
            <form action="<?php echo e(route('rentals.history')); ?>" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Tìm theo tên hoặc SĐT khách hàng..." value="<?php echo e(request('search')); ?>">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>

        <?php $__env->startPush('styles'); ?>
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
        <?php $__env->stopPush(); ?>

        <!-- Mobile Card View -->
        <div class="history-cards d-lg-none">
            <?php if($rentals->count() > 0): ?>
                <?php $__currentLoopData = $rentals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rental): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="card history-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Đơn #<?php echo e($rental->id); ?></strong>
                            </div>
                            <div>
                                <?php if($rental->status === 'returned'): ?>
                                    <span class="badge bg-info">Đã trả</span>
                                <?php elseif($rental->isOverdue()): ?>
                                    <span class="badge bg-danger">Quá hạn</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Đang thuê</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="mb-1"><strong>Sản phẩm:</strong> 
                                <?php $__currentLoopData = $rental->products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="badge bg-light text-dark border"><?php echo e($product->product_code); ?></span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </p>
                            <p class="mb-1"><strong>Khách hàng:</strong> <?php echo e($rental->customer->name); ?> - <?php echo e($rental->customer->phone); ?></p>
                            <p class="mb-1"><strong>Ngày thuê:</strong> <?php echo e($rental->rental_date->format('d/m/Y')); ?></p>
                            <p class="mb-1"><strong>Tổng tiền:</strong> <?php echo e(number_format($rental->total_price)); ?> VNĐ</p>
                            
                            <a href="<?php echo e(route('rentals.show', $rental)); ?>" class="btn btn-sm btn-outline-info w-100">
                                <i class="fas fa-eye"></i> Xem chi tiết
                            </a>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <!-- No rentals message for mobile -->
            <?php endif; ?>
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
                        <th>Tổng tiền</th>
                        <th>Tiền cọc</th>
                        <th>Trạng thái</th>
                        <th class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $rentals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rental): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td>#<?php echo e($rental->id); ?></td>
                        <td>
                            <?php $__currentLoopData = $rental->products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="badge bg-light text-dark border"><?php echo e($product->product_code); ?></span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <span class="badge bg-primary rounded-pill"><?php echo e($rental->products->count()); ?></span>
                        </td>
                        <td>
                            <strong><?php echo e($rental->customer->name); ?></strong><br>
                            <small><?php echo e($rental->customer->phone); ?></small>
                        </td>
                        <td><?php echo e($rental->rental_date->format('d/m/Y')); ?></td>
                        <td>
                            <?php if($rental->actual_return_date): ?>
                                <?php echo e($rental->actual_return_date->format('d/m/Y')); ?>

                            <?php else: ?>
                                <span class="text-muted">Chưa trả</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo e(number_format($rental->total_price)); ?> VNĐ</td>
                        <td><?php echo e(number_format($rental->deposit_amount)); ?> VNĐ</td>
                        <td>
                            <?php if($rental->status === 'returned'): ?>
                                <span class="badge bg-info">Đã trả</span>
                            <?php elseif($rental->isOverdue()): ?>
                                <span class="badge bg-danger">Quá hạn</span>
                            <?php else: ?>
                                <span class="badge bg-success">Đang thuê</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <a href="<?php echo e(route('rentals.show', $rental)); ?>" 
                               class="btn btn-sm btn-outline-info"
                               data-bs-toggle="tooltip" title="Xem chi tiết">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        
        <?php if($rentals->count() > 0): ?>
            <div class="d-flex justify-content-center mt-3">
                <?php echo e($rentals->links()); ?>

            </div>
        <?php else: ?>
            <div class="text-center py-4">
                <i class="fas fa-history fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Chưa có lịch sử thuê nào</h5>
                <p class="text-muted">Khi có đơn thuê, lịch sử sẽ được ghi nhận tại đây.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Enable Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
<?php $__env->stopPush(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\auris\resources\views/rentals/history.blade.php ENDPATH**/ ?>