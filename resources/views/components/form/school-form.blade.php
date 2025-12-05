<div class="card bg-base-300 py-6 px-6 shadow-lg">
    <h2 id="formTitle" class="text-2xl font-bold text-base-content mb-6">Add New School</h2>

    <form id="schoolForm" class="space-y-4">

        <input type="hidden" id="schoolId" name="school_id" value="">

        <div class="grid grid-cols-2 gap-3">
            <!-- Name -->
            <div class="form-control flex flex-col gap-2">
                <label class="label">
                    <span class="label-text font-semibold text-base-content">Name</span>
                </label>
                <input type="text" name="name" placeholder="Enter school name" class="input input-bordered input-sm" required />
            </div>

            <!-- Level -->
            <div class="form-control flex flex-col gap-2">
                <label class="label">
                    <span class="label-text font-semibold text-base-content">Level</span>
                </label>
                <input type="text" name="level" placeholder="e.g. Elementary, Middle, High" class="input input-bordered input-sm" required />
            </div>

            <!-- Phone Number -->
            <div class="form-control flex flex-col gap-2">
                <label class="label">
                    <span class="label-text font-semibold text-base-content">Phone Number</span>
                </label>
                <input type="text" name="phone_number" placeholder="Enter phone number" class="input input-bordered input-sm" required />
            </div>

            <!-- Address -->
            <div class="form-control flex flex-col gap-2">
                <label class="label">
                    <span class="label-text font-semibold text-base-content">Address</span>
                </label>
                <input type="text" name="address" placeholder="Enter address" class="input input-bordered input-sm" required />
            </div>

            <!-- Region -->
            <div class="form-control flex flex-col gap-2 col-span-2">
                <label class="label">
                    <span class="label-text font-semibold text-base-content">Region</span>
                </label>
                <select name="region_id" id="regionSelect" class="select select-bordered select-sm" required>
                    <option value="">Select region</option>
                </select>
            </div>

            <!-- School Admin User -->
            <div class="form-control flex flex-col gap-2 col-span-2">
                <label class="label">
                    <span class="label-text font-semibold text-base-content">School Admin User</span>
                </label>
                <select name="user_id" id="userSelect" class="select select-bordered select-sm">
                    <option value="">-- Select User (Optional) --</option>
                </select>
            </div>
        </div>

        <!-- Success Alert -->
        <div id="successAlert" class="alert alert-success hidden gap-2 py-2 px-3">
            <i class="ri-check-line"></i>
            <span id="successMessage">School berhasil ditambahkan!</span>
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
                    <i class="ri-save-line"></i> Save School
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
    let currentSchoolId = null;

    // Load regions from API
    function loadRegions() {
        $.ajax({
            url: '{{ route("region.all") }}',
            method: 'GET',
            data: {
                draw: 1,
                start: 0,
                length: 1000
            },
            success: function(response) {
                const regionSelect = document.getElementById('regionSelect');

                // Clear existing options except the first one
                while (regionSelect.options.length > 1) {
                    regionSelect.remove(1);
                }

                // Add region options
                if (response.data && response.data.length > 0) {
                    response.data.forEach(function(region) {
                        const option = document.createElement('option');
                        option.value = region.id;
                        option.textContent = region.name;
                        regionSelect.appendChild(option);
                    });
                }
            },
            error: function(xhr) {
                console.error('Gagal memuat data region:', xhr);
            }
        });
    }

    // Load users from API
    function loadUsers() {
        $.ajax({
            url: '{{ route("user.all") }}',
            method: 'GET',
            data: {
                draw: 1,
                start: 0,
                length: 1000
            },
            success: function(response) {
                const userSelect = document.getElementById('userSelect');

                // Clear existing options except the first one
                while (userSelect.options.length > 1) {
                    userSelect.remove(1);
                }

                // Add user options
                if (response.data && response.data.length > 0) {
                    response.data.forEach(function(user) {
                        const option = document.createElement('option');
                        option.value = user.id;
                        option.textContent = user.name + ' (' + user.email + ')';
                        userSelect.appendChild(option);
                    });
                }
            },
            error: function(xhr) {
                console.error('Gagal memuat data user:', xhr);
            }
        });
    }

    // Load regions and users when document is ready
    $(document).ready(function() {
        loadRegions();
        loadUsers();
    });

    function editSchool(id) {
        currentSchoolId = id;

        // Fetch school data
        $.ajax({
            url: '{{ route("school.get", ":id") }}'.replace(':id', id),
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const school = response.data;

                    // Populate form
                    document.getElementById('schoolId').value = school.id;
                    document.querySelector('input[name="name"]').value = school.name;
                    document.querySelector('input[name="level"]').value = school.level;
                    document.querySelector('input[name="phone_number"]').value = school.phone_number;
                    document.querySelector('input[name="address"]').value = school.address;
                    document.getElementById('regionSelect').value = school.region_id || '';
                    document.getElementById('userSelect').value = school.user_id || '';

                    // Change title
                    document.getElementById('formTitle').textContent = 'Edit School';
                    document.getElementById('submitBtn').innerHTML = '<i class="ri-edit-line"></i> Update School';

                    // Show form - call toggleForm from school-management.blade.php
                    toggleForm();
                }
            },
            error: function() {
                alert('Gagal memuat data school');
            }
        });
    }

    $('#schoolForm').on('submit', function(e) {
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
        const schoolId = document.getElementById('schoolId').value;

        // Hide alerts
        successAlert.classList.add('hidden');
        errorAlert.classList.add('hidden');

        // Determine URL dan method
        let url = '{{ route("school.store") }}';
        let method = 'POST';

        if (schoolId) {
            url = '{{ route("school.update", ":id") }}'.replace(':id', schoolId);
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
                    document.getElementById('schoolId').value = '';
                    currentSchoolId = null;

                    // Refresh datatable
                    if ($.fn.DataTable.isDataTable('#schoolTable')) {
                        $('#schoolTable').DataTable().ajax.reload();
                    }

                    // Auto close form after 2 seconds
                    setTimeout(() => {
                        toggleForm();
                        document.getElementById('formTitle').textContent = 'Add New School';
                        document.getElementById('submitBtn').innerHTML = '<i class="ri-save-line"></i> Save School';
                    }, 2000);
                } else {
                    errorMessage.textContent = data.message || 'Gagal menyimpan school';
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