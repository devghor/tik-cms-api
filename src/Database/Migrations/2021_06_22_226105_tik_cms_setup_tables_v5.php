<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TikCmsSetupTablesV5 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('tik_cms_blogs')){
            if(!Schema::hasColumn('tik_cms_blogs','slug_url')) {
                Schema::table('tik_cms_blogs', function(Blueprint $table) {
                    $table->string('slug_url')->nullable();
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
