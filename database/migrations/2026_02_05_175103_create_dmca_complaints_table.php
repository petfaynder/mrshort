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
        Schema::create('dmca_complaints', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('link_id')->nullable()->index();
            $table->string('link_code', 50);
            $table->text('original_url');
            $table->string('complaint_type', 50); // copyright, malware, illegal, phishing, sexual_content, other
            $table->string('reporter_name');
            $table->string('reporter_email');
            $table->string('reporter_ip', 45)->nullable();
            $table->text('description');
            $table->string('status', 20)->default('pending'); // pending, reviewing, resolved, rejected
            $table->text('admin_notes')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('link_code');
            $table->index('status');
            $table->index('complaint_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dmca_complaints');
    }
};
