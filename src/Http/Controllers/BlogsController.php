<?php

namespace Devghor\TikCmsApi\Http\Controllers;

use App\Http\Controllers\Controller;
use Devghor\TikCmsApi\Models\Blog;
use Devghor\TikCmsApi\Models\BlogCategories;
use Devghor\TikCmsApi\Models\BlogType;
use Devghor\TikCmsApi\Models\FacebookTagContent;
use Devghor\TikCmsApi\Models\GoogleTagContent;
use Devghor\TikCmsApi\Models\MetaTagContent;
use Devghor\TikCmsApi\Data\CharacterList;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;



class BlogsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $blog_type = BlogType::where('id', $request->data['type'])->first();

        $url = $request->data['language'].'/'.strtolower($blog_type->type_name).'/'.strtolower($request->data['title']);
        $url = str_replace(' ', '', $url);
        $url = preg_replace('/[^a-zA-Z0-9_\/-]/s','',$url);

        $slug_url = $this->createSlugUrl(trim($request->data['title']));

        $blog = Blog::create([
            'title'             => trim($request->data['title']),
            'short_description' => $request->data['short_description'],
            'featured_image'    => $request->data['image_src'],
            'content'           => $request->data['content'],
            'published_content' => null,
            'status'            => 'draft',
            'author'            => auth()->user()->id,
            'has_changes'       => 0,
            'language'          => $request->data['language'],
            'category'          => $request->data['category'],
            'type'              => $request->data['type'],
            'tags'              => $request->data['tags'],
            'url'               => $url,
            'slug_url'          => $slug_url,
        ]);
        if($blog) {
            $meta_tag_content = MetaTagContent::create([
                'item_id'       => $blog['id'],
                'item_type'     => "blog",
            ]);
            $google_tag_content = GoogleTagContent::create([
                'item_id'       => $blog['id'],
                'item_type'     => "blog",
            ]);
            $facebook_tag_content = FacebookTagContent::create([
                'item_id'       => $blog['id'],
                'item_type'     => "blog",
            ]);
            return response()->json(['success'=>$blog]);
        }
        else {
            return response()->json(['error'=>"blog is not created!"]);
        }
    }

    public function createSlugUrl($title) {
        //check if same title is already exist
        $existing_blogs = Blog::where('title', $title)->get()->count();

        $translated = str_replace(array_keys(CharacterList::DATA), CharacterList::DATA, $title);
        //remove special characters
        $without_special_char = trim(preg_replace('/[^a-zA-Z0-9- ]/','', strtolower($translated)));
        //replace space with '-' & make lower case
        $slug = str_replace(' ', '-', $without_special_char);
        //check count
        if($existing_blogs > 0) {
            //increase number value
            $existing_blogs +=  1;
            //create & return slug
            return $slug.'-'.$existing_blogs;
        }
        //create & return slug
        return $slug;

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function cloneBlog(Request $request)
    {
        $parent_blog = Blog::where('id', $request->data['component_id'])->first();

        if($parent_blog) {
            foreach ($request->data['languages'] as $language){
                $blog_type = BlogType::where('id', $parent_blog['type'])->first();
                $url = $language.'/'.strtolower($blog_type->type_name).'/'.strtolower($request->data['name']);
                $url = str_replace(' ', '', $url);
                $url = preg_replace('/[^a-zA-Z0-9_\/-]/s','',$url);

                $slug_url = $this->createSlugUrl(trim($request->data['name']));

                $blog = Blog::create([
                    'title'             => $request->data['name'],
                    'short_description' => $parent_blog['short_description'],
                    'featured_image'    => $parent_blog['featured_image'],
                    'content'           => $parent_blog['content'],
                    'published_content' => null,
                    'status'            => 'draft',
                    'author'            => auth()->user()->id,
                    'category'          => $request->data['category'],
                    'type'              => $parent_blog['type'],
                    'language'          => $language,
                    'tags'              => $parent_blog['tags'],
                    'url'               => $url,
                    'slug_url'          => $slug_url,
                ]);
                if($blog) {
                    $meta_tag_content = MetaTagContent::create([
                        'item_id'       => $blog['id'],
                        'item_type'     => "blog",
                    ]);
                    $google_tag_content = GoogleTagContent::create([
                        'item_id'       => $blog['id'],
                        'item_type'     => "blog",
                    ]);
                    $facebook_tag_content = FacebookTagContent::create([
                        'item_id'       => $blog['id'],
                        'item_type'     => "blog",
                    ]);
                }
            }
            return response()->json(['message'=>'blog cloned.']);
        }
        else {
            return response()->json(['message'=>'something wrong']);
        }

    }


    /**
     * Display the specified resource.
     *
     *
     */
    public function show()
    {
        $blog = Blog::select('id','title', 'content', 'status')
            ->where('id', request()->get('blog_id'))->first();
        if($blog){
            return response()->json(['blog' => $blog]);
        }
        return response()->json(['error' => "blog not found"]);
    }

    /**
     * Display the specified blog published designs.
     * need blog id
     * blog published content will be returned
     */
    public function showPublishedContent()
    {
        $blog = Blog::select('id','title', 'author', 'author_name', 'short_description','status','published_content', 'language', 'url', 'slug_url', 'published_date', 'last_edit')
            ->where('id', request()->get('blog_id'))
            ->where('status','published')
            ->first();
        if($blog){
            return response()->json(['blog' => $blog]);
        }
        else {
            return response()->json(['error' => "published blog content not found"]);
        }
    }

    public function showAllPublishedBlog()
    {
        $blog = Blog::where('status','published')->get();
        if($blog){
            return response()->json(['blog' => $blog]);
        }
        else {
            return response()->json(['error' => "published blog content not found"]);
        }

    }

    public function showAll()
    {
        $blog = Blog::select('id','title', 'author', 'author_name', 'short_description','status', 'has_changes', 'language', 'url', 'slug_url', 'category', 'published_date', 'last_edit')
            ->where('language', request()->get('language'))
            ->get();
        if($blog){
            return response()->json(['blog' => $blog]);
        }
        return response()->json(['blogs' => "blogs not found"]);
    }

    public function showAllTrashBlogs()
    {
        $blogs = Blog::onlyTrashed()->get();
        if($blogs){
            return response()->json(['blog' => $blogs]);
        }
        return response()->json(['blogs' => "blogs not found"]);
    }


    /**
     * Show the form for editing the specified resource.
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $blog = Blog::where('id', request()->get('blog_id'))->first();
        if (!empty($blog)) {
            if (empty($blog->published_date)) {
                $blog->published_date = now();
            }
            $blog->update([
                'published_content' => $request->data['content'],
                'content' => $request->data['content'],
                'status' => "published",
                'published_date' => $blog->published_date,
                'last_edit' => now(),
                'has_changes' => 0,
            ]);
            return response()->json(['success' => 'blog updated']);
        }
        else {
            return response()->json(['error'=>'something wrong!']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @return \Illuminate\Http\Response
     */
    public function draftContent(Request $request)
    {
        $blog = Blog::where('id', request()->get('item'))
            ->update([
                'content'   => $request->data['content'],
                'has_changes'    => 1,
            ]);
        if($blog) {
            return response()->json(['success'=>'blog updated']);
        }
        else {
            return response()->json(['error'=>'something wrong!']);
        }
    }

    public function updateChangeStatus(Request $request)
    {
        $blog = Blog::where('id', request()->get('item'))
            ->update([
                'has_changes'    => 0,
            ]);
        if($blog) {
            return response()->json(['success'=>'Blog status updated']);
        }
        else {
            return response()->json(['error'=>'something wrong!']);
        }
    }

    public function updateInfo(Request $request)
    {
        $url = str_replace(' ', '', $request->data['url']);
        $url = preg_replace('/[^a-zA-Z0-9_\/-]/s','',$url);

        $blog = Blog::where('id', request()->get('item_id'))
            ->update([
                'title'             => $request->data['name'],
                'status'            => $request->data['status'],
                'short_description' => $request->data['short_description'],
                'category'          => $request->data['category'],
                'author_name'       => $request->data['author_name'],
                'url'               => $url
            ]);
        if($blog) {
            return response()->json(['message'=>'Blog info updated.']);
        }
        else {
            return response()->json(['message'=>'Something wrong!']);
        }
    }

    public function updateImage(Request $request)
    {

        $blog = Blog::where('id', request()->get('blog_id'))
            ->update([
                'featured_image'=> $request->data['image_src']
            ]);
        if($blog) {
            return response()->json(['message'=>'Blog image updated.']);
        }
        else {
            return response()->json(['message'=>'Something wrong!']);
        }
    }



    public function restore()
    {
        $blog = Blog::onlyTrashed()->find(request()->get('blog_id'));
        if($blog){
            $meta_tag_content = MetaTagContent::onlyTrashed()->where('item_id', $blog['id'])
                ->where('item_type', "blog")
                ->first();
            $meta_tag_content->restore();
            $google_tag_content = GoogleTagContent::onlyTrashed()->where('item_id', $blog['id'])
                ->where('item_type', "blog")
                ->first();
            $google_tag_content->restore();
            $facebook_tag_content = FacebookTagContent::onlyTrashed()->where('item_id', $blog['id'])
                ->where('item_type', "blog")
                ->first();
            $facebook_tag_content->restore();
            $blog->restore();
            return response()->json(['blog' => $blog]);
        }
        return response()->json(['blogs' => "blogs not found"]);
    }

    /**
     * Remove the specified resource from storage.
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        $find = Blog::where('id', request()->get('item_id'))->first();
        if($find){
            $meta_tag_content = MetaTagContent::where('item_id', $find['id'])
                ->where('item_type', "blog")
                ->first();
            $meta_tag_content->delete();
            $google_tag_content = GoogleTagContent::where('item_id', $find['id'])
                ->where('item_type', "blog")
                ->first();
            $google_tag_content->delete();
            $facebook_tag_content = FacebookTagContent::where('item_id', $find['id'])
                ->where('item_type', "blog")
                ->first();
            $facebook_tag_content->delete();
            $blog = $find->delete();
            return response()->json(['success' => "blog deleted successfully!"]);
        }
        else {
            return response()->json(['error' => "something wrong!"]);
        }
    }

    public function permanentDelete()
    {
        $find = Blog::onlyTrashed()->find(request()->get('item_id'));
        if($find){
            $meta_tag_content = MetaTagContent::onlyTrashed()->where('item_id', $find['id'])
                ->where('item_type', "blog")
                ->first();
            $meta_tag_content->forceDelete();
            $google_tag_content = GoogleTagContent::onlyTrashed()->where('item_id', $find['id'])
                ->where('item_type', "blog")
                ->first();
            $google_tag_content->forceDelete();
            $facebook_tag_content = FacebookTagContent::onlyTrashed()->where('item_id', $find['id'])
                ->where('item_type', "blog")
                ->first();
            $facebook_tag_content->forceDelete();
            $blog = $find->forceDelete();
            return response()->json(['success' => "blog deleted successfully!"]);
        }
        else {
            return response()->json(['error' => "something wrong!"]);
        }
    }

    /**
     * Get all seo tags for a blog
     * request parameter  blog id
     * return response all seo tags
     */

    public function getAllTagInfo () {
        $seo_tags = DB::table('tik_cms_blogs')
            ->where('tik_cms_blogs.id', request()->get('blog_id'))
            ->join('tik_cms_meta_tag_contents', function($join)
            {
                $join->on("tik_cms_blogs.id", "=", "tik_cms_meta_tag_contents.item_id")
                    ->where("tik_cms_meta_tag_contents.item_type", "=", "blog");
            })
            ->join('tik_cms_google_tag_contents', function($join)
            {
                $join->on("tik_cms_blogs.id", "=", "tik_cms_google_tag_contents.item_id")
                    ->where("tik_cms_google_tag_contents.item_type", "=", "blog");
            })
            ->join('tik_cms_facebook_tag_contents', function($join)
            {
                $join->on("tik_cms_blogs.id", "=", "tik_cms_facebook_tag_contents.item_id")
                    ->where("tik_cms_facebook_tag_contents.item_type", "=", "blog");
            })
            ->select('tik_cms_blogs.title as blog_title',
                'tik_cms_meta_tag_contents.title as meta_title','tik_cms_meta_tag_contents.keywords as meta_keywords','tik_cms_meta_tag_contents.description as meta_description',
                'tik_cms_google_tag_contents.title as google_title','tik_cms_google_tag_contents.keywords as google_keywords','tik_cms_google_tag_contents.description as google_description',
                'tik_cms_facebook_tag_contents.title as fb_title','tik_cms_facebook_tag_contents.keywords as fb_keywords','tik_cms_facebook_tag_contents.description as fb_description', 'tik_cms_facebook_tag_contents.img_src as fb_img_src')
            ->get();

        if($seo_tags){
            return response()->json(['$seo_tags' => $seo_tags]);
        }
        return response()->json(['$seo_tags' => "seo tags contents not found"]);
    }

    public function individualPageTags() {
        $data = [
            'blog_id' => request()->get('item_id'),
            'meta_tag'      => '',
            'google_tag'    => '',
            'facebook_tag'  => ''
        ];
        $published_blog = Blog::select('id', 'title')
            ->where('id', request()->get('item_id'))
            ->first();
        if($published_blog) {
            $meta_tag = MetaTagContent::where('item_id', request()->get('item_id'))
                ->where('item_type', 'blog')
                ->first();
            $google_tag = GoogleTagContent::where('item_id', request()->get('item_id'))
                ->where('item_type', 'blog')
                ->first();
            $facebook_tag = FacebookTagContent::where('item_id', request()->get('item_id'))
                ->where('item_type', 'blog')
                ->first();

            $data['meta_tag']    = $meta_tag;
            $data['google_tag']    = $google_tag;
            $data['facebook_tag']  = $facebook_tag;
            return response()->json(['data' => $data]);
        }
        else {
            return response()->json(['data' => "seo tags contents not found"]);
        }

    }

    public function showAllIndividualTypePublishedBlog() {
        $post_type = BlogType::select('id')->where('type_name', request()->get('post_type'))->first();

        if($post_type) {
            $posts = Blog::select('id', 'title', 'featured_image', 'author', 'author_name', 'short_description', 'featured_image', 'author', 'language', 'created_at', 'short_description', 'featured_image', 'author', 'slug_url', 'published_date', 'last_edit')
                ->where([
                    'type'  => $post_type->id,
                    'status'=> "published"
                ])->get();
            if($posts) {
                return response()->json(['data' => $posts]);
            }
            else {
                return response()->json(['data' => "Something wrong."]);
            }
        }
        else {
            return response()->json(['data' => "Type not found"]);
        }
    }

    public function showAllIndividualCategoryPublishedBlog() {
        $post_category = BlogCategories::select('id')->where('category_name', request()->get('blog_category'))->first();

        if($post_category) {
            $posts = Blog::select('id', 'title', 'featured_image', 'author', 'author_name', 'short_description', 'featured_image', 'author', 'language', 'created_at', 'short_description', 'featured_image', 'author', 'slug_url', 'published_date', 'last_edit')

                ->where([
                    'category'  => $post_category->id,
                    'status'=> "published"
                ])->get();
            if($posts) {
                return response()->json(['data' => $posts]);
            }
            else {
                return response()->json(['data' => "Something wrong."]);
            }
        }
        else {
            return response()->json(['data' => "Category not found"]);
        }
    }

    public function getAllBlogWithLanguage() {

        $allBlogs = Blog::where('published_date', 'last_edit')
            ->where('status', 'published')
            ->get()
            ->groupBy('language');
            


        if($allBlogs) {
            $allBlogs = $allBlogs->groupBy('language');
            $data = [
                'list' => $allBlogs
            ];
            return response()->json(['data' => $data]);
        }
        return response()->json(['data' => "No blogs found"]);
    }

    public function getAllBlogWithLanguageAndCategory() {

        $category = BlogCategories::where('category_name', request()->get('category_name'))->first();
        $allBlogs = Blog::select('id','title', 'featured_image', 'author', 'author_name', 'short_description','status','published_content', 'language', 'url', 'slug_url', 'published_date', 'last_edit')
            ->where('category', $category->id)
            ->where('status', 'published')
            ->get();

        if($allBlogs) {
            $allBlogs = $allBlogs->groupBy('language');
            $data = [
                'list' => $allBlogs
            ];
            return response()->json(['data' => $data]);
        }
        else{
            return response()->json(['data' => "No blogs found"]);
        }
    }


}
