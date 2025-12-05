<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\StudentSpp;
use App\Models\StudentSppTracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\Snap;

use function Symfony\Component\Clock\now;

class PaymentController extends Controller
{
    public function __construct()
    {
        \Midtrans\Config::$serverKey = config('app.midtrans_key');
        \Midtrans\Config::$isProduction = config('app.midtrans_prod');
        \Midtrans\Config::$isSanitized = config('app.midtrans_sanitized');
        \Midtrans\Config::$is3ds = config('app.midtrans_3ds');
    }

    public function store(Request $request, $studentSppTrackingId)
    {
        try {
            $studentId = Auth::guard('student')->user()->id;

            // Ambil StudentSppTracking berdasarkan ID dan validasi milik student yang login
            // Parameter: $studentSppTrackingId adalah ID dari student_spp_trackings table
            $studentSppTracking = StudentSppTracking::join('student_spps', 'student_spp_trackings.student_spp_id', '=', 'student_spps.id')
                ->join('student_classes', 'student_spps.student_class_id', '=', 'student_classes.id')
                ->where('student_spp_trackings.id', $studentSppTrackingId)
                ->where('student_classes.student_id', $studentId)
                ->select('student_spp_trackings.*', 'student_spps.price', 'student_spps.id as student_spp_id')
                ->first();

            if (!$studentSppTracking) {
                return response()->json([
                    'success' => false,
                    'message' => 'SPP tidak ditemukan atau Anda tidak memiliki akses!'
                ], 404);
            }

            // Cek jika sudah dibayar berdasarkan status StudentSppTracking
            if ($studentSppTracking->status === 'paid') {
                // Jika sudah dibayar, cari payment untuk redirect ke success page
                $payment = Payment::where('student_spp_id', $studentSppTracking->student_spp_id)
                    ->where('status_payment', 'completed')
                    ->orderByDesc('id')
                    ->first();

                return response()->json([
                    'success' => true,
                    'is_already_paid' => true,
                    'message' => 'SPP bulan ini sudah dibayar',
                    'payment_id' => $payment ? $payment->id : null
                ]);
            }

            // Cek apakah ada payment pending untuk StudentSppTracking ini
            // IMPORTANT: Cari berdasarkan student_spp_id dan status, bukan tracking ID
            // Karena satu StudentSpp bisa punya banyak StudentSppTracking (bulan-bulan berbeda)
            $existingPayment = Payment::where('student_spp_id', $studentSppTracking->student_spp_id)
                ->where('status_payment', 'pending')
                ->latest()
                ->first();

            if ($existingPayment) {
                // Jika ada payment pending dari StudentSpp ini, gunakan ulang
                try {
                    // Generate snap token untuk payment yang sudah ada
                    $snapToken = Snap::getSnapToken([
                        'transaction_details' => [
                            'order_id' => $existingPayment->payment_id,
                            'gross_amount' => $existingPayment->total_price,
                        ],
                        'customer_details' => [
                            'first_name' => Auth::guard('student')->user()->name,
                            'last_name' => "",
                            'email' => "student@example.com",
                            'phone' => ""
                        ]
                    ]);

                    return response()->json([
                        'success' => true,
                        'is_pending' => true,
                        'message' => 'Anda memiliki pembayaran yang masih pending',
                        'payment_id' => $existingPayment->payment_id,
                        'token' => $snapToken,
                        'snap_redirect_url' => 'https://app.sandbox.midtrans.com/snap/v2/vtweb/' . $snapToken
                    ]);
                } catch (\Exception $e) {
                    // Jika error (mungkin order_id sudah expired), buat payment baru
                    $existingPayment->delete();
                }
            }

            // Buat payment record baru dengan unique order_id
            $bill = Payment::create([
                'payment_id' => 'SPP' . now()->getTimestamp() . rand(100, 999),
                'student_spp_id' => $studentSppTracking->student_spp_id,
                'student_spp_tracking_id' => $studentSppTracking->id,
                'total_price' => $studentSppTracking->price,
                'payment_method' => 'midtrans',
                'status_payment' => 'pending'
            ]);

            // Prepare Midtrans transaction
            $transaction_details = array(
                'order_id' => $bill->payment_id,
                'gross_amount' => $bill->total_price,
            );

            $customer_details = array(
                'first_name' => Auth::guard('student')->user()->name,
                'last_name' => "",
                'email' => "student@example.com",
                'phone' => ""
            );

            $transaction = array(
                'transaction_details' => $transaction_details,
                'customer_details' => $customer_details
            );

            // Get Snap token dari Midtrans
            $snapToken = Snap::getSnapToken($transaction);

            return response()->json([
                'success' => true,
                'message' => 'Redirect ke pembayaran',
                'token' => $snapToken,
                'snap_redirect_url' => 'https://app.sandbox.midtrans.com/snap/v2/vtweb/' . $snapToken
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function finish(Request $request)
    {
        try {
            $orderId = $request->query('order_id');
            $statusCode = $request->query('status_code');
            $transactionStatus = $request->query('transaction_status');

            // Validasi parameter
            if (!$orderId || !$transactionStatus) {
                return redirect()->route('dashboard.student.spp')
                    ->with('error', 'Parameter tidak lengkap');
            }

            // Cari payment berdasarkan payment_id
            $payment = Payment::with('studentSpp.studentClass.student')
                ->where('payment_id', $orderId)
                ->first();

            if (!$payment) {
                return redirect()->route('dashboard.student.spp')
                    ->with('error', 'Pembayaran tidak ditemukan');
            }

            // Update payment status berdasarkan transaction status dari Midtrans
            if ($transactionStatus === 'settlement' || $transactionStatus === 'capture') {
                // Pembayaran berhasil - UPDATE status payment
                $payment->update(['status_payment' => 'completed']);

                // Update StudentSppTracking status menjadi 'paid' berdasarkan tracking ID yang ada di payment
                if ($payment->student_spp_tracking_id) {
                    StudentSppTracking::where('id', $payment->student_spp_tracking_id)
                        ->update(['status' => 'paid']);
                }

                // Redirect ke halaman success dengan payment_id
                return redirect()->route('payment.success', ['payment_id' => $payment->id]);
            } elseif ($transactionStatus === 'pending') {
                // Pembayaran masih pending - jangan ubah status, tetap pending
                $payment->update(['status_payment' => 'pending']);

                return redirect()->route('dashboard.student.spp')
                    ->with('warning', 'Pembayaran masih dalam proses verifikasi');
            } else {
                // Pembayaran gagal atau dibatalkan
                $payment->update(['status_payment' => 'failed']);

                return redirect()->route('dashboard.student.spp')
                    ->with('error', 'Pembayaran gagal atau dibatalkan');
            }
        } catch (\Exception $e) {
            return redirect()->route('dashboard.student.spp')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function success($paymentId)
    {
        try {
            $payment = Payment::with('studentSpp.studentClass.student')
                ->where('id', $paymentId)
                ->first();

            if (!$payment) {
                return redirect()->route('dashboard.student.spp')
                    ->with('error', 'Pembayaran tidak ditemukan');
            }

            // Validasi bahwa payment milik student yang login
            $studentId = Auth::guard('student')->user()->id;
            if ($payment->studentSpp->studentClass->student->id !== $studentId) {
                return redirect()->route('dashboard.student.spp')
                    ->with('error', 'Akses ditolak');
            }

            return view('Student.payment.success', compact('payment'));
        } catch (\Exception $e) {
            return redirect()->route('dashboard.student.spp')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
