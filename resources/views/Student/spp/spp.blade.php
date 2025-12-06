@extends('layouts.app')

@section('section')
<div class="min-h-screen bg-gradient-to-br from-base-200 via-base-100 to-base-200 p-4 md:p-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-4xl font-bold text-base-content mb-2">
                        <span class="text-primary">Pembayaran SPP</span>
                    </h1>
                    <p class="text-base-content/70 text-lg">Kelola dan lihat status pembayaran SPP Anda</p>
                </div>
            </div>
        </div>

        <!-- Filter & Status Summary -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <!-- Belum Bayar -->
            <div class="card bg-base-100 shadow-md border-l-4 border-error">
                <div class="card-body py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-base-content/70 text-sm font-medium">Belum Bayar</p>
                            <p id="count-unpaid" class="text-3xl font-bold text-error mt-1">0</p>
                        </div>
                        <div class="bg-error/10 p-3 rounded-lg">
                            <i class="ri-close-circle-line ri-lg text-error"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sudah Bayar -->
            <div class="card bg-base-100 shadow-md border-l-4 border-success">
                <div class="card-body py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-base-content/70 text-sm font-medium">Sudah Bayar</p>
                            <p id="count-paid" class="text-3xl font-bold text-success mt-1">0</p>
                        </div>
                        <div class="bg-success/10 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-success" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Menunggu Konfirmasi -->
            <div class="card bg-base-100 shadow-md border-l-4 border-warning">
                <div class="card-body py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-base-content/70 text-sm font-medium">Menunggu Konfirmasi</p>
                            <p id="count-pending" class="text-3xl font-bold text-warning mt-1">0</p>
                        </div>
                        <div class="bg-warning/10 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-warning" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SPP Cards Grid -->
        <div id="spp-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Loading State -->
            <div class="col-span-full flex justify-center items-center py-12">
                <span class="loading loading-spinner loading-lg text-primary"></span>
            </div>
        </div>

        <!-- Empty State -->
        <div id="empty-state" class="hidden col-span-full">
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-base-content/30 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-xl font-semibold text-base-content mb-2">Tidak ada data SPP</h3>
                <p class="text-base-content/70">Data SPP Anda tidak ditemukan di sistem</p>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail SPP -->
<dialog id="detail-modal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-primary" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 1 1 0 100-2 4 4 0 00-4 4v10a4 4 0 004 4h12a4 4 0 004-4V5a4 4 0 00-4-4 1 1 0 100 2 2 2 0 012 2v10a2 2 0 01-2 2H6a2 2 0 01-2-2V5z" clip-rule="evenodd"></path>
            </svg>
            Detail Pembayaran SPP
        </h3>

        <div id="detail-content" class="space-y-4">
            <!-- Detail akan di-inject oleh JavaScript -->
        </div>

        <div class="modal-action mt-6">
            <button class="btn btn-ghost" onclick="document.getElementById('detail-modal').close()">Tutup</button>
            <button id="download-btn" class="btn btn-error text-white gap-2 hidden" onclick="downloadPdf()">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
                Download PDF
            </button>
            <button id="pay-btn" class="btn btn-primary gap-2" onclick="confirmPayment()">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"></path>
                </svg>
                Bayar Sekarang
            </button>
        </div>
    </div>
</dialog>

<!-- Confirmation Modal untuk Payment -->
<dialog id="payment-confirm-modal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4 flex items-center gap-2">
            <svg class="w-6 h-6 text-warning" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            Konfirmasi Pembayaran
        </h3>

        <div class="space-y-4">
            <p class="text-base-content/80">Anda akan melakukan pembayaran dengan detail:</p>

            <div class="bg-base-200 p-4 rounded-lg space-y-2">
                <div class="flex justify-between">
                    <span class="text-base-content/70">Nominal:</span>
                    <span id="confirm-price" class="font-bold text-lg text-primary">Rp 0</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-base-content/70">Bulan:</span>
                    <span id="confirm-month" class="font-semibold text-base-content">-</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-base-content/70">Status:</span>
                    <span id="confirm-status" class="font-semibold text-base-content">-</span>
                </div>
            </div>

            <div class="alert alert-info">
                <svg class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Anda akan diarahkan ke halaman pembayaran Midtrans</span>
            </div>
        </div>

        <div class="modal-action mt-6">
            <button class="btn btn-ghost" onclick="document.getElementById('payment-confirm-modal').close()">Batal</button>
            <button id="confirm-pay-btn" class="btn btn-primary gap-2" onclick="processPayment()">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"></path>
                </svg>
                Lanjut ke Pembayaran
            </button>
        </div>
    </div>
