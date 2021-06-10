<?php


use Devghor\TikCmsApi\Http\Controllers\WebPageController;
use Illuminate\Support\Facades\Route;
use Devghor\TikCmsApi\Http\Controllers\SubPagesController;
use Devghor\TikCmsApi\Http\Controllers\BlogsController;
use Devghor\TikCmsApi\Http\Controllers\MetaTagContentController;
use Devghor\TikCmsApi\Http\Controllers\GoogleTagContentController;
use Devghor\TikCmsApi\Http\Controllers\FacebookTagContentController;
use Devghor\TikCmsApi\Http\Controllers\LanguageController;
use Devghor\TikCmsApi\Http\Controllers\BlogCommentRepliesController;
use Devghor\TikCmsApi\Http\Controllers\BlogPostReactionsController;
use Devghor\TikCmsApi\Http\Controllers\BlogPostCommentsController;
use Devghor\TikCmsApi\Http\Controllers\PageGroupController;
use Devghor\TikCmsApi\Http\Controllers\BlogCategoryController;
use Devghor\TikCmsApi\Http\Controllers\BlogDefaultStyleController;
use Devghor\TikCmsApi\Http\Controllers\BlogTypeController;
use Devghor\TikCmsApi\Http\Controllers\FileController;
use Devghor\TikCmsApi\Http\Controllers\BlogCategoryNameTranslationController;

