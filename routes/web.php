<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\PropertyTypeController;
use App\Http\Controllers\AmenityController;
use App\Http\Controllers\DevelopmentController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\BrandedResidenceController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\RecognitionController;
use App\Http\Controllers\SettingController;
use App\Http\Middleware\CheckSessionAndRole;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PropertyFrontController;
use App\Http\Controllers\DevelopmentFrontController;
use App\Http\Controllers\InvestmentFrontController;
use App\Http\Controllers\BrandedResidenceFrontController;
use App\Http\Controllers\CommunityFrontController;

use App\Http\Controllers\PageController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BlogPostController;
use App\Http\Controllers\SitemapController;

use App\Http\Controllers\DevelopmentFloorPlanController;
use App\Http\Controllers\DevelopmentAmenityController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

///////////////////////////////////////////////////////////
//                      Website Routes                   //
///////////////////////////////////////////////////////////
// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Sitemap for search engines
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Contact
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact/submit', [HomeController::class, 'contactSubmit'])->name('contact.submit');

Route::post('/inquiry/submit', [HomeController::class, 'inquirySubmit'])->name('inquiry.submit');

// Properties
Route::get('/properties/for-sale',          [PropertyFrontController::class, 'sale'])->name('properties.sale');
Route::get('/properties/for-rent',          [PropertyFrontController::class, 'rent'])->name('properties.rent');
Route::get('/properties/private-office',    [PropertyFrontController::class, 'private'])->name('properties.private');
Route::get('/properties/international',     [PropertyFrontController::class, 'international'])->name('properties.international');
Route::get('/properties/search',            [PropertyFrontController::class, 'search'])->name('properties.search');
Route::get('/properties/{slug}',            [PropertyFrontController::class, 'show'])->name('properties.show');

// Developments
Route::get('/buy', [DevelopmentFrontController::class, 'index'])->name('developments.index');
Route::get('/buy/{slug}', [DevelopmentFrontController::class, 'show'])->name('developments.show');
// Route::post('/inquiry/submit', [HomeController::class, 'contactSubmit'])->name('inquiry.submit');

Route::get('/sell', [PageController::class, 'sell'])->name('sell');

// Investments
Route::get('/investments',        [InvestmentFrontController::class, 'index'])->name('investments.index');
Route::get('/investments/{slug}', [InvestmentFrontController::class, 'show'])->name('investments.show');

// Branded Residences
Route::get('/branded-residences',        [BrandedResidenceFrontController::class, 'index'])->name('branded-residences.index');
Route::get('/branded-residences/{slug}', [BrandedResidenceFrontController::class, 'show'])->name('branded-residences.show');

// Communities
Route::get('/communities',        [CommunityFrontController::class, 'index'])->name('communities.index');
Route::get('/communities/{slug}', [CommunityFrontController::class, 'show'])->name('communities.show');

// Inquiry submit (from property pages)
// Route::post('/inquiry/submit', [HomeController::class, 'contactSubmit'])->name('inquiry.submit');



// ->middleware('check.session.role')

