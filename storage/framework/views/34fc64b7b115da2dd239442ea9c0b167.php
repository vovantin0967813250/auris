

<?php $__env->startSection('title', 'Quản lý Sản phẩm'); ?>

<?php $__env->startSection('page-title', 'Quản lý Sản phẩm'); ?>

<?php $__env->startSection('content'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Hide table on mobile */
    @media (max-width: 767.98px) {
        .table-responsive {
            display: none;
        }
    }
    /* Show cards on mobile */
    .product-cards {
        display: none;
    }
    @media (max-width: 767.98px) {
        .product-cards {
            display: block;
        }
    }
    .product-card {
        margin-bottom: 1rem;
        border: 1px solid #dee2e6;
        border-radius: .375rem;
    }
    .product-card .card-body {
        padding: 1rem;
    }
    .product-card .product-img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: .25rem;
    }

    /* Prevent text wrapping in desktop table view */
    .table-responsive .table th,
    .table-responsive .table td {
        white-space: nowrap;
        vertical-align: middle;
    }
</style>
<?php $__env->stopPush(); ?>

<!-- Statistics Cards -->
<div class="row">
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Tổng sản phẩm</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($products->total()); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-box fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Tổng lượt thuê</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($totalRentalCount); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary font-size-14">
            <i class="fas fa-box me-2"></i>Danh sách sản phẩm
        </h6>
        <a href="<?php echo e(route('products.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Thêm sản phẩm
        </a>
    </div>
    <div class="card-body">
        <!-- Mobile Card View -->
        <div class="product-cards d-md-none">
            <?php if($products->count() > 0): ?>
                <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="card product-card">
                        <div class="card-body">
                            <div class="d-flex align-items-start">
                                <!-- Image -->
                                <?php if($product->image): ?>
                                    <img src="<?php echo e(asset('storage/' . $product->image)); ?>" alt="<?php echo e($product->name); ?>" class="product-img me-3">
                                <?php else: ?>
                                    <div class="bg-light d-flex align-items-center justify-content-center me-3" style="width: 150px; height: 150px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                <?php endif; ?>

                                <!-- Product Info -->
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="mb-1"><strong><?php echo e($product->name); ?></strong></h6>
                                        <div>
                                            <?php if($product->status === 'available'): ?>
                                                <span class="badge bg-success">Có sẵn</span>
                                            <?php elseif($product->status === 'rented'): ?>
                                                <span class="badge bg-warning">Đang thuê</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Bảo trì</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <p class="mb-1 text-muted"><strong>Mã:</strong> <?php echo e($product->product_code); ?></p>
                                    <p class="mb-1"><strong>Giá thuê:</strong> <?php echo e(number_format($product->rental_price)); ?> VNĐ</p>
                                    <p class="mb-1"><strong>Giá cọc:</strong> <?php echo e(number_format($product->deposit_price)); ?> VNĐ</p>
                                    <p class="mb-2"><strong>Lượt thuê:</strong> <span class="badge bg-primary rounded-pill"><?php echo e($product->rentals_count); ?></span></p>
                                    
                                    <!-- Actions -->
                                    <div class="btn-group" role="group">
                                        <a href="<?php echo e(route('products.edit', $product)); ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i> Sửa</a>
                                        <form action="<?php echo e(route('products.destroy', $product)); ?>" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?')" style="display: inline;">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i> Xóa</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <!-- No products message for mobile -->
            <?php endif; ?>
        </div>

        <!-- Desktop Table View -->
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center">Mã SP</th>
                        <th class="text-center">Hình ảnh</th>
                        <th class="text-center">Tên sản phẩm</th>
                        <th class="text-center">Giá cho thuê</th>
                        <th class="text-center">Giá cọc</th>
                        <th class="text-center">Ngày mua</th>
                        <th class="text-center">Trạng thái</th>
                        <th class="text-center">Số lần thuê</th>
                        <th class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="text-center">
                            <strong><?php echo e($product->product_code); ?></strong>
                        </td>
                        <td class="text-center" style="width: 150px;">
                            <?php if($product->image): ?>
                                <img src="<?php echo e(asset('storage/' . $product->image)); ?>" 
                                     alt="<?php echo e($product->name); ?>" 
                                     class="img-thumbnail" 
                                     style="width: 150px; height: 150px; object-fit: cover;">
                            <?php else: ?>
                                <div class="bg-light d-flex align-items-center justify-content-center" 
                                     style="width: 150px; height: 150px;">
                                    <i class="fas fa-image text-muted"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <strong><?php echo e($product->name); ?></strong>
                            <?php if($product->description): ?>
                                <br><small class="text-muted"><?php echo e(Str::limit($product->description, 50)); ?></small>
                            <?php endif; ?>
                        </td>
                        <td class="text-center"><?php echo e(number_format($product->rental_price)); ?> VNĐ</td>
                        <td class="text-center"><?php echo e(number_format($product->deposit_price)); ?> VNĐ</td>
                        <td class="text-center"><?php echo e($product->purchase_date->format('d/m/Y')); ?></td>
                        <td class="text-center">
                            <?php if($product->status === 'available'): ?>
                                <span class="badge bg-success">Có sẵn</span>
                            <?php elseif($product->status === 'rented'): ?>
                                <span class="badge bg-warning">Đang thuê</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Bảo trì</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-primary rounded-pill"><?php echo e($product->rentals_count); ?></span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="<?php echo e(route('products.edit', $product)); ?>" 
                                   class="btn btn-sm btn-outline-primary"
                                   data-bs-toggle="tooltip" title="Chỉnh sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="<?php echo e(route('products.destroy', $product)); ?>" 
                                      method="POST" 
                                      onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?')"
                                      style="display: inline;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" 
                                            class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="tooltip" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        
        <?php if($products->count() > 0): ?>
            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                <?php echo e($products->links()); ?>

            </div>
        <?php else: ?>
            <div class="text-center py-4">
                <i class="fas fa-box fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Chưa có sản phẩm nào</h5>
                <p class="text-muted">Bắt đầu bằng cách thêm sản phẩm đầu tiên</p>
                <a href="<?php echo e(route('products.create')); ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Thêm sản phẩm
                </a>
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
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\auris\resources\views/products/index.blade.php ENDPATH**/ ?>