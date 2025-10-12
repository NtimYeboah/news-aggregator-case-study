<?php

use App\Models\NewsRetrievalEvent;
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
        Schema::create('news_retrieval_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(NewsRetrievalEvent::class, 'event_id');
            $table->string('status');
            $table->string('source');
            $table->string('url');
            $table->string('response_code')->nullable();
            $table->smallInteger('total_results')->nullable();
            $table->timestamp('retrieved_from');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_retrieval_attempts');
    }
};
