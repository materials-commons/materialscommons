<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('project_notes', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('project_notes')->onDelete('cascade');
            $table->string('title')->nullable();
            $table->text('content');
            $table->datetime('pinned_at')->nullable();
            $table->datetime('private_at')->nullable();
            $table->json('mentions')->nullable(); // Store user IDs who are mentioned
            $table->timestamps();

            $table->index(['project_id', 'pinned_at']);
            $table->index(['project_id', 'author_id']);
            $table->index(['parent_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('project_notes');
    }
};
