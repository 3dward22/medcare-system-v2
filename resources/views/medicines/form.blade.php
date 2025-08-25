<div class="mb-3">
    <label>Name</label>
    <input type="text" name="name" class="form-control" value="{{ $medicine->name ?? '' }}" required>
</div>
<div class="mb-3">
    <label>Stock</label>
    <input type="number" name="stock" class="form-control" value="{{ $medicine->stock ?? '' }}" required>
</div>
<div class="mb-3">
    <label>Expiry Date</label>
    <input type="date" name="expiry_date" class="form-control" value="{{ $medicine->expiry_date ?? '' }}">
</div>
