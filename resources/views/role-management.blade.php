@extends('layouts.app')

@section('section')
<div class="p-6 md:p-8">
    <h1 class="text-3xl font-bold text-base-content mb-6">Role Management</h1>

    <div class="mb-3">
        <button id="btnAdd" class="btn btn-primary btn-sm gap-2" onclick="toggleForm()"><i class="ri-add-fill"></i> Add</button>
    </div>

    <!-- Datatable Section -->
    <div id="datatableSection" class="card bg-base-300 py-3 px-4">
        {!! App\Helper\DatatableBuilderHelper::create([
        'name' => 'role',
        'url' => route('role.all'),
        'columns' => App\Http\Controllers\RoleController::headerColumn(),
        'searching' => true,
        'ordering' => true,
        ]) !!}
    </div>

    <h3 class="text-md font-bold text-base-content mb-4 mt-10">Menu Akses</h3>
    <!-- Menu Akses -->
    <div class="card bg-base-300 py-3 px-4">
        <!-- Input Role -->
        <div class="form-control flex flex-col gap-2 col-span-2">
            <select name="role_name" id="roleSelect" class="select select-bordered select-sm">
                <option value="">Select role</option>
            </select>
            <div id="cotentMenu">

            </div>
        </div>
    </div>

    <!-- Form Section -->
    <div id="formSection" class="hidden">
        @include('components.form.role-form')
    </div>
</div>

@push('additional-js')
<script>

    function loadRoles() {
        $.ajax({
            url: '{{ route("role.all") }}',
            method: 'GET',
            data: {
                draw: 1,
                start: 0,
                length: 1000 // Get all data with reasonable limit
            },
            success: function(response) {
                const roleSelect = document.getElementById('roleSelect');

                // Clear existing options except the first one
                while (roleSelect.options.length > 1) {
                    roleSelect.remove(1);
                }

                // Add region options
                if (response.data && response.data.length > 0) {
                    response.data.forEach(function(role) {
                        const option = document.createElement('option');
                        option.value = role.id;
                        option.textContent = role.name;
                        regionSelect.appendChild(option);
                    });
                }
            },
            error: function(xhr) {
                console.error('Gagal memuat data region:', xhr);
            }
        })
    }

    $(document).ready(function() {
        loadRoles();
    })

    function toggleForm() {
        const datatableSection = document.getElementById('datatableSection');
        const formSection = document.getElementById('formSection');
        const btnAdd = document.getElementById('btnAdd');

        // Toggle visibility
        datatableSection.classList.toggle('hidden');
        formSection.classList.toggle('hidden');

        // Update button text
        if (formSection.classList.contains('hidden')) {
            btnAdd.innerHTML = '<i class="ri-add-fill"></i> Add';
        } else {
            btnAdd.innerHTML = '<i class="ri-arrow-left-line"></i> Back';
        }
    }
</script>
@endpush
@endsection