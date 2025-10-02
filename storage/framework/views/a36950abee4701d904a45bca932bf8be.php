

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4">🎓 Student Dashboard</h1>
    <p class="text-muted">
        Welcome, <?php echo e(auth()->user()->name); ?>! Here are your upcoming appointments:
    </p>

    <?php if($appointments->count() > 0): ?>
        <table class="table table-hover mt-4">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e(\Carbon\Carbon::parse($appointment->requested_datetime)->format('F d, Y')); ?></td>
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
        <p class="text-muted mt-4">No upcoming appointments.</p>
    <?php endif; ?>

    <a href="<?php echo e(route('student.appointments.index')); ?>" class="btn btn-primary mt-3">Book Appointment</a>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\med\resources\views\students\dashboard.blade.php ENDPATH**/ ?>