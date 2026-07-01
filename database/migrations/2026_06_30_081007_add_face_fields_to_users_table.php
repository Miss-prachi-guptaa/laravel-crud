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
        Schema::table('users', function (Blueprint $table) {

            $table->longText('face_descriptor')
                  ->nullable();
    

            $table->boolean('is_face_registered')
                  ->default(false)
                  ->after('face_descriptor');

            $table->timestamp('face_registered_at')
                  ->nullable()
                  ->after('is_face_registered');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropColumn([
                'face_descriptor',
                'is_face_registered',
                'face_registered_at'
            ]);

        });
    }
};