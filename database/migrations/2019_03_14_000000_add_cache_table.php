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

class AddCacheTable extends Migration
{
    public function up()
    {
        Schema::create('cache', function($table)
        {
            $table->string('key')->unique();
            $table->text('value');
            $table->integer('expiration');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cache');
    }

}
