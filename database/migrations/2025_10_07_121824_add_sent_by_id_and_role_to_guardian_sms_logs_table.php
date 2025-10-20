<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('guardian_sms_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('sent_by_id')->nullable()->after('message');
            $table->string('sent_by_role')->nullable()->after('sent_by_id');
        });
    }

    public function down(): void
    {
        Schema::table('guardian_sms_logs', function (Blueprint $table) {
            $table->dropColumn(['sent_by_id', 'sent_by_role']);
        });
    }
};
