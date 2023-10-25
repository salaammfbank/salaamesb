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
        Schema::create('customer_cifs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->string('email')->nullable();
            $table->string('preferred_email')->nullable();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('gender');
            $table->string('title');
            $table->string('short_name');
            $table->string('pin_number')->nullable();
            $table->boolean('should_change_pin')->default(true)->after('pin_number');
            $table->boolean('has_i365')->default(false);
            $table->boolean('sent_to_i365')->default(false);
            $table->timestamp('i365_at')->nullable()->comment('time onboarded to i365');
            $table->json('i365_reponse')->nullable();
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('address_line3')->nullable();
            $table->string('address_line4')->nullable();
            $table->string('address_country')->nullable();
            $table->string('preferred_mobile_number')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('is_verified')->nullable();
            $table->string('nationality')->nullable();
            $table->string('unique_id_name')->nullable();
            $table->string('unique_id_value')->nullable();
            $table->timestamp('cif_created_at')->nullable();
            $table->string('customer_type')->nullable();
            $table->string('category');
            $table->foreignId('maker')->references('id')->on('users');
            $table->foreignId('checker')->nullable()->references('id')->on('users');
            $table->smallInteger('onboard_status')->default(0);
            $table->boolean('is_active')->default(false);
            $table->boolean('is_locked')->default(false);
            $table->timestamp('approved_at')->nullable();
            $table->string('reject_reason')->nullable();
            $table->foreignId('reject_by')->nullable()->references('id')->on('users');
            $table->timestamp('rejected_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_cifs');
    }
};
