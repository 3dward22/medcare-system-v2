<tr>
    <td>{{ $appointment->id }}</td>
    <td>{{ $appointment->student_name }}</td>
    <td>{{ $appointment->appointment_date }}</td>
    <td>{{ ucfirst($appointment->status) }}</td>
    <td>
        <a href="#" class="btn btn-sm btn-warning">Edit</a>
        <a href="#" class="btn btn-sm btn-danger">Delete</a>
    </td>
</tr>
