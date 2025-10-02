

<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <h2 class="mb-4">📋 Manage All Appointments</h2>

    
    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    
    <div class="mb-3">
        <a href="<?php echo e(route('admin.appointments.all')); ?>" class="btn btn-outline-primary btn-sm">All</a>
        <a href="<?php echo e(route('admin.appointments.today')); ?>" class="btn btn-outline-success btn-sm">Today</a>
        <a href="<?php echo e(route('admin.appointments.week')); ?>" class="btn btn-outline-info btn-sm">This Week</a>
    </div>

    <?php if($allAppointments->count() > 0): ?>
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Student</th>
                    <th>Requested Date</th>
                    <th>Status</th>
                    <th>Approved Date</th>
                    <th>Admin Note</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $allAppointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($appointment->student->name); ?></td>
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
                        
                        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#manageModal<?php echo e($appointment->id); ?>">
                            Manage
                        </button>
                    </td>
                </tr>

                
                <div class="modal fade" id="manageModal<?php echo e($appointment->id); ?>" tabindex="-1" aria-labelledby="manageModalLabel<?php echo e($appointment->id); ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="POST" action="<?php echo e(route('admin.appointments.update', $appointment->id)); ?>">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="manageModalLabel<?php echo e($appointment->id); ?>">Manage Appointment</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Approved Date & Time</label>
                                        <input type="datetime-local" class="form-control" name="approved_datetime" value="<?php echo e($appointment->approved_datetime); ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-select" required>
                                            <option value="pending" <?php if($appointment->status === 'pending'): echo 'selected'; endif; ?>>Pending</option>
                                            <option value="approved" <?php if($appointment->status === 'approved'): echo 'selected'; endif; ?>>Approved</option>
                                            <option value="rescheduled" <?php if($appointment->status === 'rescheduled'): echo 'selected'; endif; ?>>Rescheduled</option>
                                            <option value="declined" <?php if($appointment->status === 'declined'): echo 'selected'; endif; ?>>Declined</option>
                                            <option value="completed" <?php if($appointment->status === 'completed'): echo 'selected'; endif; ?>>Completed</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Admin Note</label>
                                        <textarea name="admin_note" class="form-control" rows="2"><?php echo e($appointment->admin_note); ?></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-muted">No appointments found.</p>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\med\resources\views\admin\index.blade.php ENDPATH**/ ?>