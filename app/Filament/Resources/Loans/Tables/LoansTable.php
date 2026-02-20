<?php

namespace App\Filament\Resources\Loans\Tables;

use App\Models\ItemUnit;
use App\Events\ActivityLogged;
use Dom\Text;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;

class LoansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('user.name')
                    ->label('Peminjam')
                    ->tooltip(fn ($record) => $record->user ? $record->user->email : null)
                    ->searchable(),

                TextColumn::make('reason')
                    ->label('Alasan Peminjaman')
                    ->searchable(),

                TextColumn::make('loanItems.item.name')
                    ->label('Daftar Barang')
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->limitList(2),

                TextColumn::make('loanItems.loanItemunits.itemUnit.unit_code')
                    ->label('Daftar Unit')
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->limitList(2)
                    ->toggleable(isToggledHiddenByDefault: true) 
                    ->expandableLimitedList(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->sortable()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'approved' => 'info',
                        'on_going' => 'warning',
                        'overdue' => 'danger',
                        'returned' => 'success',
                        'rejected' => 'danger',
                        'cancelled' => 'gray',
                    }),

                TextColumn::make('start_date')
                    ->label('Waktu Mulai')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('due_date')
                    ->label('Waktu Selesai')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('returned_at')
                    ->label('Waktu Pengembalian')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                // Action::make('approve')
                //     ->label('Setujui')
                //     ->icon('heroicon-o-check-circle')
                //     ->color('success')
                //     ->visible(fn ($record) => $record->status === 'pending')
                //     ->action(function ($record) {
                //         $record->update([
                //             'status' => 'approved',
                //             'approver_id' => auth()->id(),
                //         ]);
                //         $record->itemUnits()->update(['status' => 'on_loan']);
                //         ActivityLogged::dispatch('approved', "Peminjaman diterima (id peminjaman:{$record->id})", $record);
                //     }),

                Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->schema([
                        Radio::make('assign_mode')
                            ->label('Mode Assign Unit')
                            ->options([
                                'auto' => 'Auto (FIFO)',
                                'manual' => 'Manual Pilih Unit',
                            ])
                            ->default('auto')
                            ->required()
                            ->reactive(),

                        // Field untuk menampung units yang dipilih per item
                        // Repeater::make('item_assignments')
                        //     ->label('Assign Unit per Item')
                        //     ->schema([
                        //         TextInput::make('item_name')
                        //             ->label('Nama Item')
                        //             ->disabled()
                        //             ->default(fn ($record) => $record->item->name ?? '-'),

                        //         TextInput::make('quantity')
                        //             ->label('Jumlah Dibutuhkan')
                        //             ->disabled()
                        //             ->default(fn ($record) => $record->quantity),

                        //         Select::make('selected_units')
                        //             ->label('Pilih Unit')
                        //             ->multiple()
                        //             ->options(function ($record) {
                        //                 return ItemUnit::where('item_id', $record->item_id)
                        //                     ->where('status', 'available')
                        //                     ->pluck('unit_code', 'id')
                        //                     ->toArray();
                        //             })
                        //             ->required()
                        //             ->rules([
                        //                 fn ($record) => function ($attribute, $value, $fail) use ($record) {
                        //                     if (count($value) != $record->quantity) {
                        //                         $fail("Jumlah unit yang dipilih harus {$record->quantity} unit.");
                        //                     }
                        //                 }
                        //             ])
                        //             ->visible(fn ($get) => $get('../../assign_mode') === 'manual'),
                        //     ])
                        //     ->itemLabel(fn (array $state): ?string => $state['item_name'] ?? null)
                        //     ->defaultItems(0)
                        //     ->visible(fn ($get) => $get('assign_mode') === 'manual'),

                        // Alternatif: Select grouping per item
                        Select::make('selected_units_grouped')
                            ->label('Pilih Unit')
                            ->multiple()
                            ->options(function (\App\Models\Loan $record) {
                                $options = [];
                                
                                foreach ($record->loanItems as $loanItem) {
                                    $item = $loanItem->item;
                                    if ($item) {
                                        $units = ItemUnit::where('item_id', $item->id)
                                            ->where('status', 'available')
                                            ->pluck('unit_code', 'id')
                                            ->toArray();
                                        
                                        foreach ($units as $id => $code) {
                                            $options[$id] = "{$item->name} - {$code}";
                                        }
                                    }
                                }
                                
                                return $options;
                            })
                            ->visible(fn ($get) => $get('assign_mode') === 'manual')
                            ->required(fn ($get) => $get('assign_mode') === 'manual')
                            ->rules([
                                fn ($record) => function ($attribute, $value, $fail) use ($record) {
                                    // Validasi jumlah unit per item
                                    $counts = [];
                                    foreach ($value as $unitId) {
                                        $unit = ItemUnit::find($unitId);
                                        if ($unit) {
                                            $counts[$unit->item_id] = ($counts[$unit->item_id] ?? 0) + 1;
                                        }
                                    }
                                    
                                    foreach ($record->loanItems as $loanItem) {
                                        $selected = $counts[$loanItem->item_id] ?? 0;
                                        if ($selected != $loanItem->quantity) {
                                            $fail("Untuk item '{$loanItem->item->name}', harus dipilih {$loanItem->quantity} unit (anda memilih {$selected}).");
                                        }
                                    }
                                }
                            ]),
                    ])
                    ->action(function ($record, array $data) {
                        DB::transaction(function () use ($record, $data) {
                            
                            // Map untuk tracking unit yang sudah digunakan
                            $usedUnitIds = [];
                            
                            foreach ($record->loanItems as $loanItem) {
                                
                                if ($data['assign_mode'] === 'auto') {
                                    // Mode AUTO - FIFO
                                    $units = ItemUnit::where('item_id', $loanItem->item_id)
                                        ->where('status', 'available')
                                        ->whereNotIn('id', $usedUnitIds) // Hindari duplicate
                                        ->orderByRaw('last_used_at IS NULL DESC, last_used_at ASC')
                                        ->limit($loanItem->quantity)
                                        ->lockForUpdate()
                                        ->get();

                                    if ($units->count() < $loanItem->quantity) {
                                        throw new \Exception("Stok item '{$loanItem->item->name}' tidak mencukupi.");
                                    }

                                } else {
                                    // Mode MANUAL
                                    $unitIds = [];
                                    
                                    // Filter unit IDs untuk item ini
                                    foreach ($data['selected_units_grouped'] ?? $data['selected_units'] ?? [] as $unitId) {
                                        $unit = ItemUnit::find($unitId);
                                        if ($unit && $unit->item_id == $loanItem->item_id) {
                                            $unitIds[] = $unitId;
                                        }
                                    }
                                    
                                    // Cek duplikasi dengan item lain
                                    foreach ($unitIds as $unitId) {
                                        if (in_array($unitId, $usedUnitIds)) {
                                            throw new \Exception("Unit ID {$unitId} sudah digunakan untuk item lain.");
                                        }
                                    }
                                    
                                    // Cek ketersediaan
                                    $units = ItemUnit::whereIn('id', $unitIds)
                                        ->where('status', 'available')
                                        ->lockForUpdate()
                                        ->get();
                                    
                                    if ($units->count() != $loanItem->quantity) {
                                        throw new \Exception("Jumlah unit yang dipilih untuk item '{$loanItem->item->name}' harus {$loanItem->quantity} unit.");
                                    }
                                }
                                
                                // Assign units
                                foreach ($units as $unit) {
                                    // Buat record loan item unit
                                    $loanItem->loanItemUnits()->create([
                                        'item_unit_id' => $unit->id,
                                    ]);
                                    
                                    // Update status unit
                                    $unit->update([
                                        'status' => 'on_loan',
                                        'last_used_at' => now(),
                                    ]);
                                    
                                    // Track used unit
                                    $usedUnitIds[] = $unit->id;
                                }
                            }

                            // Update status loan
                            $record->update([
                                'status' => 'approved',
                                'approver_id' => auth()->id(),
                            ]);
                        });

                        ActivityLogged::dispatch('approved', "Peminjaman diterima (id peminjaman:{$record->id})", $record);
                    }),
                
                Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'rejected',
                            'approver_id' => auth()->id(),
                        ]);
                        $record->loanItems()->loanItemUnits()->update(['status' => 'available']);
                        ActivityLogged::dispatch('rejected', "Peminjaman ditolak (id peminjaman:{$record->id})", $record);
                    }),

                Action::make('mark_on_going')
                    ->label('Mulai Peminjaman')
                    ->icon('heroicon-o-arrow-path-rounded-square')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->status === 'approved')
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'on_going',
                        ]);
                        // $record->itemUnits()->update(['status' => 'on_loan']); // sudah ada dalam action approve
                        ActivityLogged::dispatch('on_going', "Peminjaman dimulai (id peminjaman:{$record->id})", $record);
                    }),

                Action::make('mark_returned')
                    ->label('Kembalikan Barang')
                    ->icon('heroicon-o-arrow-path-rounded-square')
                    ->color('success')
                    ->schema([
                        TextInput::make('fine_amount')
                            ->label('Denda (Rp)')
                            ->numeric()
                            ->default(0),
                        Select::make('fine_reason')
                            ->label('Alasan Denda')
                            ->options([
                                'damaged' => 'Rusak / Kurang',
                                'late' => 'Terlambat',
                                'other' => 'Lainnya',
                            ])
                    ])
                    ->visible(fn ($record) => $record->status === 'on_going')
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'returned',
                            'returned_at' => now(),
                        ]);
                        foreach ($record->loanItems as $loanItem) {
                            foreach ($loanItem->loanItemUnits as $assigned) {
                                $assigned->itemUnit->update([
                                    'status' => 'available',
                                    'last_used_at' => now(),
                                ]);
                            }
                        }

                        ActivityLogged::dispatch('returned', "Barang peminjaman telah dikembalikan (id peminjaman:{$record->id})", $record);
                    }),

                Action::make('fine_status')
                    ->label('Status Denda')
                    ->color('warning')
                    ->visible(fn ($record) => $record->status === 'returned')
                    ->schema([
                        Select::make('fine_status')
                            ->label('Status Denda')
                            ->options([
                                'paid' => 'Lunas',
                                'unpaid' => 'Belum Lunas',
                            ])
                    ])
                    ->action(function ($record) {
                        $record->update([
                            'fine_status' => $record->fine_status,
                        ]);
                        ActivityLogged::dispatch('fine_status', "Status denda peminjaman telah diubah (id peminjaman:{$record->id})", $record);
                    })
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'on_going' => 'On Going',
                        'returned' => 'Returned',
                        'rejected' => 'Rejected',
                        'cancelled' => 'Cancelled',
                    ])
                    ->label('Status'),
            ]);
    }
}