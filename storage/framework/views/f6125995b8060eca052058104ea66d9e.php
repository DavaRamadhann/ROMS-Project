

<?php $__env->startSection('title', 'Verifikasi Email Anda - ROMS'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header" style="background-color: #84994F; color: white; font-weight: 600;">
                    <?php echo e(__('Verifikasi Alamat Email Anda')); ?>

                </div>

                <div class="card-body" style="background-color: #fff;">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>

                    <p><?php echo e(__('Sebelum melanjutkan, silakan periksa email Anda untuk link verifikasi.')); ?></p>
                    <p><?php echo e(__('Jika Anda tidak menerima email, klik tombol di bawah untuk mengirim ulang.')); ?></p>

                    <form class="d-inline" method="POST" action="<?php echo e(route('verification.send')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-primary" style="background-color: #FCB53B; border: none; color: white;">
                            <?php echo e(__('Kirim Ulang Email Verifikasi')); ?>

                        </button>
                    </form>
                    
                    <hr>
                    <p class="text-muted small">
                        <strong>Mode Development:</strong> 
                        <?php if(config('mail.mailer') === 'log'): ?>
                            Cek email di <code>storage/logs/laravel.log</code>.
                        <?php elseif(config('mail.mailer') === 'smtp' && config('mail.host') === 'sandbox.smtp.mailtrap.io'): ?>
                            Cek inbox di <strong>Mailtrap</strong> Anda.
                        <?php else: ?>
                            Cek inbox email Anda.
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\davar\Documents\Proyek3_C2_RepeatOrder_SOMEAH\resources\views/auth/verify-email.blade.php ENDPATH**/ ?>