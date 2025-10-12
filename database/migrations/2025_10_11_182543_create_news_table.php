<?php

use App\Models\Author;
use App\Models\Category;
use App\Models\Source;
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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Source::class, 'source_id');
            $table->foreignIdFor(Category::class, 'category_id');
            $table->foreignIdFor(Author::class, 'author_id');
            $table->string('title');
            $table->text('content')->nullable();
            $table->text('description')->nullable();
            $table->text('url');
            $table->text('image_url')->nullable();
            $table->text('api_url')->nullable();
            $table->timestampTz('published_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