Route::prefix('api/tikcms')->group(function () {

    Route::prefix('page')->group(function () {
        Route::get('/get/all/language/published/design', [WebPageController::class,'getSpecificPageDesignForAllLanguage']);
        Route::get('/get/all/group/published/design', [WebPageController::class,'getSpecificGroupPageDesignForAllLanguage']);
        Route::get('/get/all/published/design/by/group', [WebPageController::class,'getSpecificGroupPageDesignByGroupIDForAllLanguage']);
        Route::get('/show/published', [WebPageController::class,'showPublishedPageDesign']);
        Route::get('/all/show/published', [WebPageController::class,'getAllPublishedPageDesign']);
        Route::get('/seo/tag/show', [WebPageController::class,'getAllTagInfo']);
        Route::get('/seo/tag/all/show', [WebPageController::class,'getAllTagInfoWithPublishedPageDesign']);
        Route::get('/individual/all/subpage/show', [SubPagesController::class,'showAllSubpagesOfIndividualPage']);
        Route::get('/individual/all/section/show', [SubPagesController::class,'showAllSectionOfIndividualPage']);

    });

    Route::prefix('blog')->group(function () {
        Route::get('/list', [BlogsController::class,'getAllBlogWithLanguage']);
        Route::get('/category/list', [BlogsController::class,'getAllBlogWithLanguageAndCategory']);
        Route::get('/show/published', [BlogsController::class,'showPublishedContent']);
        Route::get('/all/published/show', [BlogsController::class,'showAllPublishedBlog']);

        Route::get('/individual/type/published/show', [BlogsController::class,'showAllIndividualTypePublishedBlog']);
        Route::get('/individual/category/published/show', [BlogsController::class,'showAllIndividualCategoryPublishedBlog']);
    });

    Route::prefix('meta-tag')->group(function () {
        Route::get('/page/all/show', [MetaTagContentController::class,'showAllMetaTagContentForPages']);
        Route::get('/blog/all/show', [MetaTagContentController::class,'showAllMetaTagContentForBlogs']);
    });

    Route::prefix('category')->group(function () {
        Route::get('/all/show', [BlogCategoryController::class,'showAllBlogCategories']);
        Route::prefix('translations')->group(function () {
            Route::get('/by/language/all/show', [BlogCategoryNameTranslationController::class,'getAllCategoryAccordingToLanguage']);
            Route::get('/by/all/language/show', [BlogCategoryNameTranslationController::class,'getAllCategoryOfAllLanguage']);
        });
    });

    Route::post('/upload/storeImageDatabase', [FileController::class,'uploadStoreImageDatabase']);


    Route::middleware('auth:api')->group(function () {

        Route::prefix('page')->group(function () {
            Route::post('/create', [WebPageController::class,'create']);
            Route::get('/all/show', [WebPageController::class,'showAll']);
            Route::get('/show', [WebPageController::class,'show']);
            Route::get('/trash/list', [WebPageController::class,'showAllTrashPages']);
            Route::delete('/delete', [WebPageController::class,'destroy']);
            Route::delete('/permanent/delete', [WebPageController::class,'permanentDelete']);
            Route::put('/draft/save', [WebPageController::class,'draftContent']);
            Route::patch('/restore', [WebPageController::class,'restore']);
            Route::patch('/update/status', [WebPageController::class,'updateChangeStatus']);
            Route::post('/clone', [WebPageController::class,'clonePage']);

            Route::put('/info/update', [WebPageController::class,'updateInfo']);
            Route::get('/seo/tag/info', [WebPageController::class,'individualPageTags']);


        });

        Route::get('/design/show', [WebPageController::class,'showPageDesign']);
        Route::put('/design/edit', [WebPageController::class,'editDesign']);

        Route::prefix('sub-page')->group(function () {
            Route::post('/create', [SubPagesController::class,'create']);
            Route::post('/clone', [SubPagesController::class,'cloneSubPage']);
            Route::get('/show', [SubPagesController::class,'show']);
            Route::get('/all/show', [SubPagesController::class,'showAll']);
            Route::get('/trash/list', [SubPagesController::class,'showAllTrashPages']);
            Route::put('/design/edit', [SubPagesController::class,'editDesign']);
            Route::put('/draft/save', [SubPagesController::class,'draftContent']);
            Route::patch('/restore', [SubPagesController::class,'restore']);
            Route::delete('/delete', [SubPagesController::class,'destroy']);
            Route::delete('/permanent/delete', [SubPagesController::class,'permanentDelete']);
            Route::get('/show/published', [SubPagesController::class,'showPublishedSubPageDesign']);
            Route::patch('/update/status', [SubPagesController::class,'updateChangeStatus']);
            Route::put('/info/update', [SubPagesController::class,'updateInfo']);

            Route::get('/seo/tag/info', [SubPagesController::class,'individualSubPageTags']);
        });

        Route::prefix('blog')->group(function () {

            Route::get('/show', [BlogsController::class,'show']);
            Route::get('/all/show', [BlogsController::class,'showAll']);
            Route::get('/trash/list', [BlogsController::class,'showAllTrashBlogs']);
            Route::put('/edit', [BlogsController::class,'edit']);
            Route::put('/draft/save', [BlogsController::class,'draftContent']);
            Route::delete('/delete', [BlogsController::class,'destroy']);
            Route::delete('/permanent/delete', [BlogsController::class,'permanentDelete']);
            Route::patch('/restore', [BlogsController::class,'restore']);
            Route::patch('/update/status', [BlogsController::class,'updateChangeStatus']);
            Route::get('/seo/tag/show', [BlogsController::class,'getAllTagInfo']);
            Route::post('/create', [BlogsController::class,'create']);
            Route::post('/clone', [BlogsController::class,'cloneBlog']);

            Route::put('/info/update', [BlogsController::class,'updateInfo']);
            Route::put('/update/feature-image', [BlogsController::class,'updateImage']);
            Route::get('/seo/tag/info', [BlogsController::class,'individualPageTags']);

            Route::prefix('type')->group(function () {
                Route::post('/create', [BlogTypeController::class,'create']);
                Route::get('/all/show', [BlogTypeController::class,'showAllBlogTypes']);
            });

            Route::prefix('comments')->group(function () {
                Route::post('/create', [BlogPostCommentsController::class,'create']);
                Route::get('/post/all/show', [BlogPostCommentsController::class,'getAllCommentsForSpecificBlog']);
            });
            Route::prefix('reactions')->group(function () {
                Route::post('/create', [BlogPostReactionsController::class,'create']);
            });
            Route::prefix('comment/reply')->group(function () {
                Route::post('/create', [BlogCommentRepliesController::class,'create']);
            });
        });

        Route::prefix('meta-tag')->group(function () {
            Route::post('/content/create', [MetaTagContentController::class,'create']);
            Route::get('/content/all/show', [MetaTagContentController::class,'showAll']);
            Route::put('/content/update', [MetaTagContentController::class,'updateMetaTagContent']);
            Route::get('/content/show', [MetaTagContentController::class,'showItemMetaTag']);

        });

        Route::prefix('google-tag')->group(function () {
            Route::get('/content/all/show', [GoogleTagContentController::class,'showAll']);
            Route::put('/content/update', [GoogleTagContentController::class,'updateGoogleTagContent']);
            Route::get('/content/show', [GoogleTagContentController::class,'showItemGoogleTag']);
        });

        Route::prefix('facebook-tag')->group(function () {
            Route::get('/content/all/show', [FacebookTagContentController::class,'showAll']);
            Route::put('/content/update', [FacebookTagContentController::class,'updateFacebookTagContent']);
            Route::get('/content/show', [FacebookTagContentController::class,'showItemFacebookTag']);
        });

        Route::prefix('language')->group(function () {
            Route::post('/create', [LanguageController::class,'create']);
            Route::get('/all/show', [LanguageController::class,'showAll']);

        });
        Route::prefix('group')->group(function () {
            Route::post('/create', [PageGroupController::class,'create']);
            Route::get('/all/show', [PageGroupController::class,'showAllPageGroups']);
            Route::put('/update', [PageGroupController::class,'updateGroupName']);
            Route::delete('/delete', [PageGroupController::class,'destroy']);
            Route::get('/trash/list', [PageGroupController::class,'showAllTrashGroups']);
            Route::delete('/permanent/delete', [PageGroupController::class,'permanentDelete']);
            Route::patch('/restore', [PageGroupController::class,'restore']);

        });

        Route::prefix('category')->group(function () {
            Route::post('/create', [BlogCategoryController::class,'create']);
            Route::get('/all/show', [BlogCategoryController::class,'showAllBlogCategories']);
            Route::put('/update', [BlogCategoryController::class,'updateCategoryName']);
            Route::delete('/delete', [BlogCategoryController::class,'destroy']);
            Route::get('/trash/list', [BlogCategoryController::class,'showAllTrashCategory']);
            Route::delete('/permanent/delete', [BlogCategoryController::class,'permanentDelete']);
            Route::patch('/restore', [BlogCategoryController::class,'restore']);

            Route::prefix('translations')->group(function () {
                Route::post('/create', [BlogCategoryNameTranslationController::class,'create']);
                Route::get('/all/show', [BlogCategoryNameTranslationController::class,'showAllTranslatedBlogCategories']);
                Route::put('/update', [BlogCategoryNameTranslationController::class,'updateTranslatedBlogCategoryName']);
                Route::delete('/delete', [BlogCategoryNameTranslationController::class,'destroy']);
            });
        });
        Route::prefix('style')->group(function () {
            Route::post('/blog/default', [BlogDefaultStyleController::class,'create']);
            Route::get('/blog/default/show', [BlogDefaultStyleController::class,'showDefaultStyle']);
            Route::put('/blog/default/update', [BlogDefaultStyleController::class,'updateStyle']);
        });

    });
});



