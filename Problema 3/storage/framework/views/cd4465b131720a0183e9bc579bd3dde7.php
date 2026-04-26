<?php $__env->startSection('titulo', 'Tareas · Nosecaen'); ?>

<?php $__env->startSection('contenido'); ?>
    <?php $rol = auth()->user()->tipo; ?>

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h2 class="mb-0">
            <i class="fa-solid fa-clipboard-list"></i>
            <?php if($rol === 'Administrador'): ?>
                Todas las tareas
            <?php else: ?>
                Mis tareas asignadas
            <?php endif; ?>
        </h2>

        <?php if($rol === 'Administrador'): ?>
            <a href="<?php echo e(url('/tareas/crear')); ?>" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i> Nueva tarea
            </a>
        <?php endif; ?>
    </div>

    <?php if($tareas->isEmpty()): ?>
        <div class="alert alert-info">
            <?php if($rol === 'Administrador'): ?>
                Aún no hay tareas registradas.
            <?php else: ?>
                No tienes tareas asignadas en este momento.
            <?php endif; ?>
        </div>
    <?php else: ?>
        <table class="table table-striped table-bordered align-middle">
            <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Contacto</th>
                <th>Descripción</th>
                <?php if($rol === 'Administrador'): ?>
                    <th>Operario</th>
                <?php endif; ?>
                <th>F. realización</th>
                <th>Estado</th>
                <th style="width: 250px;">Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?php $__currentLoopData = $tareas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($t->id); ?></td>
                    <td><?php echo e($t->cliente?->nombre); ?></td>
                    <td><?php echo e($t->personaNombre); ?></td>
                    <td><?php echo e(\Illuminate\Support\Str::limit($t->descripcionTarea, 50)); ?></td>
                    <?php if($rol === 'Administrador'): ?>
                        <td>
                            <?php if($t->operario): ?>
                                <?php echo e($t->operario->nombre); ?>

                            <?php else: ?>
                                <span class="text-danger">
                                    <i class="fa-solid fa-triangle-exclamation"></i> Sin asignar
                                </span>
                            <?php endif; ?>
                        </td>
                    <?php endif; ?>
                    <td><?php echo e($t->fechaRealizacion?->format('d/m/Y')); ?></td>
                    <td>
                        <?php
                            $badgeColor = match($t->estadoTarea) {
                                'P' => 'warning',
                                'R' => 'success',
                                'C' => 'secondary',
                                default => 'light',
                            };
                            $labelEstado = match($t->estadoTarea) {
                                'P' => 'Pendiente',
                                'R' => 'Realizada',
                                'C' => 'Cancelada',
                                default => $t->estadoTarea,
                            };
                        ?>
                        <span class="badge bg-<?php echo e($badgeColor); ?> <?php echo e($badgeColor === 'warning' ? 'text-dark' : ''); ?>">
                            <?php echo e($labelEstado); ?>

                        </span>
                    </td>
                    <td class="text-nowrap">
                        <a href="<?php echo e(url('/tareas/detalle/'.$t->id)); ?>"
                           class="btn btn-sm btn-secondary" title="Ver">
                            <i class="fa-solid fa-eye"></i>
                        </a>

                        <?php if($rol === 'Administrador'): ?>
                            <a href="<?php echo e(url('/tareas/editar/'.$t->id)); ?>"
                               class="btn btn-sm btn-warning" title="Modificar">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <a href="<?php echo e(url('/tareas/eliminar/'.$t->id)); ?>"
                               class="btn btn-sm btn-danger" title="Eliminar">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        <?php else: ?>
                            
                            <?php if($t->estadoTarea === 'P'): ?>
                                <a href="<?php echo e(url('/tareas/completar/'.$t->id)); ?>"
                                   class="btn btn-sm btn-success" title="Completar">
                                    <i class="fa-solid fa-check"></i> Completar
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

        <?php echo e($tareas->links()); ?>

    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.base', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Apellidos Nombre - PC\Problema 3\proyecto\resources\views/tareas/lista.blade.php ENDPATH**/ ?>