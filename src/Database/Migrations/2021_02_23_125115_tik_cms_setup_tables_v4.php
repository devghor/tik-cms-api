<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TikCmsSetupTablesV4 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('tik_cms_blog_category_name_translations')){
            Schema::create('tik_cms_blog_category_name_translations', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('category_id');
                $table->unsignedBigInteger('language_id');
                $table->string('translated_name')->nullable();
                $table->foreign('category_id')->references('id')->on('tik_cms_blog_categories');
                $table->foreign('language_id')->references('id')->on('tik_cms_languages');
                $table->timestamps();
                $table->softDeletes();
            });
        }
        if(Schema::hasTable('tik_cms_blog_category_name_translations')){
            if(!Schema::hasColumn('tik_cms_blog_category_name_translations','parent_change_tracker')) {
                Schema::table('tik_cms_blog_category_name_translations', function(Blueprint $table) {
                    $table->integer('parent_change_tracker')->nullable();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tik_cms_blog_category_name_translations');
    }
}
