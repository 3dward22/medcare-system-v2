<?php $__env->startSection('content'); ?>
<main class="pt-20 bg-gradient-to-br from-sky-50 via-white to-teal-50 min-h-screen">
    <div class="max-w-6xl mx-auto px-6 py-8">

        <!-- 🧭 Header -->
        <div class="flex flex-col sm:flex-row items-center justify-between mb-10">
            <div class="text-center sm:text-left">
                <h1 class="text-3xl sm:text-4xl font-bold text-gray-800 mb-2 flex items-center justify-center sm:justify-start">
                    <span class="mr-2">📅</span> My Appointments
                </h1>
                <p class="text-gray-600 text-base">Manage and track your clinic appointments easily below.</p>
            </div>

            <!-- ➕ Button to open modal -->
            <button data-bs-toggle="modal" data-bs-target="#appointmentModal"
                    class="mt-4 sm:mt-0 inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-teal-500 text-white font-semibold rounded-xl shadow hover:from-blue-700 hover:to-teal-600 transition-all duration-200">
                <span class="mr-1">➕</span> Request Appointment
            </button>
        </div>

        <!-- 📋 Appointment List -->
        <div class="bg-white shadow-md rounded-2xl border border-gray-200 overflow-hidden">
            <div class="p-5 border-b bg-gradient-to-r from-blue-50 to-teal-50 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-700 flex items-center">
                    <span class="mr-2">🗓️</span> My Appointments
                </h2>
                <span class="text-sm text-gray-500">Total: <span id="appointmentCount"><?php echo e($appointments->count()); ?></span></span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm divide-y divide-gray-200" id="appointmentsTable">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold">
                        <tr>
                            <th class="px-6 py-3 text-left">Date & Time</th>
                            <th class="px-6 py-3 text-left">Status</th>
                            <th class="px-6 py-3 text-left">Approved Date</th>
                            <th class="px-6 py-3 text-left">Nurse Note</th>
                            <th class="px-6 py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100" id="appointmentBody">
                        <?php $__empty_1 = true; $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-blue-50 transition">
                                <td class="px-6 py-4 text-gray-800 font-medium">
                                    <?php echo e(\Carbon\Carbon::parse($appointment->requested_datetime)->format('M d, Y h:i A')); ?>

                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full
                                        <?php if($appointment->status === 'approved'): ?> bg-green-100 text-green-800
                                        <?php elseif($appointment->status === 'pending'): ?> bg-yellow-100 text-yellow-800
                                        <?php elseif($appointment->status === 'declined'): ?> bg-red-100 text-red-800
                                        <?php elseif($appointment->status === 'completed'): ?> bg-blue-100 text-blue-800
                                        <?php else: ?> bg-gray-100 text-gray-700 <?php endif; ?>">
                                        <?php echo e(ucfirst($appointment->status)); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    <?php echo e($appointment->approved_datetime 
                                        ? \Carbon\Carbon::parse($appointment->approved_datetime)->format('M d, Y h:i A') 
                                        : '—'); ?>

                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    <?php echo e($appointment->admin_note ?? '—'); ?>

                                </td>
                                <td class="px-6 py-4 text-center">
                                    <?php if($appointment->status === 'pending'): ?>
                                        <form action="<?php echo e(route('student.appointments.destroy', $appointment->id)); ?>"
                                              method="POST"
                                              onsubmit="return confirm('Are you sure you want to cancel this appointment?')"
                                              class="inline-block">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit"
                                                    class="px-3 py-1 bg-red-500 text-white text-xs font-semibold rounded-md hover:bg-red-600 transition">
                                                Cancel
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-gray-400 text-xs italic">No action</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="5" class="text-center py-8 text-gray-500">No appointments yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<!-- 📅 Modal -->
<div class="modal fade" id="appointmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-2xl shadow-lg border-0">
            <div class="modal-header bg-gradient-to-r from-blue-600 to-teal-500 text-white rounded-t-2xl">
                <h5 class="modal-title font-semibold">📨 Request Appointment</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="appointmentForm" action="<?php echo e(route('student.appointments.store')); ?>" method="POST" class="p-6">
                <?php echo csrf_field(); ?>
                <div class="mb-4">
    <label for="requested_datetime" class="block text-sm font-medium text-gray-700 mb-1">
        Choose Date & Time
    </label>
    <input type="datetime-local" 
       name="requested_datetime" 
       id="requested_datetime"
       min="<?php echo e(now()->format('Y-m-d\TH:i')); ?>" 
       step="1800" 
       class="w-full border-gray-300 rounded-lg shadow-sm text-sm px-3 py-2 focus:ring-blue-400 focus:border-blue-400"
       required>
