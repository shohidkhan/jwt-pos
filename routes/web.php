<?php

use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\CustomerController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\InvoiceController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\ReportController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Middleware\TokenVerificationMiddleware;
use Illuminate\Support\Facades\Route;

Route::post("/userRegistration",[UserController::class,"userRegistration"])->name("userRegistration");
Route::post("/userLogin",[UserController::class,"UserLogin"])->name("userLogin");
Route::post("/send-otp",[UserController::class,"sendOTPCode"])->name("send-otp");
Route::post("/verify-otp",[UserController::class,"verifyOTP"])->name("verify-otp");
Route::post("/reset-password",[UserController::class,"passwordReset"])->middleware(TokenVerificationMiddleware::class);

//user Logout
Route::get("/logout",[UserController::class,"userLogout"])->name("logout");

//pages Route

Route::get("/",[HomeController::class,"homePage"]);
Route::get("/user-registration",[UserController::class,"registrationPage"])->name("user-registration");
Route::get("/user-login",[UserController::class,"loginPage"])->name("user-login");
Route::get("/send-otp",[UserController::class,"sendOtpPage"])->name("send-otp");
Route::get("/verifyOtp",[UserController::class,"verifyOtpPage"])->name("verifyOtp");
Route::get("/reset-password",[UserController::class,"resetPasswordPage"])->name("reset-password")->middleware(TokenVerificationMiddleware::class);
Route::get("/profile-page",[UserController::class,"profilePage"])->name("profile-page")->middleware(TokenVerificationMiddleware::class);
Route::get("/user-details",[UserController::class,"userDetails"])->name("user-details")->middleware(TokenVerificationMiddleware::class);
Route::put("/user-details",[UserController::class,"userDetailsUpdate"])->name("user-details-update")->middleware(TokenVerificationMiddleware::class);


//Dashboard

Route::get("/dashboard",[DashboardController::class,"dashboardPage"])->name("dashboard")->middleware(TokenVerificationMiddleware::class);

//catgories page routes
Route::get("/categoryPage",[CategoryController::class,"categoryPage"])->name("categoryPage")->middleware(TokenVerificationMiddleware::class);


//categories backend route api
Route::get("/categoryList",[CategoryController::class,"categoryList"])->name("categoryList")->middleware(TokenVerificationMiddleware::class);
Route::post("/singleCategory",[CategoryController::class,"singleCategory"])->name("singleCategory")->middleware(TokenVerificationMiddleware::class);

Route::post("/categoryCreate",[CategoryController::class,"categoryCreate"])->name("categoryCreate")->middleware(TokenVerificationMiddleware::class);

Route::put("/categoryUpdate",[CategoryController::class,"categoryUpdate"])->name("categoryUpdate")->middleware(TokenVerificationMiddleware::class);

Route::post("/categoryDelete",[CategoryController::class,"categoryDelete"])->name("categoryDelete")->middleware(TokenVerificationMiddleware::class);

//Customers page Routes
Route::get("/customerPage",[CustomerController::class,"customerPage"])->name("customerPage")->middleware(TokenVerificationMiddleware::class);


//customers backend route api
Route::post("/createCustomer",[CustomerController::class,"createCustomer"])->name("createCustomer")->middleware(TokenVerificationMiddleware::class);
Route::get("/customerList",[CustomerController::class,"customerList"])->name("customerList")->middleware(TokenVerificationMiddleware::class);
Route::post("/singleCustomer",[CustomerController::class,"singleCustomer"])->name("singleCustomer")->middleware(TokenVerificationMiddleware::class);
Route::put("/customerUpdate",[CustomerController::class,"customerUpdate"])->name("customerUpdate")->middleware(TokenVerificationMiddleware::class);
Route::post("/customerDelete",[CustomerController::class,"customerDelete"])->name("customerDelete")->middleware(TokenVerificationMiddleware::class);


//products page route
Route::get("/productPage",[ProductController::class,"productPage"])->name("productPage")->middleware(TokenVerificationMiddleware::class);

//Backend Products route
Route::post("/createProduct",[ProductController::class,"createProduct"])->name("createProduct")->middleware(TokenVerificationMiddleware::class);
Route::get("/productList",[ProductController::class,"productList"])->name("productList")->middleware(TokenVerificationMiddleware::class);
Route::post("/productSingle",[ProductController::class,"productSingle"])->name("productSingle")->middleware(TokenVerificationMiddleware::class);
Route::post( "/productDelete",[ProductController::class,"productDelete"])->name("productDelete")->middleware(TokenVerificationMiddleware::class);
Route::post("/productUpdate",[ProductController::class,"productUpdate"])->name("productUpdate")->middleware(TokenVerificationMiddleware::class);


//invoice page route
Route::get("/invoicePage",[InvoiceController::class,"invoicePage"])->name("invoicePage")->middleware(TokenVerificationMiddleware::class);
Route::post("/createInvoice",[InvoiceController::class,"createInvoice"])->name("createInvoice")->middleware(TokenVerificationMiddleware::class);
Route::get("/invoiceSelect",[InvoiceController::class,"invoiceSelect"])->name("invoiceSelect")->middleware(TokenVerificationMiddleware::class);
Route::post("/invoiceDetail",[InvoiceController::class,"invoiceDetail"])->name("invoiceDetail")->middleware(TokenVerificationMiddleware::class);
Route::post("/invoiceDelete",[InvoiceController::class,"invoiceDelete"])->name("invoiceDelete")->middleware(TokenVerificationMiddleware::class);


//SALE PAGE ROUTE
Route::get("/salePage",[InvoiceController::class,"salePage"])->name("salePage")->middleware(TokenVerificationMiddleware::class);
Route::get("/invoicePage",[InvoiceController::class,"invoicePage"])->name("invoicePage")->middleware(TokenVerificationMiddleware::class);
//dashboard summary
Route::get("/summary",[DashboardController::class,"summary"])->name("summary")->middleware(TokenVerificationMiddleware::class);


//Report backend and frontend route

Route::get("/reportPage",[ReportController::class,"reportPage"])->name("reportPage")->middleware(TokenVerificationMiddleware::class);
Route::get("/salesReport/{FormDate}/{ToDate}",[ReportController::class,"salesReport"])->name("salesReport")->middleware(TokenVerificationMiddleware::class);