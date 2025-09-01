@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-3">Medicines</h1>

    <!-- Add Medicine Button -->
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addMedicineModal">
        Add Medicine
    </button>

    <!-- Medicines Table -->
    <div id="medicinesTableWrapper">
        @include('medicines.partials.table', ['medicines' => $medicines])
    </div>
</div>

<!-- Add Medicine Modal -->
<div class="modal fade" id="addMedicineModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="addMedicineForm" method="POST" action="{{ route('medicines.store') }}">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Add Medicine</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Stock</label>
            <input type="number" name="stock" class="form-control" required>
          </div>
          <!-- ✅ Fixed: add unit field -->
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
          <!-- ✅ Fixed: renamed expiry_date → expiration_date -->
          <div class="mb-3">
            <label>Expiration Date</label>
            <input type="date" name="expiration_date" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Save</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Medicine Modal -->
<div class="modal fade" id="editMedicineModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content" id="editMedicineContent"></div>
  </div>
</div>

<!-- ✅ View Medicine Modal -->
<div class="modal fade" id="viewMedicineModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content" id="viewMedicineContent"></div>
  </div>
</div>
@endsection


<script>
$(document).ready(function () {

    // ✅ Add Medicine - Insert New Row
    $('#addMedicineForm').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            url: "/medicines", // ✅ use route directly
            type: "POST",
            data: $(this).serialize(),
            success: function (response) {
                if (response.success) {
                    $('#medicinesTable tbody').prepend(response.row);
                    toastr.success('Medicine added successfully.');
                    $('#addMedicineModal').modal('hide');
                    $('#addMedicineForm')[0].reset();
                }
            },
            error: function () {
                toastr.error('Failed to add medicine.');
            }
        });
    });

    // ✅ Open Edit Modal
    $(document).on('click', '.edit-medicine', function (e) {
        e.preventDefault();
        let url = $(this).attr('href');

        $.get(url, function (data) {
            $('#editMedicineContent').html(data);
            $('#editMedicineModal').modal('show');
        }).fail(() => toastr.error('Failed to load edit form.'));
    });

    // ✅ Submit Edit Medicine Form (AJAX)
    $(document).on('submit', '#editMedicineForm', function (e) {
        e.preventDefault();
        let form = $(this);

        $.ajax({
            url: form.attr('action'),
            type: 'PUT',
            data: form.serialize(),
            success: function (response) {
                if (response.success) {
                    toastr.success(response.message);
                    $('#editMedicineModal').modal('hide');
                    $('#medicinesTable tr[data-id="' + response.id + '"]').replaceWith(response.row);
                }
            },
            error: function () {
                toastr.error('Failed to update medicine.');
            }
        });
    });

    // ✅ View Medicine Modal
    $(document).on('click', '.view-medicine', function (e) {
        e.preventDefault();
        let url = $(this).attr('href');

        $.get(url, function (data) {
            $('#viewMedicineContent').html(data);
            $('#viewMedicineModal').modal('show');
        }).fail(() => toastr.error('Failed to load medicine details.'));
    });

    // ✅ Delete Medicine
    $(document).on('click', '.delete-medicine', function (e) {
        e.preventDefault();
        let url = $(this).attr('href');
        let row = $(this).closest('tr');

        Swal.fire({
            title: 'Are you sure?',
            text: "This will permanently delete the medicine.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: { _token: $('meta[name="csrf-token"]').attr('content') },
                    success: function () {
                        toastr.success('Medicine deleted successfully.');
                        row.fadeOut(500, function () {
                            $(this).remove();
                        });
                    },
                    error: function () {
                        toastr.error('Failed to delete medicine.');
                    }
                });
            }
        });
    });

    // ✅ Refresh Table
    function refreshMedicinesTable() {
        $.get("/medicines?ajax=true", function (data) {
            $('#medicinesTableWrapper').html(data);
        }).fail(() => toastr.error('Could not refresh medicines table.'));
    }
});
</script>
