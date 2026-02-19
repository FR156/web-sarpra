<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('item_units', function (Blueprint $table) {
            $table->enum('condition', ['good', 'minor_damage', 'major_damage', 'lost'])->default('good')->change();
            $table->enum('status', ['available', 'booked', 'on_loan', 'maintenance', 'unavailable'])->default('available')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('item_units', function (Blueprint $table) {
            //
        });
    }
};
