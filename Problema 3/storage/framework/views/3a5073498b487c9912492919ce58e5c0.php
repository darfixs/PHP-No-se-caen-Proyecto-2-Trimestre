<?php $__env->startSection('titulo', 'Iniciar sesión · Nosecaen S.L.'); ?>

<?php $__env->startSection('contenido'); ?>
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <h2 class="mb-3 text-center">
                <i class="fa-solid fa-right-to-bracket"></i> Iniciar sesión
            </h2>
            <p class="text-muted small text-center mb-4">
                Introduce tu correo y contraseña para acceder.
            </p>

            
            <?php if($errors->any()): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($err); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="post" action="<?php echo e(url('login')); ?>">
                <?php echo csrf_field(); ?>

                <div class="mb-3">
                    <label class="form-label">Correo</label>
                    <input type="email" name="correo" class="form-control"
                           value="<?php echo e(old('correo')); ?>" required autofocus>
                </div>

                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox"
                           id="recordarme" name="recordarme" value="1">
                    <label class="form-check-label" for="recordarme">
                        Recordarme
                    </label>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    <i class="fa-solid fa-right-to-bracket"></i> Entrar
                </button>
            </form>

            <hr class="my-4">
            <div class="text-center small">
                ¿Eres cliente y quieres reportar una incidencia?<br>
                <a href="<?php echo e(url('/incidencia')); ?>">Registrar una incidencia</a>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.base', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Apellidos Nombre - PC\Problema 3\proyecto\resources\views/auth/login.blade.php ENDPATH**/ ?>