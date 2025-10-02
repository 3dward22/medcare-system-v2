

<?php $__env->startSection('content'); ?>
<div class="container">
    <h3 class="mb-4">📅 Manage Appointments</h3>

    
    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    <?php if($appointments->isEmpty()): ?>
        <p class="text-muted">No appointments found.</p>
    <?php else: ?>
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>Student</th>
                    <th>Requested Date</th>
                    <th>Approved Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($appointment->student->name ?? 'Unknown'); ?></td>
                        <td><?php echo e(\Carbon\Carbon::parse($appointment->requested_datetime)->format('M d, Y h:i A')); ?></td>
                        <td>
                            <?php if($appointment->approved_datetime): ?>
                                <?php echo e(\Carbon\Carbon::parse($appointment->approved_datetime)->format('M d, Y h:i A')); ?>

                            <?php else: ?>
                                <span class="text-muted">Not set</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge 
                                <?php if($appointment->status === 'pending'): ?> bg-warning 
                                <?php elseif($appointment->status === 'approved'): ?> bg-success
                                <?php elseif($appointment->status === 'rescheduled'): ?> bg-info
                                <?php elseif($appointment->status === 'declined'): ?> bg-danger
                                <?php endif; ?>">
                                <?php echo e(ucfirst($appointment->status)); ?>

                            </span>
                        </td>
                        <td>
                            <!-- Button to open modal -->
                            <button class="btn btn-primary btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#manageAppointmentModal"
                                    data-id="<?php echo e($appointment->id); ?>"
                                    data-approved_datetime="<?php echo e($appointment->approved_datetime); ?>"
                                    data-status="<?php echo e($appointment->status); ?>"
                                    data-note="<?php echo e($appointment->admin_note); ?>">
                                Manage
                            </button>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

        
        <div class="mt-3">
            <?php echo e($appointments->links()); ?>

        </div>
    <?php endif; ?>
</div>

<!-- Modal for managing appointment -->
<div class="modal fade" id="manageAppointmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="manageAppointmentForm">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Manage Appointment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="approved_datetime" class="form-label">Approved Date & Time</label>
                        <input type="datetime-local" name="approved_datetime" id="approved_datetime" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="approved">Approve</option>
                            <option value="rescheduled">Reschedule</option>
                            <option value="declined">Decline</option>
                            <option value="completed">Mark as Completed</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="admin_note" class="form-label">Admin Note</label>
                        <textarea name="admin_note" id="admin_note" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save Changes</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    const manageModal = document.getElementById('manageAppointmentModal');
    manageModal.addEventListener('show.bs.modal', function (event) {
        let button = event.relatedTarget;
        let id = button.getAttribute('data-id');
        let approvedDatetime = button.getAttribute('data-approved_datetime');
        let status = button.getAttribute('data-status');
        let note = button.getAttribute('data-note');

        let form = document.getElementById('manageAppointmentForm');
        form.action = "<?php echo e(url('/nurse/appointments')); ?>/" + id;

        // ✅ Automatically set approved_datetime to current time if empty
        const approvedInput = document.getElementById('approved_datetime');
        if (!approvedDatetime) {
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            approvedInput.value = `${year}-${month}-${day}T${hours}:${minutes}`;
        } else {
            approvedInput.value = approvedDatetime.replace(' ', 'T');
        }

        document.getElementById('status').value = status;
        document.getElementById('admin_note').value = note ?? '';
    });
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\med\resources\views\nurse\appointments\index.blade.php ENDPATH**/ ?>