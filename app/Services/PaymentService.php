<?php
declare(strict_types=1);

namespace App\Services;

class PaymentService
{
    /**
     * Verify payment signature or gateway callback
     */
    public function verifyPayment(string $gateway, array $payload): array
    {
        // COD requires no online payment verification
        if ($gateway === 'cod') {
            return [
                'verified' => true,
                'status' => 'Pending',
                'reference' => 'COD-' . time(),
            ];
        }

        // Online payment gateway
        // For production, integrates with Razorpay / Cashfree / Stripe
        $paymentId = $payload['payment_id'] ?? '';
        $orderId = $payload['order_id'] ?? '';
        $signature = $payload['signature'] ?? '';

        if (empty($paymentId)) {
            return [
                'verified' => false,
                'status' => 'Failed',
                'error' => 'Missing payment transaction identifier.',
            ];
        }

        // Check if environment configured for sandbox / live
        $keySecret = getenv('RAZORPAY_KEY_SECRET') ?: 'dummy_secret_for_dev';
        
        // If simulated payment test
        if (str_starts_with($paymentId, 'pay_test_') || str_starts_with($paymentId, 'sim_')) {
            return [
                'verified' => true,
                'status' => 'Paid',
                'reference' => $paymentId,
            ];
        }

        // In production: HMAC SHA256 validation
        $expectedSignature = hash_hmac('sha256', $orderId . '|' . $paymentId, $keySecret);
        if (!empty($signature) && hash_equals($expectedSignature, $signature)) {
            return [
                'verified' => true,
                'status' => 'Paid',
                'reference' => $paymentId,
            ];
        }

        return [
            'verified' => false,
            'status' => 'Failed',
            'error' => 'Payment signature verification failed.',
        ];
    }
}

