$(document).ready(function () {
    $('#addMedicineForm').on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            type: "POST",
            data: $(this).serialize(),
            success: function (response) {
                $('#addMedicineModal').modal('hide');
                toastr.success("Medicine added successfully!");
                $('#addMedicineForm')[0].reset();

                // optionally refresh the table
                $('#medicinesTable').load(location.href + ' #medicinesTable');
            },
            error: function (xhr) {
                toastr.error("Failed to add medicine");
                console.log(xhr.responseText);
            }
        });
    });

    // =========================
    // View Medicine
    // =========================
    $(document).on("click", ".viewMedicine", function () {
        let id = $(this).data("id");

        $.get(`/medicines/${id}`, function (data) {
            $("#viewMedicineModal .modal-body").html(data);
            $("#viewMedicineModal").modal("show");
        });
    });

    // =========================
    // Edit Medicine (load form)
    // =========================
    $(document).on("click", ".editMedicine", function () {
        let id = $(this).data("id");

        $.get(`/medicines/${id}/edit`, function (data) {
            $("#editMedicineModal .modal-body").html(data);
            $("#editMedicineModal").modal("show");
        });
    });

    // =========================
    // Update Medicine
    // =========================
    $(document).on("submit", "#editMedicineForm", function (e) {
        e.preventDefault();
        let id = $(this).data("id");

        $.ajax({
            url: `/medicines/${id}`,
            type: "POST",
            data: $(this).serialize(),
            success: function (response) {
                if (response.success) {
                    $("#editMedicineModal").modal("hide");

                    // Replace updated row
                    $(`#medicine-row-${response.id}`).replaceWith(response.row);

                    toastr.success(response.message);
                }
            },
            error: function () {
                toastr.error("Failed to update medicine.");
            }
        });
    });

    // =========================
    // Delete Medicine
    // =========================
    $(document).on("click", ".deleteMedicine", function () {
        let id = $(this).data("id");

        if (!confirm("Are you sure you want to delete this medicine?")) return;

        $.ajax({
            url: `/medicines/${id}`,
            type: "DELETE",
            data: { _token: $("meta[name=csrf-token]").attr("content") },
            success: function (response) {
                if (response.success) {
                    $(`#medicine-row-${id}`).remove();
                    toastr.success("Medicine deleted successfully.");
                }
            },
            error: function () {
                toastr.error("Failed to delete medicine.");
            }
        });
    });

});
