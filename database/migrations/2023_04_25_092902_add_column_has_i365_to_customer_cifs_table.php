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
            $table->boolean('has_i365')->default(false)->after('pin_number');
            $table->boolean('sent_to_i365')->default(false)->after('has_i365');
            $table->timestamp('i365_at')->nullable()->comment('time onboarded to i365')->after('sent_to_i365');
            $table->json('i365_reponse')->nullable()->after('sent_to_i365');
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
            $table->dropColumn('has_i365');
            $table->dropColumn('sent_to_i365');
            $table->dropColumn('i365_at');
            $table->dropColumn('i365_reponse');
        });
    }
};
