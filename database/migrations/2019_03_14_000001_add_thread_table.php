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

class AddThreadTable extends Migration
{
    public function up()
    {
        Schema::create('thread', function($table)
        {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->integer('beam_id');
            $table->text('message');
            $table->timestamp('created_at')->nullable();
            $table->integer('parent_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('thread');
    }

}