<small class="text-gray-500 block mt-1">
    ⏰ Available time slots: <strong>8:00 AM – 12:00 PM</strong> and <strong>1:30 PM – 4:30 PM</strong>
</small>

</div>


                <div class="flex justify-end space-x-2 mt-6">
                    <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="submitAppointment" class="px-6 py-2 bg-gradient-to-r from-blue-600 to-teal-500 text-white font-semibold rounded-lg shadow hover:from-blue-700 hover:to-teal-600 transition-all">
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("appointmentForm");
    const modalEl = document.getElementById("appointmentModal");
    const tbody = document.getElementById("appointmentBody");
    const countEl = document.getElementById("appointmentCount");
    const submitBtn = document.getElementById("submitAppointment");

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        submitBtn.disabled = true;
        submitBtn.innerHTML = "⏳ Submitting...";

        const formData = new FormData(form);

        try {
            const response = await fetch(form.action, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name=\"csrf-token\"]').content,
                    "Accept": "application/json"
                },
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                toastr.success("Appointment requested successfully!", "Success");

                // ✅ Close modal
                const modalInstance = bootstrap.Modal.getInstance(modalEl);
                if (modalInstance) modalInstance.hide();

                form.reset();

                // ✅ Add new appointment instantly
                const date = new Date(formData.get("requested_datetime"));
                const formatted = date.toLocaleString('en-US', {
                    month: 'short', day: '2-digit', year: 'numeric',
                    hour: '2-digit', minute: '2-digit'
                });

                const newRow = `
                    <tr class='animate-fadeIn'>
                        <td class='px-6 py-4 text-gray-800 font-medium'>${formatted}</td>
                        <td class='px-6 py-4'>
                            <span class='inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800'>
                                Pending
                            </span>
                        </td>
                        <td class='px-6 py-4 text-gray-600'>—</td>
                        <td class='px-6 py-4 text-gray-600'>—</td>
                        <td class='px-6 py-4 text-center text-xs text-gray-400 italic'>Awaiting response</td>
                    </tr>
                `;

                tbody.insertAdjacentHTML("afterbegin", newRow);
                countEl.textContent = parseInt(countEl.textContent) + 1;
            } else {
                toastr.error(result.message || "Failed to create appointment.", "Error");
            }
        } catch (err) {
            console.error("Error:", err);
            toastr.error("An unexpected error occurred.", "Error");
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = "Submit";
        }
    });

    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: "toast-top-right",
        timeOut: "3000"
    };
    // ✅ Restrict time selection to clinic hours: 8:00–12:00 and 13:30–16:30
const datetimeInput = document.getElementById("requested_datetime");

datetimeInput.addEventListener("change", () => {
    const selected = new Date(datetimeInput.value);
    const hours = selected.getHours();
    const minutes = selected.getMinutes();

    // Convert to total minutes since midnight
    const totalMinutes = hours * 60 + minutes;

    const morningStart = 8 * 60;       // 8:00 AM
    const morningEnd = 12 * 60;        // 12:00 PM
    const afternoonStart = 13.5 * 60;  // 1:30 PM
    const afternoonEnd = 16.5 * 60;    // 4:30 PM

    const withinMorning = totalMinutes >= morningStart && totalMinutes <= morningEnd;
    const withinAfternoon = totalMinutes >= afternoonStart && totalMinutes <= afternoonEnd;

    if (!withinMorning && !withinAfternoon) {
        toastr.warning("⏰ Appointments are only allowed between 8:00 AM–12:00 PM or 1:30 PM–4:30 PM.");
        datetimeInput.value = ""; // clear invalid selection
    }
});

});
</script>

<style>
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-5px); }
  to { opacity: 1; transform: translateY(0); }
}
.animate-fadeIn { animation: fadeIn 0.5s ease-in-out; }
</style>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\medcare-system\resources\views/students/appointments/index.blade.php ENDPATH**/ ?>