<?php

namespace App\Filament\Resources\ActivityLogs\Tables;

use App\Models\ActivityLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms\Components\DatePicker;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;

class ActivityLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->recordActions([
                //
            ])
            ->headerActions([
                Action::make('exportReport')
                    ->label('Download Laporan Struktur')
                    ->icon('heroicon-o-document-arrow-down')
                    ->form([
                        DatePicker::make('start_date')->required(),
                        DatePicker::make('end_date')->required(),
                    ])
                    ->action(function (array $data) {
                        $logs = ActivityLog::whereBetween('created_at', [$data['start_date'], $data['end_date']])->get();
                        
                        // Logic hitung statistik disini
                        $pdf = PDF::loadView('reports.activity-log', [
                            'logs' => $logs,
                            'startDate' => $data['start_date'],
                            'endDate' => $data['end_date'],
                            'approvedCount' => $logs->where('description', 'approved')->count(),
                            // ... statistik lainnya
                        ]);

                        return response()->streamDownload(fn () => print($pdf->output()), 'laporan-sarpra.pdf');
                    })
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
