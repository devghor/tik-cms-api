<?php


namespace Tikweb\TikCmsApi\Http\Controllers;


use Tikweb\TikCmsApi\Models\SubPage;
use Illuminate\Http\Request;
use Tikweb\TikCmsApi\Models\WebPage;
use Tikweb\TikCmsApi\Models\FacebookTagContent;
use Tikweb\TikCmsApi\Models\GoogleTagContent;
use Tikweb\TikCmsApi\Models\MetaTagContent;

class SubPagesController extends Controller
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

        if($request->data['type'] == 'subpage') {
            $parent_page = WebPage::where('id', $request->data['parent_page'])->first();

            $url = $parent_page->language.'/'.strtolower($parent_page->name).'/'.strtolower($request->data['name']);
            $url = str_replace(' ', '', $url);
            $url = preg_replace('/[^a-zA-Z0-9_\/-]/s','',$url);

            $sub_page = SubPage::create([
                'parent_id'         => $request->data['parent_page'],
                'name'              => $request->data['name'],
                'html'              => $request->data['html'],
                'published_html'    => $request->data['html'],
                'status'            => 'draft',
                'type'              => $request->data['type'],
                'url'               => $url,
            ]);
        }
        else {
            $sub_page = SubPage::create([
                'parent_id'         => $request->data['parent_page'],
                'name'              => $request->data['name'],
                'html'              => $request->data['html'],
                'published_html'    => $request->data['html'],
                'status'            => 'draft',
                'type'              => $request->data['type'],
            ]);
        }

        if($sub_page) {
            if($sub_page->type === 'subpage') {
                $meta_tag_content = MetaTagContent::create([
                    'item_id'       => $sub_page['id'],
                    'item_type'     => "subpage",
                ]);
                $google_tag_content = GoogleTagContent::create([
                    'item_id'       => $sub_page['id'],
                    'item_type'     => "subpage",
                ]);
                $facebook_tag_content = FacebookTagContent::create([
                    'item_id'       => $sub_page['id'],
                    'item_type'     => "subpage",
                ]);
                return response()->json(['data'=>'Sub-page added']);
            }
            else {
                return response()->json(['data'=>'Section added']);
            }

        }
        else {
            return response()->json(['data'=>'something wrong']);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function cloneSubPage(Request $request)
    {
        $parent_sub_page = SubPage::where('id', $request->data['component_id'])->first();

        if($parent_sub_page) {
            $sub_page = SubPage::create([
                'name'              => $request->data['name'],
                'parent_id'         => $parent_sub_page['parent_id'],
                'html'              => $parent_sub_page['html'],
                'published_html'    => $parent_sub_page['published_html'],
                'status'            => 'draft',
                'type'              => $parent_sub_page['type'],
            ]);
            if($sub_page) {
                if($sub_page->type === 'subpage') {
                    $meta_tag_content = MetaTagContent::create([
                        'item_id'       => $sub_page['id'],
                        'item_type'     => "subpage",
                    ]);
                    $google_tag_content = GoogleTagContent::create([
                        'item_id'       => $sub_page['id'],
                        'item_type'     => "subpage",
                    ]);
                    $facebook_tag_content = FacebookTagContent::create([
                        'item_id'       => $sub_page['id'],
                        'item_type'     => "subpage",
                    ]);
                    return response()->json(['data'=>'Sub-page added']);
                }
                else {
                    return response()->json(['data'=>'Section added']);
                }
            }
        }
        else {
            return response()->json(['error'=>'something wrong']);
        }
        return response()->json(['error'=>'something wrong']);
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
     *
     */
    public function show()
    {
        $page = SubPage::select('id', 'name', 'parent_id', 'has_changes','type', 'html', 'status')
            ->where('id', request()->get('page_id'))->first();
        if($page){
            return response()->json(['page' => $page]);
        }
        return response()->json(['error' => "page not found"]);
    }

    /**
     * Display all resource.
     *
     */
    public function showAll()
    {
        $sub_page = SubPage::select('id','parent_id','name','status', 'type', 'has_changes','url')->get();
        if($sub_page){
            return response()->json(['sub_pages' => $sub_page]);
        }
        return response()->json(['page' => "page not found"]);
    }

    public function showPublishedSubPageDesign () {
        $design = SubPage::select('id','published_html')
            ->where('id', request()->get('page_id'))
            ->where('status','published')->first();
        if($design){
            return response()->json(['design' => $design]);
        }
        return response()->json(['design' => "design not found"]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SubPage  $subPage
     * @return \Illuminate\Http\Response
     */
    public function edit(SubPage $subPage)
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
        $sub_page = SubPage::where('id', request()->get('page_id'))
            ->update([
                'published_html'    => $request->data['html'],
                'html'              => $request->data['html'],
                'status'            => "published",
                'has_changes'              => 0,
            ]);
        if($sub_page) {
            return response()->json(['success'=>'sub-page updated.']);
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
        $page = SubPage::where('id', request()->get('item'))
            ->update([
                'html'              => $request->data['content'],
                'has_changes'       => 1,
            ]);
        if($page) {
            return response()->json(['success'=>'page updated.']);
        }
        else {
            return response()->json(['error'=>'something wrong!']);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SubPage  $subPage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SubPage $subPage)
    {
        //
    }

    public function updateChangeStatus()
    {
        $sub_page = SubPage::where('id', request()->get('item'))
            ->update([
                'has_changes'              => 0,
            ]);
        if($sub_page) {
            return response()->json(['success'=>'sub-page updated.']);
        }
        else {
            return response()->json(['error'=>'something wrong!']);
        }
    }

    public function updateInfo(Request $request)
    {
        $url = str_replace(' ', '', $request->data['url']);
        $url = preg_replace('/[^a-zA-Z0-9_\/-]/s','',$url);
        $sub_page = SubPage::where('id', request()->get('item_id'))
            ->update([
                'name'              => $request->data['name'],
                'status'            => $request->data['status'],
                'url'               => $url
            ]);
        if($sub_page) {
            return response()->json(['message'=>'Sub-page info updated.']);
        }
        else {
            return response()->json(['message'=>'Something wrong!']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        $sub_page = SubPage::where('id', request()->get('item_id'))->first();
        if($sub_page) {
            if($sub_page->type === 'subpage'){
                $meta_tag_content = MetaTagContent::where('item_id', $sub_page['id'])
                    ->where('item_type', "subpage")
                    ->first();
                $meta_tag_content->delete();
                $google_tag_content = GoogleTagContent::where('item_id', $sub_page['id'])
                    ->where('item_type', "subpage")
                    ->first();
                $google_tag_content->delete();
                $facebook_tag_content = FacebookTagContent::where('item_id', $sub_page['id'])
                    ->where('item_type', "subpage")
                    ->first();
                $facebook_tag_content->delete();
            }
            $sub_page->delete();
            return response()->json(['success' => "sub-page deleted successfully!"]);
        }
        else {
            return response()->json(['error' => "something wrong!"]);
        }
    }

    public function showAllTrashPages()
    {
        $sub_pages = SubPage::onlyTrashed()->get();
        if($sub_pages){
            return response()->json(['sub_pages' => $sub_pages]);
        }
        return response()->json(['page' => "no page found"]);
    }

    public function restore()
    {
        $sub_page = SubPage::onlyTrashed()->where('id',request()->get('page_id'))->first();
        if($sub_page){
            if($sub_page->type === 'subpage'){
                $meta_tag_content = MetaTagContent::onlyTrashed()->where('item_id', $sub_page['id'])
                    ->where('item_type', "subpage")
                    ->first();
                $meta_tag_content->restore();
                $google_tag_content = GoogleTagContent::onlyTrashed()->where('item_id', $sub_page['id'])
                    ->where('item_type', "subpage")
                    ->first();
                $google_tag_content->restore();
                $facebook_tag_content = FacebookTagContent::onlyTrashed()->where('item_id', $sub_page['id'])
                    ->where('item_type', "subpage")
                    ->first();
                $facebook_tag_content->restore();
            }
            $sub_page->restore();
            return response()->json(['sub_page' => $sub_page]);
        }
        return response()->json(['sub_page' => "page not found"]);
    }

    public function permanentDelete()
    {
        $find = SubPage::onlyTrashed()->find(request()->get('item_id'));
        if($find){
            if($find->type === 'subpage'){
                $meta_tag_content = MetaTagContent::onlyTrashed()->where('item_id', $find['id'])
                    ->where('item_type', "subpage")
                    ->first();
                $meta_tag_content->restore();
                $google_tag_content = GoogleTagContent::onlyTrashed()->where('item_id', $find['id'])
                    ->where('item_type', "subpage")
                    ->first();
                $google_tag_content->restore();
                $facebook_tag_content = FacebookTagContent::onlyTrashed()->where('item_id', $find['id'])
                    ->where('item_type', "subpage")
                    ->first();
                $facebook_tag_content->forceDelete();
            }
            $sub_page = $find->forceDelete();
            return response()->json(['success' => "sub-page deleted successfully!"]);
        }
        else {
            return response()->json(['error' => "something wrong!"]);
        }
    }

    public function showAllSubpagesOfIndividualPage() {

        $page = WebPage::select('id')
            ->where(['name'=>request()->get('page_name'), 'language'=>request()->get('page_language')])
            ->first();

        if($page) {
            $design = SubPage::select('id','name','published_html')
                ->where('parent_id', $page->id)
                ->where('status','published')
                ->where('type', 'subpage')
                ->get();
            if($design){
                return response()->json(['design' => $design]);
            }
            return response()->json(['design' => "design not found"]);
        }

    }

    public function showAllSectionOfIndividualPage() {

        $page = WebPage::select('id')
            ->where(['name'=>request()->get('page_name'), 'language'=>request()->get('page_language')])
            ->first();

        if($page) {
            $design = SubPage::select('id','name','published_html')
                ->where('parent_id', $page->id)
                ->where('status','published')
                ->where('type', 'section')
                ->get();
            if($design){
                return response()->json(['design' => $design]);
            }
            return response()->json(['design' => "design not found"]);
        }

    }


    public function individualSubPageTags() {
        $data = [
            'page_id' => request()->get('item_id'),
            'meta_tag'      => '',
            'google_tag'    => '',
            'facebook_tag'  => ''
        ];
        $published_page = SubPage::select('id', 'name')
            ->where('id', request()->get('item_id'))
            ->first();
        if($published_page) {
            $meta_tag = MetaTagContent::where('item_id', request()->get('item_id'))
                ->where('item_type', 'subpage')
                ->first();
            $google_tag = GoogleTagContent::where('item_id', request()->get('item_id'))
                ->where('item_type', 'subpage')
                ->first();
            $facebook_tag = FacebookTagContent::where('item_id', request()->get('item_id'))
                ->where('item_type', 'subpage')
                ->first();

            $data['meta_tag']    = $meta_tag;
            $data['google_tag']    = $google_tag;
            $data['facebook_tag']  = $facebook_tag;
            return response()->json(['data' => $data]);
        }
        return response()->json(['data' => "seo tags contents not found"]);
    }
}
