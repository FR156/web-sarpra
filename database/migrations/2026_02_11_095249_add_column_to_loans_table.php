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
        Schema::table('loans', function (Blueprint $table) {
            $table->string('reason')->after('user_id');
            $table->decimal('fine_amount', 10, 2)->default(0)->nullable()->after('returned_at');
            $table->enum('fine_reason', ['damaged', 'late', 'other'])->nullable()->after('fine_amount');
            $table->enum('fine_status', ['paid', 'unpaid'])->nullable()->after('fine_reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            //
        });
    }
};
