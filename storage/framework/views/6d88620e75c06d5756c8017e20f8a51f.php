

<?php $__env->startSection('content'); ?>
<main class="pt-20 bg-gray-50 min-h-screen px-6">
    <div class="max-w-6xl mx-auto bg-white shadow-lg rounded-xl p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                <span class="mr-2">📱</span> Guardian SMS Logs
            </h1>
            <a href="<?php echo e(route('dashboard')); ?>" 
               class="text-sm text-blue-600 hover:underline">← Back to Dashboard</a>
        </div>

        <?php if(session('success')): ?>
            <div class="mb-4 bg-green-100 text-green-700 px-4 py-3 rounded">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <?php if($logs->isEmpty()): ?>
            <p class="text-gray-500 text-center py-6">No SMS logs found yet.</p>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-gray-700 border">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="px-4 py-3 text-left">Guardian Name</th>
                            <th class="px-4 py-3 text-left">Guardian Phone</th>
                            <th class="px-4 py-3 text-left">Message</th>
                            <th class="px-4 py-3 text-left">Student</th>
                            <th class="px-4 py-3 text-left">Sent At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium"><?php echo e($log->guardian_name); ?></td>
                                <td class="px-4 py-3"><?php echo e($log->guardian_phone); ?></td>
                                <td class="px-4 py-3"><?php echo e($log->message); ?></td>
                                <td class="px-4 py-3"><?php echo e($log->student->name ?? 'N/A'); ?></td>
                                <td class="px-4 py-3"><?php echo e(\Carbon\Carbon::parse($log->sent_at)->format('M d, Y h:i A')); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                <?php echo e($logs->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\medcare-system\resources\views/guardian_sms/index.blade.php ENDPATH**/ ?>