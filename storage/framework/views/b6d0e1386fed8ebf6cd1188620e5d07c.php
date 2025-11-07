<?php $__env->startSection('title', 'Register - ROMS'); ?>

<?php $__env->startSection('content'); ?>
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #FFE797;
        margin: 0;
        padding: 0;
    }

    .register-container {
        display: flex;
        min-height: 100vh;
    }

    .register-left {
        background-color: #84994F;
        color: white;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        padding: 40px;
    }

    .register-left h2 {
        font-weight: 700;
        font-size: 1.8rem;
        margin-bottom: 10px;
    }

    .register-left p {
        font-size: 1rem;
        opacity: 0.9;
        max-width: 80%;
        line-height: 1.5;
    }

    .register-right {
        flex: 1;
        background-color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 60px;
    }

    .register-box {
        width: 100%;
        max-width: 420px;
    }

    .register-box h3 {
        color: #B45253;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .register-box p {
        color: #555;
        font-size: 0.95rem;
        margin-bottom: 25px;
    }

    .form-label {
        font-weight: 500;
        color: #333;
    }

    .form-control {
        border-radius: 10px;
        border: 1px solid #ddd;
        padding: 10px 12px;
    }

    .form-control:focus {
        border-color: #FCB53B;
        box-shadow: 0 0 0 0.2rem rgba(252, 181, 59, 0.25);
    }

    .btn-register {
        background-color: #FCB53B;
        border: none;
        border-radius: 10px;
        padding: 10px;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
        width: 100%;
    }

    .btn-register:hover {
        background-color: #B45253;
        transform: translateY(-2px);
    }

    .divider {
        text-align: center;
        margin: 20px 0;
        color: #999;
        position: relative;
    }

    .divider::before, .divider::after {
        content: '';
        position: absolute;
        top: 50%;
        width: 40%;
        height: 1px;
        background: #ddd;
    }

    .divider::before {
        left: 0;
    }

    .divider::after {
        right: 0;
    }

    .social-btn {
        border-radius: 10px;
        border: 1px solid #ccc;
        padding: 10px;
        background-color: #fff;
        font-weight: 500;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .social-btn:hover {
        background-color: #FFE797;
        border-color: #FCB53B;
    }

    .login-link {
        margin-top: 15px;
        text-align: center;
        color: #555;
    }

    .login-link a {
        color: #B45253;
        font-weight: 600;
        text-decoration: none;
    }

    .login-link a:hover {
        text-decoration: underline;
    }

    @media (max-width: 992px) {
        .register-container {
            flex-direction: column;
        }

        .register-left {
            display: none;
        }

        .register-right {
            padding: 40px 20px;
        }
    }
</style>

<div class="register-container">
    <div class="register-left">
        <h2>Bergabung dengan ROMS Sekarang!</h2>
        <p>Daftar dan nikmati kemudahan dalam mengelola pengiriman Anda.  
           ROMS membantu bisnis dan pelanggan dengan sistem pengiriman yang cepat dan efisien.</p>
    </div>

    <div class="register-right">
        <div class="register-box">
            <h3>Selamat Datang di ROMS!</h3>
            <p>Buat akun baru Anda untuk mulai menggunakan layanan kami.</p>

            <form method="POST" action="/register">
                <?php echo csrf_field(); ?>

                <div class="mb-3">
                    <label for="name" class="form-label">Nama Lengkap *</label>
                    <input type="text"
                           class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           id="name"
                           name="name"
                           value="<?php echo e(old('name')); ?>"
                           required
                           autofocus
                           placeholder="Masukkan nama lengkap Anda">
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email *</label>
                    <input type="email"
                           class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           id="email"
                           name="email"
                           value="<?php echo e(old('email')); ?>"
                           required
                           placeholder="Masukkan email Anda">
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Kata Sandi *</label>
                    <input type="password"
                           class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           id="password"
                           name="password"
                           required
                           placeholder="Masukkan kata sandi">
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi *</label>
                    <input type="password"
                           class="form-control"
                           id="password_confirmation"
                           name="password_confirmation"
                           required
                           placeholder="Masukkan kembali kata sandi">
                </div>

                <button type="submit" class="btn-register">Daftar Sekarang</button>
            </form>

            <div class="divider">atau</div>
            <div class="d-grid gap-2">
                <a href="<?php echo e(route('google.redirect')); ?>" class="social-btn">
                    <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google" style="width: 20px; height: 20px;">
                    Login dengan Google
                </a>
            </div>

            <div class="login-link">
                Sudah punya akun? <a href="<?php echo e(route('login')); ?>">Login Sekarang</a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\davar\Documents\Proyek3_C2_RepeatOrder_SOMEAH\resources\views/auth/register.blade.php ENDPATH**/ ?>