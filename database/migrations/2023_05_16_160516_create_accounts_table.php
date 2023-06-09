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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->string('acct_number')->unique();
            $table->enum('type', ['savings', 'current', 'domiciliary']);
            $table->enum('status', ['active', 'restricted', 'dormant']);
            $table->enum('currency', ['NGN', 'USD', 'GBP', 'EUR']);
            $table->double('available_balance', 17, 2);
            $table->string('pin');
            $table->string('officer_name');
            $table->string('officer_email');
            $table->string('officer_phone');
            $table->timestamps();
            $table->softDeletesTz($column = 'deleted_at', $precision = 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
    }
};
