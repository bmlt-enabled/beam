<?php
/**
 * Created by IntelliJ IDEA.
 * User: danny
 * Date: 2019-03-13
 * Time: 12:13
 */
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBeamsTable extends Migration
{
    public function up()
    {
        Schema::create('beams', function($table)
        {
            $table->bigIncrements('id');
            $table->text('name');
            $table->text('url');
        });
    }

    public function down()
    {
        Schema::dropIfExists('beams');
    }
}
