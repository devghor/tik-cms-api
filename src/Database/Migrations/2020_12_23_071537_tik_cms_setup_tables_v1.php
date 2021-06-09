<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TikCmsSetupTablesV1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('tik_cms_languages')){
            Schema::create('tik_cms_languages', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('language')->nullable();
                $table->string('short_code')->nullable();
                $table->text('flag_src')->nullable();
                $table->text('countries')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if(!Schema::hasTable('tik_cms_facebook_tag_contents')){
            Schema::create('tik_cms_facebook_tag_contents', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('item_id')->unsigned();
                $table->string('item_type')->nullable();
                $table->string('title')->nullable();
                $table->text('keywords')->nullable();
                $table->text('description')->nullable();
                $table->text('img_src')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if(!Schema::hasTable('tik_cms_google_tag_contents')){
            Schema::create('tik_cms_google_tag_contents', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('item_id')->unsigned();
                $table->string('item_type')->nullable();
                $table->string('title')->nullable();
                $table->text('keywords')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if(!Schema::hasTable('tik_cms_meta_tag_contents')){
            Schema::create('tik_cms_meta_tag_contents', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('item_id')->unsigned();
                $table->string('item_type')->nullable();
                $table->string('title')->nullable();
                $table->text('keywords')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if(!Schema::hasTable('tik_cms_sub_pages')){
            Schema::create('tik_cms_sub_pages', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('parent_id')->unsigned();;
                $table->string('name');
                $table->longText('html')->nullable();
                $table->longText('published_html')->nullable();
                $table->string('status')->nullable();
                $table->integer('has_changes')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if(!Schema::hasTable('tik_cms_blogs')) {
            Schema::create('tik_cms_blogs', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('title');
                $table->text('short_description');
                $table->text('featured_image');
                $table->longText('content');
                $table->longText('published_content')->nullable();
                $table->string('author')->nullable();
                $table->string('status');
                $table->string('language');
                $table->string('tags')->nullable();
                $table->integer('has_changes')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if(!Schema::hasTable('tik_cms_web_pages')){
            Schema::create('tik_cms_web_pages', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->longText('html')->nullable();
                $table->longText('published_html')->nullable();
                $table->string('status')->nullable();
                $table->string('language')->nullable();
                $table->integer('has_changes')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if(!Schema::hasTable('tik_cms_blog_post_comments')){
            Schema::create('tik_cms_blog_post_comments', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('user_id')->unsigned();
                $table->integer('blog_id')->unsigned();
                $table->longText('comment')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if(!Schema::hasTable('tik_cms_blog_comment_replies')){
            Schema::create('tik_cms_blog_comment_replies', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('comment_id')->unsigned();
                $table->integer('user_id')->unsigned();
                $table->longText('reply_message')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }
        if(!Schema::hasTable('tik_cms_blog_post_reactions')){
            Schema::create('tik_cms_blog_post_reactions', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('blog_id')->unsigned();
                $table->integer('user_id')->unsigned();
                $table->string('reaction_type')->nullable();
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
        Schema::dropIfExists('tik_cms_languages');
        Schema::dropIfExists('tik_cms_facebook_tag_contents');
        Schema::dropIfExists('tik_cms_google_tag_contents');
        Schema::dropIfExists('tik_cms_meta_tag_contents');
        Schema::dropIfExists('tik_cms_sub_pages');
        Schema::dropIfExists('tik_cms_blogs');
        Schema::dropIfExists('tik_cms_web_pages');
        Schema::dropIfExists('tik_cms_blog_post_comments');
        Schema::dropIfExists('tik_cms_blog_comment_replies');
        Schema::dropIfExists('tik_cms_blog_post_reactions');

    }
}
