<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QueueReport;
use App\Models\Token;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QueueReportController extends Controller
{
    /**
     * ✅ Main Report Page - Uses TOKENS table for real data
     */
    public function index(Request $request)
    {
        // ✅ Base query - TOKENS table
        $query = Token::with('doctor');

        // ✅ Date Range Filter
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // ✅ Single date filter (backward compatibility)
        if ($request->filled('date') && !$request->filled('from_date')) {
            $query->whereDate('created_at', $request->date);
        }

        // ✅ Doctor Filter
        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        // ✅ Status Filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // ✅ Department Filter
        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        // ✅ Get paginated reports
        $reports = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // ✅ Today's Statistics
        $today = today();
        $totalPatients = Token::count();
        $todayTotal = Token::whereDate('created_at', $today)->count();
        $completedToday = Token::whereDate('created_at', $today)
            ->where('status', 'completed')
            ->count();

        // ✅ Average Times (only completed tokens)
        $avgWaitingTime = Token::where('status', 'completed')
            ->whereNotNull('called_at')
            ->select(DB::raw('AVG(TIMESTAMPDIFF(MINUTE, created_at, called_at)) as avg_wait'))
            ->value('avg_wait') ?? 0;

        $avgServiceTime = Token::where('status', 'completed')
            ->whereNotNull('started_at')
            ->whereNotNull('completed_at')
            ->select(DB::raw('AVG(TIMESTAMPDIFF(MINUTE, started_at, completed_at)) as avg_service'))
            ->value('avg_service') ?? 0;

        // ✅ Status-wise Statistics
        $statusStats = [
            'waiting' => Token::where('status', 'waiting')->count(),
            'in_progress' => Token::whereIn('status', ['calling', 'serving'])->count(),
            'completed' => Token::where('status', 'completed')->count(),
            'cancelled' => Token::whereIn('status', ['cancelled', 'missed'])->count(),
        ];

        // ✅ Department-wise Distribution
        $departmentStats = Token::select('department', DB::raw('count(*) as total'))
            ->whereNotNull('department')
            ->groupBy('department')
            ->orderBy('total', 'desc')
            ->get()
            ->map(function($item) {
                return [
                    'department' => $item->department ?? 'General',
                    'total' => $item->total
                ];
            });

        // ✅ Daily Trend (Last 7 Days)
        $dailyStats = Token::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as total')
            )
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date', 'asc')
            ->get()
            ->map(function($item) {
                return [
                    'date' => \Carbon\Carbon::parse($item->date)->format('d M'),
                    'total' => $item->total
                ];
            });

        // ✅ Doctor-wise Report
        $doctorStats = Token::select(
                'doctor_id',
                DB::raw('count(*) as total_patients'),
                DB::raw('SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed'),
                DB::raw('SUM(CASE WHEN status = "waiting" THEN 1 ELSE 0 END) as waiting'),
                DB::raw('SUM(CASE WHEN status IN ("cancelled", "missed") THEN 1 ELSE 0 END) as cancelled')
            )
            ->whereNotNull('doctor_id')
            ->groupBy('doctor_id')
            ->with('doctor')
            ->get()
            ->map(function($item) {
                return [
                    'doctor_name' => $item->doctor ? 'Dr. ' . $item->doctor->name : 'Unknown',
                    'specialization' => $item->doctor ? $item->doctor->specialization : 'N/A',
                    'total_patients' => $item->total_patients,
                    'completed' => $item->completed,
                    'waiting' => $item->waiting,
                    'cancelled' => $item->cancelled,
                ];
            });

        // ✅ Get lists for filters
        $departments = Token::distinct()->whereNotNull('department')->pluck('department');
        $doctors = Doctor::where('status', 'active')->orderBy('name')->get();

        return view('Pages.Admin.queue_reports', compact(
            'reports',
            'totalPatients',
            'todayTotal',
            'completedToday',
            'avgWaitingTime',
            'avgServiceTime',
            'statusStats',
            'departmentStats',
            'dailyStats',
            'doctorStats',
            'departments',
            'doctors'
        ));
    }

    /**
     * ✅ Show Single Report
     */
    public function show($id)
    {
        $report = Token::with('doctor')->findOrFail($id);
        return view('Layout.show-queue-report', compact('report'));
    }

    /**
     * ✅ Export CSV - Real Data
     */
    public function export(Request $request)
    {
        $query = Token::with('doctor');

        // Apply same filters
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        if ($request->filled('date') && !$request->filled('from_date')) {
            $query->whereDate('created_at', $request->date);
        }

        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        $reports = $query->orderBy('created_at', 'desc')->get();

        // Filename
        $filename = 'queue_report_';
        if ($request->from_date && $request->to_date) {
            $filename .= $request->from_date . '_to_' . $request->to_date;
        } elseif ($request->date) {
            $filename .= $request->date;
        } else {
            $filename .= date('Y-m-d_H-i-s');
        }
        $filename .= '.csv';

        // Generate CSV
        $handle = fopen('php://temp', 'w');

        // Headers
        fputcsv($handle, [
            'Token #',
            'Patient Name',
            'Phone',
            'Doctor',
            'Specialization',
            'Department',
            'Status',
            'Position',
            'Est. Time (min)',
            'Created At',
            'Called At',
            'Started At',
            'Completed At'
        ]);

        // Data
        foreach ($reports as $report) {
            fputcsv($handle, [
                $report->token_number,
                $report->patient_name,
                $report->phone,
                $report->doctor ? 'Dr. ' . $report->doctor->name : 'N/A',
                $report->doctor ? $report->doctor->specialization : 'N/A',
                $report->department,
                ucfirst(str_replace('_', ' ', $report->status)),
                $report->position,
                $report->estimated_time,
                $report->created_at ? $report->created_at->format('Y-m-d H:i:s') : '',
                $report->called_at ? $report->called_at->format('Y-m-d H:i:s') : '',
                $report->started_at ? $report->started_at->format('Y-m-d H:i:s') : '',
                $report->completed_at ? $report->completed_at->format('Y-m-d H:i:s') : '',
            ]);
        }

        // Summary
        fputcsv($handle, []);
        fputcsv($handle, ['SUMMARY']);
        fputcsv($handle, ['Total Records', $reports->count()]);
        fputcsv($handle, ['Generated On', now()->format('Y-m-d H:i:s')]);
        fputcsv($handle, ['Filter From', $request->from_date ?? 'All']);
        fputcsv($handle, ['Filter To', $request->to_date ?? 'All']);

        rewind($handle);
        $csvContent = stream_get_contents($handle);
        fclose($handle);

        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * ✅ Summary API (AJAX)
     */
    public function summary()
    {
        $today = today();
        $weekAgo = now()->subDays(7);

        return response()->json([
            'total_patients' => Token::count(),
            'today_patients' => Token::whereDate('created_at', $today)->count(),
            'week_patients' => Token::where('created_at', '>=', $weekAgo)->count(),
            'avg_waiting_time' => round(Token::where('status', 'completed')->avg('estimated_time') ?? 0),
            'pending_patients' => Token::whereIn('status', ['waiting', 'calling', 'serving'])->count(),
            'completed_today' => Token::whereDate('created_at', $today)->where('status', 'completed')->count(),
        ]);
    }
}