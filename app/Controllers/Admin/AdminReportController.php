<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\View;
use App\Services\ReportService;

class AdminReportController
{
    private ReportService $reportService;

    public function __construct()
    {
        $this->reportService = new ReportService();
    }

    public function index(Request $request): void
    {
        $range = (string)$request->query('range', '30days');
        $analytics = $this->reportService->getAnalytics($range);

        echo View::render('admin/reports/index', [
            'title' => 'Sales Reports & Analytics | HOMEIQ Admin',
            'range' => $range,
            'analytics' => $analytics,
        ], 'admin');
    }

    public function export(Request $request): void
    {
        $range = (string)$request->query('range', '30days');
        $csv = $this->reportService->exportOrdersCsv($range);

        $filename = 'homeiq_orders_report_' . $range . '_' . date('Y-m-d') . '.csv';

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        echo $csv;
        exit;
    }
}

