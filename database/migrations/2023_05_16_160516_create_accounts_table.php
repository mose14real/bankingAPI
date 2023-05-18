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
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('acct_no');
            $table->enum('acct_type', ['savings', 'current', 'domiciliary']);
            $table->enum('acct_status', ['active', 'restricted', 'dormant']);
            $table->enum('currency', ['NGN', 'USD', 'GBP', 'EUR']);
            $table->double('balance', 8, 2);
            $table->string('pin');
            $table->string('officer_name');
            $table->string('officer_email');
            $table->string('officer_phone');
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
        Schema::dropIfExists('accounts');
    }
};
