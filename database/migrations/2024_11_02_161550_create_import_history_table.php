<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('import_history', function (Blueprint $table) {
            $table->id();
            $table->timestamp('imported_at')->useCurrent();
            $table->string('source_file');
            $table->integer('imported_count');
            $table->enum('status', ['success', 'failed']);
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('import_history');
    }
};
