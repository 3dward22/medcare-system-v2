

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4">Your Appointments</h1>

    
    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    
    <div class="card mb-4">
        <div class="card-header">📅 Request New Appointment</div>
        <div class="card-body">
            <form action="<?php echo e(route('student.appointments.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="mb-3">
                    <label for="requested_datetime" class="form-label">Choose Date & Time</label>
                    <input type="datetime-local" name="requested_datetime" id="requested_datetime" 
                           class="form-control <?php $__errorArgs = ['requested_datetime'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                    <?php $__errorArgs = ['requested_datetime'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <button type="submit" class="btn btn-primary">Request Appointment</button>
            </form>
        </div>
    </div>

    
    <div class="card">
        <div class="card-header">My Appointments</div>
        <div class="card-body">
            <?php if($appointments->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Date & Time</th>
                                <th>Status</th>
                                <th>Approved Date</th>
                                <th>Admin Note</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e(\Carbon\Carbon::parse($appointment->requested_datetime)->format('M d, Y h:i A')); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo e($appointment->status === 'approved' ? 'success' : 
                                        ($appointment->status === 'pending' ? 'warning' : 
                                        ($appointment->status === 'declined' ? 'danger' : 'secondary'))); ?>">
                                        <?php echo e(ucfirst($appointment->status)); ?>

                                    </span>
                                </td>
                                <td><?php echo e($appointment->approved_datetime ? \Carbon\Carbon::parse($appointment->approved_datetime)->format('M d, Y h:i A') : '-'); ?></td>
                                <td><?php echo e($appointment->admin_note ?? '-'); ?></td>
                                <td>
                                    <?php if($appointment->status === 'pending'): ?>
                                        <form action="<?php echo e(route('student.appointments.destroy', $appointment->id)); ?>" 
                                              method="POST" 
                                              onsubmit="return confirm('Are you sure you want to cancel this appointment?')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button class="btn btn-danger btn-sm">Cancel</button>
                                        </form>
                                    <?php else: ?>
                                        <em>No action</em>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted">No appointments booked yet.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\med\resources\views\students\appointments\index.blade.php ENDPATH**/ ?>