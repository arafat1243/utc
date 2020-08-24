<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
    Route::get('/', function(){
    return Inertia::render('public/Home');
    })->name('home');

    Route::get('/courses/{slug?}','PublicController@courses')->name('public.courses');

    Route::get('/services/{slug?}','PublicController@services')->name('public.services');

    Route::get('/clientForCarasul','PublicController@clientForCarasul')->name('public.clientForCarasul');

    Route::get('/portfolio/{slug?}','PublicController@clients')->name('public.portfolio');

    Route::get('/pages/{slug?}','PublicController@pages')->name('public.pages');

    Route::get('/about/{slug?}','PublicController@about')->name('public.about');

    Route::post('/review','ReviewController@store')->name('public.review.store');

    Route::get('/apply/{courseId?}','StudentController@create')->name('public.apply.create');
    Route::post('/apply}','StudentController@store')->name('public.apply.store');

    Auth::routes(['register' => false]);
    Auth::routes(['verify' => true]);
    Route::prefix('admin')->middleware(['auth','verified'])->group(function(){
        Route::get('/users/new',function(){
            return Inertia::render('admin/user/Create');
        })->name('users.newUser')->middleware('can:isEmployee');
        Route::post('/users/new','EmployeController@store')->name('users.store')->middleware('can:isEmployee');
        Route::middleware('can:checkStatus')->group(function(){
            Route::get('/',function(){
                return Inertia::render('admin/Dashboard');
            })->name('admin');
            Route::get('/users','UserController@index')->name('users.index')->middleware('can:isAdmin');
            Route::post('/users/{users}','UserController@addRoles')->name('users.addRole')->middleware('can:isAdmin');
            Route::post('/users','UserController@addUser')->name('users.addUser')->middleware('can:isAdmin');
            Route::delete('/users/{user}','UserController@destroy')->name('users.destroy')->middleware('can:isAdmin');
            Route::get('/profile','UserController@profile')->name('users.profile');
            Route::post('/profile/{user}','UserController@update')->name('users.update');

            Route::resource('/roles','RoleController',['except' => ['show', 'update']])->middleware('can:isAdmin');
            Route::post('/roles/{roles}','RoleController@update')->name('roles.update')->middleware('can:isAdmin');

            Route::resource('/courseCategories','CourseCategoryController',['except' => ['show','create', 'edit','update']])->middleware('can:canDoIt,"course_cate_create:course_cate_view:course_cate_delete"');
            Route::post('/courseCategories/{courseCategories}','CourseCategoryController@update')->name('courseCategories.update')->middleware('can:canDoIt,"course_cate_update"');

            Route::resource('/courses','CourseControllar',['except' => ['show','update']])->middleware('can:canDoIt,"course_create:course_view:course_delete"');
            Route::post('/courses/{course}','CourseControllar@update')->name('courses.update')->middleware('can:canDoIt,"course_update"');

            Route::resource('/services','ServiceController',['except' => ['show','update']])->middleware('can:canDoIt,"service_create:service_view:service_delete"');
            Route::post('/services/{service}','ServiceController@update')->name('services.update')->middleware('can:canDoIt,"service_update"');

            Route::resource('/clientCategories','ClientCategoryController',['except' => ['show','create', 'edit','update']])->middleware('can:canDoIt,"client_cate_create:client_cate_view:client_cate_delete"');
            Route::post('/clientCategories/{clientCategories}','ClientCategoryController@update')->name('clientCategories.update')->middleware('can:canDoIt,"client_cate_update"');
            
            Route::resource('/clients','ClientController',['except' => ['show','update']])->middleware('can:canDoIt,"client_create:client_view:client_delete"');
            Route::post('/clients/{clientsclient}','ClientController@update')->name('clients.update')->middleware('can:canDoIt,"client_update"');

            Route::resource('/gallery','GalleryController',['except' => ['show','create', 'edit','update']])->middleware('can:canDoIt,"gallery_create:gallery_view:gallery_delete"');
            Route::post('/gallery/{gallery}','GalleryController@update')->name('gallery.update')->middleware('can:canDoIt,"gallery_update"');

            Route::resource('/review','ReviewController',['except' => ['show','create', 'store', 'edit','update']])->middleware('can:canDoIt,"gallery_create:gallery_view:gallery_delete"');
            Route::post('/review/{review}','ReviewController@update')->name('review.update')->middleware('can:canDoIt,"gallery_update"');

            Route::resource('/students','StudentController',['except' => ['create', 'store','update']])->middleware('can:canDoIt,"student_view:student_delete:student_update"');
            Route::post('/students/{student}','StudentController@update')->name('students.update')->middleware('can:canDoIt,"student_update"');
            Route::post('/student/','StudentController@course')->name('students.course')->middleware('can:canDoIt,"student_update"');
        });
    });
