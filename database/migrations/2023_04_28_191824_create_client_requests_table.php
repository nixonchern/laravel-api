<?php

use App\Enums\ClientRequestsStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('client_requests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->enum('status', array_column(ClientRequestsStatusEnum::cases(), 'value'))->default(ClientRequestsStatusEnum::Active->value);
            $table->text('message');
            $table->text('comment')->nullable();
            $table->timestamps();
            $table->foreignId('user_id')->nullable()->constrained();

            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_requests');
    }
};
