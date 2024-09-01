<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('todos', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('task'); // Title of the to-do item
            $table->integer('status')->default(0); // Status of the to-do item (e.g., 0 for incomplete, 1 for complete)
            $table->timestamps(); // Created_at and updated_at timestamps
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('todos');
    }
};
