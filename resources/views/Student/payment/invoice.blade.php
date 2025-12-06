<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice SPP - {{ $student->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            line-height: 1.6;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 40px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header-left h1 {
            color: #2563eb;
            font-size: 28px;
            margin-bottom: 5px;
        }

        .header-left p {
            color: #666;
            font-size: 14px;
        }

        .invoice-number {
            text-align: right;
            color: #666;
        }

        .invoice-number-label {
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        .info-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 40px;
        }

        .info-block {
            border-left: 4px solid #2563eb;
            padding-left: 15px;
        }

        .info-block-title {
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 10px;
            font-size: 13px;
            text-transform: uppercase;
        }

        .info-block-content {
            color: #333;
            line-height: 1.8;
        }

        .info-block-content p {
            margin-bottom: 5px;
            font-size: 14px;
        }

        .info-block-content strong {
            font-weight: 600;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .details-table thead {
            background-color: #2563eb;
            color: white;
        }

        .details-table th {
            padding: 12px;
            text-align: left;
            font-weight: 600;
            border: 1px solid #ddd;
        }

        .details-table td {
            padding: 12px;
            border: 1px solid #ddd;
            font-size: 14px;
        }

        .details-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .details-table tbody tr:hover {
            background-color: #f0f0f0;
        }

        .text-right {
            text-align: right;
        }

        .amount {
            font-weight: 600;
            font-size: 15px;
        }

        .summary {
            margin-left: auto;
            width: 300px;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .summary-row label {
            font-weight: 500;
            color: #666;
        }

        .summary-row.total {
            border-top: 2px solid #2563eb;
            padding-top: 10px;
            margin-top: 10px;
            font-size: 16px;
        }

        .summary-row.total label {
            color: #2563eb;
            font-weight: 700;
        }

        .summary-row.total .value {
            color: #2563eb;
            font-weight: 700;
            font-size: 18px;
        }

        .status-badge {
            text-transform: uppercase;
            color: #10b981;
        }

        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #999;
            font-size: 12px;
        }

        .payment-info {
            background-color: #e8f5e9;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #10b981;
        }

        .payment-info-title {
            font-weight: 600;
            color: #2e7d32;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .payment-info-content {
            font-size: 13px;
            color: #1b5e20;
            line-height: 1.6;
        }

        .payment-info-content p {
            margin-bottom: 4px;
        }

        @media print {
            body {
                background-color: white;
            }

            .container {
                box-shadow: none;
                max-width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-left">
                <h1>BUKTI PEMBAYARAN SPP</h1>
                <p>Sistem Pembayaran SPP</p>
            </div>
            <div class="invoice-number">
                <div class="invoice-number-label">Nomor Transaksi</div>
                <div style="font-size: 16px; font-weight: bold; color: #2563eb;">{{ $payment->payment_id }}</div>
            </div>
        </div>

        <!-- Info Sections -->
        <div class="info-section">
            <div class="info-block">
                <div class="info-block-title">Informasi Siswa</div>
                <div class="info-block-content">
                    <p><strong>Nama:</strong> {{ $student->name }}</p>
                    <p><strong>NISN:</strong> {{ $student->nisn }}</p>
                    <p><strong>Kelas:</strong> {{ $class->name }}</p>
                    <p><strong>Program Studi:</strong> {{ $major->name }}</p>
                </div>
            </div>

            <div class="info-block">
                <div class="info-block-title">Informasi Pembayaran</div>
                <div class="info-block-content">
                    <p><strong>Tanggal Pembayaran:</strong> {{ \Carbon\Carbon::now()->format('d-m-Y H:i') }}</p>
                    <p><strong>Metode Pembayaran:</strong> {{ ucfirst($payment->payment_method) }}</p>
                    <p><strong>Status:</strong> <span class="status-badge">{{ $payment->status_payment }}</span></p>
                </div>
            </div>
        </div>

        <!-- Payment Status -->
        <div class="payment-info">
            <div class="payment-info-title">Pembayaran Berhasil</div>
            <div class="payment-info-content">
                <p>Pembayaran SPP untuk bulan <strong>{{ $month }}</strong> telah diterima dan dikonfirmasi oleh sistem.</p>
                <p>Status akademik Anda telah diperbarui dalam sistem.</p>
            </div>
        </div>

        <!-- Details Table -->
        <table class="details-table">
            <thead>
                <tr>
                    <th>Deskripsi</th>
                    <th>Periode</th>
                    <th class="text-right">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Biaya Pendidikan (SPP)</td>
                    <td>{{ $month }} {{ $tracking->date_month->year }}</td>
                    <td class="text-right amount">Rp {{ $price }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Summary -->
        <div class="summary">
            <div class="summary-row">
                <label>Subtotal:</label>
                <span>Rp {{ $price }}</span>
            </div>
            <div class="summary-row">
                <label>Diskon:</label>
                <span>Rp 0</span>
            </div>
            <div class="summary-row">
                <label>Pajak:</label>
                <span>Rp 0</span>
            </div>
            <div class="summary-row total">
                <label>Total Pembayaran:</label>
                <span class="value">Rp {{ $price }}</span>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Bukti pembayaran ini adalah dokumen resmi dan berlaku sebagai tanda terima.</p>
            <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d-m-Y H:i:s') }}</p>
            <p style="margin-top: 10px;">Terima kasih atas pembayaran Anda</p>
        </div>
    </div>
</body>

</html>