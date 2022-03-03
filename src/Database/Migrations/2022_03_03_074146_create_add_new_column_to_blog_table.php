<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddNewColumnToBlogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('tik_cms_blogs')){
            if(!Schema::hasColumn('tik_cms_blogs','author_name')) {
                Schema::table('tik_cms_blogs', function(Blueprint $table) {
                    $table->string('author_name')->nullable()->after('author');
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
        if(Schema::hasTable('tik_cms_blogs')){
            Schema::dropColumns('tik_cms_blogs', ['author_name']);
        }
    }
}
