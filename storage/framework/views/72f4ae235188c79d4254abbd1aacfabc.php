<?php $__env->startSection('content'); ?>
<main class="pt-20 bg-gradient-to-br from-sky-50 via-white to-teal-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-6 py-8">

        <!-- 🏥 Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-10">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Admin Dashboard</h1>
                <p class="text-gray-600 mt-1">Monitor appointments, users, and analytics</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <a href="<?php echo e(route('admin.appointments.all')); ?>" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition">
                    <span class="mr-2">📅</span> Manage Appointments
                </a>
            </div>
        </div>

        <!-- 📊 Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-sm text-gray-500 font-semibold uppercase">Total Appointments</h2>
                        <p class="text-3xl font-bold text-blue-600 mt-2"><?php echo e($appointmentsCount ?? 0); ?></p>
                        <p class="text-xs text-gray-500 mt-1">All scheduled consultations</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <span class="text-blue-600 text-2xl">📋</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-sm text-gray-500 font-semibold uppercase">Registered Users</h2>
                        <p class="text-3xl font-bold text-green-600 mt-2"><?php echo e(\App\Models\User::count()); ?></p>
                        <p class="text-xs text-gray-500 mt-1">All active accounts</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <span class="text-green-600 text-2xl">👥</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-teal-500">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-sm text-gray-500 font-semibold uppercase">System Status</h2>
                        <p class="text-3xl font-bold text-teal-600 mt-2">✅</p>
                        <p class="text-xs text-gray-500 mt-1">Running smoothly</p>
                    </div>
                    <div class="bg-teal-100 p-3 rounded-full">
                        <span class="text-teal-600 text-2xl">⚙️</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 🩻 Health Reports / Analytics -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 mb-10">
            <div class="bg-gradient-to-r from-blue-50 to-teal-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                    <span class="mr-2">📈</span> Health Reports & Analytics
                </h2>
            </div>
            <div class="p-6">
                <canvas id="healthChart" 
                    data-approved="<?php echo e(\App\Models\Appointment::where('status', 'approved')->count()); ?>"
                    data-pending="<?php echo e(\App\Models\Appointment::where('status', 'pending')->count()); ?>"
                    data-declined="<?php echo e(\App\Models\Appointment::where('status', 'declined')->count()); ?>">
                </canvas>
            </div>
        </div>
        <div class="text-right mt-4">
            <a href="<?php echo e(route('reports.monthly')); ?>" 
                class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition">
                🧾 Download Monthly Report (PDF)
            </a>
        </div>
        <!-- 🗓️ Today's Appointments -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 mb-10">
            <div class="bg-gradient-to-r from-blue-50 to-teal-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                    <span class="mr-2">🗓️</span> Today’s Appointments
                </h2>
            </div>

            <div class="p-6">
                <?php
                    $todayAppointments = \App\Models\Appointment::whereDate('requested_datetime', \Carbon\Carbon::today())
                        ->orderBy('requested_datetime', 'asc')
                        ->take(5)
                        ->get();
                ?>

                <?php if($todayAppointments->isEmpty()): ?>
                    <p class="text-center text-gray-500 py-4">No appointments scheduled for today.</p>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase">Student</th>
                                    <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase">Date & Time</th>
                                    <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php $__currentLoopData = $todayAppointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 font-medium text-gray-800"><?php echo e($appt->student_name ?? 'N/A'); ?></td>
                                        <td class="px-6 py-4 text-gray-600"><?php echo e(\Carbon\Carbon::parse($appt->requested_datetime)->format('M d, Y - h:i A')); ?></td>
                                        <td class="px-6 py-4">
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                                <?php if($appt->status === 'approved'): ?> bg-green-100 text-green-700
                                                <?php elseif($appt->status === 'pending'): ?> bg-yellow-100 text-yellow-700
                                                <?php elseif($appt->status === 'declined'): ?> bg-red-100 text-red-700
                                                <?php else: ?> bg-gray-100 text-gray-700 <?php endif; ?>">
                                                <?php echo e(ucfirst($appt->status)); ?>

                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- 👥 User Management -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
            <div class="bg-gradient-to-r from-blue-50 to-teal-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                    <span class="mr-2">👤</span> User Management
                </h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase">Name</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase">Email</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase">Role</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php $__currentLoopData = App\Models\User::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $roleColors = [
                                    'admin' => 'bg-red-100 text-red-800',
                                    'nurse' => 'bg-green-100 text-green-800',
                                    'student' => 'bg-blue-100 text-blue-800',
                                    'default' => 'bg-gray-100 text-gray-700',
                                ];
                                $color = $roleColors[$user->role] ?? $roleColors['default'];
                            ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-900"><?php echo e($user->name); ?></td>
                                <td class="px-6 py-4 text-gray-600"><?php echo e($user->email); ?></td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold <?php echo e($color); ?>">
                                        <?php if($user->role === 'admin'): ?> 🔑 Admin
                                        <?php elseif($user->role === 'nurse'): ?> 👩‍⚕️ Nurse
                                        <?php elseif($user->role === 'student'): ?> 🎓 Student
                                        <?php else: ?> 👤 <?php echo e(ucfirst($user->role)); ?>

                                        <?php endif; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
            <a href="<?php echo e(route('guardian.sms.index')); ?>" 
                class="group bg-white p-6 rounded-xl shadow-md border border-gray-200 hover:border-blue-300 hover:-translate-y-1 transition-all duration-300 flex flex-col">
                <div class="flex items-center mb-4">
                    <div class="bg-blue-100 p-3 rounded-lg group-hover:bg-blue-200 transition-colors">
                        <span class="text-blue-600 text-2xl">📲</span>
                    </div>
                        <h3 class="text-lg font-semibold text-gray-800 ml-3">Guardian SMS Logs</h3>
                </div>
                    <p class="text-gray-600 text-sm mt-auto">View all messages sent to student guardians</p>
            </a>                        
        <!-- 🩺 Footer -->
        <div class="text-center mt-10">
            <p class="text-sm text-gray-500">MedCare System © <?php echo e(date('Y')); ?> | Admin Portal</p>
        </div>
    </div>
</main>



<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('healthChart');
    if (!ctx) return;

    const data = {
        labels: ['Approved', 'Pending', 'Declined'],
        datasets: [{
            label: 'Appointment Status Overview',
            data: [APPROVED_COUNT, PENDING_COUNT, DECLINED_COUNT],
            backgroundColor: ['#22c55e', '#facc15', '#ef4444'],
            borderWidth: 1,
            borderColor: '#fff'
        }]
    };

    new Chart(ctx, {
        type: 'doughnut',
        data,
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' },
                title: { display: true, text: 'Appointment Statistics', font: { size: 16 } }
            }
        }
    });
});
</script>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\medcare-system\resources\views/dashboard.blade.php ENDPATH**/ ?>