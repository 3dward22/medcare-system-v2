

<?php $__env->startSection('content'); ?>
<main class="pt-16 py-4 bg-dashboard-gradient min-h-screen">
    <div class="w-full max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 py-8">

        <!-- Header Section -->
        <div class="mb-12 text-center">
            <div class="flex items-center justify-center mb-6">
                <div class="bg-gradient-to-r from-teal-600 to-blue-600 p-4 rounded-full shadow-lg mr-4">
                    <span class="text-white text-2xl">
                        🏥</span>
                </div>
                <h1 class="text-4xl font-bold text-gray-800">MedCare Dashboard</h1>
            </div>
            <p class="text-lg text-gray-600 mb-4">Healthcare Management System</p>
            <div class="h-1 w-32 bg-gradient-to-r from-teal-500 to-blue-500 rounded-full mx-auto"></div>
        </div>

        <!-- Quick Stats Cards -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between">

            <!-- Total Patients Card -->
            <div class="group relative overflow-hidden bg-white rounded-2xl p-8 border-l-4 border-teal-500 card-shadow">
                <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-teal-100 to-teal-200 rounded-full -mr-10 -mt-10 opacity-60"></div>
                <div class="relative flex items-center justify-between">
                    <div>
                        <div class="flex items-center mb-3">
                            <div class="bg-teal-100 p-2 rounded-lg mr-3">
                                <span class="text-teal-600 text-xl">👤</span>
                            </div>
                            <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-500">Registered Patients</h2>
                        </div>
                        <p class="text-4xl font-bold mb-3 text-gray-800"><?php echo e($patientsCount); ?></p>
                        <div class="flex items-center text-sm text-gray-600">
                            <div class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></div>
                            Active in system
                        </div>
                    </div>
                    <div class="bg-gradient-to-br from-teal-500 to-teal-600 p-4 rounded-xl shadow-lg group-hover:scale-105 transition-transform duration-300">
                        <span class="text-white text-3xl">👥</span>
                    </div>
                </div>
            </div>

            <!-- Total Appointments Card -->
            <div class="group relative overflow-hidden bg-white rounded-2xl p-8 border-l-4 border-blue-500 card-shadow">
                <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-blue-100 to-blue-200 rounded-full -mr-10 -mt-10 opacity-60"></div>
                <div class="relative flex items-center justify-between">
                    <div>
                        <div class="flex items-center mb-3">
                            <div class="bg-blue-100 p-2 rounded-lg mr-3">
                                <span class="text-blue-600 text-xl">📅</span>
                            </div>
                            <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-500">Total Appointments</h2>
                        </div>
                        <p class="text-4xl font-bold mb-3 text-gray-800"><?php echo e($appointmentsCount); ?></p>
                        <div class="flex items-center text-sm text-gray-600">
                            <div class="w-2 h-2 bg-blue-400 rounded-full mr-2 animate-pulse"></div>
                            Scheduled consultations
                        </div>
                    </div>
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-4 rounded-xl shadow-lg group-hover:scale-105 transition-transform duration-300">
                        <span class="text-white text-3xl">📋</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
    <a href="<?php echo e(route('admin.appointments.all')); ?>" 
       class="group bg-white p-6 rounded-xl shadow-md card-shadow border border-gray-200 hover:border-teal-300 transition-all duration-300 hover:-translate-y-1 flex flex-col">
        <div class="flex items-center mb-4">
            <div class="bg-teal-100 p-3 rounded-lg group-hover:bg-teal-200 transition-colors">
                <span class="text-teal-600 text-2xl">📋</span>
            </div>
            <h3 class="text-lg font-semibold text-gray-800 ml-3">View Appointments</h3>
        </div>
        <p class="text-gray-600 text-sm mt-auto">Manage and review all patient appointments</p>
    </a>

    <a href="#" class="group bg-white p-6 rounded-xl shadow-md card-shadow border border-gray-200 hover:border-blue-300 transition-all duration-300 hover:-translate-y-1 flex flex-col">
        <div class="flex items-center mb-4">
            <div class="bg-blue-100 p-3 rounded-lg group-hover:bg-blue-200 transition-colors">
                <span class="text-blue-600 text-2xl">📄</span>
            </div>
            <h3 class="text-lg font-semibold text-gray-800 ml-3">Medical Records</h3>
        </div>
        <p class="text-gray-600 text-sm mt-auto">Access patient medical history and records</p>
    </a>

    <a href="#" class="group bg-white p-6 rounded-xl shadow-md card-shadow border border-gray-200 hover:border-green-300 transition-all duration-300 hover:-translate-y-1 flex flex-col">
        <div class="flex items-center mb-4">
            <div class="bg-green-100 p-3 rounded-lg group-hover:bg-green-200 transition-colors">
                <span class="text-green-600 text-2xl">📊</span>
            </div>
            <h3 class="text-lg font-semibold text-gray-800 ml-3">Reports</h3>
        </div>
        <p class="text-gray-600 text-sm mt-auto">Generate medical reports and analytics</p>
    </a>
