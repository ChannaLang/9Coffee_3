
<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Products\ProductsController;
use App\Http\Controllers\Admins\AdminsController;
use App\Http\Controllers\Users\UsersController;
use App\Http\Controllers\HomeController;
use App\Exports\SalesReportExport;
use App\Exports\OrdersExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Admins\AdminPaymentController;
use App\Http\Controllers\Admins\BookingController;
use App\Http\Controllers\Admins\ExpenseController;
use App\Http\Controllers\Admins\OrderController;
use App\Http\Controllers\Admins\ProductController;
use App\Http\Models\Product\RawMaterial;
use App\Http\Controllers\Admins\RawMaterialController;
use App\Http\Controllers\Admins\ReportController;
use App\Http\Controllers\Admins\StaffController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 🟢 Default Laravel auth
Auth::routes();

// 🟢 Public user pages
Route::get('/', function () {return redirect()->route('view.login');});
// Route::get('products/contact', [ProductsController::class, 'contact'])->name('product.contact');
// Route::get('products/service', [ProductsController::class, 'service'])->name('product.service');
// Route::get('products/menu', [ProductsController::class, 'menu'])->name('product.menu');
// Route::get('products/about', [ProductsController::class, 'about'])->name('product.about');
// Route::get('products/product-single/{id}', [ProductsController::class, 'singleProduct'])->name('product.single');
// Route::get('/welcome', [HomeController::class, 'welcome'])->name('welcome');
// Route::post('products/product-single/{id}', [ProductsController::class, 'addCart'])->name('add.cart');
// Route::get('products/cart', [ProductsController::class, 'cart'])->name('cart')->middleware('auth:web');
// Route::get('products/cart-delete/{id}', [ProductsController::class, 'deleteProductCart'])->name('cart.product.delete');
// Route::post('products/prepare-checkout', [ProductsController::class, 'prepareCheckout'])->name('prepare.checkout');
// Route::get('products/checkout', [ProductsController::class, 'checkout'])->name('checkout')->middleware('auth:web');
// Route::post('products/store-checkout', [ProductsController::class, 'storeCheckout'])->name('store.checkout');
// Route::post('products/checkout', [ProductsController::class, 'proccessCheckout'])->name('proccess.checkout')->middleware('auth:web');
// Route::get('products/success', [ProductsController::class, 'success'])->name('products.success');
// Route::get('products/paypal', [ProductsController::class, 'paywithpaypal'])->name('products.paypal')->middleware('check.for.price');
// Route::post('products/paypal', [ProductsController::class, 'paywithpaypal'])->name('products.paypal')->middleware('check.for.price');
// Route::get('admin/staff-qr-pay/{order_ref}', [AdminsController::class, 'qrPay'])->name('staff.qr-pay');
// Route::middleware(['auth:admin'])->group(function () {
// Route::get('/admin/paypal', [AdminsController::class, 'paywithPaypal'])->name('admin.paypal');
// Route::get('/admin/paypal-success', [AdminsController::class, 'paypalSuccess'])->name('admin.paypal.success');});
// Route::get('products/success', [ProductsController::class, 'success'])->name('products.success')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
// Route::get('/admin/qr-payment', [AdminsController::class, 'showQrPayment'])->name('admin.qr.payment');
// Route::get('receipt/{id}', [ProductsController::class, 'showReceipt'])->name('receipt.show');
// Route::post('products/booking', [ProductsController::class, 'BookingTables'])->name('booking.tables');
// Route::get('users/menu', [UsersController::class, 'displayOrders'])->name('users.orders')->middleware('auth:web');
// Route::get('users/bookings', [UsersController::class, 'displayBookings'])->name('users.bookings')->middleware('auth:web');
// Route::get('users/write-reviews', [UsersController::class, 'writeReviews'])->name('write.reviews')->middleware('auth:web');
// Route::post('users/write-reviews', [UsersController::class, 'proccesswriteReviews'])->name('proccess.write.reviews')->middleware('auth:web');
// Route::get('/staff-qr-pay/{order_ref}', [AdminsController::class, 'qrPay'])->name('staff.qr-pay');







    Route::middleware('guest:admin')->group(function () {
    Route::post('/login-user', [ProductsController::class, 'loginUser'])->name('login.user');
    Route::get('admin/login', [AdminsController::class, 'viewLogin'])->name('view.login');
    Route::post('admin/login', [AdminsController::class, 'checkLogin'])->name('check.login');
});
// 🔒 Protected admin routes
Route::middleware(['auth:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminsController::class, 'index'])->name('admins.dashboard');
    Route::post('/logout', [AdminsController::class, 'logout'])->name('admin.logout');
    Route::get('/all-users', [AdminsController::class, 'DisplayAllUsers'])->name('all.users');
    Route::get('/all-admins', [AdminsController::class, 'DisplayAllAdmins'])->name('all.admins');
    Route::get('/create-admins', [AdminsController::class, 'createAdmins'])->name('create.admins');
    Route::post('/create-admins', [AdminsController::class, 'storeAdmins'])->name('store.admins');
    Route::get('/edit-admin/{id}', [AdminsController::class, 'editAdmin'])->name('edit.admin');
    Route::post('/update-admin/{id}', [AdminsController::class, 'updateAdmin'])->name('update.admins');
    Route::delete('/delete-admin/{id}', [AdminsController::class, 'deleteAdmin'])->name('delete.admin');
    Route::get('/help', [AdminsController::class, 'Help'])->name('admins.help');

    // RawMaterial routes
    Route::get('/raw-materials/list', [RawMaterialController::class, 'list'])
        ->name('admin.raw-materials.list');

    Route::post('/raw-material/store', [RawMaterialController::class, 'store'])
        ->name('admin.raw-material.store');

    Route::delete('/raw-material/{id}', [RawMaterialController::class, 'destroy'])
        ->name('admin.raw-material.destroy');

    Route::patch('/raw-material/update/{id}', [RawMaterialController::class, 'updateRawMaterial'])
        ->name('admin.raw-material.update');

    Route::get('/stock', [RawMaterialController::class, 'viewRawMaterials'])
        ->name('admin.raw-material.stock');

    // RAW MATERIAL STOCK ADJUST
    Route::post('/raw-material/{id}/add-stock', [RawMaterialController::class, 'addStock'])
        ->name('admin.raw-material.addStock');

    Route::post('/raw-material/{id}/reduce-stock', [RawMaterialController::class, 'reduceStock'])
        ->name('admin.raw-material.reduceStock');



    // Product + Materials
    Route::post('/product/{product}/add-materials', [ProductController::class, 'addMaterials'])
        ->name('admin.product.addMaterials');
    Route::get('/admin/product/{id}/get-assigned-materials', [ProductController::class, 'getAssignedMaterials'])
    ->name('admin.product.getAssignedMaterials');






    // Admin Reports & Analytics
    Route::get('admin/reports/sales', [ReportController::class, 'salesReport'])->name('admin.sales.report');
    Route::get('admin/low-stock', [ReportController::class, 'lowStock'])->name('admin.low.stock');



    Route::post('admin/expenses', [ExpenseController::class, 'storeExpense'])->name('admin.expenses.store');
    Route::get('admin/reports/sales/download', function () {return Excel::download(new SalesReportExport, 'sales_report.xlsx');})->name('admin.sales.report.download');
    Route::get('/orders/export', function() {return Excel::download(new OrdersExport, 'orders.xlsx');})->name('orders.export');
    Route::get('admin/expenses', [ExpenseController::class, 'viewExpenses'])->name('admin.expenses');


    // Orders management
    Route::get('/all-orders', [OrderController::class, 'DisplayAllOrders'])->name('all.orders');
    Route::get('/edit-orders/{id}', [OrderController::class, 'EditOrders'])->name('edit.orders');
    Route::post('/edit-orders/{id}', [OrderController::class, 'UpdateOrders'])->name('update.orders');
    Route::delete('/delete-orders/{id}', [OrderController::class, 'DeleteOrders'])->name('delete.orders');
    Route::delete('/delete-all-orders', [OrderController::class, 'DeleteAllOrders'])->name('delete.all.orders');


    // Products management
    Route::get('/all-products', [ProductController::class, 'DisplayProducts'])->name('all.products');
    Route::get('/create-products', [ProductController::class, 'CreateProducts'])->name('create.products');
    Route::post('/store-products', [ProductController::class, 'StoreProducts'])->name('store.products');
    Route::post('/edit-products/{id}', [ProductController::class, 'AjaxUpdateProducts'])->name('ajax.edit.products');
    Route::post('/update-products/{id}', [ProductController::class, 'UpdateProducts'])->name('update.products');
    Route::delete('/delete-products/{id}', [ProductController::class, 'DeleteProducts'])->name('ajax.delete.products');

    // Bookings management
    Route::get('/all-bookings', [BookingController::class, 'DisplayBookings'])->name('all.bookings');
    Route::get('/edit-bookings/{id}', [BookingController::class, 'EditBookings'])->name('edit.bookings');
    Route::post('/update-bookings/{id}', [BookingController::class, 'UpdateBookings'])->name('update.bookings');
    Route::delete('/delete-bookings/{id}', [BookingController::class, 'DeleteBookings'])->name('delete.bookings');
    Route::get('/create-bookings', [BookingController::class, 'CreateBookings'])->name('create.bookings');
    Route::post('/store-bookings', [BookingController::class, 'StoreBookings'])->name('store.bookings');

    // Payments
    Route::get('/paypal', [AdminPaymentController::class, 'paywithPaypal'])->name('admin.paypal');
    Route::get('/paypal-success', [AdminPaymentController::class, 'paypalSuccess'])->name('admin.paypal.success');
    Route::get('/qr-payment', [AdminPaymentController::class, 'showQrPayment'])->name('admin.qr.payment');

    // Other tools
    Route::get('/staff-sell', [StaffController::class, 'StaffSellForm'])->name('staff.sell.form');
    Route::post('/staff-sell', [StaffController::class, 'StaffSellProduct'])->name('staff.sell');
    Route::post('/staff-checkout', [StaffController::class, 'staffCheckout'])->name('staff.checkout');
    Route::get('/staff-checkout', [StaffController::class, 'staffCheckout'])->name('staff.checkout');


});

