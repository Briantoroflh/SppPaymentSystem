<div class="card bg-base-300 py-6 px-6 shadow-lg">
    <h2 id="formTitle" class="text-2xl font-bold text-base-content mb-6">Add New Menu</h2>

    <form id="menuForm" class="space-y-4">

        <input type="hidden" id="menuId" name="menu_id" value="">

        <div class="grid grid-cols-2 gap-3">
            <!-- Sequence -->
            <div class="form-control flex flex-col gap-2">
                <label class="label">
                    <span class="label-text font-semibold text-base-content">Sequence</span>
                </label>
                <input type="number" name="sequence" placeholder="Enter sequence number" class="input input-bordered input-sm" required />
            </div>

            <!-- Head Title (Section) -->
            <div class="form-control flex flex-col gap-2">
                <label class="label">
                    <span class="label-text font-semibold text-base-content">Head Title</span>
                </label>
                <input type="text" name="head_title" placeholder="e.g., Banking, Services, Other" class="input input-bordered input-sm" required />
            </div>

            <!-- Title -->
            <div class="form-control flex flex-col gap-2">
                <label class="label">
                    <span class="label-text font-semibold text-base-content">Title</span>
                </label>
                <input type="text" name="title" placeholder="Enter menu title" class="input input-bordered input-sm" required />
            </div>

            <!-- Icon (Remix Icon class) -->
            <div class="form-control flex flex-col gap-2">
                <label class="label">
                    <span class="label-text font-semibold text-base-content">Icon</span>
                </label>
                <input type="text" name="icon" placeholder="e.g., ri-dashboard-3-line" class="input input-bordered input-sm" required />
            </div>

            <!-- URL -->
            <div class="form-control flex flex-col gap-2">
                <label class="label">
                    <span class="label-text font-semibold text-base-content">Url</span>
                </label>
                <input type="text" name="url" placeholder="e.g., /dashboard" class="input input-bordered input-sm" required />
            </div>
        </div>


        <!-- Success Alert -->
        <div id="successAlert" class="alert alert-success hidden gap-2 py-2 px-3">
            <i class="ri-check-line"></i>
            <span id="successMessage">Menu berhasil ditambahkan!</span>
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
                    <i class="ri-save-line"></i> Save Menu
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
    let currentMenuId = null;

    function editMenu(id) {
        currentMenuId = id;

        // Fetch menu data
        $.ajax({
            url: '{{ route("menu.get", ":id") }}'.replace(':id', id),
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const menu = response.data;

                    // Populate form
                    document.getElementById('menuId').value = menu.id;
                    document.querySelector('input[name="sequence"]').value = menu.sequence;
                    document.querySelector('input[name="head_title"]').value = menu.head_title;
                    document.querySelector('input[name="title"]').value = menu.title;
                    document.querySelector('input[name="icon"]').value = menu.icon;
                    document.querySelector('input[name="url"]').value = menu.url;

                    // Change title
                    document.getElementById('formTitle').textContent = 'Edit Menu';
                    document.getElementById('submitBtn').innerHTML = '<i class="ri-edit-line"></i> Update Menu';

                    // Show form
                    toggleForm();
                }
            },
            error: function() {
                alert('Gagal memuat data menu');
            }
        });
    }

    $('#menuForm').on('submit', function(e) {
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
        const menuId = document.getElementById('menuId').value;

        // Hide alerts
        successAlert.classList.add('hidden');
        errorAlert.classList.add('hidden');

        // Determine URL dan method
        let url = '{{ route("menu.store") }}';
        let method = 'POST';

        if (menuId) {
            url = '{{ route("menu.update", ":id") }}'.replace(':id', menuId);
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
                    document.getElementById('menuId').value = '';
                    currentMenuId = null;

                    // Refresh datatable
                    if ($.fn.DataTable.isDataTable('#menuTable')) {
                        $('#menuTable').DataTable().ajax.reload();
                    }

                    // Auto close form after 2 seconds
                    setTimeout(() => {
                        toggleForm();
                        document.getElementById('formTitle').textContent = 'Add New Menu';
                        document.getElementById('submitBtn').innerHTML = '<i class="ri-save-line"></i> Save Menu';
                    }, 1000);
                } else {
                    errorMessage.textContent = data.message || 'Gagal menyimpan menu';
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