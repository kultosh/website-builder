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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('pages')->nullOnDelete();
            $table->boolean('is_parent')->default(0)->comment('To identify page as parent or child');
            $table->string('title');
            $table->string('slug');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->enum('page_type', ['standard','contact'])->default('standard');
            $table->integer('order')->default(1);
            $table->boolean('is_menu')->default(0)->comment('To display/make page in menu');
            $table->boolean('add_to_home')->default(0)->comment('To display page in Home page');
            $table->boolean('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
