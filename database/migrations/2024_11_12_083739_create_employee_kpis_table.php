<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employee_kpis', function (Blueprint $table) {
            $table->id();
            $table->string('unit_name');
            $table->string('employee_name');
            $table->string('position')->default('เจ้าหน้าที่');
            $table->integer('departmental_kpi_score');
            $table->integer('departmental_kpi_weight')->default(30);
            $table->decimal('individual_kpi_score', 3, 1);
            $table->integer('individual_kpi_weight')->default(70);
            $table->decimal('total_weighted_score', 3, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_kpis');
    }
};
