

<?php $__env->startSection('title', 'Chi tiết Đơn thuê'); ?>
<?php $__env->startSection('page-title', 'Chi tiết Đơn thuê #' . $rental->id); ?>

<?php $__env->startSection('content'); ?>
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
                    <?php if($rental->status === 'returned'): ?>
                        <span class="badge bg-info">Đã trả</span>
                    <?php elseif($rental->isOverdue()): ?>
                        <span class="badge bg-danger">Quá hạn</span>
                    <?php else: ?>
                        <span class="badge bg-success">Đang thuê</span>
                    <?php endif; ?>
                </p>
                <p><strong>Ngày thuê:</strong> <?php echo e($rental->rental_date->format('d/m/Y')); ?></p>
                <p><strong>Ngày trả dự kiến:</strong> <?php echo e($rental->expected_return_date->format('d/m/Y')); ?></p>
                <?php if($rental->actual_return_date): ?>
                <p><strong>Ngày trả thực tế:</strong> <?php echo e($rental->actual_return_date->format('d/m/Y')); ?></p>
                <?php endif; ?>
                 <?php if($rental->isOverdue()): ?>
                    <p class="text-danger"><strong>Quá hạn:</strong> <?php echo e($rental->getOverdueDays()); ?> ngày</p>
                <?php endif; ?>
                <hr>
                <div class="row">
                    <div class="col-6">
                        <p><strong>Tiền thuê:</strong></p>
                        <p><strong>Cọc:</strong></p>
                        <p><strong>Tổng trả:</strong></p>
                        <?php if($rental->status === 'returned' && $rental->hasMoneyDeposit()): ?>
                        <p><strong>Hoàn lại:</strong></p>
                        <?php endif; ?>
                    </div>
                    <div class="col-6 text-end">
                        <p class="text-primary"><?php echo e(number_format($rental->rental_fee)); ?> VNĐ</p>
                        <p><?php echo e($rental->getDepositInfo()); ?></p>
                        <p class="fw-bold text-success"><?php echo e(number_format($rental->total_paid)); ?> VNĐ</p>
                        <?php if($rental->status === 'returned' && $rental->hasMoneyDeposit()): ?>
                        <p class="text-info"><?php echo e(number_format($rental->deposit_amount)); ?> VNĐ</p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if($rental->notes): ?>
                <hr>
                <p><strong>Ghi chú:</strong> <?php echo e($rental->notes); ?></p>
                <?php endif; ?>
                <?php if($rental->status === 'returned' && $rental->getLateDays() > 0): ?>
                    <div class="alert alert-warning">
                        <strong>Khách trả trễ <?php echo e($rental->getLateDays()); ?> ngày.</strong><br>
                        Tiền phạt: <strong><?php echo e(number_format($rental->getLateFee())); ?> VNĐ</strong><br>
                        <?php if($rental->hasMoneyDeposit()): ?>
                            Đã trừ vào tiền cọc. Số tiền hoàn lại: <strong><?php echo e(number_format($rental->refund_amount)); ?> VNĐ</strong>
                        <?php elseif($rental->hasIdCardDeposit()): ?>
                            Vui lòng thu thêm <strong><?php echo e(number_format($rental->getLateFee())); ?> VNĐ</strong> từ khách.
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Customer Information -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-user me-2"></i>Thông tin khách hàng</h6>
            </div>
             <div class="card-body">
                <p><strong>Tên:</strong> <?php echo e($rental->customer->name); ?></p>
                <p><strong>SĐT:</strong> <?php echo e($rental->customer->phone); ?></p>
                <?php if($rental->customer->email): ?>
                <p><strong>Email:</strong> <?php echo e($rental->customer->email); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Rented Products -->
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-box me-2"></i>Các sản phẩm đã thuê (<?php echo e($rental->products->count()); ?>)
                </h6>
                <div>
                     <?php if($rental->status === 'active'): ?>
                        <form action="<?php echo e(route('rentals.return', $rental)); ?>" method="POST" onsubmit="return confirm('Xác nhận trả toàn bộ sản phẩm trong đơn này?')" style="display: inline;">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-sm btn-success"><i class="fas fa-undo me-1"></i>Đánh dấu đã trả</button>
                        </form>
                    <?php endif; ?>
                    <a href="<?php echo e(route('rentals.index')); ?>" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-left me-1"></i>Quay lại</a>
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
                            <?php $__currentLoopData = $rental->products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><strong><?php echo e($product->product_code); ?></strong></td>
                                <td>
                                    <?php if($product->image): ?>
                                        <img src="<?php echo e(asset('storage/' . $product->image)); ?>" alt="<?php echo e($product->name); ?>" class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="bg-light d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($product->name); ?></td>
                                <td><?php echo e(number_format($product->rental_price)); ?> VNĐ</td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\auris\resources\views/rentals/show.blade.php ENDPATH**/ ?>