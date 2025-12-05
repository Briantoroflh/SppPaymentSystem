@extends('layouts.app')

@section('section')
<div class="min-h-screen bg-gradient-to-br from-base-200 via-base-100 to-base-200 p-4 md:p-8 flex items-center justify-center">
    <div class="w-full max-w-md">
        <!-- Success Card -->
        <div class="card bg-base-100 shadow-2xl border-t-4 border-success">
            <div class="card-body items-center text-center">
                <!-- Success Icon -->
                <div class="mb-6">
                    <div class="w-24 h-24 bg-success/10 rounded-full flex items-center justify-center mx-auto mb-4 animate-bounce">
                        <svg class="w-12 h-12 text-success" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>

                <!-- Success Title -->
                <h1 class="text-3xl font-bold text-success mb-2">
                    Pembayaran Berhasil!
                </h1>
                <p class="text-base-content/70 mb-6">
                    Transaksi SPP Anda telah berhasil diproses
                </p>

                <!-- Payment Details -->
                <div class="w-full space-y-4 mb-6">
                    <!-- Payment ID -->
                    <div class="bg-base-200 p-4 rounded-lg text-left">
                        <p class="text-sm text-base-content/70 font-medium mb-1">ID Transaksi</p>
                        <p class="text-lg font-bold text-base-content break-all">{{ $payment->payment_id }}</p>
                    </div>

                    <!-- Student Name -->
                    <div class="bg-base-200 p-4 rounded-lg text-left">
                        <p class="text-sm text-base-content/70 font-medium mb-1">Nama Siswa</p>
                        <p class="text-lg font-bold text-base-content">{{ $payment->studentSpp->studentClass->student->name }}</p>
                    </div>

                    <!-- Amount -->
                    <div class="bg-success/10 p-4 rounded-lg text-left border border-success">
                        <p class="text-sm text-base-content/70 font-medium mb-1">Nominal Pembayaran</p>
                        <p class="text-2xl font-bold text-success">Rp {{ number_format($payment->total_price, 0, ',', '.') }}</p>
                    </div>

                    <!-- Date -->
                    <div class="bg-base-200 p-4 rounded-lg text-left">
                        <p class="text-sm text-base-content/70 font-medium mb-1">Tanggal Pembayaran</p>
                        <p class="text-lg font-bold text-base-content">
                            {{ $payment->created_at->format('d F Y H:i:s') }}
                        </p>
                    </div>

                    <!-- Status -->
                    <div class="bg-base-200 p-4 rounded-lg text-left">
                        <p class="text-sm text-base-content/70 font-medium mb-1">Status Pembayaran</p>
                        <div class="badge badge-success gap-2 text-white">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Berhasil
                        </div>
                    </div>
                </div>

                <!-- Info Alert -->
                <div class="alert alert-info w-full">
                    <svg class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-sm">Status SPP Anda telah diperbarui menjadi "Sudah Bayar"</span>
                </div>

                <!-- Action Buttons -->
                <div class="card-actions w-full gap-3 mt-8">
                    <a href="{{ route('dashboard.student.spp') }}" class="btn btn-primary flex-1 gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                        Kembali ke SPP
                    </a>
                    <a href="{{ route('dashboard.student.index') }}" class="btn btn-outline flex-1 gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.5 1.5H5a3.5 3.5 0 00-3.5 3.5v7A3.5 3.5 0 005 18.5h10a3.5 3.5 0 003.5-3.5V5a3.5 3.5 0 00-3.5-3.5zM9 16H8a1 1 0 110-2h1a1 1 0 110 2zm0-4H8a1 1 0 110-2h1a1 1 0 110 2zm0-4H8a1 1 0 110-2h1a1 1 0 110 2zm3 8h-1a1 1 0 110-2h1a1 1 0 110 2zm0-4h-1a1 1 0 110-2h1a1 1 0 110 2zm0-4h-1a1 1 0 110-2h1a1 1 0 110 2z"></path>
                        </svg>
                        Dashboard
                    </a>
                </div>

                <!-- Thank You Message -->
                <div class="mt-8 pt-6 border-t border-base-300">
                    <p class="text-sm text-base-content/60">
                        Terima kasih telah melakukan pembayaran SPP. Jika ada pertanyaan, silakan hubungi admin sekolah.
                    </p>
                </div>
            </div>
        </div>

        <!-- Footer Info -->
        <div class="text-center mt-6">
            <p class="text-sm text-base-content/50">
                Bukti pembayaran ini dapat diakses kembali melalui halaman riwayat pembayaran
            </p>
        </div>
    </div>
</div>

<style>
    @keyframes bounce {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-10px);
        }
    }

    .animate-bounce {
        animation: bounce 2s infinite;
    }
</style>
@endsection