<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * HU-13: guarda la calificación de cada materia cursada para el kárdex.
 * Es opcional (nullable) para no afectar las materias que ya estaban marcadas.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('materias_cursadas', function (Blueprint $table) {
            $table->unsignedTinyInteger('calificacion')->nullable()->after('materia_id');
        });
    }

    public function down(): void
    {
        Schema::table('materias_cursadas', function (Blueprint $table) {
            $table->dropColumn('calificacion');
        });
    }
};
