<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TikCmsSetupTablesV3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('tik_cms_blog_types')){
            Schema::create('tik_cms_blog_types', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('type_name')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if(Schema::hasTable('tik_cms_blogs')){
            if(!Schema::hasColumn('tik_cms_blogs','type')) {
                Schema::table('tik_cms_blogs', function(Blueprint $table) {
                    $table->string('type')->nullable();
                });
            }
        }
        if(Schema::hasTable('tik_cms_sub_pages')){
            if(!Schema::hasColumn('tik_cms_sub_pages','type')) {
                Schema::table('tik_cms_sub_pages', function(Blueprint $table) {
                    $table->string('type')->nullable();
                });
            }
        }

        if(Schema::hasTable('tik_cms_web_pages')){
            if(!Schema::hasColumn('tik_cms_web_pages','url')) {
                Schema::table('tik_cms_web_pages', function(Blueprint $table) {
                    $table->string('url')->nullable();
                });
            }
        }
        if(Schema::hasTable('tik_cms_sub_pages')){
            if(!Schema::hasColumn('tik_cms_sub_pages','url')) {
                Schema::table('tik_cms_sub_pages', function(Blueprint $table) {
                    $table->string('url')->nullable();
                });
            }
        }
        if(Schema::hasTable('tik_cms_blogs')){
            if(!Schema::hasColumn('tik_cms_blogs','url')) {
                Schema::table('tik_cms_blogs', function(Blueprint $table) {
                    $table->string('url')->nullable();
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
        Schema::dropIfExists('tik_cms_blog_types');
    }
}
