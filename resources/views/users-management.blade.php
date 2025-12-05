@extends('layouts.app')

@section('section')
<div class="p-6 md:p-8">
    <h1 class="text-3xl font-bold text-base-content mb-6">Users Management</h1>

    <div class="mb-3">
        <button id="btnAdd" class="btn btn-primary btn-sm gap-2" onclick="toggleForm()"><i class="ri-add-fill"></i> Add</button>
    </div>

    <!-- Datatable Section -->
    <div id="datatableSection" class="card bg-base-300 py-3 px-4">
        {!! App\Helper\DatatableBuilderHelper::create([
        'name' => 'user',
        'url' => route('user.all'),
        'columns' => App\Http\Controllers\UserController::headerColumn(),
        'searching' => true,
        'ordering' => true,
        ]) !!}
    </div>

    <!-- Form Section -->
    <div id="formSection" class="hidden">
        @include('components.form.user-form')
    </div>
</div>

@push('additional-js')
<script>
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