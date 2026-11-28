<?php
declare(strict_types=1);

/**
 * HOMEIQ Warranty Registration Module Configuration
 */

$baseDir = dirname(__DIR__);
$storageDir = $baseDir . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'warranty';

return [
    'storage' => [
        'base' => $storageDir,
        'registrations' => $storageDir . DIRECTORY_SEPARATOR . 'registrations',
        'invoices' => $storageDir . DIRECTORY_SEPARATOR . 'invoices',
        'email_logs' => $storageDir . DIRECTORY_SEPARATOR . 'email-logs',
        'audit_logs' => $storageDir . DIRECTORY_SEPARATOR . 'audit-logs',
        'settings' => $storageDir . DIRECTORY_SEPARATOR . 'settings',
        'policies_file' => $storageDir . DIRECTORY_SEPARATOR . 'settings' . DIRECTORY_SEPARATOR . 'warranty-policies.json',
        'email_logs_file' => $storageDir . DIRECTORY_SEPARATOR . 'email-logs' . DIRECTORY_SEPARATOR . 'email-history.json',
        'audit_logs_file' => $storageDir . DIRECTORY_SEPARATOR . 'audit-logs' . DIRECTORY_SEPARATOR . 'audit-history.json',
        'sequence_lock_file' => $storageDir . DIRECTORY_SEPARATOR . 'settings' . DIRECTORY_SEPARATOR . 'sequence.lock',
    ],
    'id_prefix' => 'HIQ-WR-',
    'id_padding' => 6,
    'allowed_invoice_mime' => [
        'application/pdf',
        'image/jpeg',
        'image/pjpeg',
        'image/png',
        'image/x-png',
    ],
    'allowed_invoice_ext' => ['pdf', 'jpg', 'jpeg', 'png'],
    'max_invoice_size_bytes' => 5 * 1024 * 1024, // 5 MB
    'categories' => [
        'Smart Door Lock',
        'VDP',
        'Retrofit Smart Module',
        'Smart Touch Switch',
        'Other',
    ],
    'platforms' => [
        'HOMEIQ Website',
        'Amazon',
        'Flipkart',
        'Other',
    ],
    'rejection_reasons' => [
        'Invalid Invoice',
        'Incorrect Serial Number',
        'Product Details Mismatch',
        'Invalid Order Details',
        'Purchase Details Could Not Be Verified',
        'Other',
    ],
    'declaration_text' => 'I confirm that the information provided is accurate and agree to its use for product registration, warranty and customer support as per the HOMEIQ Privacy Policy.',
];
