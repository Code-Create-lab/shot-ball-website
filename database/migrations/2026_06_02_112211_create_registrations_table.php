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
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();

            // Registration type
            $table->string('registration_type');
            $table->string('event_type');

            // Personal details
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->date('dob');
            $table->string('father_name');
            $table->string('mother_name');

            // Contact details
            $table->string('address');
            $table->string('village_city');
            $table->string('state')->default('Bihar');
            $table->string('district');
            $table->string('club1');
            $table->string('club2')->nullable();
            $table->string('pincode', 6);
            $table->string('country')->default('India');

            // Identity & access
            $table->string('aadhaar', 12);
            $table->string('mobile', 10);
            $table->string('email');

            // Document uploads (stored paths)
            $table->string('photo_path');
            $table->string('signature_path');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
