<div class="card bg-base-300 py-6 px-6 shadow-lg">
    <h2 id="formTitle" class="text-2xl font-bold text-base-content mb-6">Add New Class</h2>

    <form id="classForm" class="space-y-4">

        <input type="hidden" id="classId" name="class_id" value="">

        <div class="grid grid-cols-2 gap-3">
            <!-- Class Name -->
            <div class="form-control flex flex-col gap-2">
                <label class="label">
                    <span class="label-text font-semibold text-base-content">Class Name</span>
                </label>
                <input type="text" name="name" placeholder="e.g., 10-A, 11-B" class="input input-bordered input-sm" required />
            </div>

            <!-- Major -->
            <div class="form-control flex flex-col gap-2">
                <label class="label">
                    <span class="label-text font-semibold text-base-content">Major</span>
                </label>
                <select name="major_id" id="majorSelect" class="select select-bordered select-sm" required>
                    <option value="">-- Select Major --</option>
                </select>
            </div>

            <!-- Homeroom Teacher -->
            <div class="form-control flex flex-col gap-2">
                <label class="label">
                    <span class="label-text font-semibold text-base-content">Homeroom Teacher</span>
                </label>
                <select name="homeroom_teacher" id="teacherSelect" class="select select-bordered select-sm">
                    <option value="">-- Select Teacher (Optional) --</option>
                </select>
            </div>
        </div>

        <!-- Success Alert -->
        <div id="successAlert" class="alert alert-success hidden gap-2 py-2 px-3">
            <i class="ri-check-line"></i>
            <span id="successMessage">Class berhasil ditambahkan!</span>
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
                    <i class="ri-save-line"></i> Save Class
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
    let currentClassId = null;

    // Load majors and teachers on page load
    function loadMajorsAndTeachers() {
        // Load majors
        $.ajax({
            url: '{{ route("major.all") }}',
            method: 'GET',
            data: {
                draw: 1,
                start: 0,
                length: 1000
            },
            success: function(response) {
                const majorSelect = document.getElementById('majorSelect');
                if (response.data && response.data.length > 0) {
                    response.data.forEach(function(major) {
                        const option = document.createElement('option');
                        option.value = major.id;
                        option.textContent = major.name;
                        majorSelect.appendChild(option);
                    });
                }
            }
        });

        // Load teachers
        $.ajax({
            url: '{{ route("teacher.all") }}',
            method: 'GET',
            data: {
                draw: 1,
                start: 0,
                length: 1000
            },
            success: function(response) {
                const teacherSelect = document.getElementById('teacherSelect');
                if (response.data && response.data.length > 0) {
                    response.data.forEach(function(teacher) {
                        const option = document.createElement('option');
                        option.value = teacher.id;
                        option.textContent = teacher.name;
                        teacherSelect.appendChild(option);
                    });
                }
            }
        });
    }

    $(document).ready(function() {
        loadMajorsAndTeachers();
    });

    function editClass(id) {
        currentClassId = id;

        // Fetch class data
        $.ajax({
            url: '{{ route("class.get", ":id") }}'.replace(':id', id),
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const classData = response.data;

                    // Populate form
                    document.getElementById('classId').value = classData.id;
                    document.querySelector('input[name="name"]').value = classData.name;
                    document.getElementById('majorSelect').value = classData.major_id || '';
                    document.getElementById('teacherSelect').value = classData.homeroom_teacher || '';

                    // Change title
                    document.getElementById('formTitle').textContent = 'Edit Class';
                    document.getElementById('submitBtn').innerHTML = '<i class="ri-edit-line"></i> Update Class';

                    // Show form
                    toggleForm();
                }
            },
            error: function() {
                alert('Gagal memuat data class');
            }
        });
    }

    $('#classForm').on('submit', function(e) {
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
        const classId = document.getElementById('classId').value;

        // Hide alerts
        successAlert.classList.add('hidden');
        errorAlert.classList.add('hidden');

        // Determine URL dan method
        let url = '{{ route("class.store") }}';
        let method = 'POST';

        if (classId) {
            url = '{{ route("class.update", ":id") }}'.replace(':id', classId);
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
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="ri-loader-4-line animate-spin"></' + 'i> Saving...';
            },
            success: function(data) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;

                if (data.success) {
                    successMessage.textContent = data.message;
                    successAlert.classList.remove('hidden');

                    // Reset form
                    form.reset();
                    document.getElementById('classId').value = '';
                    currentClassId = null;

                    // Refresh datatable
                    if ($.fn.DataTable.isDataTable('#classTable')) {
                        $('#classTable').DataTable().ajax.reload();
                    }

                    // Auto close form after 2 seconds
                    setTimeout(() => {
                        toggleForm();
                        document.getElementById('formTitle').textContent = 'Add New Class';
                        document.getElementById('submitBtn').innerHTML = '<i class="ri-save-line"></' + 'i> Save Class';
                    }, 2000);
                } else {
                    errorMessage.textContent = data.message || 'Gagal menyimpan class';
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