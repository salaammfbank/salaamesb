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
            $table->string('customer_email')->nullable()->after('customer_telephone');
            $table->string('reject_reason')->nullable()->after('onboard_status');
            $table->foreignId('reject_by')->nullable()->after('checker')->references('id')->on('users');
            $table->timestamp('rejected_at')->nullable()->after('approved_at');
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
            $table->dropColumn('customer_email');
            $table->dropColumn('reject_reason');
            $table->dropColumn('rejected_at');
            $table->dropConstrainedForeignId('reject_by');
        });
    }
};
