<div class="card bg-base-300 py-6 px-6 shadow-lg">
    <h2 id="formTitle" class="text-2xl font-bold text-base-content mb-6">Add New Region</h2>

    <form id="regionForm" class="space-y-4">

        <input type="hidden" id="regionId" name="region_id" value="">

        <div class="grid grid-cols-2 gap-3">
            <!-- Name -->
            <div class="form-control flex flex-col gap-2">
                <label class="label">
                    <span class="label-text font-semibold text-base-content">Name</span>
                </label>
                <input type="text" name="name" placeholder="Enter region name" class="input input-bordered input-sm" required />
            </div>

            <!-- Longitude -->
            <div class="form-control flex flex-col gap-2">
                <label class="label">
                    <span class="label-text font-semibold text-base-content">Longitude</span>
                </label>
                <input type="text" name="longitude" placeholder="Enter longitude" class="input input-bordered input-sm" required />
            </div>

            <!-- Latitude -->
            <div class="form-control flex flex-col gap-2">
                <label class="label">
                    <span class="label-text font-semibold text-base-content">Latitude</span>
                </label>
                <input type="text" name="latitude" placeholder="Enter latitude" class="input input-bordered input-sm" required />
            </div>
        </div>

        <!-- Success Alert -->
        <div id="successAlert" class="alert alert-success hidden gap-2 py-2 px-3">
            <i class="ri-check-line"></i>
            <span id="successMessage">Region berhasil ditambahkan!</span>
        </div>

        <!-- Error Alert -->
        <div id="errorAlert" class="alert alert-error hidden gap-2 py-2 px-3">
            <i class="ri-error-warning-line"></i>
            <span id="errorMessage">Terjadi kesalahan</span>
        </div>

        <!-- Submit Buttons -->
        <div class="form-control mt-6">
            <div class="flex gap-3">
                <button type="submit" id="submitBtn" class="btn btn-primary btn-sm flex-1 gap-2">
                    <i class="ri-save-line"></i> Save Region
                </button>
                <button type="button" onclick="toggleForm()" class="btn btn-ghost btn-sm flex-1 gap-2">
                    <i class="ri-close-line"></i> Cancel
                </button>
            </div>
        </div>
    </form>
</div>

@push('additional-js')
<script>
    let currentRegionId = null;

    function editRegion(id) {
        currentRegionId = id;

        // Fetch region data
        $.ajax({
            url: '{{ route("region.get", ":id") }}'.replace(':id', id),
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const region = response.data;

                    // Populate form
                    document.getElementById('regionId').value = region.id;
                    document.querySelector('input[name="name"]').value = region.name;
                    document.querySelector('input[name="longitude"]').value = region.longitude;
                    document.querySelector('input[name="latitude"]').value = region.latitude;

                    // Change title
                    document.getElementById('formTitle').textContent = 'Edit Region';
                    document.getElementById('submitBtn').innerHTML = '<i class="ri-edit-line"></i> Update Region';

                    // Show form
                    toggleForm();
                }
            },
            error: function() {
                alert('Gagal memuat data region');
            }
        });
    }

    $('#regionForm').on('submit', function(e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);
        const successAlert = document.getElementById('successAlert');
        const errorAlert = document.getElementById('errorAlert');
        const successMessage = document.getElementById('successMessage');
        const errorMessage = document.getElementById('errorMessage');
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        const regionId = document.getElementById('regionId').value;

        // Hide alerts
        successAlert.classList.add('hidden');
        errorAlert.classList.add('hidden');

        // Determine URL dan method
        let url = '{{ route("region.store") }}';
        let method = 'POST';

        if (regionId) {
            url = '{{ route("region.update", ":id") }}'.replace(':id', regionId);
            method = 'PUT';
            formData.append('_method', 'PUT');
        }

        $.ajax({
            url: url,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            },
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                // Show loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="ri-loader-4-line animate-spin"></i> Saving...';
            },
            success: function(data) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;

                if (data.success) {
                    successMessage.textContent = data.message;
                    successAlert.classList.remove('hidden');

                    // Reset form
                    form.reset();
                    document.getElementById('regionId').value = '';
                    currentRegionId = null;

                    // Refresh datatable
                    if ($.fn.DataTable.isDataTable('#regionTable')) {
                        $('#regionTable').DataTable().ajax.reload();
                    }

                    // Auto close form after 2 seconds
                    setTimeout(() => {
                        toggleForm();
                        document.getElementById('formTitle').textContent = 'Add New Region';
                        document.getElementById('submitBtn').innerHTML = '<i class="ri-save-line"></i> Save Region';
                    }, 2000);
                } else {
                    errorMessage.textContent = data.message || 'Gagal menyimpan region';
                    errorAlert.classList.remove('hidden');
                }
            },
            error: function(xhr, status, error) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
                errorMessage.textContent = 'Terjadi kesalahan: ' + error;
                errorAlert.classList.remove('hidden');
                console.log(xhr);
            }
        });
    });
</script>
@endpush