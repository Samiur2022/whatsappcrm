<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone', 20)->unique();
            $table->string('email')->nullable();
            $table->string('file_path')->nullable(); // For PDF/DOC uploads
            $table->enum('status', ['new', 'active', 'pending', 'cancelled', 'success'])->default('new');
            $table->unsignedBigInteger('assigned_user_id')->nullable();
            $table->timestamp('last_contact_at')->nullable();
            $table->timestamp('status_updated_at')->nullable();
            $table->softDeletes(); // Soft delete column
            $table->timestamps();
            
            $table->index(['status', 'last_contact_at']);
            $table->index('phone');
            $table->index('assigned_user_id');
            $table->index('deleted_at'); // For soft delete query optimization
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};