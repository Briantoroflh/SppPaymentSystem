<div class="overflow-x-auto">
    <table class="table table-zebra" id="{{ $name }}Table">
        <!-- head -->
        <thead>
            <tr>
                @if($columns)
                @foreach($columns as $column)
                <th>{{ $column['key'] }}</th>
                @endforeach
                @endif
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<!-- Delete Confirmation Modal -->
<dialog id="delete{{ ucfirst($name) }}Modal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg text-base-content mb-4">Konfirmasi Hapus</h3>
        <p class="py-2 text-base-content/80">Apakah Anda yakin ingin menghapus <span id="delete{{ ucfirst($name) }}Name" class="font-semibold text-primary"></span>?</p>
        <p class="text-sm text-base-content/60">Tindakan ini tidak dapat dibatalkan.</p>

        <div class="modal-action">
            <form method="dialog" class="flex gap-3">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('delete{{ ucfirst($name) }}Modal').close()">Batal</button>
                <button type="button" class="btn btn-error gap-2" id="confirmDelete{{ ucfirst($name) }}Btn" onclick="confirmDelete{{ ucfirst($name) }}()">
                    <i class="ri-delete-bin-line"></i> Hapus
                </button>
            </form>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<!-- Toast Container -->
<div id="toastContainer" class="toast toast-top toast-end z-50"></div>



@push('additional-js')
<script text="text/javascript">
    let delete{{ucfirst($name)}}Id = null;

    $(document).ready(function() {
        $('#{{ $name }}Table').DataTable({
            processing: true,
            serverSide: true,
            searching: {!!$searching!!},
            ordering: {!!$ordering!!},
            ajax: {
                url: '{{ $url }}',
                method: '{{ $method }}',
                data: function(d) {
                    return d;
                }
            },
            columns: [
                @foreach($columns as $column) {
                    data: '{{ $column["value"] }}',
                    name: '{{ $column["value"] }}'
                },
                @endforeach {
                    data: null,
                    render: function(data, type, row) {
                        return '<div class="flex gap-2"><button class="btn btn-sm btn-info" onclick="edit{{ ucfirst($name) }}(' + row.id + ')" data-id="' + row.id + '">Edit</button><button class="btn btn-sm btn-error" onclick="openDelete{{ ucfirst($name) }}Modal(' + row.id + ', \'' + (row.title || row.name) + '\')">Delete</button></div>';
                    }
                }
            ],
            columnDefs: [{
                targets: '_all',
                className: 'dt-left'
            }]
        });
    });

    function openDelete{{ucfirst($name)}}Modal(id, name) {
        delete{{ucfirst($name)}}Id = id;
        document.getElementById('delete{{ ucfirst($name) }}Name').textContent = name;
        document.getElementById('delete{{ ucfirst($name) }}Modal').showModal();
    }

    function confirmDelete{{ucfirst($name)}}() {
        const confirmBtn = document.getElementById('confirmDelete{{ ucfirst($name) }}Btn');
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        confirmBtn.disabled = true;
        confirmBtn.innerHTML = '<i class="ri-loader-4-line animate-spin"></i> Menghapus...';

        $.ajax({
            url: '{{ route("{$name}.destroy", ":id") }}'.replace(':id', delete{{ucfirst($name)}}Id),
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            },
            success: function(data) {
                if (data.success) {
                    showToast(data.message, 'success');
                    document.getElementById('delete{{ ucfirst($name) }}Modal').close();

                    // Refresh datatable
                    if ($.fn.DataTable.isDataTable('#{{ $name }}Table')) {
                        $('#{{ $name }}Table').DataTable().ajax.reload();
                    }

                    delete{{ucfirst($name)}}Id = null;
                } else {
                    showToast('Gagal menghapus {{ $name }}', 'error');
                }
            },
            error: function(xhr) {
                showToast('Terjadi kesalahan saat menghapus', 'error');
                document.getElementById('delete{{ ucfirst($name) }}Modal').close();
            },
            complete: function() {
                confirmBtn.disabled = false;
                confirmBtn.innerHTML = '<i class="ri-delete-bin-line"></i> Hapus';
            }
        });
    }

    function showToast(message, type = 'info') {
        const toastContainer = document.getElementById('toastContainer');

        let alertClass = 'alert-info';
        let icon = 'ri-information-line';

        if (type === 'success') {
            alertClass = 'alert-success';
            icon = 'ri-check-circle-line';
        } else if (type === 'error') {
            alertClass = 'alert-error';
            icon = 'ri-error-warning-line';
        }

        const toast = document.createElement('div');
        toast.className = `alert ${alertClass} gap-2 shadow-lg animate-fade-in`;
        toast.innerHTML = `
            <i class="${icon}"></i>
            <span>${message}</span>
        `;

        toastContainer.appendChild(toast);

        // Auto remove after 3 seconds
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }
</script>
@endpush