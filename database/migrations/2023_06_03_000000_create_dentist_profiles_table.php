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
        Schema::create('dentist_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('registration_number')->unique();
            $table->string('university');
            $table->year('graduation_year');
            $table->text('professional_experience')->nullable();
            $table->json('services_offered')->nullable();
            $table->json('languages')->nullable();
            $table->string('office_address')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('postal_code');
            $table->string('phone_number');
            $table->string('website_url')->nullable();
            $table->json('social_media')->nullable();
            $table->boolean('accepts_insurance')->default(false);
            $table->json('insurance_networks')->nullable();
            $table->enum('verification_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('verification_notes')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamps();
        });

        // Create the documents table for dentist verification
        Schema::create('dentist_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dentist_profile_id')->constrained()->onDelete('cascade');
            $table->string('document_type'); // license, degree, certification, etc.
            $table->string('file_path');
            $table->string('original_filename');
            $table->string('mime_type');
            $table->integer('file_size');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dentist_documents');
        Schema::dropIfExists('dentist_profiles');
    }
};
