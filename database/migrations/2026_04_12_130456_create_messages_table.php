<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained()->onDelete('cascade');
            $table->foreignId('contact_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('direction', ['inbound', 'outbound']);
            $table->text('body');
            $table->string('type')->default('text');
            $table->string('status')->default('sent');
            $table->string('provider_message_id')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
            
            $table->index(['conversation_id', 'created_at']);
            $table->index(['contact_id', 'direction']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};