<?php

use App\Http\Controllers\Frontend\BikeContactController;
use App\Http\Controllers\Frontend\FrontendAuthController;
use App\Http\Controllers\Frontend\FrontEndCustomerController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\InquiryController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\ProductController; 
use App\Http\Controllers\Frontend\BikeInquiryController; 
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\CustomHelpers;
use App\Services\ApiClient\ApiClient;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
|
| Here is where you can register frontend routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "frontend" middleware group. Now create something great!
|
*/




  // Routing ProductView Page
  Route::get('/product-view', function(){
    return Inertia::render('ProductView/index');
  })->name('product-view');
  // 


  Route::get('/all-product', [ProductController::class, 'AllProduct'])->name('product');
  Route::get('/Brows-product', [ProductController::class, 'BrowsProduct'])->name('brows.product');
  Route::get('/product-details/{id}', [ProductController::class, 'ProductDetailsView'])->name('product.details.view');
  Route::get('/category-product-details/{id}', [ProductController::class, 'CategoryProductDetailsView'])->name('category.details.view');
  Route::get('/all-product', [ProductController::class, 'AllProduct'])->name('product');

  Route::get('/manufacture', [ProductController::class, 'make'])->name('make');
  Route::get('/model', [ProductController::class, 'model'])->name('model');


  Route::post('/inquiry', [BikeInquiryController::class, 'inquirySend'])->name('inquiry.store');

  Route::post('/contact', [BikeContactController::class, 'contactMessage'])->name('contact.message.store');

  Route::get('/Register', [FrontendAuthController::class, 'makeRegister'])->name('front.end.customer.store');
  Route::get('/Login', [FrontendAuthController::class, 'makeLogin'])->name('front.end.customer.login');
  Route::get('/Logout', [FrontendAuthController::class, 'makeLogout'])->name('front.end.customer.logout');


  // Routing ProductGroup Page
  Route::get('/product-group', function(){
    return Inertia::render('BrandProducts/index');
  })->name('product-group');
  
  // Routing Home Page
  Route::get('/', [HomeController::class, 'index'])->name('home');
  // 
  //Routing ContactUs Page
  Route::get('/contact-us', function(){
    return Inertia::render('ContactUs/index');
  })->name('contact-us');
  //
  //Routing AboutUs Page
  Route::get('/about-us', function(){
    return Inertia::render('AboutUs/index');
  })->name('about-us');
  //
  //Routing FAQ Page
  Route::get('/faq', function(){
    return Inertia::render('FAQ/index');
  })->name('faq');
  //
  //Login Page
  Route::get('/login', function(){
    return Inertia::render('LoginPage/index');
  })->name('front.user.login');
  //
  //Register Page
  Route::get('/register', function(){
    return Inertia::render('RegisterPage/index');
  })->name('front.user.register');
  //
  //ForgotPassword Page
  Route::get('/forget-password', function(){
    return Inertia::render('ForgetPassword/index');
  })->name('front.user.forget-password');
  //


  Route::get('/forgot-password', [PageController::class, 'forgotpassword'])->name('forgotpassword');
  Route::post('/register', [FrontendAuthController::class, 'makeRegister'])->name('front.end.customer.store');
  Route::post('/login', [FrontendAuthController::class, 'makeLogin'])->name('front.end.customer.login');
  Route::get('/logout', [FrontendAuthController::class, 'makeLogout'])->name('front.end.customer.logout');

  Route::get('/getdata', [HomeController::class, 'getdata'])->name('index.getdata');

  Route::post('/submit-inquiry', [InquiryController::class, 'submit'])->name('submit-inquiry');


  