<?php

namespace Tikweb\TikCmsApi\Http\Controllers;

use Tikweb\TikCmsApi\Models\FacebookTagContent;
use Tikweb\TikCmsApi\Models\GoogleTagContent;
use Tikweb\TikCmsApi\Models\MetaTagContent;
use Tikweb\TikCmsApi\Models\PageGroup;
use Tikweb\TikCmsApi\Models\WebPage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class WebPageController extends Controller
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
        $_exist = WebPage::where('name',$request->data['name'])
            ->where('language',$request->data['language'])
            ->first();

        if($_exist) {
            return response()->json(['error'=>'You have a page in same name & language']);
        }
        else {
            $url = $request->data['language'].'/'.strtolower($request->data['name']);
            $url = str_replace(' ', '', $url);
            $url = preg_replace('/[^a-zA-Z0-9_\/-]/s','',$url);

            $page = WebPage::create([
                'name'              => $request->data['name'],
                'html'              => $request->data['html'],
                'published_html'    => $request->data['html'],
                'page_group'        => $request->data['group'],
                'status'            => 'draft',
                'language'          => $request->data['language'],
                'url'               => $url,
            ]);
            if($page) {
                $meta_tag_content = MetaTagContent::create([
                    'item_id'       => $page['id'],
                    'item_type'     => "page",
                ]);
                $google_tag_content = GoogleTagContent::create([
                    'item_id'       => $page['id'],
                    'item_type'     => "page",
                ]);
                $facebook_tag_content = FacebookTagContent::create([
                    'item_id'       => $page['id'],
                    'item_type'     => "page",
                ]);
                return response()->json(['success'=>'page added successfully']);
            }
            else {
                return response()->json(['error'=>'something wrong']);
            }
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function clonePage(Request $request)
    {
        $parent_page = WebPage::where('id', $request->data['component_id'])->first();
        $_exist_page = '';

        if($parent_page) {
            foreach ($request->data['languages'] as $language){

                $_exist = WebPage::where('name',$request->data['name'])
                    ->where('language',$language)
                    ->first();

                if($_exist){
                    $_exist_page = $language . ' ' . $_exist_page;
                }
                else {

                    $url = $language.'/'.strtolower($request->data['name']);
                    $url = str_replace(' ', '', $url);
                    $url = preg_replace('/[^a-zA-Z0-9_\/-]/s','',$url);

                    $page = WebPage::create([
                        'name'              => $request->data['name'],
                        'html'              => $parent_page['html'],
                        'published_html'    => $parent_page['published_html'],
                        'language'          => $language,
                        'page_group'        => $request->data['group'],
                        'status'            => 'draft',
                        'has_changes'       => 0,
                        'url'               => $url,
                    ]);
                    if($page) {
                        $meta_tag_content = MetaTagContent::create([
                            'item_id'       => $page['id'],
                            'item_type'     => "page",
                        ]);
                        $google_tag_content = GoogleTagContent::create([
                            'item_id'       => $page['id'],
                            'item_type'     => "page",
                        ]);
                        $facebook_tag_content = FacebookTagContent::create([
                            'item_id'       => $page['id'],
                            'item_type'     => "page",
                        ]);

                    }
                }
            }
            if($_exist_page !== ''){
                return response()->json(['message'=>'Page is already exist for the following language(s): '.$_exist_page.'.']);
            }
            else {
                return response()->json(['message'=>'Page added successfully for your selected languages.']);
            }

        }
        else {
            return response()->json(['message'=>'something wrong']);
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $page = WebPage::where('name', request()->get('page_name'))->first();
        if($page){
            return response()->json(['page' => $page]);
        }
        return response()->json(['page' => "page not found"]);
    }

    public function showPageDesign () {
        $design = WebPage::select('html')
            ->where('id', request()->get('page_id'))->first();
        if($design){
            return response()->json(['design' => $design]);
        }
        return response()->json(['design' => "design not found"]);
    }


    public function showPublishedPageDesign () {
        $design = WebPage::select('id','published_html')
            ->where('name', request()->get('page_name'))
            ->where('language', request()->get('page_language'))
            ->where('status','published')->first();
        if($design){
            return response()->json(['design' => $design]);
        }
        else {
            return response()->json(['design' => "design not found"]);
        }
    }


    public function getAllPublishedPageDesign () {
        $all_design = WebPage::select('id','name','published_html')
            ->where('language', request()->get('page_language'))
            ->where('status','published')
            ->get();
        if($all_design){
            return response()->json(['all_design' => $all_design]);
        }
        else {
            return response()->json(['design' => "design not found"]);
        }
    }

    public function showAll()
    {
        $page = WebPage::select('id','name', 'status', 'has_changes', 'language', 'page_group','url')
            ->where('language', request()->get('language'))
            ->get();
        if($page){
            return response()->json(['page' => $page]);
        }
        return response()->json(['page' => "page not found"]);
    }

    /**
     * Display the specified resource.
     * pass page name
     * return get array of published html designs of all languages of a page
     */
    public function getSpecificPageDesignForAllLanguage()
    {
        $data = [
            'name' => '',
            'pages' => [],
        ];

        $pages = WebPage::select('language', 'published_html')
            ->where('name', request()->get('page_name'))
            ->where('status', 'published')
            ->get();

        if ($pages) {
            $data['name'] = request()->get('page_name');
            foreach ($pages as $pg) {
                $data['pages'][] = [
                    'locale' => $pg->language,
                    'content' => $pg->published_html
                ];
            }
            return response()->json(['data' => $data]);
        }
        return response()->json(['error' => "page not found"]);
    }

    /**
     * Display the specified resource.
     * pass group name
     * return get array of published html designs of all languages of a group
     */
    public function getSpecificGroupPageDesignForAllLanguage()
    {
        $data = [
            'group_name' => '',
            'pages' => [],
        ];

        $page_group = PageGroup::select('id')
            ->where('group_name', request()->get('page_group_name'))
            ->first();

        $pages = WebPage::select('name','language', 'published_html')
            ->where('page_group', $page_group->id)
            ->where('status', 'published')
            ->get();

        if ($pages) {
            $data['group_name'] = request()->get('page_group_name');
            foreach ($pages as $pg) {
                $data['pages'][] = [
                    'page_name' => $pg->name,
                    'locale'    => $pg->language,
                    'content'   => $pg->published_html
                ];
            }
            return response()->json(['data' => $data]);
        }
        return response()->json(['error' => "page not found"]);
    }


    /**
     * Display the specified resource.
     * pass group name
     * return get array of published html designs of all languages of a group
     */
    public function getSpecificGroupPageDesignByGroupIDForAllLanguage()
    {
        $data = [
            'group_name' => '',
            'pages' => [],
        ];

        $page_group = PageGroup::select('group_name')
            ->where('id', request()->get('page_group_id'))
            ->first();

        $pages = WebPage::select('name','language', 'published_html')
            ->where('page_group', request()->get('page_group_id'))
            ->where('status', 'published')
            ->get();

        if ($pages) {
            $data['group_name'] = $page_group->group_name;
            foreach ($pages as $pg) {
                $data['pages'][] = [
                    'page_name' => $pg->name,
                    'locale'    => $pg->language,
                    'content'   => $pg->published_html
                ];
            }
            return response()->json(['data' => $data]);
        }
        return response()->json(['error' => "page not found"]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function editDesign(Request $request)
    {
        $page = WebPage::where('id', request()->get('page_id'))
            ->update([
                'published_html'    => $request->data['html'],
                'html'              => $request->data['html'],
                'status'            => "published",
                'has_changes'       => 0,
            ]);
        if($page) {
            return response()->json(['success'=>'page updated.']);
        }
        else {
            return response()->json(['error'=>'something wrong!']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     */
    public function draftContent(Request $request)
    {
        $page = WebPage::where('id', request()->get('item'))
            ->update([
                'html'              => $request->data['content'],
                'has_changes'              => 1,
            ]);
        if($page) {
            return response()->json(['success'=>'page updated.']);
        }
        else {
            return response()->json(['error'=>'something wrong!']);
        }
    }

    public function updateChangeStatus(Request $request)
    {
        $page = WebPage::where('name', request()->get('item'))
            ->update([
                'has_changes'              => 0,
            ]);
        if($page) {
            return response()->json(['success'=>'page updated.']);
        }
        else {
            return response()->json(['error'=>'something wrong!']);
        }
    }

    public function updateInfo(Request $request)
    {
        $url = str_replace(' ', '', $request->data['url']);
        $url = preg_replace('/[^a-zA-Z0-9_\/-]/s','',$url);
        $page = WebPage::where('id', request()->get('item_id'))
            ->update([
                'name'              => $request->data['name'],
                'status'            => $request->data['status'],
                'page_group'        => $request->data['group'],
                'url'               => $url
            ]);
        if($page) {
            return response()->json(['message'=>'Page info updated.']);
        }
        else {
            return response()->json(['message'=>'Something wrong!']);
        }
    }

    public function showAllTrashPages()
    {
        $pages = WebPage::onlyTrashed()->get();
        if($pages){
            return response()->json(['pages' => $pages]);
        }
        return response()->json(['page' => "no page found"]);
    }

    public function restore()
    {
        $page = WebPage::onlyTrashed()->where('id',request()->get('page_id'))->first();
        if($page){
            $meta_tag_content = MetaTagContent::onlyTrashed()->where('item_id', $page['id'])
                ->where('item_type', "page")
                ->first();
            $meta_tag_content->restore();
            $google_tag_content = GoogleTagContent::onlyTrashed()->where('item_id', $page['id'])
                ->where('item_type', "page")
                ->first();
            $google_tag_content->restore();
            $facebook_tag_content = FacebookTagContent::onlyTrashed()->where('item_id', $page['id'])
                ->where('item_type', "page")
                ->first();
            $facebook_tag_content->restore();
            $page->restore();
            return response()->json(['page' => $page]);
        }
        return response()->json(['page' => "page not found"]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        $page = WebPage::where('id', request()->get('item_id'))->first();
        if($page) {
            $meta_tag_content = MetaTagContent::where('item_id', $page['id'])
                ->where('item_type', "page")
                ->first();
            $meta_tag_content->delete();
            $google_tag_content = GoogleTagContent::where('item_id', $page['id'])
                ->where('item_type', "page")
                ->first();
            $google_tag_content->delete();
            $facebook_tag_content = FacebookTagContent::where('item_id', $page['id'])
                ->where('item_type', "page")
                ->first();
            $facebook_tag_content->delete();

            $page->delete();
            return response()->json(['success' => "page deleted successfully!"]);
        }
        else {
            return response()->json(['error' => "something wrong!"]);
        }
    }

    public function permanentDelete()
    {
        $find = WebPage::onlyTrashed()->find(request()->get('item_id'));
        if($find){
            $meta_tag_content = MetaTagContent::onlyTrashed()->where('item_id', $find['id'])
                ->where('item_type', "page")
                ->first();
            $meta_tag_content->forceDelete();
            $google_tag_content = GoogleTagContent::onlyTrashed()->where('item_id', $find['id'])
                ->where('item_type', "page")
                ->first();
            $google_tag_content->forceDelete();
            $facebook_tag_content = FacebookTagContent::onlyTrashed()->where('item_id', $find['id'])
                ->where('item_type', "page")
                ->first();
            $facebook_tag_content->forceDelete();
            $page = $find->forceDelete();
            return response()->json(['success' => "page deleted successfully!"]);
        }
        else {
            return response()->json(['error' => "something wrong!"]);
        }
    }


    public function getAllTagInfo () {
        $seo_tags = DB::table('tik_cms_web_pages')
            ->where('tik_cms_web_pages.id', request()->get('page_id'))
            ->join('tik_cms_meta_tag_contents', function($join)
            {
                $join->on("tik_cms_web_pages.id", "=", "tik_cms_meta_tag_contents.item_id")
                    ->where("tik_cms_meta_tag_contents.item_type", "=", "page");
            })
            ->join('tik_cms_google_tag_contents', function($join)
            {
                $join->on("tik_cms_web_pages.id", "=", "tik_cms_google_tag_contents.item_id")
                    ->where("tik_cms_google_tag_contents.item_type", "=", "page");
            })
            ->join('tik_cms_facebook_tag_contents', function($join)
            {
                $join->on("tik_cms_web_pages.id", "=", "tik_cms_facebook_tag_contents.item_id")
                    ->where("tik_cms_facebook_tag_contents.item_type", "=", "page");
            })
            ->select('tik_cms_web_pages.name as page_name',
                'tik_cms_meta_tag_contents.title as meta_title','tik_cms_meta_tag_contents.keywords as meta_keywords','tik_cms_meta_tag_contents.description as meta_description',
                'tik_cms_google_tag_contents.title as google_title','tik_cms_google_tag_contents.keywords as google_keywords','tik_cms_google_tag_contents.description as google_description',
                'tik_cms_facebook_tag_contents.title as fb_title','tik_cms_facebook_tag_contents.keywords as fb_keywords','tik_cms_facebook_tag_contents.description as fb_description', 'tik_cms_facebook_tag_contents.img_src as fb_img_src')
            ->get();

        if($seo_tags){
            return response()->json(['$seo_tags' => $seo_tags]);
        }
        return response()->json(['$seo_tags' => "seo tags contents not found"]);
    }

    public function getAllTagInfoWithPublishedPageDesign () {

        $data = [
            'pages'     => [],
        ];
        $published_pages = WebPage::where('language', request()->get('page_language'))
            ->where('tik_cms_web_pages.status', 'published')
            ->get();

        if($published_pages){
            foreach ($published_pages as $published_page){
                $meta_tag = MetaTagContent::select('title as meta_title','keywords as meta_keywords','description as meta_description')
                    ->where('item_id', $published_page->id)
                    ->where('item_type', 'page')
                    ->first();
                $google_tag = GoogleTagContent::select('title as google_title','keywords as google_keywords','description as google_description')
                    ->where('item_id', $published_page->id)
                    ->where('item_type', 'page')
                    ->first();
                $facebook_tag = FacebookTagContent::select('title as fb_title','keywords as fb_keywords','description as fb_description', 'img_src as fb_img_src')
                    ->where('item_id', $published_page->id)
                    ->where('item_type', 'page')
                    ->first();

                $data['pages'][]    = [
                    'page_id'       => $published_page->id,
                    'page_name'     => $published_page->name,
                    'html'          => $published_page->published_html,
                    'meta_tag'      => $meta_tag,
                    'google_tag'    => $google_tag,
                    'facebook_tag'  => $facebook_tag
                ];
            }
            return response()->json(['data' => $data]);
        }
        return response()->json(['$seo_tags' => "seo tags contents not found"]);
    }

    public function individualPageTags() {
        $data = [
            'page_id' => request()->get('item_id'),
            'meta_tag'      => '',
            'google_tag'    => '',
            'facebook_tag'  => ''
        ];
        $published_page = WebPage::select('id', 'name')
            ->where('id', request()->get('item_id'))
            ->first();
        if($published_page) {
            $meta_tag = MetaTagContent::where('item_id', request()->get('item_id'))
                ->where('item_type', 'page')
                ->first();
            $google_tag = GoogleTagContent::where('item_id', request()->get('item_id'))
                ->where('item_type', 'page')
                ->first();
            $facebook_tag = FacebookTagContent::where('item_id', request()->get('item_id'))
                ->where('item_type', 'page')
                ->first();

            $data['meta_tag']    = $meta_tag;
            $data['google_tag']    = $google_tag;
            $data['facebook_tag']  = $facebook_tag;
            return response()->json(['data' => $data]);
        }
        return response()->json(['data' => "Seo tags contents not found"]);
    }
}
