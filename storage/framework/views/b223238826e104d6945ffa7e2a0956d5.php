<?php $__env->startSection('content'); ?>
<main class="pt-20 bg-gradient-to-br from-sky-50 via-white to-teal-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
                    🩺 Manage Appointments
                </h1>
                <p class="text-gray-600 text-sm">Review, update, and log findings for each appointment.</p>
            </div>
        </div>

        <!-- Flash messages -->
        <?php if(session('success')): ?>
            <div class="alert alert-success shadow-sm rounded-lg"><?php echo e(session('success')); ?></div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="alert alert-danger shadow-sm rounded-lg"><?php echo e(session('error')); ?></div>
        <?php endif; ?>

        <?php if($appointments->isEmpty()): ?>
            <div class="text-center py-12 text-gray-500">No appointments found.</div>
        <?php else: ?>
        <!-- Table -->
        <div class="bg-white shadow-lg rounded-2xl overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-gradient-to-r from-blue-50 to-teal-50 text-gray-700 text-sm uppercase">
                        <tr>
                            <th class="px-4 py-3 text-left">Student</th>
                            <th class="px-4 py-3 text-left">Requested</th>
                            <th class="px-4 py-3 text-left">Approved</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-left">Completion</th>
                            <th class="px-4 py-3 text-center">Action</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 font-medium text-gray-800"><?php echo e($appointment->student->name ?? 'Unknown'); ?></td>
                            <td class="px-4 py-3 text-gray-600"><?php echo e(\Carbon\Carbon::parse($appointment->requested_datetime)->format('M d, Y h:i A')); ?></td>
                            <td class="px-4 py-3 text-gray-500">
                                <?php if($appointment->approved_datetime): ?>
                                    <?php echo e(\Carbon\Carbon::parse($appointment->approved_datetime)->format('M d, Y h:i A')); ?>

                                <?php else: ?>
                                    <span class="italic text-gray-400">Not set</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3">
                                <span class="badge d-flex align-items-center gap-1 px-2 py-1 text-sm rounded-pill
                                    <?php if($appointment->status === 'pending'): ?> bg-warning text-dark
                                    <?php elseif($appointment->status === 'approved'): ?> bg-success
                                    <?php elseif($appointment->status === 'rescheduled'): ?> bg-info text-dark
                                    <?php elseif($appointment->status === 'declined'): ?> bg-danger
                                    <?php elseif($appointment->status === 'completed'): ?> bg-primary
                                    <?php elseif($appointment->status === 'cancelled'): ?> bg-secondary text-white
                                    <?php endif; ?>">
                                    <?php switch($appointment->status):
                                        case ('approved'): ?> ✅ <?php break; ?>
                                        <?php case ('pending'): ?> ⏳ <?php break; ?>
                                        <?php case ('rescheduled'): ?> 🔁 <?php break; ?>
                                        <?php case ('declined'): ?> ❌ <?php break; ?>
                                        <?php case ('completed'): ?> 🩺 <?php break; ?>
                                        <?php case ('cancelled'): ?> 🚫 <?php break; ?>
                                    <?php endswitch; ?>
                                    <?php echo e(ucfirst($appointment->status)); ?>

                                </span>
                            </td>
                            <td class="px-4 py-3">
    <?php if($appointment->status === 'completed'): ?>
        <span class="badge bg-success text-white px-3 py-1 rounded-pill">✅ Completed</span>
    <?php else: ?>
        <span class="badge bg-secondary text-white px-3 py-1 rounded-pill">Not Completed</span>
    <?php endif; ?>
</td>

                            <td class="px-4 py-3 text-center">
    <?php if(!in_array($appointment->status, ['cancelled', 'declined', 'completed', 'rescheduled', 'approved'])): ?>
        <button class="btn btn-sm btn-primary shadow-sm"
            data-bs-toggle="modal"
            data-bs-target="#manageAppointmentModal"
            data-action="<?php echo e(route('nurse.appointments.update', $appointment)); ?>"
            data-approved_datetime="<?php echo e($appointment->approved_datetime); ?>"
            data-status="<?php echo e($appointment->status); ?>"
            data-note="<?php echo e($appointment->admin_note); ?>"
            data-findings="<?php echo e($appointment->findings); ?>">
            Manage
        </button>
    <?php else: ?>
        <span class="text-gray-400 text-sm italic">No actions available</span>
    <?php endif; ?>
</td>

                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <div class="p-4">
                <?php echo e($appointments->links()); ?>

            </div>
        </div>
        <?php endif; ?>
    </div>
</main>

