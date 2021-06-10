<?php


namespace Tikweb\TikCmsApi\Database\Seeders;

use Illuminate\Database\Seeder;
use Tikweb\TikCmsApi\Models\PageGroup;

class PageGroupTableSeeder  extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $groups = [
            [
                'group_name'=>'About Us',
            ],
            [
                'group_name'=>'Terms and Conditions',
            ],
            [
                'group_name'=>'Services',
            ],
            [
                'group_name'=>'FAQ',
            ],
        ];

        foreach ($groups as $item) {
            $mGroup = new PageGroup();
            $mGroup->group_name = $item['group_name'];
            $mGroup->save();
        }
    }
}
