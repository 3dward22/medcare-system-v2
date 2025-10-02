

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>📅 This Week's Appointments</h1>
        <div>
            <a href="<?php echo e(route('admin.appointments.all')); ?>" 
               class="btn <?php echo e(Route::is('admin.appointments.all') ? 'btn-primary' : 'btn-outline-primary'); ?> me-2">
               All
            </a>
            <a href="<?php echo e(route('admin.appointments.today')); ?>" 
               class="btn <?php echo e(Route::is('admin.appointments.today') ? 'btn-success' : 'btn-outline-success'); ?> me-2">
               Today
            </a>
            <a href="<?php echo e(route('admin.appointments.week')); ?>" 
               class="btn <?php echo e(Route::is('admin.appointments.week') ? 'btn-warning text-white' : 'btn-outline-warning'); ?>">
               This Week
            </a>
        </div>
    </div>

    <?php if($appointments->count() > 0): ?>
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Student Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($appointment->user->name ?? 'Unknown'); ?></td>
                        <td><?php echo e(\Carbon\Carbon::parse($appointment->requested_datetime)->format('M d, Y')); ?></td>
                        <td><?php echo e(\Carbon\Carbon::parse($appointment->requested_datetime)->format('h:i A')); ?></td>
                        <td>
                            <span class="badge 
                                <?php if($appointment->status === 'pending'): ?> bg-warning
                                <?php elseif($appointment->status === 'approved'): ?> bg-success
                                <?php else: ?> bg-secondary
                                <?php endif; ?>">
                                <?php echo e(ucfirst($appointment->status)); ?>

                            </span>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-muted text-center my-3">No appointments scheduled for this week.</p>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\med\resources\views\admin\appointments\week.blade.php ENDPATH**/ ?>