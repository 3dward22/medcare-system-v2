<!-- Users Management Section -->
<div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-8 py-6 border-b border-gray-200">
        <div class="flex items-center">
            <div class="bg-gradient-to-r from-teal-500 to-blue-500 p-3 rounded-lg mr-4">
                <!-- Users Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-6 w-6 text-white">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-gray-800">Healthcare Staff Management</h3>
                <p class="text-gray-600 text-sm mt-1">Manage system users and access permissions</p>
            </div>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="px-8 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Staff Member</th>
                    <th class="px-8 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                    <th class="px-8 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Role</th>
                    <th class="px-8 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
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
                                    <div class="text-xs text-gray-500">Medical Staff</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="text-sm text-gray-900"><?php echo e($user->email); ?></div>
                            <div class="text-xs text-gray-500">System Access</div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full 
                                <?php if($user->role == 'admin'): ?> bg-red-100 text-red-800 
                                <?php elseif($user->role == 'doctor'): ?> bg-blue-100 text-blue-800 
                                <?php elseif($user->role == 'nurse'): ?> bg-green-100 text-green-800 
                                <?php else: ?> bg-gray-100 text-gray-800 <?php endif; ?>">
                                <?php if($user->role == 'admin'): ?> 🔑 Administrator
                                <?php elseif($user->role == 'doctor'): ?> 👨‍⚕️ Doctor
                                <?php elseif($user->role == 'nurse'): ?> 👩‍⚕️ Nurse
                                <?php else: ?> 👤 <?php echo e(ucfirst($user->role)); ?> <?php endif; ?>
                            </span>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex space-x-3">
                                <a href="#" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-400 to-yellow-500 text-white text-xs font-medium rounded-lg hover:from-yellow-500 hover:to-yellow-600 transform hover:-translate-y-0.5 transition-all duration-200 shadow-sm hover:shadow-md">
                                    <!-- Pencil Icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit
                                </a>
                                <form action="<?php echo e(route('admin.users.destroy', $user->id)); ?>" method="POST" style="display:inline;">
                                    <?php echo csrf_field(); ?> 
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white text-xs font-medium rounded-lg hover:from-red-600 hover:to-red-700 transform hover:-translate-y-0.5 transition-all duration-200 shadow-sm hover:shadow-md">
                                        <!-- Trash Icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Remove
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\med\resources\views\partials\staff-table.blade.php ENDPATH**/ ?>