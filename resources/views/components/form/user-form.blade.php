<div class="card bg-base-300 py-6 px-6 shadow-lg">
    <h2 id="formTitle" class="text-2xl font-bold text-base-content mb-6">Add New User</h2>

    <form id="userForm" class="space-y-4">

        <input type="hidden" id="userId" name="user_id" value="">

        <div class="grid grid-cols-2 gap-3">
            <!-- Name -->
            <div class="form-control flex flex-col gap-2">
                <label class="label">
                    <span class="label-text font-semibold text-base-content">Name</span>
                </label>
                <input type="text" name="name" placeholder="Enter user name" class="input input-bordered input-sm" required />
            </div>

            <!-- Email -->
            <div class="form-control flex flex-col gap-2">
                <label class="label">
                    <span class="label-text font-semibold text-base-content">Email</span>
                </label>
                <input type="email" name="email" placeholder="Enter email" class="input input-bordered input-sm" required />
            </div>

            <!-- Password -->
            <div class="form-control flex flex-col gap-2">
                <label class="label">
                    <span class="label-text font-semibold text-base-content">Password</span>
                </label>
                <input type="password" name="password" placeholder="Enter password" class="input input-bordered input-sm" id="passwordField" required />
            </div>
        </div>

        <!-- Success Alert -->
        <div id="successAlert" class="alert alert-success hidden gap-2 py-2 px-3">
            <i class="ri-check-line"></i>
            <span id="successMessage">User berhasil ditambahkan!</span>
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
                    <i class="ri-save-line"></i> Save User
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
    let currentUserId = null;

    function editUser(id) {
        currentUserId = id;

        // Fetch user data
        $.ajax({
            url: '{{ route("user.get", ":id") }}'.replace(':id', id),
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const user = response.data;

                    // Populate form
                    document.getElementById('userId').value = user.id;
                    document.querySelector('input[name="name"]').value = user.name;
                    document.querySelector('input[name="email"]').value = user.email;
                    document.getElementById('passwordField').removeAttribute('required');
                    document.getElementById('passwordField').placeholder = 'Leave empty to keep current password';

                    // Change title
                    document.getElementById('formTitle').textContent = 'Edit User';
                    document.getElementById('submitBtn').innerHTML = '<i class="ri-edit-line"></i> Update User';

                    // Show form
                    toggleForm();
                }
            },
            error: function() {
                alert('Gagal memuat data user');
            }
        });
    }

    $('#userForm').on('submit', function(e) {
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
        const userId = document.getElementById('userId').value;

        // Hide alerts
        successAlert.classList.add('hidden');
        errorAlert.classList.add('hidden');

        // Determine URL dan method
        let url = '{{ route("user.store") }}';
        let method = 'POST';

        if (userId) {
            url = '{{ route("user.update", ":id") }}'.replace(':id', userId);
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
                    document.getElementById('userId').value = '';
                    currentUserId = null;
                    document.getElementById('passwordField').setAttribute('required', 'required');
                    document.getElementById('passwordField').placeholder = 'Enter password';

                    // Refresh datatable
                    if ($.fn.DataTable.isDataTable('#userTable')) {
                        $('#userTable').DataTable().ajax.reload();
                    }

                    // Auto close form after 2 seconds
                    setTimeout(() => {
                        toggleForm();
                        document.getElementById('formTitle').textContent = 'Add New User';
                        document.getElementById('submitBtn').innerHTML = '<i class="ri-save-line"></i> Save User';
                    }, 2000);
                } else {
                    errorMessage.textContent = data.message || 'Gagal menyimpan user';
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