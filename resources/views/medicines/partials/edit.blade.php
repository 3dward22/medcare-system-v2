<form id="editMedicineForm" data-id="{{ $medicine->id }}">
    @csrf
    @method('PUT')

    <div class="form-group mb-2">
        <label>Name</label>
        <input type="text" name="name" class="form-control" value="{{ $medicine->name }}" required>
    </div>

    <div class="form-group mb-2">
        <label>Stock</label>
        <input type="number" name="stock" class="form-control" value="{{ $medicine->stock }}" required>
    </div>

    <div class="form-group mb-2">
        <label>Expiry Date</label>
        <input type="date" name="expiry_date" class="form-control" value="{{ $medicine->expiry_date }}" required>
    </div>

    <button type="submit" class="btn btn-primary">Update</button>
</form>
