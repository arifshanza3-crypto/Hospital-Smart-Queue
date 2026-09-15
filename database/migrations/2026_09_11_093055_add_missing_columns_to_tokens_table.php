<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tokens', function (Blueprint $table) {
            
            // ✅ Add patient_name
            if (!Schema::hasColumn('tokens', 'patient_name')) {
                $table->string('patient_name')->nullable();
            }
            
            // ✅ Add email
            if (!Schema::hasColumn('tokens', 'email')) {
                $table->string('email')->nullable();
            }
            
            // ✅ Add type
            if (!Schema::hasColumn('tokens', 'type')) {
                $table->string('type')->default('OPD');
            }
            
            // ✅ Add position
            if (!Schema::hasColumn('tokens', 'position')) {
                $table->integer('position')->default(0);
            }
            
            // ✅ Add estimated_time
            if (!Schema::hasColumn('tokens', 'estimated_time')) {
                $table->integer('estimated_time')->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::table('tokens', function (Blueprint $table) {
            $columns = ['patient_name', 'email', 'type', 'position', 'estimated_time'];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('tokens', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};