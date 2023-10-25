<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(0)->after('password');
            $table->boolean('is_active')->default(1)->after('password');
            $table->smallInteger('user_theme')->default(0)->after('is_active');
            $table->boolean('is_system')->default(false)->after('is_admin');
            $table->boolean('force_password_reset')->default(true)->after('password');
            $table->string('phone_number')->nullable()->after('is_system');
            $table->string('address')->nullable()->after('phone_number');
            $table->foreignId('department')->nullable()->after('phone_number')->references('id')->on('departments');
            $table->boolean('is_agent')->default(false)->after('password');
            $table->string('mpesa_callback_url')->nullable()->after('is_agent');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_admin');
            $table->dropColumn('is_active');
            $table->dropColumn('user_theme');
            $table->dropColumn('is_system');
            $table->dropColumn('force_password_reset');
            $table->dropColumn('phone_number');
            $table->dropColumn('address');
            $table->dropConstrainedForeignId('department');
            $table->dropColumn('is_agent');
            $table->dropColumn('mpesa_callback_url');
        });
    }
};