<!-- 🩺 Responsive Modal -->
<div class="modal fade" id="manageAppointmentModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl"> 
    <form method="POST" id="manageAppointmentForm" class="w-100">
      <?php echo csrf_field(); ?>
      <?php echo method_field('PUT'); ?>
      <div class="modal-content shadow-2xl border-0 rounded-3">

        <!-- Header -->
        <div class="modal-header bg-gradient-to-r from-teal-500 to-blue-600 text-white">
          <h5 class="modal-title fw-semibold flex items-center gap-2">
            🩺 Manage Appointment
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <!-- Body -->
        <div class="modal-body p-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <!-- Approved Date -->
            <div class="mb-3">
              <label class="form-label fw-semibold">Approved Date & Time</label>
              <input type="datetime-local" name="approved_datetime" id="approved_datetime"
                     class="form-control focus:ring-2 focus:ring-teal-400 focus:outline-none rounded-lg shadow-sm">
            </div>

            <!-- Status -->
            <div class="mb-3">
              <label class="form-label fw-semibold">Status</label>
              <select name="status" id="status"
                      class="form-select focus:ring-2 focus:ring-blue-400 focus:outline-none rounded-lg shadow-sm"
                      required>
                <option value="approved">Approve</option>
                <option value="rescheduled">Reschedule</option>
                <option value="declined">Decline</option>
                <option value="completed">Completed</option>
              </select>
            </div>
          </div>

          <!-- Nurse Note -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Nurse Note</label>
            <textarea name="admin_note" id="admin_note"
                      class="form-control focus:ring-2 focus:ring-blue-400 focus:outline-none rounded-lg shadow-sm"
                      rows="2" placeholder="Type any note here..."></textarea>
          </div>

          <!-- Findings -->
          <div class="mb-3">
            <label class="form-label fw-semibold">🩺 Findings</label>
            <textarea name="findings" id="findings"
                      class="form-control focus:ring-2 focus:ring-teal-400 focus:outline-none rounded-lg shadow-sm"
                      rows="3"
                      placeholder="e.g. Mild fever, advised hydration..."></textarea>
          </div>
        </div>

        <!-- Footer -->
        <div class="modal-footer bg-gray-50 flex flex-wrap justify-end gap-2">
          <button type="submit" id="saveBtn"
                  class="btn btn-success shadow-sm px-4 py-2 transition hover:scale-105">
            💾 Save
          </button>
          <button type="button" class="btn btn-outline-secondary px-4 py-2"
                  data-bs-dismiss="modal">
            Close
          </button>
        </div>

      </div>
    </form>
  </div>
</div>

<!-- Responsive Tweaks -->
<style>
@media (max-width: 768px) {
  .modal-dialog {
    max-width: 95% !important;
    margin: auto;
  }
  .modal-content {
    border-radius: 1rem;
  }
  .modal-body {
    padding: 1rem !important;
  }
  .modal-footer {
    flex-direction: column;
    gap: 0.5rem;
  }
}
</style>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const manageModal = document.getElementById('manageAppointmentModal');
    const form = document.getElementById('manageAppointmentForm');
    const saveBtn = document.getElementById('saveBtn');

    // 🩺 Fill modal when opened
    manageModal.addEventListener('show.bs.modal', (event) => {
        const button = event.relatedTarget;
        form.action = button.getAttribute('data-action');

        const approvedDatetime = button.getAttribute('data-approved_datetime');
        const status = button.getAttribute('data-status');
        const note = button.getAttribute('data-note');
        const findings = button.getAttribute('data-findings');

        const approvedInput = document.getElementById('approved_datetime');
        approvedInput.value = approvedDatetime
            ? approvedDatetime.replace(' ', 'T')
            : new Date(Date.now() + 5 * 60 * 1000).toISOString().slice(0, 16);

        document.getElementById('status').value = status ?? 'approved';
        document.getElementById('admin_note').value = note ?? '';
        document.getElementById('findings').value = findings ?? '';
    });

    // ✅ AJAX submission with clearer error handling
    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        saveBtn.disabled = true;
        saveBtn.innerHTML = '⏳ Saving...';

        try {
            const url = form.action;
            const formData = new FormData(form);

            const res = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-HTTP-Method-Override': 'PUT',
                    'Accept': 'application/json' // ✅ Force Laravel to respond in JSON
                },
                body: formData
            });

            const data = await res.json().catch(() => null);

            if (!res.ok || !data?.success) {
                throw new Error(data?.message || 'Something went wrong while updating the appointment.');
            }

            toastr.success('✅ Appointment updated successfully!');
            const modal = bootstrap.Modal.getInstance(manageModal);
            modal.hide();

            // Refresh after modal closes
            setTimeout(() => window.location.reload(), 1000);

        } catch (err) {
            console.error('⚠️ Error:', err);
            toastr.error(err.message || 'An unexpected error occurred.', 'Error');
        } finally {
            saveBtn.disabled = false;
            saveBtn.innerHTML = '💾 Save';
        }
    });

    // 🔔 Toastr configuration
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: 'toast-top-right',
        timeOut: '3000'
    };
});
</script>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\medcare-system\resources\views/nurse/appointments/index.blade.php ENDPATH**/ ?>