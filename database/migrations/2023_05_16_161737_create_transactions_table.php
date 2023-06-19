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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignId('account_id')->constrained('accounts')->cascadeOnDelete();
            $table->string('date_time');
            $table->string('sender_name')->nullable();
            $table->string('sender_acct')->nullable();
            $table->string('receiver_name')->nullable();
            $table->string('receiver_acct')->nullable();
            $table->string('description');
            $table->enum('type', ['debit', 'credit']);
            $table->enum('currency', ['NGN', 'USD', 'GBP', 'EUR']);
            $table->double('amount', 17, 2);
            $table->double('opening_balance', 17, 2);
            $table->double('closing_balance', 17, 2);
            $table->string('reference');
            $table->enum('status', ['successful', 'pending', 'failed']);
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
        Schema::dropIfExists('transactions');
    }
};
