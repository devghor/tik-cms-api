<?php


namespace Tikweb\TikCmsApi\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tikweb\TikCmsApi\Models\BlogCategories;
use Tikweb\TikCmsApi\Models\Blog;
use Tikweb\TikCmsApi\Models\BlogCategoryNameTranslations;

class BlogCategoryController extends Controller
{

    public function create(Request $request) {

        if($request->data['category_name']) {
            $category = BlogCategories::create([
                'category_name' => $request->data['category_name'],
            ]);
            if($category) {

                $blog_category_name_translation = BlogCategoryNameTranslations::create([
                    'category_id'           => $category->id,
                    'language_id'           => $request->data['language'],
                    'translated_name'       => $request->data['category_name'],
                    'parent_change_tracker' => 1,
                ]);
                return response()->json(['data' => "Category added."]);
            }
            else {
                return response()->json(['data' => "Something wrong. Category can not be added."]);
            }
        }
        else {
            return response()->json(['data' => "Please pass a string"]);
        }

    }

    public function showAllBlogCategories() {
        $categories = BlogCategories::select('id', 'category_name')->get();
        if($categories) {
            return response()->json(['data' => $categories]);
        }
        else {
            return response()->json(['message' => "Something wrong."]);
        }
    }

    public function updateCategoryName(Request $request) {

        $categoryUpdated = BlogCategories::where('id', request()->get('category_id'))
            ->update([
                'category_name' => $request->data['new_name'],
            ]);
        if($categoryUpdated) {
            $translatedBlogCategory = BlogCategoryNameTranslations::where('category_id',request()->get('category_id'))
                ->where('parent_change_tracker', 1)
                ->update([
                    'translated_name' => $request->data['new_name'],
                ]);
            if($translatedBlogCategory) {
                return response()->json(['data' => "Name updated with child translation."]);
            }else {
                return response()->json(['data' => "Name updated."]);
            }
        }
        else {
            return response()->json(['data' => "Something wrong."]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        $category = BlogCategories::where('id', request()->get('item_id'))
            ->first();

        if($category) {
            $assigned_blogs = Blog::where('category', $category['id'])
                ->get();

            if(!$assigned_blogs->isEmpty()) {
                return response()->json(['data' => "Some blogs are already assigned in these category.\nPlease delete those pages."]);
            }
            else {
                $category->delete();
                return response()->json(['data' => "Category deleted successfully!"]);
            }
        }
        else {
            return response()->json(['data' => "something wrong!"]);
        }
    }

    public function showAllTrashCategory()
    {
        $categories = BlogCategories::select('id', 'category_name')
            ->onlyTrashed()
            ->get();
        if($categories){
            return response()->json(['data' => $categories]);
        }
        else {
            return response()->json(['data' => "No category is in trash."]);
        }
    }

    public function restore()
    {
        $category = BlogCategories::onlyTrashed()->find(request()->get('category_id'));
        if($category){
            $category->restore();
            return response()->json(['data' => "Category restored."]);
        }
        else {
            return response()->json(['data' => "Something wrong!"]);
        }
    }

    public function permanentDelete()
    {
        $category = BlogCategories::onlyTrashed()->find(request()->get('item_id'));
        if($category){
            $category->forceDelete();
            return response()->json(['data' => "Category deleted successfully!"]);
        }
        else {
            return response()->json(['data' => "Something wrong!"]);
        }
    }

}
