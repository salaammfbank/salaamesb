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
        Schema::table('customer_cifs', function (Blueprint $table) {
            $table->boolean('should_change_pin')->default(true)->after('pin_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_cifs', function (Blueprint $table) {
            $table->dropColumn('should_change_pin');
        });
    }
};
