<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeploymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deployments', function (Blueprint $table) {
            $table->char('id', 26)->primary();
            $table->char('project_id', 26)->nullable();
            $table->char('server_id', 26)->nullable();
            $table->string('status')->default('pending');
            $table->string('release');
            $table->json('commit');

            $table->longText('raw_output')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deployments');
    }
}
