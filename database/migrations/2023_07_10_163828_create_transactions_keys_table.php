<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('transactions_keys')) {
            Schema::create('transactions_keys', function (Blueprint $table) {
                $table->id();
                $table->string('transaction_type');
                $table->integer('transaction_id');
                $table->char('key_used', 20);
                $table->timestamp('created_at')->useCurrent();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions_keys');
    }
};
