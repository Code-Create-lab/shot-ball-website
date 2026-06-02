<?php

use App\Models\Registration;
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
        Schema::table('registrations', function (Blueprint $table) {
            $table->string('registration_id')->nullable()->unique()->after('id');
        });

        // Backfill any existing rows.
        Registration::whereNull('registration_id')->get()->each(function (Registration $r) {
            $r->registration_id = 'GSBAB-' . str_pad((string) $r->id, 4, '0', STR_PAD_LEFT);
            $r->saveQuietly();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropUnique(['registration_id']);
            $table->dropColumn('registration_id');
        });
    }
};
