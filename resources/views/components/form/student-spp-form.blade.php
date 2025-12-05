<div class="card bg-base-300 py-6 px-6 shadow-lg">
    <h2 id="formTitle" class="text-2xl font-bold text-base-content mb-6">Add Student SPP</h2>

    <form id="studentSppForm" class="space-y-4">

        <input type="hidden" id="studentSppId" name="student_spp_id" value="">

        <div class="grid grid-cols-2 gap-3">
            <!-- Student Class -->
            <div class="form-control flex flex-col gap-2">
                <label class="label">
                    <span class="label-text font-semibold text-base-content">Student Class</span>
                </label>
                <select name="student_class_id" id="studentClassSelect" class="select select-bordered select-sm" required>
                    <option value="">-- Select Student Class --</option>
                </select>
            </div>

            <!-- Semester -->
            <div class="form-control flex flex-col gap-2">
                <label class="label">
                    <span class="label-text font-semibold text-base-content">Semester</span>
                </label>
                <select name="semester" class="select select-bordered select-sm" required>
                    <option value="">-- Select Semester --</option>
                    <option value="1">Semester 1</option>
                    <option value="2">Semester 2</option>
                </select>
            </div>

            <!-- Price -->
            <div class="form-control flex flex-col gap-2 col-span-2">
                <label class="label">
                    <span class="label-text font-semibold text-base-content">Price</span>
                </label>
                <input type="number" name="price" placeholder="Enter SPP price" class="input input-bordered input-sm" required />
            </div>
        </div>

        <!-- Success Alert -->
        <div id="successAlert" class="alert alert-success hidden gap-2 py-2 px-3">
            <i class="ri-check-line"></i>
            <span id="successMessage">Student SPP berhasil ditambahkan!</span>
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
                    <i class="ri-save-line"></i> Save SPP
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
    let currentStudentSppId = null;

    // Load student classes on page load
    function loadStudentClasses() {
        $.ajax({
            url: '{{ route("studentClass.all") }}',
            method: 'GET',
            data: {
                draw: 1,
                start: 0,
                length: 1000
            },
            success: function(response) {
                const studentClassSelect = document.getElementById('studentClassSelect');
                if (response.data && response.data.length > 0) {
                    response.data.forEach(function(studentClass) {
                        const option = document.createElement('option');
                        option.value = studentClass.id;
                        option.textContent = studentClass.student + ' - ' + studentClass.class;
                        studentClassSelect.appendChild(option);
                    });
                }
            }
        });
    }

    $(document).ready(function() {
        loadStudentClasses();
    });

    function editStudentSpp(id) {
        currentStudentSppId = id;

        // Fetch student spp data
        $.ajax({
            url: '{{ route("studentSpp.get", ":id") }}'.replace(':id', id),
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const studentSpp = response.data;

                    // Populate form
                    document.getElementById('studentSppId').value = studentSpp.id;
                    document.getElementById('studentClassSelect').value = studentSpp.student_class_id || '';
                    document.querySelector('select[name="semester"]').value = studentSpp.semester || '';
                    document.querySelector('input[name="price"]').value = studentSpp.price;

                    // Change title
                    document.getElementById('formTitle').textContent = 'Edit Student SPP';
                    document.getElementById('submitBtn').innerHTML = '<i class="ri-edit-line"></' + 'i> Update SPP';

                    // Show form
                    toggleForm();
                }
            },
            error: function() {
                alert('Gagal memuat data');
            }
        });
    }

    $('#studentSppForm').on('submit', function(e) {
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
        const studentSppId = document.getElementById('studentSppId').value;

        // Hide alerts
        successAlert.classList.add('hidden');
        errorAlert.classList.add('hidden');

        // Determine URL dan method
        let url = '{{ route("studentSpp.store") }}';
        let method = 'POST';

        if (studentSppId) {
            url = '{{ route("studentSpp.update", ":id") }}'.replace(':id', studentSppId);
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
                    document.getElementById('studentSppId').value = '';
                    currentStudentSppId = null;

                    // Refresh datatable
                    if ($.fn.DataTable.isDataTable('#studentsppTable')) {
                        $('#studentsppTable').DataTable().ajax.reload();
                    }

                    // Auto close form after 2 seconds
                    setTimeout(() => {
                        toggleForm();
                        document.getElementById('formTitle').textContent = 'Add Student SPP';
                        document.getElementById('submitBtn').innerHTML = '<i class="ri-save-line"></' + 'i> Save SPP';
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