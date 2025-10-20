<?php $__env->startSection('content'); ?>
<main class="pt-20 bg-gradient-to-br from-sky-50 via-white to-teal-50 min-h-screen">
    <div class="max-w-6xl mx-auto px-6 py-8">
<!-- 🧭 Header -->
<!-- 🧭 Header -->
<div class="text-center mb-10">
    <h1 class="text-4xl font-bold text-gray-800 mb-2 flex items-center justify-center">
        <span class="mr-2">🎓</span> Student Dashboard
    </h1>
    <p class="text-gray-600 text-base">
        Welcome back, 
        <span class="font-semibold text-blue-700">
            <?php echo e(auth()->user()->name); ?>

        </span>!
    </p>
    <div class="h-1 w-32 bg-gradient-to-r from-blue-500 to-teal-400 mx-auto mt-4 rounded-full"></div>
</div>

<!-- 📊 Appointment Summary Cards -->
<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
    <div class="bg-white border border-gray-200 rounded-2xl shadow-md p-6 text-center hover:shadow-lg transition">
        <h3 class="text-gray-500 text-sm uppercase font-semibold">Pending</h3>
        <p class="text-3xl font-bold text-yellow-500 mt-2">
            <?php echo e($appointments->where('status', 'pending')->count()); ?>

        </p>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl shadow-md p-6 text-center hover:shadow-lg transition">
        <h3 class="text-gray-500 text-sm uppercase font-semibold">Approved</h3>
        <p class="text-3xl font-bold text-green-500 mt-2">
            <?php echo e($appointments->where('status', 'approved')->count()); ?>

        </p>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl shadow-md p-6 text-center hover:shadow-lg transition">
        <h3 class="text-gray-500 text-sm uppercase font-semibold">Completed</h3>
        <p class="text-3xl font-bold text-blue-500 mt-2">
            <?php echo e($appointments->where('status', 'completed')->count()); ?>

        </p>
    </div>
</div>

<!-- 📋 Appointment List -->
<div class="bg-white shadow-md rounded-2xl border border-gray-200 p-6">
    <!-- Header with button -->
    <div class="flex flex-col sm:flex-row items-center justify-between border-b pb-3 mb-4 text-center sm:text-left">
        <h2 class="text-lg font-semibold text-gray-700 flex items-center mb-3 sm:mb-0">
            <span class="mr-2">📅</span> Recent & Upcoming Appointments
        </h2>

        <a href="<?php echo e(route('student.appointments.index')); ?>"
           class="inline-flex items-center px-5 py-2 bg-gradient-to-r from-blue-600 to-teal-500 text-white text-sm font-semibold rounded-lg shadow hover:shadow-lg hover:from-blue-700 hover:to-teal-600 transition-all duration-200">
            <span class="mr-2">➕</span> Book Appointment
        </a>
    </div>

    <?php if($appointments->count() > 0): ?>
        <div class="space-y-4">
            <?php $__currentLoopData = $appointments->sortByDesc('requested_datetime'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="border border-gray-100 rounded-xl p-4 hover:bg-blue-50 transition">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-md font-semibold text-gray-800">
                                <?php echo e(\Carbon\Carbon::parse($appointment->requested_datetime)->format('F d, Y - h:i A')); ?>

                            </h3>
                            <p class="text-sm mt-1 text-gray-500">
                                Status:
                                <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full
                                    <?php if($appointment->status === 'pending'): ?> bg-yellow-100 text-yellow-800
                                    <?php elseif($appointment->status === 'approved'): ?> bg-green-100 text-green-800
                                    <?php elseif($appointment->status === 'completed'): ?> bg-blue-100 text-blue-800
                                    <?php elseif($appointment->status === 'declined'): ?> bg-red-100 text-red-800
                                    <?php else: ?> bg-gray-100 text-gray-700 <?php endif; ?>">
                                    <?php echo e(ucfirst($appointment->status)); ?>

                                </span>
                            </p>

                            <?php if($appointment->findings): ?>
                                <p class="text-sm mt-2 text-gray-600 italic">
                                    “<?php echo e(Str::limit($appointment->findings, 60, '...')); ?>”
                                </p>
                            <?php else: ?>
                                <p class="text-sm mt-2 text-gray-400 italic">No findings yet.</p>
                            <?php endif; ?>
                        </div>

                        <button class="px-4 py-2 bg-gradient-to-r from-blue-600 to-teal-500 text-white font-semibold rounded-lg shadow hover:from-blue-700 hover:to-teal-600 transition"
                            data-bs-toggle="modal"
                            data-bs-target="#appointmentModal<?php echo e($appointment->id); ?>">
                            View
                        </button>
                    </div>
                </div>

                <!-- 🩺 Modal -->
                <div class="modal fade" id="appointmentModal<?php echo e($appointment->id); ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-sm">
                        <div class="modal-content rounded-2xl border-0 shadow-lg">
                            <div class="modal-header bg-gradient-to-r from-blue-600 to-teal-500 text-white rounded-t-2xl">
                                <h5 class="modal-title font-semibold">🩺 Appointment Details</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body text-gray-700 text-sm space-y-2">
                                <p><strong>Date:</strong> <?php echo e(\Carbon\Carbon::parse($appointment->requested_datetime)->format('F d, Y - h:i A')); ?></p>
                                <p><strong>Status:</strong> <?php echo e(ucfirst($appointment->status)); ?></p>
                                <?php if($appointment->approved_by): ?>
                                    <p><strong>Attending Nurse:</strong> <?php echo e(\App\Models\User::find($appointment->approved_by)->name ?? 'N/A'); ?></p>
                                <?php endif; ?>
                                <?php if($appointment->admin_note): ?>
                                    <p><strong>Diagnosis / Complaint:</strong> <?php echo e($appointment->admin_note); ?></p>
                                <?php endif; ?>
                                <?php if($appointment->findings): ?>
                                    <p><strong>Findings & Treatment:</strong> <?php echo e($appointment->findings); ?></p>
                                <?php endif; ?>
                                <?php if($appointment->updated_at): ?>
                                    <p><strong>Last Updated:</strong> <?php echo e($appointment->updated_at->format('F d, Y h:i A')); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="modal-footer bg-gray-50 rounded-b-2xl">
                                <button type="button"
                                    class="px-4 py-2 bg-gradient-to-r from-blue-600 to-teal-500 text-white rounded-lg shadow hover:from-blue-700 hover:to-teal-600 transition"
                                    data-bs-dismiss="modal">
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php else: ?>
        <div class="text-center py-10 text-gray-500 text-sm">No upcoming appointments yet.</div>
    <?php endif; ?>
</div>


        
    </div>
</main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\medcare-system\resources\views/students/dashboard.blade.php ENDPATH**/ ?>