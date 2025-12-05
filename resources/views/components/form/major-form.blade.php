<div class="card bg-base-300 py-6 px-6 shadow-lg">
    <h2 id="formTitle" class="text-2xl font-bold text-base-content mb-6">Add New Major</h2>

    <form id="majorForm" class="space-y-4">

        <input type="hidden" id="majorId" name="major_id" value="">

        <div class="grid grid-cols-2 gap-3">
            <!-- Name -->
            <div class="form-control flex flex-col gap-2">
                <label class="label">
                    <span class="label-text font-semibold text-base-content">Name</span>
                </label>
                <input type="text" name="name" placeholder="Enter major name" class="input input-bordered input-sm" required />
            </div>

            <!-- Start At -->
            <div class="form-control flex flex-col gap-2">
                <label class="label">
                    <span class="label-text font-semibold text-base-content">Start At</span>
                </label>
                <input type="date" name="start_at" class="input input-bordered input-sm" required />
            </div>

            <!-- Active -->
            <div class="form-control flex flex-col gap-2">
                <label class="label">
                    <span class="label-text font-semibold text-base-content">Active</span>
                </label>
                <select name="isActive" class="select select-bordered select-sm" required>
                    <option value="">Select status</option>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
        </div>

        <!-- Success Alert -->
        <div id="successAlert" class="alert alert-success hidden gap-2 py-2 px-3">
            <i class="ri-check-line"></i>
            <span id="successMessage">Major berhasil ditambahkan!</span>
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
                    <i class="ri-save-line"></i> Save Major
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
    let currentMajorId = null;

    function editMajor(id) {
        currentMajorId = id;

        // Fetch major data
        $.ajax({
            url: '{{ route("major.get", ":id") }}'.replace(':id', id),
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const major = response.data;

                    // Populate form
                    document.getElementById('majorId').value = major.id;
                    document.querySelector('input[name="name"]').value = major.name;
                    document.querySelector('input[name="start_at"]').value = major.start_at.split(' ')[0];
                    document.querySelector('select[name="isActive"]').value = major.isActive ? '1' : '0';

                    // Change title
                    document.getElementById('formTitle').textContent = 'Edit Major';
                    document.getElementById('submitBtn').innerHTML = '<i class="ri-edit-line"></i> Update Major';

                    // Show form
                    toggleForm();
                }
            },
            error: function() {
                alert('Gagal memuat data major');
            }
        });
    }

    $('#majorForm').on('submit', function(e) {
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
        const majorId = document.getElementById('majorId').value;

        // Hide alerts
        successAlert.classList.add('hidden');
        errorAlert.classList.add('hidden');

        // Determine URL dan method
        let url = '{{ route("major.store") }}';
        let method = 'POST';

        if (majorId) {
            url = '{{ route("major.update", ":id") }}'.replace(':id', majorId);
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
                    document.getElementById('majorId').value = '';
                    currentMajorId = null;

                    // Refresh datatable
                    if ($.fn.DataTable.isDataTable('#majorTable')) {
                        $('#majorTable').DataTable().ajax.reload();
                    }

                    // Auto close form after 2 seconds
                    setTimeout(() => {
                        toggleForm();
                        document.getElementById('formTitle').textContent = 'Add New Major';
                        document.getElementById('submitBtn').innerHTML = '<i class="ri-save-line"></i> Save Major';
                    }, 2000);
                } else {
                    errorMessage.textContent = data.message || 'Gagal menyimpan major';
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