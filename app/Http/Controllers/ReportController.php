<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Loan;
use App\Models\Item;
use App\Models\ItemUnit;

class ReportController extends Controller
{
    public function export()
    {
        $data = [
            'loans' => Loan::all(),
            'items' => Item::all(),
            'itemUnits' => ItemUnit::all(),
        ];

        $pdf = Pdf::loadView('pdf.report', $data);

        return $pdf->download('report.pdf');
    }
}