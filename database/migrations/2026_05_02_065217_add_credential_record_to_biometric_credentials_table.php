<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('biometric_credentials', function (Blueprint $table) {
            $table->json('credential_record')->nullable()->after('public_key');
        });
    }

    public function down(): void
    {
        Schema::table('biometric_credentials', function (Blueprint $table) {
            $table->dropColumn('credential_record');
        });
    }
};
