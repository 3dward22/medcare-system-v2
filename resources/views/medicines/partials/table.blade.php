<table class="table table-bordered" id="medicinesTable">
    <thead>
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Stock</th>
            <th>Unit</th>
            <th>Expiration Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($medicines as $medicine)
            @include('medicines.partials.row', ['medicine' => $medicine])
        @empty
            <tr>
                <td colspan="4" class="text-center">No medicines found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{-- Pagination (only for full page, not AJAX calls) --}}
@if (!request()->ajax())
    <div class="mt-3">
        {{ $medicines->links() }}
    </div>
@endif
