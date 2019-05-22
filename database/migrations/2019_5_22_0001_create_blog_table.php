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

class CreateBlogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog', function (Blueprint $table) {
            $table->integer('id')->autoIncrement()->comment('主键ID');
            $table->string('title', 120)->comment('标题');
            $table->text('content')->comment('文章内容');
            $table->integer('type')->comment('文章类型');

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
        Schema::dropIfExists('blog');
    }
}