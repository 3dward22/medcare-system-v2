

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-2">👩‍⚕️ Nurse Dashboard</h1>
    <p class="text-muted">
        Welcome, <?php echo e(auth()->user()->name); ?>! You are logged in as 
        <strong><?php echo e(auth()->user()->role); ?></strong>.
    </p>

    <!-- Today's Appointments Summary -->
    <div class="my-3">
        <h5>📅 You have <strong id="todayAppointmentsCount"><?php echo e($todayAppointments->count()); ?></strong> appointment(s) today</h5>
    </div>

    <!-- Today's Appointments Table -->
    <div class="card shadow-sm rounded-4 mt-3">
        <div class="card-body">
            <h4 class="card-title mb-3">📋 Today's Appointments</h4>

            <div id="todayAppointmentsTableWrapper">
                <?php if($todayAppointments->count() > 0): ?>
                    <table class="table table-hover align-middle" id="todayAppointmentsTable">
                        <thead class="table-light">
                            <tr>
                                <th>Patient Name</th>
                                <th>Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $todayAppointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($appointment->user->name ?? 'Unknown'); ?></td>
                                    <td><?php echo e(\Carbon\Carbon::parse($appointment->requested_datetime)->format('h:i A')); ?></td>
                                    <td>
                                        <span class="badge 
                                            <?php if($appointment->status === 'pending'): ?> bg-warning
                                            <?php elseif($appointment->status === 'approved'): ?> bg-success
                                            <?php else: ?> bg-secondary
                                            <?php endif; ?>">
                                            <?php echo e(ucfirst($appointment->status)); ?>

                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-muted text-center my-3">No appointments scheduled for today.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- All Upcoming Appointments -->
    <div class="card shadow-sm rounded-4 mt-4">
        <div class="card-body">
            <h4 class="card-title mb-3">📋 All Upcoming Appointments</h4>

            <div>
                <?php if($upcomingAppointments->count() > 0): ?>
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Patient Name</th>
                                <th>Date & Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $upcomingAppointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($appointment->user->name ?? 'Unknown'); ?></td>
                                    <td><?php echo e(\Carbon\Carbon::parse($appointment->requested_datetime)->format('M d, Y h:i A')); ?></td>
                                    <td>
                                        <span class="badge 
                                            <?php if($appointment->status === 'pending'): ?> bg-warning
                                            <?php elseif($appointment->status === 'approved'): ?> bg-success
                                            <?php elseif($appointment->status === 'rescheduled'): ?> bg-info
                                            <?php elseif($appointment->status === 'declined'): ?> bg-danger
                                            <?php else: ?> bg-secondary
                                            <?php endif; ?>">
                                            <?php echo e(ucfirst($appointment->status)); ?>

                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-muted text-center my-3">No upcoming appointments.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card shadow-sm rounded-4">
                <div class="card-body text-center">
                    <h5 class="card-title">📅 Manage All Appointments</h5>
                    <p class="card-text">View and manage all upcoming appointments.</p>
                    <a href="<?php echo e(route('nurse.appointments.index')); ?>" class="btn btn-success">Open</a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm rounded-4">
                <div class="card-body text-center">
                    <h5 class="card-title">📖 Student Records</h5>
                    <p class="card-text">View student medical history and profiles.</p>
                    <a href="<?php echo e(route('nurse.students.index')); ?>" class="btn btn-info">Open</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Real-time JS for Today's Appointments -->
<script>
function fetchTodayAppointments() {
    fetch('<?php echo e(route("nurse.appointments.today-json")); ?>')
        .then(response => response.json())
        .then(data => {
            document.getElementById('todayAppointmentsCount').innerText = data.count;
            let tableWrapper = document.getElementById('todayAppointmentsTableWrapper');
            if (data.count > 0) {
                let rows = data.appointments.map(app => {
                    let statusClass = 'bg-secondary';
                    if (app.status === 'pending') statusClass = 'bg-warning';
                    else if (app.status === 'approved') statusClass = 'bg-success';
                    let time = new Date(app.requested_datetime);
                    let formattedTime = time.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                    return `<tr>
                        <td>${app.user?.name ?? 'Unknown'}</td>
                        <td>${formattedTime}</td>
                        <td><span class="badge ${statusClass}">${app.status.charAt(0).toUpperCase() + app.status.slice(1)}</span></td>
                    </tr>`;
                }).join('');

                tableWrapper.innerHTML = `
                    <table class="table table-hover align-middle" id="todayAppointmentsTable">
                        <thead class="table-light">
                            <tr>
                                <th>Patient Name</th>
                                <th>Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${rows}
                        </tbody>
                    </table>
                `;
            } else {
                tableWrapper.innerHTML = '<p class="text-muted text-center my-3">No appointments scheduled for today.</p>';
            }
        })
        .catch(error => console.error('Error fetching today\'s appointments:', error));
}

// Poll every 10 seconds
setInterval(fetchTodayAppointments, 10000);
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\med\resources\views/nurse/dashboard.blade.php ENDPATH**/ ?>