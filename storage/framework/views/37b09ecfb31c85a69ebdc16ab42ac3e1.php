<tr>
    <td><?php echo e($appointment->id); ?></td>
    <td><?php echo e($appointment->student_name); ?></td>
    <td><?php echo e($appointment->appointment_date); ?></td>
    <td><?php echo e(ucfirst($appointment->status)); ?></td>
    <td>
        <a href="#" class="btn btn-sm btn-warning">Edit</a>
        <a href="#" class="btn btn-sm btn-danger">Delete</a>
    </td>
</tr>
<?php /**PATH C:\xampp\htdocs\med\resources\views\appointments\partials\row.blade.php ENDPATH**/ ?>