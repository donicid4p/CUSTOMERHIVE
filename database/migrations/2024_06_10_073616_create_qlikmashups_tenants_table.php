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
        Schema::create('qlikapps_tenants', function (Blueprint $table) {
            //
            $table->increments('id');
            $table->unsignedBigInteger('qlik_apps_id');
            $table->unsignedInteger('tenant_id');
            $table->unique(['qlik_apps_id', 'tenant_id'], 'qlik_app_tenant_unique');
            $table->foreign('qlik_apps_id')->references('id')->on('qlik_apps')->onDelete('cascade');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('qlikapps_tenants', function (Blueprint $table) {
            //
        });
    }
};
