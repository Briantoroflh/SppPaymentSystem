<div class="card bg-base-300 py-6 px-6 shadow-lg">
    <h2 id="formTitle" class="text-2xl font-bold text-base-content mb-6">Add Student to Class</h2>

    <form id="studentClassForm" class="space-y-4">

        <input type="hidden" id="studentClassId" name="student_class_id" value="">

        <div class="grid grid-cols-2 gap-3">
            <!-- Student -->
            <div class="form-control flex flex-col gap-2">
                <label class="label">
                    <span class="label-text font-semibold text-base-content">Student</span>
                </label>
                <select name="student_id" id="studentSelect" class="select select-bordered select-sm" required>
                    <option value="">-- Select Student --</option>
                </select>
            </div>

            <!-- Class -->
            <div class="form-control flex flex-col gap-2">
                <label class="label">
                    <span class="label-text font-semibold text-base-content">Class</span>
                </label>
                <select name="class_id" id="classSelect" class="select select-bordered select-sm" required>
                    <option value="">-- Select Class --</option>
                </select>
            </div>
        </div>

        <!-- Success Alert -->
        <div id="successAlert" class="alert alert-success hidden gap-2 py-2 px-3">
            <i class="ri-check-line"></i>
            <span id="successMessage">Student Class berhasil ditambahkan!</span>
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
                    <i class="ri-save-line"></i> Save
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
    let currentStudentClassId = null;

    // Load students and classes on page load
    function loadStudentsAndClasses() {
        // Load students
        $.ajax({
            url: '{{ route("student.all") }}',
            method: 'GET',
            data: {
                draw: 1,
                start: 0,
                length: 1000
            },
            success: function(response) {
                const studentSelect = document.getElementById('studentSelect');
                if (response.data && response.data.length > 0) {
                    response.data.forEach(function(student) {
                        const option = document.createElement('option');
                        option.value = student.id;
                        option.textContent = student.name;
                        studentSelect.appendChild(option);
                    });
                }
            }
        });

        // Load classes
        $.ajax({
            url: '{{ route("class.all") }}',
            method: 'GET',
            data: {
                draw: 1,
                start: 0,
                length: 1000
            },
            success: function(response) {
                const classSelect = document.getElementById('classSelect');
                if (response.data && response.data.length > 0) {
                    response.data.forEach(function(classData) {
                        const option = document.createElement('option');
                        option.value = classData.id;
                        option.textContent = classData.name;
                        classSelect.appendChild(option);
                    });
                }
            }
        });
    }

    $(document).ready(function() {
        loadStudentsAndClasses();
    });

    function editStudentClass(id) {
        currentStudentClassId = id;

        // Fetch student class data
        $.ajax({
            url: '{{ route("studentClass.get", ":id") }}'.replace(':id', id),
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const studentClass = response.data;

                    // Populate form
                    document.getElementById('studentClassId').value = studentClass.id;
                    document.getElementById('studentSelect').value = studentClass.student_id || '';
                    document.getElementById('classSelect').value = studentClass.class_id || '';

                    // Change title
                    document.getElementById('formTitle').textContent = 'Edit Student Class';
                    document.getElementById('submitBtn').innerHTML = '<i class="ri-edit-line"></' + 'i> Update';

                    // Show form
                    toggleForm();
                }
            },
            error: function() {
                alert('Gagal memuat data');
            }
        });
    }

    $('#studentClassForm').on('submit', function(e) {
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
        const studentClassId = document.getElementById('studentClassId').value;

        // Hide alerts
        successAlert.classList.add('hidden');
        errorAlert.classList.add('hidden');

        // Determine URL dan method
        let url = '{{ route("studentClass.store") }}';
        let method = 'POST';

        if (studentClassId) {
            url = '{{ route("studentClass.update", ":id") }}'.replace(':id', studentClassId);
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
                    document.getElementById('studentClassId').value = '';
                    currentStudentClassId = null;

                    // Refresh datatable
                    if ($.fn.DataTable.isDataTable('#studentclassTable')) {
                        $('#studentclassTable').DataTable().ajax.reload();
                    }

                    // Auto close form after 2 seconds
                    setTimeout(() => {
                        toggleForm();
                        document.getElementById('formTitle').textContent = 'Add Student to Class';
                        document.getElementById('submitBtn').innerHTML = '<i class="ri-save-line"></' + 'i> Save';
                    }, 2000);
                } else {
                    errorMessage.textContent = data.message || 'Gagal menyimpan';
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