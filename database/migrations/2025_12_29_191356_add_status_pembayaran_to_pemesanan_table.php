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
    Schema::table('pemesanan', function (Blueprint $table) {
        if (!Schema::hasColumn('pemesanan', 'status_pembayaran')) {
            $table->string('status_pembayaran')
                ->default('pending');
        }
    });
}
public function down(): void
{
    Schema::table('pemesanan', function (Blueprint $table) {
        $table->dropColumn(['status_pembayaran', 'bukti_transfer']);
    });
}
};
