<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('birthdays', function (Blueprint $table) {
            $table->index('birth_date');
        });

        Schema::table('news', function (Blueprint $table) {
            $table->index(['status', 'published_at']);
        });

        Schema::table('events', function (Blueprint $table) {
            $table->index(['status', 'start_date']);
            $table->index(['status', 'end_date']);
        });

        Schema::table('best_officers', function (Blueprint $table) {
            $table->index(['year', 'month']);
        });

        Schema::table('pembinas', function (Blueprint $table) {
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('birthdays', function (Blueprint $table) {
            $table->dropIndex(['birth_date']);
        });

        Schema::table('news', function (Blueprint $table) {
            $table->dropIndex(['status', 'published_at']);
        });

        Schema::table('events', function (Blueprint $table) {
            $table->dropIndex(['status', 'start_date']);
            $table->dropIndex(['status', 'end_date']);
        });

        Schema::table('best_officers', function (Blueprint $table) {
            $table->dropIndex(['year', 'month']);
        });

        Schema::table('pembinas', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
        });
    }
};
