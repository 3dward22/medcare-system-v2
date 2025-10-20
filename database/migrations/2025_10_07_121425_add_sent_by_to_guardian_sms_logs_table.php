<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('guardian_sms_logs', function (Blueprint $table) {
            $table->string('sent_by')->nullable()->after('message');
        });
    }

    public function down(): void
    {
        Schema::table('guardian_sms_logs', function (Blueprint $table) {
            $table->dropColumn('sent_by');
        });
    }
};
