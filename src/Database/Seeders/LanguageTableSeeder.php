<?php


namespace Tikweb\TikCmsApi\Database\Seeders;

use Illuminate\Database\Seeder;
use Tikweb\TikCmsApi\Models\Language;

class LanguageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $languages = [
            [
                'language'=>'English',
                'short_code'=>'en',
                'country'=>'United Kingdom',
            ],
            [
                'language'=>'Dansih',
                'short_code'=>'da',
                'country'=>'Denmark',
            ],
        ];

        foreach ($languages as $item) {
            $mLanguage = new Language();
            $mLanguage->language = $item['language'];
            $mLanguage->short_code = $item['short_code'];
            $mLanguage->countries = $item['country'];
            $mLanguage->save();
        }
    }
}
