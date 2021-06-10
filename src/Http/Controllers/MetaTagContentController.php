<?php


namespace Tikweb\TikCmsApi\Http\Controllers;

use Tikweb\TikCmsApi\Models\MetaTag;
use Tikweb\TikCmsApi\Models\MetaTagContent;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class MetaTagContentController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $meta_tag_content = MetaTagContent::create([
            'item_id'       => $request->data['item_id'],
            'item_type'     => $request->data['item_type'],
            'title'         => null,
            'keywords'      => null,
            'description'   => null,
        ]);
        if($meta_tag_content) {
            return response()->json(['success'=>$meta_tag_content]);
        }
        else {
            return response()->json(['error'=>"meta tag content  is not created!"]);
        }
    }


    public function showAll()
    {
        $meta_tag_content = MetaTagContent::all();
        if($meta_tag_content){
            return response()->json(['meta_tag_contents' => $meta_tag_content]);
        }
        return response()->json(['meta_tag_contents' => "meta tag contents not found"]);
    }

    public function showItemMetaTag()
    {
        $meta_tag_content = MetaTagContent::where('item_id', request()->get('item_id'))
            ->where('item_type',request()->get('item_type'))
            ->get();

        if($meta_tag_content){
            return response()->json(['meta_tag_contents' => $meta_tag_content]);
        }
        return response()->json(['meta_tag_content' => "meta tag contents not found"]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateMetaTagContent(Request $request)
    {
        $meta_tag = MetaTagContent::where('id', request()->get('item_id'))
            ->update([
                'title'       => $request->data['title'],
                'keywords'    => $request->data['keywords'],
                'description' => $request->data['description']
            ]);
        if($meta_tag) {
            return response()->json(['success'=>'meta tag content updated']);
        }
        else {
            return response()->json(['error'=>'something wrong!']);
        }
    }

    /**
     * get all  page meta tag contents
     */
    public function showAllMetaTagContentForPages()
    {
        $meta_tag_contents = DB::table('tik_cms_meta_tag_contents')
            ->where('item_type','page')
            ->join('tik_cms_web_pages', function($join)
            {
                $join->on("tik_cms_web_pages.id", "=", "tik_cms_meta_tag_contents.item_id")
                    ->where("tik_cms_web_pages.status", "=", "published");
            })
            ->select('tik_cms_web_pages.id as page_id','tik_cms_web_pages.name as page_name', 'tik_cms_web_pages.language as page_language',
                'tik_cms_meta_tag_contents.title as meta_title','tik_cms_meta_tag_contents.keywords as meta_keywords','tik_cms_meta_tag_contents.description as meta_description')
            ->get();
        if($meta_tag_contents) {
            return response()->json(['success'=>$meta_tag_contents]);
        }
        else {
            return response()->json(['error'=>'something wrong!']);
        }

    }

    public function showAllMetaTagContentForBlogs() {
        $meta_tag_contents = DB::table('tik_cms_meta_tag_contents')
            ->where('item_type', 'blog')
            ->join('tik_cms_blogs', 'tik_cms_blogs.id', '=', 'tik_cms_meta_tag_contents.item_id')
            ->select('tik_cms_blogs.id as blog_id', 'tik_cms_blogs.title as blog_name', 'tik_cms_blogs.language as blogs_language',
                'tik_cms_meta_tag_contents.title as meta_title','tik_cms_meta_tag_contents.keywords as meta_keywords','tik_cms_meta_tag_contents.description as meta_description')
            ->get();

        if($meta_tag_contents) {
            return response()->json(['success'=>$meta_tag_contents]);
        }
        else {
            return response()->json(['error'=>'something wrong!']);
        }
    }


}
