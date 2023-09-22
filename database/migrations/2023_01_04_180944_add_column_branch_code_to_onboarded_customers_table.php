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
            $table->string('branch_code')->default('000')->after('customer_number');
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
            $table->dropColumn('branch_code');
        });
    }
};
