<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tokens', function (Blueprint $table) {
            if (!Schema::hasColumn('tokens', 'doctor_id')) {
                $table->unsignedBigInteger('doctor_id')->nullable()->after('patient_name');
                $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tokens', function (Blueprint $table) {
            if (Schema::hasColumn('tokens', 'doctor_id')) {
                $table->dropForeign(['doctor_id']);
                $table->dropColumn('doctor_id');
            }
        });
    }
};