<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customer_queues', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('customer_name')->nullable();
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('counter_id')->nullable();
            $table->integer('queue_number');
            $table->enum('status', ['waiting', 'called', 'skipped', 'served'])->default('waiting');
            $table->timestamp('joined_at')->useCurrent();
            $table->timestamp('called_at')->nullable();
            $table->timestamp('served_at')->nullable();
            $table->timestamps();

            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
            $table->foreign('counter_id')->references('id')->on('counters')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_queues');
    }
};
