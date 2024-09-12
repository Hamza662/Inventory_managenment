<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\managenment\UnitController;
use App\Http\Controllers\managenment\SearchController;
use App\Http\Controllers\managenment\InvoiceController;
use App\Http\Controllers\managenment\ProductController;
use App\Http\Controllers\managenment\CategoryController;
use App\Http\Controllers\managenment\CustomerController;
use App\Http\Controllers\managenment\PurchaseController;
use App\Http\Controllers\managenment\CreditCustomerController;

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

Route::get('/', function () {
    return view('auth.login');
});

// Role Admin 
Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/admin/dashboard', [AdminController::class, 'AdminDashboard'])->name('admin.index');

    Route::get('/admin/profile', [AdminController::class, 'AdminProfile'])->name('admin.profile');

    Route::put('admin/profile/update', [Admincontroller::class, 'AdminProfileUpdate'])->name('admin.profile.update');

    Route::get('/admin/change/password', [AdminController::class, 'AdminChangepassword'])->name('admin.change.password');
    Route::post('/admin/update/password', [AdminController::class, 'AdminUpdatePassword'])->name('admin.update.password');

    //Global search method route

    Route::get('/redirectsearch', [SearchController::class, 'redirectSearch'])->name('redirectSearch');

    // Supplier Controller
    

    Route::get('/suppliers/trash', [SupplierController::class, 'trash'])->name('suppliers.trash');

    Route::get('suppliers/restore/{id}', [SupplierController::class, 'restore'])->name('suppliers.restore');


    Route::delete('suppliers/trash/{id}/force-delete', [SupplierController::class, 'ForceDelete'])->name('suppliers.forcedelete');

    Route::get('/search',[SupplierController::class,'search']);
    
    Route::resource('suppliers', SupplierController::class);

    // Customers Controller
    Route::get('/search',[CustomerController::class,'search']);
    Route::get('/customers/trash', [CustomerController::class, 'trash'])->name('customers.trash');
    Route::get('restore/{id}', [CustomerController::class, 'restore'])->name('customers.restore');
    Route::delete('trash/{user}/force-delete', [customerController::class, 'ForceDelete'])->name('customers.forcedelete');
    Route::resource('customers', CustomerController::class);

    // Credit Customer route
    
    Route::resource('creditcustomers', CreditCustomerController::class);
    Route::get('/credit/customer/print', [CreditCustomerController::class, 'ShowCreditCustomerPrintPage'])->name('credit_customer_print');
    Route::get('/download/pdf', [CreditCustomerController::class, 'downloadPDF'])->name('download_pdf');

    // Units Route
    Route::resource('/units', UnitController::class);

    //Category routes
    Route::get('categories/search', [CategoryController::class, 'searches'])->name('categories.search');

    Route::get('categories/trash', [CategoryController::class, 'trash'])->name('categories.trash');

    Route::resource('categories', CategoryController::class);

    Route::get('/categories/restore/{id}', [CategoryController::class, 'restore'])->name('categories.restore');
    Route::delete('categories/{category}/force-delete', [CategoryController::class, 'forceDelete'])->name('categories.forcedelete');

    //  Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Product Routes 
    Route::get('/search',[ProductController::class,'search']);
    Route::get('/getCategories/{supplier_id}', [ProductController::class, 'getCategories']);

    //  Route::get('/getproducts/{supplier_id}',[ProductController::class ,'getProduct']);   

    Route::get('trash', [ProductController::class, 'trash'])->name('products.trash');
    Route::resource('/products', ProductController::class);

    Route::get('products/restore/{id}', [ProductController::class, 'restore'])->name('products.restore');
    Route::delete('trash/{user}/force-delete', [ProductController::class, 'ForceDelete'])->name('products.forcedelete');
    //  Route::delete('products/{product}/trash', [ProductController::class, 'destroy'])->name('products.destroy');



    //Purchase Route
    // Route::get('/buy_products/search', [PurchaseController::class, 'search'])->name('buy_products.search');

    // Route::get('/products/{product}/details', [PurchaseController::class, 'getProductDetails'])->name('products.details');


    Route::get('/products/{product}/unit-price', [PurchaseController::class, 'getProductDetails']);

    Route::get('purchases/report/pdf', [PurchaseController::class, 'generatePdf'])->name('purchases.generatePdf');

    Route::match(['get', 'post'], 'purchases/report', [PurchaseController::class, 'generateReport'])->name('purchases.generateReport');


    Route::delete('trash/{purchase}/force-delete', [PurchaseController::class, 'ForceDelete'])->name('purchases.forcedelete');

    Route::get('purchases/restore/{id}', [PurchaseController::class, 'restore'])->name('purchases.restore');

    Route::get('purchases/trash', [PurchaseController::class, 'trash'])->name('purchases.trash');

    Route::get('purchases/approval', [PurchaseController::class, 'approvalPage'])->name('purchases.approvalPage');

    Route::get('/suppliers/{supplier}/categories', [PurchaseController::class, 'getCategories']);

    Route::get('/suppliers/{supplier}/products', [PurchaseController::class, 'getProducts']);

    Route::get('/suppliers/{supplierId}/categories/{categoryId}/products', [PurchaseController::class, 'getProductsByCategory']);

    Route::post('purchases/approve', [PurchaseController::class, 'approve'])->name('purchases.approve');

    Route::resource('purchases', PurchaseController::class);



    // Invoice Route

    Route::post('/check-stock', [InvoiceController::class, 'checkStock'])->name('check.stock');

    Route::delete('trash/{invoice}/force-delete', [InvoiceController::class, 'ForceDelete'])->name('invoices.forcedelete');

    Route::get('restore/{id}', [InvoiceController::class, 'restore'])->name('invoices.restore');

    Route::get('invoices/trash', [InvoiceController::class, 'trash'])->name('invoices.trash');

    Route::get('/suppliers/{supplierId}/categories', [InvoiceController::class, 'getCategoriesBySupplier'])
        ->name('suppliers.categories');

    Route::get('/suppliers/{supplierId}/categories/{categoryId}/products', [InvoiceController::class, 'getProductsByCategory'])
        ->name('suppliers.products');


    Route::get('/products/{product}/details', [InvoiceController::class, 'getProductDetails']);

    // Route::get('/products/{productId}/unit-price', [InvoiceController::class, 'getProductUnitPrice']);

    Route::get('invoices/approval', [InvoiceController::class, 'approvalPage'])->name('invoices.approvalPage');

    Route::post('/invoices/approve/{id}', [InvoiceController::class, 'approve'])->name('invoices.approve');

    Route::get('invoice/print/{id}', [InvoiceController::class, 'ShowInvoicePrintPage'])->name('invoices.print');

    // Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'downloadPDF'])->name('download_pdf');

    Route::match(['get', 'post'], 'invoices/report', [InvoiceController::class, 'generateInvoiceReport'])->name('invoices.generateReport');

    Route::get('invoices/generatePdf', [InvoiceController::class, 'generatePdf'])->name('invoices.generatePdf');


    Route::get('stocks/report', [InvoiceController::class, 'StockReport'])->name('stocks.report');


    // Route to display the report page
    Route::get('/reports/supplier-product', function () {
        return view('admin.managenment.invoice.supplier_product_report');
    })->name('reports.supplier_product');


    // Route to fetch suppliers
    Route::get('/supplier', [InvoiceController::class, 'GetSupplier'])->name('invoices.getSupplier');

    // Route to fetch the report for a specific supplier
    Route::get('/supplier/{id}/report', [InvoiceController::class, 'getSupplierReport'])->name('supplier.report');


    Route::get('/categorie', [InvoiceController::class, 'getCategories'])->name('categorie.fetch');

    Route::get('/product/{categoryId}', [InvoiceController::class, 'getProductsWithCategory']);

    Route::get('/category/{categoryId}/product/{productId}/report', [InvoiceController::class, 'getProductReport']);

    Route::resource('invoices', InvoiceController::class);

    // Route::get('/invoices/show',[InvoiceController::class,'show'])->name('invoices.show');

}); //End Method

//Users Route


Route::middleware(['auth', 'role:agent'])->group(function () {
    Route::get('/agent/dashboard', [AgentController::class, 'AgentDashboard'])->name('agent.dashboard');

});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// Registration Routes...
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');

Route::post('register', [RegisterController::class, 'register']);

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

Route::post('/login', [LoginController::class, 'login']);

Route::post('/user/logout', [HomeController::class, 'logout'])->name('user.logout');

