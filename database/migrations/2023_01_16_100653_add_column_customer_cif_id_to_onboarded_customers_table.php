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
            $table->foreignId('customer_cif_id')->after('id')->nullable()->references('id')->on('customer_cifs');
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
            $table->dropConstrainedForeignId('customer_cif_id');
        });
    }
};
