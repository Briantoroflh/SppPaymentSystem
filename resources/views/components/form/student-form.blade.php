<div class="card bg-base-300 py-6 px-6 shadow-lg">
    <h2 id="formTitle" class="text-2xl font-bold text-base-content mb-6">Add New Student</h2>

    <form id="studentForm" class="space-y-4">

        <input type="hidden" id="studentId" name="student_id" value="">

        <div class="grid grid-cols-2 gap-3">
            <!-- Name -->
            <div class="form-control flex flex-col gap-2">
                <label class="label">
                    <span class="label-text font-semibold text-base-content">Name</span>
                </label>
                <input type="text" name="name" placeholder="Enter student name" class="input input-bordered input-sm" required />
            </div>

            <!-- Age -->
            <div class="form-control flex flex-col gap-2">
                <label class="label">
                    <span class="label-text font-semibold text-base-content">Age</span>
                </label>
                <input type="number" name="age" placeholder="Enter age" class="input input-bordered input-sm" required />
            </div>

            <!-- NISN -->
            <div class="form-control flex flex-col gap-2">
                <label class="label">
                    <span class="label-text font-semibold text-base-content">NISN</span>
                </label>
                <input type="text" name="nisn" placeholder="Enter NISN" class="input input-bordered input-sm" required />
            </div>

            <!-- NIK -->
            <div class="form-control flex flex-col gap-2">
                <label class="label">
                    <span class="label-text font-semibold text-base-content">NIK</span>
                </label>
                <input type="text" name="nik" placeholder="Enter NIK" class="input input-bordered input-sm" required />
            </div>

            <!-- Phone Number -->
            <div class="form-control flex flex-col gap-2">
                <label class="label">
                    <span class="label-text font-semibold text-base-content">Phone Number</span>
                </label>
                <input type="text" name="phone_number" placeholder="Enter phone number" class="input input-bordered input-sm" required />
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
            <span id="successMessage">Student berhasil ditambahkan!</span>
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
                    <i class="ri-save-line"></i> Save Student
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
    let currentStudentId = null;

    function editStudent(id) {
        currentStudentId = id;

        // Fetch student data
        $.ajax({
            url: '{{ route("student.get", ":id") }}'.replace(':id', id),
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const student = response.data;

                    // Populate form
                    document.getElementById('studentId').value = student.id;
                    document.querySelector('input[name="name"]').value = student.name;
                    document.querySelector('input[name="age"]').value = student.age;
                    document.querySelector('input[name="nisn"]').value = student.nisn;
                    document.querySelector('input[name="nik"]').value = student.nik;
                    document.querySelector('input[name="phone_number"]').value = student.phone_number;
                    document.querySelector('select[name="isActive"]').value = student.isActive ? '1' : '0';

                    // Change title
                    document.getElementById('formTitle').textContent = 'Edit Student';
                    document.getElementById('submitBtn').innerHTML = '<i class="ri-edit-line"></i> Update Student';

                    // Show form
                    toggleForm();
                }
            },
            error: function() {
                alert('Gagal memuat data student');
            }
        });
    }

    $('#studentForm').on('submit', function(e) {
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
        const studentId = document.getElementById('studentId').value;

        // Hide alerts
        successAlert.classList.add('hidden');
        errorAlert.classList.add('hidden');

        // Determine URL dan method
        let url = '{{ route("student.store") }}';
        let method = 'POST';

        if (studentId) {
            url = '{{ route("student.update", ":id") }}'.replace(':id', studentId);
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
                    document.getElementById('studentId').value = '';
                    currentStudentId = null;

                    // Refresh datatable
                    if ($.fn.DataTable.isDataTable('#studentTable')) {
                        $('#studentTable').DataTable().ajax.reload();
                    }

                    // Auto close form after 2 seconds
                    setTimeout(() => {
                        toggleForm();
                        document.getElementById('formTitle').textContent = 'Add New Student';
                        document.getElementById('submitBtn').innerHTML = '<i class="ri-save-line"></i> Save Student';
                    }, 2000);
                } else {
                    errorMessage.textContent = data.message || 'Gagal menyimpan student';
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