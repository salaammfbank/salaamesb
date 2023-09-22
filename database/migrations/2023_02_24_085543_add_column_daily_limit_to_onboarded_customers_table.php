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
        Schema::table('onboarded_customers', function (Blueprint $table) {
            $table->unsignedDouble('daily_limit_mpesa')->after('transaction_limit')->default(150000);
            $table->unsignedDouble('daily_limit_airtime')->after('daily_limit_mpesa')->default(5000);
            $table->unsignedDouble('daily_limit_internal')->after('daily_limit_airtime')->default(100000);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('onboarded_customers', function (Blueprint $table) {
            $table->dropColumn('daily_limit_mpesa');
            $table->dropColumn('daily_limit_airtime');
            $table->dropColumn('daily_limit_internal');
        });
    }
};
