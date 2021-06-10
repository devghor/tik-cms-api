<?php


namespace Tikweb\TikCmsApi\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tikweb\TikCmsApi\Models\BlogCategoryNameTranslations;
use Tikweb\TikCmsApi\Models\BlogCategories;
use Tikweb\TikCmsApi\Models\Language;

class BlogCategoryNameTranslationController extends Controller
{
    public function create(Request $request)
    {
        $blog_category_name_translation = BlogCategoryNameTranslations::create([
            'category_id'           => $request->data['category'],
            'language_id'           => $request->data['language'],
            'translated_name'       => $request->data['translation'],
            'parent_change_tracker' => 0,
        ]);

        if($blog_category_name_translation) {
            return response()->json(['data' => "New translation added."]);
        }
        else {
            return response()->json(['data' => "Translation con not be added."]);
        }
    }

    public function showAllTranslatedBlogCategories()
    {
        $translatedBlogCategories = DB::table('tik_cms_blog_category_name_translations')
            ->join('tik_cms_languages', 'tik_cms_languages.id', '=', 'tik_cms_blog_category_name_translations.language_id')
            ->select('tik_cms_blog_category_name_translations.*', 'tik_cms_languages.short_code', 'tik_cms_languages.language')
            ->get();

        if($translatedBlogCategories) {
            return response()->json(['data' => $translatedBlogCategories]);
        }
        else {
            return response()->json(['message' => "Something wrong."]);
        }
    }

    public function updateTranslatedBlogCategoryName(Request $request) {

        $translatedCategory = BlogCategoryNameTranslations::where('id', request()->get('translated_category_id'))->first();

        if($translatedCategory){
            $translatedBlogCategory = BlogCategoryNameTranslations::where('id',$translatedCategory->id)
                ->update([
                    'translated_name' => $request->data['new_name'],
                ]);
            if($translatedCategory->parent_change_tracker == 1){
                $category = BlogCategories::where('id', $translatedCategory->category_id)
                    ->update([
                        'category_name' => $request->data['new_name'],
                    ]);
                return response()->json(['data' => "Name updated with parent."]);
            }
            if($translatedBlogCategory) {
                return response()->json(['data' => "Name updated."]);
            }
            else {
                return response()->json(['data' => "Something wrong."]);
            }
        }

    }

    public function destroy()
    {
        $translatedBlogCategory = BlogCategoryNameTranslations::where('id', request()->get('item_id'))
            ->first();

        if($translatedBlogCategory) {
            $translatedBlogCategory->forceDelete();
            return response()->json(['data' => "Category deleted successfully!"]);
        }
        else {
            return response()->json(['data' => "Something wrong!"]);
        }
    }

    public function getAllCategoryAccordingToLanguage() {

        $language = Language::where('short_code', request()->get('language'))
            ->select('id', 'short_code')
            ->first();
        if($language) {
            $categories = BlogCategoryNameTranslations::where('language_id', $language->id)
                ->select('translated_name as translation', 'category_id as parent_id')
                ->get();
            $data = [
                'local'      => $language->short_code,
                'categories' => $categories,
            ];
            return response()->json(['data' => $data]);
        }
        else {
            return response()->json(['data' => "Something wrong!"]);
        }
    }

    public function getAllCategoryOfAllLanguage() {
        $languages = Language::select('id', 'short_code')->get();
        $data = [
        ];
        if($languages) {
            foreach ($languages as $language) {
                $categories = BlogCategoryNameTranslations::where('language_id', $language->id)
                    ->select('translated_name as translation', 'category_id as parent_id')
                    ->get();
                $data[] =[
                    'local' => $language->short_code,
                    'category' => $categories
                ];

            }
            return response()->json(['data' => $data]);
        }
        else {
            return response()->json(['data' => "Something wrong!"]);
        }
    }

}
