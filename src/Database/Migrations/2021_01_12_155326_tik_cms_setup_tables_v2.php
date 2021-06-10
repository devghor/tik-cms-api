<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TikCmsSetupTablesV2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('tik_cms_page_groups')){
            Schema::create('tik_cms_page_groups', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('group_name')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }
        if(Schema::hasTable('tik_cms_web_pages')){
            if(!Schema::hasColumn('tik_cms_web_pages','page_group')) {
                Schema::table('tik_cms_web_pages', function(Blueprint $table) {
                    $table->string('page_group')->nullable();
                });
            }
        }
        if(!Schema::hasTable('tik_cms_blog_categories')){
            Schema::create('tik_cms_blog_categories', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('category_name')->unique()->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if(Schema::hasTable('tik_cms_blogs')){
            if(!Schema::hasColumn('tik_cms_blogs','category')) {
                Schema::table('tik_cms_blogs', function(Blueprint $table) {
                    $table->string('category');
                    $table->foreign('category')->references('id')->on('tik_cms_blog_categories');
                });
            }
        }

        if(!Schema::hasTable('tik_cms_blog_default_style')){
            Schema::create('tik_cms_blog_default_style', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('featured_image_height')->nullable();
                $table->string('featured_image_width')->nullable();
                $table->string('blog_title_font_family')->nullable();
                $table->string('blog_title_font_size')->nullable();
                $table->string('blog_title_font_weight')->nullable();
                $table->string('blog_title_color')->nullable();
                $table->string('blog_content_font_family')->nullable();
                $table->string('blog_content_font_size')->nullable();
                $table->string('blog_content_font_weight')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tik_cms_page_groups');
        Schema::dropIfExists('tik_cms_blog_categories');
        Schema::dropIfExists('tik_cms_blog_default_style');
    }
}
