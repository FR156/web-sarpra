<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Loan;
use App\Models\Item;
use App\Models\ItemUnit;

class ReportController extends Controller
{
    // Summary (default / quick report)
    public function exportSummary()
    {
        $data = [
            'loans' => Loan::latest()->take(10)->get(),
            'items' => Item::all(),
            'itemUnits' => ItemUnit::all(),
        ];

        return Pdf::loadView('pdf.reports.summary', $data)
            ->download('report-summary.pdf');
    }

    // Filtered (dynamic)
    public function exportFiltered(Request $request)
    {
        $query = Loan::query();

        if ($request->start && $request->end) {
            $query->whereBetween('created_at', [
                $request->start,
                $request->end
            ]);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $limit = $request->limit ?? 10;

        $loans = $query->latest()->take($limit)->get();

        $data = [
            'loans' => $loans,
            'items' => Item::all(),
            'itemUnits' => ItemUnit::all(),
        ];

        return Pdf::loadView('pdf.reports.filtered', $data)
            ->download('report-filtered.pdf');
    }

    // Full export (all data)
    public function exportAll()
    {
        $data = [
            'loans' => Loan::latest()->get(),
            'items' => Item::all(),
            'itemUnits' => ItemUnit::all(),
        ];

        return Pdf::loadView('pdf.reports.all', $data)
            ->download('report-all.pdf');
    }
}