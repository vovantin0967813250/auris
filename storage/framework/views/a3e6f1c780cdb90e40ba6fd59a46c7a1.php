<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Quản lý Shop Cho Thuê Quần Áo'); ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 100;
            width: 250px;
            padding: 48px 0 0;
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s;
        }
        @media (max-width: 767.98px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.active {
                transform: translateX(0);
            }
            .main-content {
                width: 100%;
                margin-left: 0;
            }
        }
        @media (min-width: 768px) {
            .main-content {
                margin-left: 250px;
            }
        }

        .sidebar-header {
            margin-bottom: 1rem;
            color: white;
            text-align: center;
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 0.75rem 1.5rem;
            border-radius: 0;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.1);
        }
        .main-content {
            transition: margin-left .3s;
        }
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        }
        
        .navbar-toggler {
            border: none;
        }
        .navbar-toggler:focus {
            box-shadow: none;
        }

        .sidebar-close-btn {
            position: absolute;
            top: 0.5rem;
            right: 0.75rem;
            font-size: 1.5rem;
            color: rgba(255,255,255,0.8);
            background: none;
            border: none;
        }
        .sidebar-close-btn:hover {
            color: white;
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <!-- Mobile top navbar -->
    <nav class="navbar navbar-dark bg-dark d-md-none">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" id="sidebarToggle">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="<?php echo e(route('dashboard')); ?>">Auris</a>
        </div>
    </nav>
    
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <button class="d-md-none sidebar-close-btn" id="sidebarCloseBtn">&times;</button>
            <div class="sidebar-header">
                <h4 class="text-white">
                    <i class="fas fa-tshirt me-2"></i>
                    Auris
                </h4>
            </div>
            
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>" href="<?php echo e(route('dashboard')); ?>">
                        <i class="fas fa-tachometer-alt fa-fw me-2"></i>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('products.*') ? 'active' : ''); ?>" href="<?php echo e(route('products.index')); ?>">
                        <i class="fas fa-box fa-fw me-2"></i>
                        Quản lý Sản phẩm
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('rentals.index') ? 'active' : ''); ?>" href="<?php echo e(route('rentals.index')); ?>">
                        <i class="fas fa-handshake fa-fw me-2"></i>
                        Quản lý Thuê
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('rentals.create') ? 'active' : ''); ?>" href="<?php echo e(route('rentals.create')); ?>">
                        <i class="fas fa-plus fa-fw me-2"></i>
                        Tạo đơn thuê
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('rentals.history') ? 'active' : ''); ?>" href="<?php echo e(route('rentals.history')); ?>">
                        <i class="fas fa-history fa-fw me-2"></i>
                        Lịch sử thuê
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('notes.*') ? 'active' : ''); ?>" href="<?php echo e(route('notes.index')); ?>">
                        <i class="fas fa-sticky-note fa-fw me-2"></i>
                        Ghi chú
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('reports.index') ? 'active' : ''); ?>" href="<?php echo e(route('reports.index')); ?>">
                        <i class="fas fa-chart-bar fa-fw me-2"></i>
                        Báo cáo thống kê
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Main content -->
        <main class="main-content flex-grow-1 p-3 p-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom d-none d-md-flex">
                <h1 class="h2"><?php echo $__env->yieldContent('page-title', 'Dashboard'); ?></h1>
            </div>

            <!-- Flash Messages -->
            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?php echo e(session('error')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <ul class="mb-0">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
         $(document).ready(function() {
            $("#sidebarToggle").click(function() {
                $("#sidebar").toggleClass("active");
            });

            $("#sidebarCloseBtn").click(function() {
                $("#sidebar").removeClass("active");
            });
        });
    </script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html> <?php /**PATH C:\xampp\htdocs\auris\resources\views/layouts/app.blade.php ENDPATH**/ ?>