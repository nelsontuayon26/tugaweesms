<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('settings')
            ->where('key', 'mail_driver')
            ->update(['value' => 'resend']);

        Setting::clearCache();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')
            ->where('key', 'mail_driver')
            ->update(['value' => 'smtp']);
    }
};
