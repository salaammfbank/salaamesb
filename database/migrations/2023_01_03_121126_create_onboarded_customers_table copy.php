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
        Schema::create('customer_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_cif_id')->nullable()->references('id')->on('customer_cifs');
            $table->string('account_number');
            $table->string('customer_number');
            $table->string('branch_code')->default('000');
            $table->string('currency')->default('KES');
            $table->string('customer_name');
            $table->string('customer_telephone');
            $table->string('customer_email')->nullable();
            $table->string('reject_reason')->nullable();
            $table->foreignId('reject_by')->nullable()->references('id')->on('users');
            $table->timestamp('rejected_at')->nullable();
            $table->string('adesc');
            $table->unsignedDouble('transaction_limit')->default(150000);
            $table->unsignedDouble('daily_limit_mpesa')->default(150000);
            $table->unsignedDouble('daily_limit_airtime')->default(5000);
            $table->unsignedDouble('daily_limit_internal')->default(100000);
            $table->string('pin_number')->nullable();
            $table->boolean('allow_debit')->default(1);
            $table->boolean('allow_credit')->default(1);
            $table->string('account_type');
            $table->boolean('is_frozen')->default(0);
            $table->string('address_1')->nullable();
            $table->string('address_2')->nullable();
            $table->string('address_3')->nullable();
            $table->string('address_4')->nullable();
            $table->string('account_status');
            $table->foreignId('maker')->references('id')->on('users');
            $table->foreignId('checker')->nullable()->references('id')->on('users');
            $table->smallInteger('onboard_status')->default(0);
            $table->boolean('is_active')->default(false);
            $table->boolean('is_locked')->default(false);
            $table->timestamp('approved_at')->nullable();
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
        Schema::dropIfExists('customer_accounts');
    }
};
