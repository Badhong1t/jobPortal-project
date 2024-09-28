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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->constrained('users')->cascadeOnDelete();
            $table->integer('category_id')->constrained('categories')->cascadeOnDelete();
            $table->integer('company_id')->constrained('companies')->cascadeOnDelete();
            $table->integer('country_id')->constrained('countries')->cascadeOnDelete();
            $table->string('job_name');
            $table->text('short_description');
            $table->text('job_details');
            $table->string('address');
            $table->double('salary');
            $table->string('experience');
            $table->string('end_date')->nullable();
            $table->string('image')->nullable();
            $table->enum('status', ['Active', 'Inactive'])->default('Inactive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