// New brand pages
Route::get('/about',           [PageController::class, 'about'])->name('about');
Route::get('/services',        [PageController::class, 'services'])->name('services');
Route::get('/giving',          [PageController::class, 'giving'])->name('giving');
Route::get('/privacy-policy',  [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms',           [PageController::class, 'terms'])->name('terms');
Route::get('/cookie-policy',   [PageController::class, 'cookies'])->name('cookies');

// Blogs (content managed in Sanity — see /studio)
Route::get('/blogs',        [BlogController::class, 'index'])->name('blogs.index');
Route::get('/blogs/{slug}', [BlogController::class, 'show'])->name('blogs.show');

// Old Insights URLs now live under /blogs
Route::permanentRedirect('/insights', '/blogs');
Route::get('/insights/{slug}', fn ($slug) => redirect()->route('blogs.show', $slug, 301));

///////////////////////////////////////////////////////////
//                      Admin Routes                     //
///////////////////////////////////////////////////////////
Route::group(['prefix' => '/admin'],function(){

    Route::get('/sign-in', [AdminController::class,'signin'])->name('signin');
    Route::post('/signin-request', [AuthController::class,'signinrequest'])->name('signinrequest');
    Route::get('/sign-out', [AuthController::class,'signout'])->name('signout');


    // ============================================================
    // PROTECTED ROUTES
    // ============================================================
    Route::middleware('check.session.role')->group(function () {

        // --------------------------------------------------------
        // Dashboard
        // --------------------------------------------------------
        Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        // --------------------------------------------------------
        // Profile
        // --------------------------------------------------------
        Route::get('/profile',        [AdminController::class, 'profile'])->name('admin.profile');
        Route::post('/profile/update',[AdminController::class, 'updateProfile'])->name('admin.profile.update');
        Route::post('/profile/password', [AdminController::class, 'updatePassword'])->name('admin.profile.password');

        // --------------------------------------------------------
        // Properties
        // --------------------------------------------------------
        Route::get('/properties',              [PropertyController::class, 'index'])->name('admin.properties.index');
        Route::get('/properties/create',       [PropertyController::class, 'create'])->name('admin.properties.create');
        Route::post('/properties/store',       [PropertyController::class, 'store'])->name('admin.properties.store');
        Route::get('/properties/{id}/edit',    [PropertyController::class, 'edit'])->name('admin.properties.edit');
        Route::post('/properties/{id}/update', [PropertyController::class, 'update'])->name('admin.properties.update');
        Route::get('/properties/{id}/delete',  [PropertyController::class, 'delete'])->name('admin.properties.delete');
        Route::get('/properties/{id}/toggle-featured',  [PropertyController::class, 'toggleFeatured'])->name('admin.properties.toggle-featured');
        Route::get('/properties/{id}/toggle-status',    [PropertyController::class, 'toggleStatus'])->name('admin.properties.toggle-status');
        Route::get('/properties/gallery/{id}/delete',   [PropertyController::class, 'deleteGalleryImage'])->name('admin.properties.gallery.delete');

        // --------------------------------------------------------
        // Property Types
        // --------------------------------------------------------
        Route::get('/property-types',              [PropertyTypeController::class, 'index'])->name('admin.property-types.index');
        Route::get('/property-types/create',       [PropertyTypeController::class, 'create'])->name('admin.property-types.create');
        Route::post('/property-types/store',       [PropertyTypeController::class, 'store'])->name('admin.property-types.store');
        Route::get('/property-types/{id}/edit',    [PropertyTypeController::class, 'edit'])->name('admin.property-types.edit');
        Route::post('/property-types/{id}/update', [PropertyTypeController::class, 'update'])->name('admin.property-types.update');
        Route::get('/property-types/{id}/delete',  [PropertyTypeController::class, 'delete'])->name('admin.property-types.delete');
        Route::get('/property-types/{id}/toggle', [PropertyTypeController::class, 'toggleStatus'])->name('admin.property-types.toggle');

        // --------------------------------------------------------
        // Amenities
        // --------------------------------------------------------
        Route::get('/amenities',              [AmenityController::class, 'index'])->name('admin.amenities.index');
        Route::get('/amenities/create',       [AmenityController::class, 'create'])->name('admin.amenities.create');
        Route::post('/amenities/store',       [AmenityController::class, 'store'])->name('admin.amenities.store');
        Route::get('/amenities/{id}/edit',    [AmenityController::class, 'edit'])->name('admin.amenities.edit');
        Route::post('/amenities/{id}/update', [AmenityController::class, 'update'])->name('admin.amenities.update');
        Route::get('/amenities/{id}/delete',  [AmenityController::class, 'delete'])->name('admin.amenities.delete');

        // --------------------------------------------------------
        // Developments (Off-Plan Projects)
        // --------------------------------------------------------
        Route::get('/developments',              [DevelopmentController::class, 'index'])->name('admin.developments.index');
        Route::get('/developments/create',       [DevelopmentController::class, 'create'])->name('admin.developments.create');
        Route::post('/developments/store',       [DevelopmentController::class, 'store'])->name('admin.developments.store');
        Route::get('/developments/{id}/edit',    [DevelopmentController::class, 'edit'])->name('admin.developments.edit');
        Route::post('/developments/{id}/update', [DevelopmentController::class, 'update'])->name('admin.developments.update');
        Route::get('/developments/{id}/delete',  [DevelopmentController::class, 'delete'])->name('admin.developments.delete');
        Route::get('/developments/{id}/toggle-featured', [DevelopmentController::class, 'toggleFeatured'])->name('admin.developments.toggle-featured');
        Route::get('/developments/{id}/toggle-status',   [DevelopmentController::class, 'toggleStatus'])->name('admin.developments.toggle-status');
        Route::get('/developments/gallery/{id}/delete',  [DevelopmentController::class, 'deleteGalleryImage'])->name('admin.developments.gallery.delete');

        Route::get('/developments/{development}/floor-plans',                 [DevelopmentFloorPlanController::class, 'index'])->name('admin.development_floor_plans.index');
        Route::get('/developments/{development}/floor-plans/create',          [DevelopmentFloorPlanController::class, 'create'])->name('admin.development_floor_plans.create');
        Route::post('/developments/{development}/floor-plans',                [DevelopmentFloorPlanController::class, 'store'])->name('admin.development_floor_plans.store');
        Route::get('/developments/{development}/floor-plans/{id}/edit',       [DevelopmentFloorPlanController::class, 'edit'])->name('admin.development_floor_plans.edit');
        Route::put('/developments/{development}/floor-plans/{id}',            [DevelopmentFloorPlanController::class, 'update'])->name('admin.development_floor_plans.update');
        Route::delete('/developments/{development}/floor-plans/{id}',         [DevelopmentFloorPlanController::class, 'destroy'])->name('admin.development_floor_plans.destroy');
        Route::post('/developments/{development}/floor-plans/reorder',        [DevelopmentFloorPlanController::class, 'reorder'])->name('admin.development_floor_plans.reorder');
        
        // Amenities — nested under a specific development (checkbox sync)
        Route::get('/developments/{development}/amenities',                   [DevelopmentAmenityController::class, 'edit'])->name('admin.development_amenities.edit');
        Route::put('/developments/{development}/amenities',                   [DevelopmentAmenityController::class, 'update'])->name('admin.development_amenities.update');

        // --------------------------------------------------------
        // Investments
        // --------------------------------------------------------
        Route::get('/investments',              [InvestmentController::class, 'index'])->name('admin.investments.index');
        Route::get('/investments/create',       [InvestmentController::class, 'create'])->name('admin.investments.create');
        Route::post('/investments/store',       [InvestmentController::class, 'store'])->name('admin.investments.store');
        Route::get('/investments/{id}/edit',    [InvestmentController::class, 'edit'])->name('admin.investments.edit');
        Route::post('/investments/{id}/update', [InvestmentController::class, 'update'])->name('admin.investments.update');
        Route::get('/investments/{id}/delete',  [InvestmentController::class, 'delete'])->name('admin.investments.delete');
        Route::get('/investments/{id}/toggle-featured', [InvestmentController::class, 'toggleFeatured'])->name('admin.investments.toggle-featured');
        Route::get('/investments/{id}/toggle-status',   [InvestmentController::class, 'toggleStatus'])->name('admin.investments.toggle-status');

        // --------------------------------------------------------
        // Branded Residences
        // --------------------------------------------------------
        Route::get('/branded-residences',              [BrandedResidenceController::class, 'index'])->name('admin.branded-residences.index');
        Route::get('/branded-residences/create',       [BrandedResidenceController::class, 'create'])->name('admin.branded-residences.create');
        Route::post('/branded-residences/store',       [BrandedResidenceController::class, 'store'])->name('admin.branded-residences.store');
        Route::get('/branded-residences/{id}/edit',    [BrandedResidenceController::class, 'edit'])->name('admin.branded-residences.edit');
        Route::post('/branded-residences/{id}/update', [BrandedResidenceController::class, 'update'])->name('admin.branded-residences.update');
        Route::get('/branded-residences/{id}/delete',  [BrandedResidenceController::class, 'delete'])->name('admin.branded-residences.delete');
        Route::get('/branded-residences/{id}/toggle-featured', [BrandedResidenceController::class, 'toggleFeatured'])->name('admin.branded-residences.toggle-featured');
        Route::get('/branded-residences/{id}/toggle-status',   [BrandedResidenceController::class, 'toggleStatus'])->name('admin.branded-residences.toggle-status');
        // --------------------------------------------------------
        // Communities
        // --------------------------------------------------------
        Route::get('/communities',              [CommunityController::class, 'index'])->name('admin.communities.index');
        Route::get('/communities/create',       [CommunityController::class, 'create'])->name('admin.communities.create');
        Route::post('/communities/store',       [CommunityController::class, 'store'])->name('admin.communities.store');
        Route::get('/communities/{id}/edit',    [CommunityController::class, 'edit'])->name('admin.communities.edit');
        Route::post('/communities/{id}/update', [CommunityController::class, 'update'])->name('admin.communities.update');
        Route::get('/communities/{id}/delete',  [CommunityController::class, 'delete'])->name('admin.communities.delete');
        Route::get('/communities/{id}/toggle',          [CommunityController::class, 'toggleStatus'])->name('admin.communities.toggle');
        Route::get('/communities/{id}/toggle-featured', [CommunityController::class, 'toggleFeatured'])->name('admin.communities.toggle-featured');

        // --------------------------------------------------------
        // Inquiries / Leads
        // --------------------------------------------------------
        Route::get('/inquiries',             [InquiryController::class, 'index'])->name('admin.inquiries.index');
        Route::get('/inquiries/{id}',        [InquiryController::class, 'show'])->name('admin.inquiries.show');
        Route::get('/inquiries/{id}/delete', [InquiryController::class, 'delete'])->name('admin.inquiries.delete');
        Route::post('/inquiries/{id}/status',[InquiryController::class, 'updateStatus'])->name('admin.inquiries.status');
        Route::post('/inquiries/{id}/notes', [InquiryController::class, 'updateNotes'])->name('admin.inquiries.notes');

        // --------------------------------------------------------
        // Recognitions (CNN, Forbes etc.)
        // --------------------------------------------------------
        Route::get('/recognitions',              [RecognitionController::class, 'index'])->name('admin.recognitions.index');
        Route::get('/recognitions/create',       [RecognitionController::class, 'create'])->name('admin.recognitions.create');
        Route::post('/recognitions/store',       [RecognitionController::class, 'store'])->name('admin.recognitions.store');
        Route::get('/recognitions/{id}/edit',    [RecognitionController::class, 'edit'])->name('admin.recognitions.edit');
        Route::post('/recognitions/{id}/update', [RecognitionController::class, 'update'])->name('admin.recognitions.update');
        Route::get('/recognitions/{id}/delete',  [RecognitionController::class, 'delete'])->name('admin.recognitions.delete');
        Route::get('/recognitions/{id}/toggle-status', [RecognitionController::class, 'toggleStatus'])->name('admin.recognitions.toggle-status');
        Route::get('/recognitions/{id}/toggle', [RecognitionController::class, 'toggleStatus'])->name('admin.recognitions.toggle');
        // --------------------------------------------------------
        // Site Settings
        // --------------------------------------------------------
        Route::get('/settings',        [SettingController::class, 'index'])->name('admin.settings.index');
        Route::post('/settings/update',[SettingController::class, 'update'])->name('admin.settings.update');

        Route::get('/blog-posts',              [BlogPostController::class, 'index'])->name('admin.blog_posts.index');
        Route::get('/blog-posts/create',       [BlogPostController::class, 'create'])->name('admin.blog_posts.create');
        Route::post('/blog-posts',             [BlogPostController::class, 'store'])->name('admin.blog_posts.store');
        Route::get('/blog-posts/{id}/edit',    [BlogPostController::class, 'edit'])->name('admin.blog_posts.edit');
        Route::put('/blog-posts/{id}',         [BlogPostController::class, 'update'])->name('admin.blog_posts.update');
        Route::delete('/blog-posts/{id}',      [BlogPostController::class, 'destroy'])->name('admin.blog_posts.destroy');
 

    });


});

Route::get('/{slug}', [WebsiteController::class,'categorydetail']);
Route::get('product/{slug}', [WebsiteController::class,'productdetail']);