</dialog>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        const studentId = {{auth('student')->user()->id ?? 'null'}};
        let sppStudentId = 0;

        if (!studentId) {
            window.location.href = "{{ route('dashboard.student.spp') }}";
            return;
        }

        loadSppData();

        function loadSppData() {
            $.ajax({
                url: `/student/spp/all/${studentId}`,
                type: 'GET',
                headers: {
                    'Accept': 'application/json'
                },
                success: function(response) {
                    if (Array.isArray(response) && response.length > 0) {
                        renderSppCards(response);
                        $('#spp-container').find('.loading').remove();
                        $('#empty-state').addClass('hidden');
                    } else {
                        $('#spp-container').html(`
                            <div class="col-span-full">
                                <div class="text-center py-12">
                                    <svg class="w-16 h-16 text-base-content/30 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <h3 class="text-xl font-semibold text-base-content mb-2">Tidak ada data SPP</h3>
                                    <p class="text-base-content/70">Data SPP Anda tidak ditemukan di sistem</p>
                                </div>
                            </div>
                        `);
                    }
                },
                error: function(xhr) {
                    $('#spp-container').html(`
                        <div class="col-span-full">
                            <div class="alert alert-error">
                                <svg class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l-2-2m0 0l-2-2m2 2l2-2m-2 2l-2 2m2-2l2 2m-2-2l-2-2"></path>
                                </svg>
                                <span>Gagal memuat data SPP. Silakan refresh halaman.</span>
                            </div>
                        </div>
                    `);
                }
            });
        }

        function renderSppCards(data) {
            let html = '';
            let paidCount = 0;
            let unpaidCount = 0;
            let pendingCount = 0;

            data.forEach((item, index) => {
                const status = item.status.toLowerCase();
                const paymentStatus = item.status_payment ? item.status_payment.toLowerCase() : ''

                const displayStatus = (paymentStatus === 'pending') ? 'pending' : status

                if (status === 'paid' && paymentStatus === 'completed') paidCount++;
                else if (status === 'unpaid' && paymentStatus === '') unpaidCount++;
                else if (status === 'unpaid' && paymentStatus === 'pending') pendingCount++;

                html += `
                    <div class="card bg-base-100 shadow-lg hover:shadow-xl transition-all duration-300 border border-base-300">
                        <div class="card-body">
                            <!-- Header with Status -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h2 class="card-title text-lg text-base-content">
                                        Bulan ${formatMonth(item.date_month)}
                                    </h2>
                                    <p class="text-sm text-base-content/60">
                                        Semester ${item.semester}
                                    </p>
                                </div>
                                <div class="${getStatusBadge(displayStatus)}">
                                    ${getStatusIcon(displayStatus)}
                                    <span class="text-xs font-semibold">${formatStatus(displayStatus)}</span>
                                </div>
                            </div>

                            <!-- Divider -->
                            <div class="divider my-2"></div>

                            <!-- Details -->
                            <div class="space-y-3 mb-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-base-content/70 text-sm">Nama Siswa:</span>
                                    <span class="font-semibold text-base-content">${item.name}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-base-content/70 text-sm">Nominal:</span>
                                    <span class="font-bold text-lg text-primary">Rp ${formatCurrency(item.price)}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-base-content/70 text-sm">Bulan Pembayaran:</span>
                                    <span class="text-sm text-base-content">${new Date(item.date_month).toLocaleDateString('id-ID', { year: 'numeric', month: 'long' })}</span>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <div class="card-actions justify-end mt-4">
                                <button class="btn btn-sm btn-primary btn-outline gap-2" onclick="showDetail('${encodeURIComponent(JSON.stringify(item))}', ${item.id})">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Lihat Detail
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });

            $('#spp-container').html(html);
            $('#total-spp').text(data.length);
            $('#count-paid').text(paidCount);
            $('#count-unpaid').text(unpaidCount);
            $('#count-pending').text(pendingCount);
        }

        function getStatusBadge(status) {
            const badges = {
                'paid': 'badge badge-success gap-2 text-white',
                'unpaid': 'badge badge-error gap-2 text-white',
                'pending': 'badge badge-warning gap-2 text-white'
            };
            return badges[status] || 'badge gap-2';
        }

        function getStatusIcon(status) {
            const icons = {
                'paid': '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>',
                'unpaid': '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>',
                'pending': '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>'
            };
            return icons[status] || '';
        }

        window.showDetail = function(jsonData, itemId) {
            try {
                const item = JSON.parse(decodeURIComponent(jsonData));
                const status = item.status.toLowerCase()
                const paymentStatus = item.status_payment ? item.status_payment.toLowerCase() : ''

                const displayStatus = (paymentStatus === 'pending') ? 'pending' : status

                // Store current item data dan ID untuk payment confirmation
                window.currentSppData = item;
                window.currentSppStudentTrackingId = itemId;

                // Check jika sudah ada payment atau status sudah paid
                let showPayButton = false;
                let paymentStatusMessage = '';

                if (status === 'unpaid') {
                    showPayButton = true;
                    paymentStatusMessage = '';
                } else if (status === 'pending') {
                    showPayButton = true;
                    paymentStatusMessage = '<div class="alert alert-warning mt-4 text-sm"><svg class="stroke-current shrink-0 h-5 w-5" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg><span>Pembayaran Anda masih menunggu konfirmasi. Klik tombol di bawah untuk melanjutkan pembayaran jika belum selesai.</span></div>';
                } else if (status === 'paid') {
                    showPayButton = false;
                    paymentStatusMessage = '';
                }

                const detailHtml = `
                    <div class="space-y-4">
                        <div class="bg-base-200 p-4 rounded-lg">
                            <p class="text-sm text-base-content/70">Nama Siswa</p>
                            <p class="text-lg font-semibold text-base-content">${item.name}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-base-200 p-4 rounded-lg">
                                <p class="text-sm text-base-content/70">Semester</p>
                                <p class="text-lg font-semibold text-primary">${item.semester}</p>
                            </div>
                            <div class="bg-base-200 p-4 rounded-lg">
                                <p class="text-sm text-base-content/70">Bulan</p>
                                <p class="text-lg font-semibold text-base-content">${formatMonth(item.date_month)}</p>
                            </div>
                        </div>

                        <div class="bg-primary/10 p-4 rounded-lg border border-primary">
                            <p class="text-sm text-base-content/70">Nominal Pembayaran</p>
                            <p class="text-2xl font-bold text-primary">Rp ${formatCurrency(item.price)}</p>
                        </div>

                        <div class="bg-base-200 p-4 rounded-lg">
                            <p class="text-sm text-base-content/70 mb-2">Status Pembayaran</p>
                            <div class="${getStatusBadge(displayStatus)} inline-flex">
                                ${getStatusIcon(displayStatus)}
                                <span class="font-semibold">${formatStatus(displayStatus)}</span>
                            </div>
                        </div>

                        ${displayStatus === 'unpaid' ? `
                            <div class="alert alert-info">
                                <svg class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Silakan lakukan pembayaran sebelum jatuh tempo</span>
                            </div>
                        ` : ''}

                        ${displayStatus === 'pending' ? `
                            <div class="alert alert-warning">
                                <svg class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Pembayaran Anda Berstatus Pending. Silahkan Lanjutkan Pembayaran</span>
                            </div>
                        ` : ''}

                        ${displayStatus === 'paid' ? `
                            <div class="alert alert-success">
                                <svg class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Pembayaran berhasil!</span>
                            </div>
                        ` : ''}

                        ${paymentStatusMessage}
                    </div>
                `;

                // Set pay button visibility based on status
                if (showPayButton) {
                    $('#pay-btn').show();
                    $('#download-btn').addClass('hidden');
                } else {
                    $('#pay-btn').hide();
                }

                // Show download button if paid
                if (status === 'paid') {
                    $('#download-btn').removeClass('hidden');
                } else {
                    $('#download-btn').addClass('hidden');
                }

                $('#detail-content').html(detailHtml);
                document.getElementById('detail-modal').showModal();
            } catch (error) {
                console.error('Error showing detail:', error);
            }
        };

        window.confirmPayment = function() {
            if (!window.currentSppData) {
                alert('Data SPP tidak ditemukan');
                return;
            }

            const item = window.currentSppData;

            // Populate confirmation modal
            $('#confirm-price').text('Rp ' + formatCurrency(item.price));
            $('#confirm-month').text(formatMonth(item.date_month));
            $('#confirm-status').text(formatStatus(item.status.toLowerCase()));

            // Close detail modal dan buka confirmation modal
            document.getElementById('detail-modal').close();
            document.getElementById('payment-confirm-modal').showModal();
        };

        window.processPayment = function() {
            if (!window.currentSppData) {
                alert('Data SPP tidak ditemukan');
                return;
            }

            if (!window.currentSppStudentTrackingId) {
                alert('ID SPP tidak ditemukan');
                return;
            }

            const confirmBtn = $('#confirm-pay-btn');

            // Show loading state
            confirmBtn.prop('disabled', true);
            confirmBtn.html(`
                <span class="flex items-center justify-center gap-2">
                    <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Memproses...
                </span>
            `);

            $.ajax({
                url: `/student/payment/${window.currentSppStudentTrackingId}`,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                },
                success: function(response) {
                    // Jika sudah dibayar, redirect ke success page
                    if (response.is_already_paid) {
                        document.getElementById('payment-confirm-modal').close();
                        alert(response.message);
                        if (response.payment_id) {
                            window.location.href = `/student/payment/success/${response.payment_id}`;
                        } else {
                            window.location.href = '{{ route("dashboard.student.spp") }}';
                        }
                        return;
                    }

                    // Jika ada pembayaran pending, tunjukkan pesan dan lanjut ke pembayaran
                    if (response.is_pending) {
                        alert(response.message + ' - Anda akan dilanjutkan ke halaman pembayaran');
                    }

                    if (response && response.snap_redirect_url) {
                        document.getElementById('payment-confirm-modal').close();
                        snap.pay(response.token, {
                            onSuccess: function(result) {
                                window.location.href = `{{ route('payment.finish') }}?order_id=${result.order_id}&transaction_status=settlement&status_code=200`;
                            },
                            onPending: function(result) {
                                console.log('Payment pending:', result);
                                alert('Pembayaran masih dalam proses');
                                document.getElementById('payment-confirm-modal').close();
                            },
                            onError: function(result) {
                                console.log('Payment failed:', result);
                                alert('Pembayaran gagal!');
                                confirmBtn.prop('disabled', false);
                                confirmBtn.html(`
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"></path>
                                    </svg>
                                    Lanjut ke Pembayaran
                                `);
                            },
                            onClose: function() {
                                console.log('Customer closed the popup');
                                confirmBtn.prop('disabled', false);
                                confirmBtn.html(`
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"></path>
                                    </svg>
                                    Lanjut ke Pembayaran
                                `);
                            }
                        });
                    }
                },
                error: function(xhr) {
                    console.error('Payment error:', xhr);
                    alert(xhr.responseJSON?.message || 'Terjadi kesalahan saat memproses pembayaran');
                    confirmBtn.prop('disabled', false);
                    confirmBtn.html(`
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"></path>
                        </svg>
                        Lanjut ke Pembayaran
                    `);
                }
            });
        };

        function formatStatus(status) {
            const statuses = {
                'paid': 'Sudah Bayar',
                'unpaid': 'Belum Bayar',
                'pending': 'Menunggu Konfirmasi'
            };
            return statuses[status] || status;
        }

        window.downloadPdf = function() {
            if (!window.currentSppData) {
                alert('Data SPP tidak ditemukan');
                return;
            }

            if (!window.currentSppStudentTrackingId) {
                alert('ID SPP tidak ditemukan');
                return;
            }

            // Download PDF dengan tracking ID
            const url = `/student/payment/invoice/${window.currentSppStudentTrackingId}/pdf`;
            window.location.href = url;
        };

        function formatMonth(dateString) {
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];
            const date = new Date(dateString);
            return months[date.getMonth()];
        }

        function formatCurrency(value) {
            return new Intl.NumberFormat('id-ID').format(value);
        }
    });
</script>
@endsection