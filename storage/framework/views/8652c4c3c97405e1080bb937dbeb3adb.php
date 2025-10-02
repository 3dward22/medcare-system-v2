

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-3">Appointment Details</h1>

    <div class="card">
        <div class="card-body">
            <p><strong>ID:</strong> <?php echo e($appointment->id); ?></p>
            <p><strong>Student:</strong> <?php echo e($appointment->student_name ?? 'N/A'); ?></p>
            <p><strong>Date & Time:</strong> <?php echo e($appointment->scheduled_at ?? 'N/A'); ?></p>
            <p><strong>Notes:</strong> <?php echo e($appointment->notes ?? 'No notes.'); ?></p>
        </div>
    </div>

    <a href="<?php echo e(route('student.appointments.index')); ?>" class="btn btn-secondary mt-3">Back to Appointments</a>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\med\resources\views\appointments\show.blade.php ENDPATH**/ ?>