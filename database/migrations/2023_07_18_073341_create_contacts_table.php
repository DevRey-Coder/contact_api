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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("country_code");
            $table->string("phone_number");
            $table->foreignId("user_id");
            $table->string("email")->nullable();
            $table->string("company")->nullable();
            $table->string("job_title")->nullable();
            $table->date("birth_day")->nullable();
            $table->text("notes")->nullable();
            $table->string("photo")->default(config("info.default_contact_photo"));
            $table->softDeletes();
            $table->timestamps();
            $table->boolean('is_favorite')->default(false);
            $table->boolean('is_deleted')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
