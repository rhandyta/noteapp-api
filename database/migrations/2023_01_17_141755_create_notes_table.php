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
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->uuid('key')->unique();
            $table->foreignId('user_id')->constrained()->references('id')->on('users');
            $table->string('title', 150);
            $table->text('body');
            $table->string('slug');
            $table->tinyInteger('visible')->default(0)->comment('0 => visible, 1 => invisible');
            $table->tinyInteger('archive')->default(0)->comment('0 => not archive, 1 => archive');
            $table->timestamps();
            $table->softDeletes('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('notes', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