</div>

<!--
        //  additional Medical Quick Stats 
        <div class="mt-12 grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-xl shadow-md card-shadow border-l-4 border-green-400">
                <div class="flex items-center">
                    <span class="text-2xl mr-3">💊</span>
                    <div>
                        <div class="text-2xl font-bold text-gray-800">124</div>
                        <div class="text-xs text-gray-600">Prescriptions</div>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-md card-shadow border-l-4 border-purple-400">
                <div class="flex items-center">
                    <span class="text-2xl mr-3">🔬</span>
                    <div>
                        <div class="text-2xl font-bold text-gray-800">89</div>
                        <div class="text-xs text-gray-600">Lab Tests</div>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-md card-shadow border-l-4 border-orange-400">
                <div class="flex items-center">
                    <span class="text-2xl mr-3">🩺</span>
                    <div>
                        <div class="text-2xl font-bold text-gray-800">45</div>
                        <div class="text-xs text-gray-600">Check-ups</div>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-md card-shadow border-l-4 border-red-400">
                <div class="flex items-center">
                    <span class="text-2xl mr-3">🚨</span>
                    <div>
                        <div class="text-2xl font-bold text-gray-800">12</div>
                        <div class="text-xs text-gray-600">Emergency</div>
                    </div>
                </div>
            </div>
        </div>
-->
        <!-- Users Management Section -->
<div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-8 py-6 border-b border-gray-200">
        <div class="flex items-center">
            <div class="bg-gradient-to-r from-teal-500 to-blue-500 p-3 rounded-lg mr-4">
                <span class="text-white text-2xl">👤</span>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-gray-800">Users Management</h3>
                <p class="text-gray-600 text-sm mt-1">Manage system users and access permissions</p>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-full table-auto">
            
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="px-8 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">User</th>
                    <th class="px-8 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                    <th class="px-8 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Role</th>
                    
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php $__currentLoopData = App\Models\User::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-8 py-6">
                            <div class="flex items-center">
                                <div class="h-10 w-10 bg-gradient-to-br from-teal-400 to-blue-500 rounded-full flex items-center justify-center shadow-md">
                                    <span class="text-sm font-bold text-white"><?php echo e(strtoupper(substr($user->name, 0, 1))); ?></span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-semibold text-gray-900"><?php echo e($user->name); ?></div>
                                    <div class="text-xs text-gray-500"> <?php if($user->role == 'admin'): ?> 
                                    <span class="mr-1">🔑</span> Administrator
                                <?php elseif($user->role == 'doctor'): ?> 
                                    <span class="mr-1">👨‍⚕️</span> Doctor
                                <?php elseif($user->role == 'nurse'): ?> 
                                    <span class="mr-1">👩‍⚕️</span> Nurse
                                <?php else: ?> 
                                    <span class="mr-1">👤</span> <?php echo e(ucfirst($user->role)); ?> 
                                <?php endif; ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="text-sm text-gray-900"><?php echo e($user->email); ?></div>
                            <div class="text-xs text-gray-500">System Access</div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full 
                                <?php if($user->role == 'admin'): ?> bg-red-100 text-red-800 
                                <?php elseif($user->role == 'doctor'): ?> bg-blue-100 text-blue-800 
                                <?php elseif($user->role == 'nurse'): ?> bg-green-100 text-green-800 
                                <?php else: ?> bg-gray-100 text-gray-800 <?php endif; ?>">
                                <?php if($user->role == 'admin'): ?> 
                                    <span class="mr-1">🔑</span> Administrator
                                <?php elseif($user->role == 'doctor'): ?> 
                                    <span class="mr-1">👨‍⚕️</span> Doctor
                                <?php elseif($user->role == 'nurse'): ?> 
                                    <span class="mr-1">👩‍⚕️</span> Nurse
                                <?php else: ?> 
                                    <span class="mr-1">👤</span> <?php echo e(ucfirst($user->role)); ?> 
                                <?php endif; ?>
                            </span>
                        </td>
                       
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>



        <!-- Footer -->
        <div class="mt-12 text-center">
            <div class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-teal-100 to-blue-100 rounded-full">
                <span class="text-teal-600 mr-2">❤️</span>
                <span class="text-sm font-medium text-gray-700">Caring for Health, Managing with Excellence</span>
                <span class="text-blue-600 ml-2">🏥</span>
            </div>
        </div>
    </div>
</main>

<style>
.bg-dashboard-gradient {
    background: linear-gradient(to bottom right, #d1fae5, #ffffff, #cffafe);
    min-height: 100vh;
}
.card-shadow {
    box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.1);
    transition: box-shadow 0.3s, transform 0.3s;
}
.card-shadow:hover {
    box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
    transform: translateY(-5px);
}
.pulse-dot {
    animation: pulse 2s infinite;
}
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\med\resources\views\dashboard.blade.php ENDPATH**/ ?>