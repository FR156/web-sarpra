<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Kita buat trigger yang memantau kolom 'status'
        DB::unprepared("
            CREATE TRIGGER after_loan_returned
            AFTER UPDATE ON loans
            FOR EACH ROW
            BEGIN
                -- Cek apakah status berubah menjadi 'returned'
                IF NEW.status = 'returned' AND OLD.status != 'returned' THEN
                    
                    -- Update status semua unit barang yang terkait dengan loan ini
                    UPDATE item_units 
                    SET status = 'available', 
                        last_used_at = NOW()
                    WHERE id IN (
                        SELECT liu.item_unit_id 
                        FROM loan_item_units liu
                        JOIN loan_items li ON liu.loan_item_id = li.id
                        WHERE li.loan_id = NEW.id
                    );
                    
                END IF;
            END
        ");
    }

    public function down(): void
    {
        DB::unprepared("DROP TRIGGER IF EXISTS after_loan_returned");
    }
};