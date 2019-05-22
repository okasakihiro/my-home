<?php
/**
 * Created by PhpStorm.
 * Author: rentianyi
 * Email:rentianyi@oa.pencilnews.cn
 * Date: 2019/5/22 4:10 PM
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateExchangeRateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exchange_rate', function (Blueprint $table) {
            $table->integer('id')->autoIncrement()->comment('主键ID');
            $table->string('usd_to_jpy')->nullable(false)->comment('美元对日元汇率');
            $table->string('cny_to_jpy')->nullable(false)->comment('人民币对日元汇率');

            $table->dateTime('created_at')
                ->nullable(false)
                ->default(DB::raw('CURRENT_TIMESTAMP'))
                ->comment('创建时间');

            $table->dateTime('updated_at')
                ->nullable(false)
                ->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))
                ->comment('修改时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exchange_rate');
    }
}