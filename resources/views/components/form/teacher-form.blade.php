<div class="card bg-base-300 py-6 px-6 shadow-lg">
    <h2 id="formTitle" class="text-2xl font-bold text-base-content mb-6">Add New Teacher</h2>

    <form id="teacherForm" class="space-y-4">

        <input type="hidden" id="teacherId" name="teacher_id" value="">

        <div class="grid grid-cols-2 gap-3">
            <!-- Teacher Name -->
            <div class="form-control flex flex-col gap-2">
                <label class="label">
                    <span class="label-text font-semibold text-base-content">Teacher Name</span>
                </label>
                <input type="text" name="name" placeholder="Enter teacher name" class="input input-bordered input-sm" required />
            </div>

            <!-- Title/Subject -->
            <div class="form-control flex flex-col gap-2">
                <label class="label">
                    <span class="label-text font-semibold text-base-content">Position</span>
                </label>
                <input type="text" name="title" placeholder="Enter position" class="input input-bordered input-sm" required />
            </div>
        </div>

        <!-- Success Alert -->
        <div id="successAlert" class="alert alert-success hidden gap-2 py-2 px-3">
            <i class="ri-check-line"></i>
            <span id="successMessage">Teacher berhasil ditambahkan!</span>
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
                    <i class="ri-save-line"></i> Save Teacher
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
    let currentTeacherId = null;

    function editTeacher(id) {
        currentTeacherId = id;

        // Fetch teacher data
        $.ajax({
            url: '{{ route("teacher.get", ":id") }}'.replace(':id', id),
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const teacher = response.data;

                    // Populate form
                    document.getElementById('teacherId').value = teacher.id;
                    document.querySelector('input[name="name"]').value = teacher.name;
                    document.querySelector('input[name="title"]').value = teacher.title;

                    // Change title
                    document.getElementById('formTitle').textContent = 'Edit Teacher';
                    document.getElementById('submitBtn').innerHTML = '<i class="ri-edit-line"></i> Update Teacher';

                    // Show form
                    toggleForm();
                }
            },
            error: function() {
                alert('Gagal memuat data teacher');
            }
        });
    }

    $('#teacherForm').on('submit', function(e) {
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
        const teacherId = document.getElementById('teacherId').value;

        // Hide alerts
        successAlert.classList.add('hidden');
        errorAlert.classList.add('hidden');

        // Determine URL dan method
        let url = '{{ route("teacher.store") }}';
        let method = 'POST';

        if (teacherId) {
            url = '{{ route("teacher.update", ":id") }}'.replace(':id', teacherId);
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
                    document.getElementById('teacherId').value = '';
                    currentTeacherId = null;

                    // Refresh datatable
                    if ($.fn.DataTable.isDataTable('#teacherTable')) {
                        $('#teacherTable').DataTable().ajax.reload();
                    }

                    // Auto close form after 2 seconds
                    setTimeout(() => {
                        toggleForm();
                        document.getElementById('formTitle').textContent = 'Add New Teacher';
                        document.getElementById('submitBtn').innerHTML = '<i class="ri-save-line"></' + 'i> Save Teacher';
                    }, 2000);
                } else {
                    errorMessage.textContent = data.message || 'Gagal menyimpan teacher';
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