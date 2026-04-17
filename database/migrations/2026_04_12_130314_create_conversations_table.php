<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->constrained()->onDelete('cascade');
            $table->foreignId('assigned_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('channel')->default('whatsapp');
            $table->text('last_message')->nullable();
            $table->timestamp('last_message_at')->nullable();
            $table->string('status')->default('open');
            $table->integer('unread_count')->default(0);
            $table->softDeletes();
            $table->timestamps();
            
            $table->index(['last_message_at', 'status']);
            $table->index('contact_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};