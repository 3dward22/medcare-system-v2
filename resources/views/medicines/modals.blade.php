<!-- Medicine Modal -->
<div class="modal fade" id="medicineModal" tabindex="-1" aria-labelledby="medicineModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="medicineModalLabel">Add Medicine</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form id="medicineForm">
          <!-- Medicine Name -->
          <div class="mb-3">
            <label for="name" class="form-label">Medicine Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
          </div>

          <!-- Stock -->
          <div class="mb-3">
            <label for="stock" class="form-label">Stock</label>
            <input type="number" name="stock" id="stock" class="form-control" required>
          </div>

          <!-- Expiration Date -->
          <div class="mb-3">
            <label for="expiration_date" class="form-label">Expiration Date</label>
            <input type="date" name="expiration_date" id="expiration_date" class="form-control" required>
          </div>

          <!-- Unit -->
          <div class="mb-3">
            <label for="unit" class="form-label">Unit</label>
            <select name="unit" id="unit" class="form-control" required>
              <option value="" disabled selected>Select unit</option>
              <option value="tablet">Tablet</option>
              <option value="capsule">Capsule</option>
              <option value="ml">Milliliter (ml)</option>
              <option value="piece">Piece</option>
            </select>
          </div>

          <!-- Submit -->
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save Medicine</button>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>
