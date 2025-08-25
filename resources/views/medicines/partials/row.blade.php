<tr id="medicine-row-{{ $medicine->id }}">
    <td>{{ $medicine->id }}</td>
    <td>{{ $medicine->name }}</td>
    <td>{{ $medicine->stock }}</td>
    <td>{{ $medicine->unit }}</td>
    <td>{{ $medicine->expiration_date }}</td>
    <td>
        <button class="btn btn-sm btn-info viewMedicine" data-id="{{ $medicine->id }}">View</button>
        <button class="btn btn-sm btn-warning editMedicine" data-id="{{ $medicine->id }}">Edit</button>
        <button class="btn btn-sm btn-danger deleteMedicine" data-id="{{ $medicine->id }}">Delete</button>
    </td>
</tr>
