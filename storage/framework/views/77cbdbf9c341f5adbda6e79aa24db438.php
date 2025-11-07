<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                Selamat Datang di Dashboard ROMS
                
                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-danger btn-sm">Logout</button>
                </form>
            </div>
            <div class="card-body">
                <p>Halo, <strong><?php echo e(auth()->user()->name); ?></strong>!</p>
                <p>Anda login sebagai: <strong><?php echo e(strtoupper(auth()->user()->role)); ?></strong></p>
                
                <p>Dari sini, Anda bisa melanjutkan ke modul manajemen pelanggan, pesanan, dan lainnya.</p>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\davar\Documents\Proyek ROMS\roms-project\resources\views/dashboard.blade.php ENDPATH**/ ?>