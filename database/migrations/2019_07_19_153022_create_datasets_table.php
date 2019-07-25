<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDatasetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('datasets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid');
            $table->string('name');
            $table->string('license');
            $table->funding('text');
            $table->text('description');
            $table->string('doi');
            $table->date('published_on');
            $table->date('privately_published_on');

            $table->text('description');
            $table->timestamps();
        });
    }

    /*
     * "authors": [ ],
"birthtime": Wed Jul 24 2019 14:55:03 GMT+00:00 ,
"description":  "" ,
"doi":  "" ,
"embargo_date":  "" ,
"funding":  "" ,
"id":  "b3c5b38b-23e7-44cb-9caf-4b8b2091baaa" ,
"institution":  "" ,
"keywords": [ ],
"license": {
"link":  "" ,
"name":  ""
} ,
"mtime": Wed Jul 24 2019 14:55:03 GMT+00:00 ,
"otype":  "dataset" ,
"owner": test@test.mc, »
"papers": [ ],
"published": false ,
"published_date": Wed Jul 24 2019 14:55:03 GMT+00:00 ,
"selection_id":  "272a6a2b-1dd1-4493-93d0-0923c4239f35" ,
"title":  "45b94927-56be-4c91-ae65-a2f7647f0c58"
     */

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('datasets');
    }
}
