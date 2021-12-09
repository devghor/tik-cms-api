<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TikCmsSetupTablesV6 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('tik_cms_blogs')){
            if(!Schema::hasColumn('tik_cms_blogs','published_date')) {
                Schema::table('tik_cms_blogs', function(Blueprint $table) {
                    $table->dateTime('published_date')->nullable();
                    $table->dateTime('last_edit')->nullable();
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

    }
}
