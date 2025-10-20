

<?php $__env->startSection('content'); ?>
<main class="pt-20 bg-gradient-to-br from-sky-50 via-white to-teal-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row items-center justify-between mb-6 space-y-3 sm:space-y-0">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                <span class="mr-2">📱</span> Guardian SMS Log
            </h1>
            <a href="<?php echo e(route('dashboard')); ?>" class="text-blue-600 hover:underline text-sm">
                ← Back to Dashboard
            </a>
        </div>

        <!-- 🔍 Filter + Search -->
        <form method="GET" 
              class="bg-white rounded-xl shadow-md border border-gray-200 p-4 mb-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            
            <!-- Search -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Search</label>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                       placeholder="Guardian name, phone, or message"
                       class="w-full border-gray-300 rounded-lg text-sm px-3 py-2 focus:ring-blue-400 focus:border-blue-400">
            </div>

            <!-- Nurse Filter (Admin Only) -->
            <?php if(auth()->user()->role === 'admin'): ?>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Filter by Nurse</label>
                    <select name="nurse" class="w-full border-gray-300 rounded-lg text-sm px-3 py-2 focus:ring-blue-400 focus:border-blue-400">
                        <option value="">All Nurses</option>
                        <?php $__currentLoopData = $nurses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nurse): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($nurse); ?>" <?php echo e(request('nurse') == $nurse ? 'selected' : ''); ?>>
                                <?php echo e($nurse); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            <?php endif; ?>

            <!-- Date Range -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Date From</label>
                <input type="date" name="date_from" value="<?php echo e(request('date_from')); ?>"
                       class="w-full border-gray-300 rounded-lg text-sm px-3 py-2 focus:ring-blue-400 focus:border-blue-400">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Date To</label>
                <input type="date" name="date_to" value="<?php echo e(request('date_to')); ?>"
                       class="w-full border-gray-300 rounded-lg text-sm px-3 py-2 focus:ring-blue-400 focus:border-blue-400">
            </div>

            <!-- Sort -->
            <div class="sm:col-span-2 lg:col-span-1">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Sort By</label>
                <select name="sort" class="w-full border-gray-300 rounded-lg text-sm px-3 py-2 focus:ring-blue-400 focus:border-blue-400">
                    <option value="desc" <?php echo e(request('sort') == 'desc' ? 'selected' : ''); ?>>Newest First</option>
                    <option value="asc" <?php echo e(request('sort') == 'asc' ? 'selected' : ''); ?>>Oldest First</option>
                </select>
            </div>

            <!-- Apply -->
            <div class="flex items-end sm:col-span-2 lg:col-span-1">
                <button type="submit" 
                        class="w-full bg-blue-600 text-white text-sm font-semibold py-2 rounded-lg hover:bg-blue-700 transition">
                    Apply Filters
                </button>
            </div>
        </form>

        <!-- 🖥 Desktop Table -->
        <div class="hidden md:block bg-white shadow-md rounded-xl border border-gray-200 overflow-x-auto">
            <table class="min-w-full text-sm divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-blue-50 to-teal-50 text-gray-700 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold">Guardian</th>
                        <th class="px-4 py-3 text-left font-semibold">Phone</th>
                        <th class="px-4 py-3 text-left font-semibold">Message</th>
                        <th class="px-4 py-3 text-left font-semibold">Sent By</th>
                        <th class="px-4 py-3 text-left font-semibold">Role</th>
                        <th class="px-4 py-3 text-left font-semibold">Date Sent</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 font-medium text-gray-800"><?php echo e($log->guardian_name ?? 'N/A'); ?></td>
                            <td class="px-4 py-3 text-gray-600"><?php echo e($log->guardian_phone ?? 'N/A'); ?></td>
                            <td class="px-4 py-3 text-gray-700 max-w-xs truncate" title="<?php echo e($log->message); ?>">
                                <?php echo e(Str::limit($log->message ?? '-', 50)); ?>

                            </td>
                            <td class="px-4 py-3 text-gray-700"><?php echo e($log->sent_by ?? 'N/A'); ?></td>
                            <td class="px-4 py-3 capitalize text-gray-600"><?php echo e($log->sent_by_role ?? '-'); ?></td>
                            <td class="px-4 py-3 text-gray-500 whitespace-nowrap">
                                <?php echo e(\Carbon\Carbon::parse($log->created_at)->format('M d, Y - h:i A')); ?>

                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center text-gray-500 py-8">No SMS records found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- 📱 Mobile Card View -->
        <div class="grid grid-cols-1 md:hidden gap-4">
            <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="bg-white p-4 rounded-xl shadow border border-gray-100 hover:shadow-md transition">
                    <p class="text-sm text-gray-700"><strong>👨‍👩 Guardian:</strong> <?php echo e($log->guardian_name ?? 'N/A'); ?></p>
                    <p class="text-sm text-gray-600"><strong>📞 Phone:</strong> <?php echo e($log->guardian_phone ?? 'N/A'); ?></p>
                    <p class="text-sm text-gray-700 mt-1"><strong>💬 Message:</strong> <?php echo e($log->message ?? '-'); ?></p>
                    <p class="text-sm text-gray-600 mt-1"><strong>👩‍⚕️ Sent By:</strong> <?php echo e($log->sent_by ?? 'Nurse'); ?> (<?php echo e($log->sent_by_role ?? 'nurse'); ?>)</p>
                    <p class="text-xs text-gray-500 mt-2"><strong>🕒 Sent:</strong> <?php echo e(\Carbon\Carbon::parse($log->created_at)->format('M d, Y - h:i A')); ?></p>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-center text-gray-500 py-7">No SMS records found.</p>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex justify-center">
            <?php echo e($logs->appends(request()->query())->links()); ?>

        </div>
    </div>
</main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\medcare-system\resources\views/guardian_sms/index.blade.php ENDPATH**/ ?>