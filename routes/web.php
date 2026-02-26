<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PremiumController;
use App\Http\Controllers\Admin\MembershipPlanController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ItineraryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LugarController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\InicioSectionController;
use App\Http\Controllers\Admin\NavItemController;
use App\Http\Controllers\Admin\ConfigurationController;
use App\Http\Controllers\Admin\MessageController as AdminMessageController;
use App\Http\Controllers\Admin\FormController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\SeccionesController;
use App\Http\Controllers\Admin\HotelController as AdminHotelController;
use App\Http\Controllers\Admin\HotelContactController as AdminHotelContactController;
use App\Http\Controllers\Admin\HotelPlanController;
use App\Http\Controllers\Admin\HotelOrderController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\LugarBannerController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\HotelOwnerController;
use App\Http\Controllers\MercadoPagoController;
use Illuminate\Support\Facades\Route;

// Hotel public routes (static before slug)
Route::get('/hoteles', [HotelController::class, 'index'])->name('hoteles.index');
Route::get('/hoteles/propietarios', [HotelOwnerController::class, 'propietarios'])->name('hoteles.propietarios');
Route::post('/hoteles/{hotel:slug}/contacto', [HotelController::class, 'contact'])->name('hoteles.contact');
Route::get('/hoteles/{hotel:slug}', [HotelController::class, 'show'])->name('hoteles.show');

// Hotel owner (auth required)
Route::middleware('auth')->prefix('hoteles')->name('hoteles.')->group(function () {
    Route::get('/planes', [HotelOwnerController::class, 'planes'])->name('owner.planes');
    Route::get('/registrar/{plan:slug}', [HotelOwnerController::class, 'create'])->name('owner.create');
    Route::post('/registrar/{plan:slug}', [HotelOwnerController::class, 'store'])->name('owner.store');
    Route::get('/checkout/{order}', [HotelOwnerController::class, 'checkout'])->name('owner.checkout');
    Route::post('/checkout/{order}', [HotelOwnerController::class, 'storeCheckout'])->name('owner.storeCheckout');
    Route::get('/mi-hotel', [HotelOwnerController::class, 'panel'])->name('owner.panel');
    Route::get('/mi-hotel/editar', [HotelOwnerController::class, 'edit'])->name('owner.edit');
    Route::put('/mi-hotel', [HotelOwnerController::class, 'update'])->name('owner.update');
    Route::get('/pedido/{order}', [HotelOwnerController::class, 'confirmacion'])->name('owner.confirmacion');
});

// Coupon AJAX validation (public)
Route::get('/api/validate-coupon', [CouponController::class, 'validate'])->name('coupon.validate');

// MercadoPago callbacks (auth required)
Route::middleware('auth')->group(function () {
    Route::get('/checkout/mercadopago/callback', [MercadoPagoController::class, 'membershipCallback'])->name('checkout.mp.membership.callback');
    Route::get('/checkout/{order}/mercadopago', [MercadoPagoController::class, 'createMembershipPreference'])->name('checkout.mp.membership');
    Route::get('/hoteles/checkout/mercadopago/callback', [MercadoPagoController::class, 'hotelCallback'])->name('checkout.mp.hotel.callback');
    Route::get('/hoteles/checkout/{hotelOrder}/mercadopago', [MercadoPagoController::class, 'createHotelPreference'])->name('checkout.mp.hotel');
});

// MercadoPago webhook (fully public, no CSRF)
Route::post('/webhooks/mercadopago', [MercadoPagoController::class, 'webhook'])->name('webhooks.mercadopago');

// Public routes
Route::get('/', [PageController::class, 'inicio'])->name('inicio');
Route::get('/lugares', [PageController::class, 'lugares'])->name('lugares');
Route::get('/lugares/{lugar}', [PageController::class, 'lugar'])->name('lugar.show');
Route::get('/guias', [PageController::class, 'guias'])->name('guias');
Route::get('/contacto', [PageController::class, 'contacto'])->name('contacto');
Route::post('/formulario/{slug}', [MessageController::class, 'store'])->name('formulario.submit');

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Membership / plans
Route::get('/premium/planes', [MembershipController::class, 'planes'])->name('membership.planes');
Route::get('/premium/checkout/{plan:slug}',          [MembershipController::class, 'checkout'])->name('membership.checkout');
Route::post('/premium/checkout/{plan:slug}/register', [MembershipController::class, 'checkoutRegister'])->name('membership.checkout.register');
Route::middleware('auth')->group(function () {
    Route::post('/premium/checkout/{plan:slug}', [MembershipController::class, 'store'])->name('membership.store');
    Route::get('/premium/pedido/{order}',        [MembershipController::class, 'confirmacion'])->name('membership.confirmacion');
});

// Premium routes
Route::get('/premium', [PremiumController::class, 'index'])->name('premium.upsell');
Route::middleware(['auth', 'premium'])->prefix('premium')->name('premium.')->group(function () {
    Route::get('/panel', [PremiumController::class, 'hub'])->name('hub');
    Route::get('/planificar', [PremiumController::class, 'planner'])->name('planner');
    Route::get('/resultados', [PremiumController::class, 'resultados'])->name('resultados');
    Route::get('/itinerario/{itinerary:slug}', [PremiumController::class, 'show'])->name('show');
});

// Admin routes
Route::prefix(env('ADMIN_PREFIX', 'admin'))->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('lugares/import', [LugarController::class, 'import'])->name('lugares.import');
    Route::resource('lugares', LugarController::class)->parameters(['lugares' => 'lugar']);
    Route::resource('usuarios', UserController::class)->except(['show']);

    // Inicio sections (specific routes before parameterised)
    Route::get('inicio', [InicioSectionController::class, 'index'])->name('inicio.index');
    Route::post('inicio/reorder', [InicioSectionController::class, 'reorder'])->name('inicio.reorder');
    Route::post('inicio/{sectionKey}/banner', [InicioSectionController::class, 'updateSectionBanner'])->name('inicio.banner.update');
    Route::delete('inicio/{sectionKey}/banner', [InicioSectionController::class, 'deleteSectionBanner'])->name('inicio.banner.delete');
    Route::put('inicio/{inicioSection}', [InicioSectionController::class, 'update'])->name('inicio.update');

    // Nav menu management
    Route::get('nav', [NavItemController::class, 'index'])->name('nav.index');
    Route::post('nav/reorder', [NavItemController::class, 'reorder'])->name('nav.reorder');
    Route::put('nav/{navItem}', [NavItemController::class, 'update'])->name('nav.update');

    // Configuraciones
    Route::get('configuraciones', [ConfigurationController::class, 'index'])->name('configuraciones.index');
    Route::post('configuraciones/backup', [ConfigurationController::class, 'updateBackup'])->name('configuraciones.backup.update');
    Route::post('configuraciones/backup/run', [ConfigurationController::class, 'runBackup'])->name('configuraciones.backup.run');
    Route::get('configuraciones/backup/download', [ConfigurationController::class, 'downloadBackup'])->name('configuraciones.backup.download');
    Route::post('configuraciones/smtp', [ConfigurationController::class, 'updateSmtp'])->name('configuraciones.smtp.update');
    Route::post('configuraciones/smtp/test', [ConfigurationController::class, 'testSmtp'])->name('configuraciones.smtp.test');
    Route::post('configuraciones/payment', [ConfigurationController::class, 'updatePayment'])->name('configuraciones.payment.update');
    Route::post('configuraciones/recaptcha', [ConfigurationController::class, 'updateRecaptcha'])->name('configuraciones.recaptcha.update');
    Route::post('configuraciones/itinerarios', [ConfigurationController::class, 'updateItineraryFilters'])->name('configuraciones.itinerarios.update');
    Route::post('configuraciones/planner-hero', [ConfigurationController::class, 'updatePlannerHero'])->name('configuraciones.planner-hero.update');
    Route::delete('configuraciones/planner-hero', [ConfigurationController::class, 'deletePlannerHero'])->name('configuraciones.planner-hero.delete');
    Route::post('configuraciones/hub-hero', [ConfigurationController::class, 'updateHubHero'])->name('configuraciones.hub-hero.update');
    Route::delete('configuraciones/hub-hero', [ConfigurationController::class, 'deleteHubHero'])->name('configuraciones.hub-hero.delete');
    Route::post('configuraciones/planner-chat', [ConfigurationController::class, 'updatePlannerChat'])->name('configuraciones.planner-chat.update');
    Route::post('configuraciones/payment-methods', [ConfigurationController::class, 'updatePaymentMethods'])->name('configuraciones.payment-methods.update');
    Route::post('configuraciones/payment-methods/test-mp', [ConfigurationController::class, 'testMercadoPago'])->name('configuraciones.payment-methods.test-mp');
    Route::post('configuraciones/mantenimiento', [ConfigurationController::class, 'updateMaintenance'])->name('configuraciones.mantenimiento.update');

    // Mensajes
    Route::get('mensajes', [AdminMessageController::class, 'index'])->name('mensajes.index');
    Route::get('mensajes/{mensaje}', [AdminMessageController::class, 'show'])->name('mensajes.show');
    Route::post('mensajes/{mensaje}/read', [AdminMessageController::class, 'markRead'])->name('mensajes.read');
    Route::post('mensajes/{mensaje}/unread', [AdminMessageController::class, 'markUnread'])->name('mensajes.unread');
    Route::delete('mensajes/{mensaje}', [AdminMessageController::class, 'destroy'])->name('mensajes.destroy');

    // Itinerarios
    Route::get('itinerarios', [ItineraryController::class, 'index'])->name('itinerarios.index');
    Route::get('itinerarios/crear', [ItineraryController::class, 'create'])->name('itinerarios.create');
    Route::post('itinerarios', [ItineraryController::class, 'store'])->name('itinerarios.store');
    Route::get('itinerarios/{itinerario}/editar', [ItineraryController::class, 'edit'])->name('itinerarios.edit');
    Route::put('itinerarios/{itinerario}', [ItineraryController::class, 'update'])->name('itinerarios.update');
    Route::delete('itinerarios/{itinerario}', [ItineraryController::class, 'destroy'])->name('itinerarios.destroy');
    Route::get('itinerarios/{itinerario}/actividades', [ItineraryController::class, 'items'])->name('itinerarios.items');
    Route::post('itinerarios/{itinerario}/actividades', [ItineraryController::class, 'storeItem'])->name('itinerarios.items.store');
    Route::put('itinerarios/{itinerario}/actividades/{item}', [ItineraryController::class, 'updateItem'])->name('itinerarios.items.update');
    Route::delete('itinerarios/{itinerario}/actividades/{item}', [ItineraryController::class, 'destroyItem'])->name('itinerarios.items.destroy');

    // Premium user management
    Route::post('usuarios/{usuario}/premium/grant', [\App\Http\Controllers\Admin\UserController::class, 'grantPremium'])->name('usuarios.premium.grant');
    Route::post('usuarios/{usuario}/premium/revoke', [\App\Http\Controllers\Admin\UserController::class, 'revokePremium'])->name('usuarios.premium.revoke');

    // Pedidos (orders)
    Route::get('pedidos', [OrderController::class, 'index'])->name('pedidos.index');
    Route::get('pedidos/{order}', [OrderController::class, 'show'])->name('pedidos.show');
    Route::post('pedidos/{order}/completar', [OrderController::class, 'complete'])->name('pedidos.complete');
    Route::post('pedidos/{order}/cancelar', [OrderController::class, 'cancel'])->name('pedidos.cancel');

    // Planes de membresía
    Route::get('planes', [MembershipPlanController::class, 'index'])->name('planes.index');
    Route::post('planes', [MembershipPlanController::class, 'store'])->name('planes.store');
    Route::put('planes/{plan}', [MembershipPlanController::class, 'update'])->name('planes.update');
    Route::delete('planes/{plan}', [MembershipPlanController::class, 'destroy'])->name('planes.destroy');
    Route::post('planes/{plan}/sale', [MembershipPlanController::class, 'updateSale'])->name('planes.sale.update');

    // Secciones (editor de páginas)
    Route::get('secciones', [SeccionesController::class, 'index'])->name('secciones.index');
    Route::post('secciones/contact-info', [SeccionesController::class, 'updateContactInfo'])->name('secciones.contact-info.update');

    // Formularios
    Route::get('formularios', [FormController::class, 'index'])->name('formularios.index');
    Route::put('formularios/{formulario}', [FormController::class, 'updateForm'])->name('formularios.update');
    Route::get('formularios/{formulario}/campos', [FormController::class, 'campos'])->name('formularios.campos');
    Route::put('formularios/{formulario}/campos/{campo}', [FormController::class, 'updateField'])->name('formularios.campos.update');
    Route::post('formularios/{formulario}/campos/reorder', [FormController::class, 'reorderFields'])->name('formularios.campos.reorder');

    // Hoteles
    Route::get('hoteles', [AdminHotelController::class, 'index'])->name('hoteles.index');
    Route::get('hoteles/analiticas', [AdminHotelController::class, 'analytics'])->name('hoteles.analytics');
    Route::get('hoteles/{hotel}', [AdminHotelController::class, 'show'])->name('hoteles.show');
    Route::post('hoteles/{hotel}/aprobar', [AdminHotelController::class, 'approve'])->name('hoteles.approve');
    Route::post('hoteles/{hotel}/rechazar', [AdminHotelController::class, 'reject'])->name('hoteles.reject');
    Route::delete('hoteles/{hotel}', [AdminHotelController::class, 'destroy'])->name('hoteles.destroy');

    // Planes de hotel
    Route::get('hotel-planes', [HotelPlanController::class, 'index'])->name('hotel-planes.index');
    Route::post('hotel-planes', [HotelPlanController::class, 'store'])->name('hotel-planes.store');
    Route::put('hotel-planes/{hotelPlan}', [HotelPlanController::class, 'update'])->name('hotel-planes.update');
    Route::delete('hotel-planes/{hotelPlan}', [HotelPlanController::class, 'destroy'])->name('hotel-planes.destroy');
    Route::post('hotel-planes/{hotelPlan}/sale', [HotelPlanController::class, 'updateSale'])->name('hotel-planes.sale.update');

    // Promociones
    Route::get('promociones', [PromotionController::class, 'index'])->name('promociones.index');
    Route::post('promociones', [PromotionController::class, 'store'])->name('promociones.store');
    Route::put('promociones/{promo}', [PromotionController::class, 'update'])->name('promociones.update');
    Route::delete('promociones/{promo}', [PromotionController::class, 'destroy'])->name('promociones.destroy');

    // Banners en Lugares
    Route::resource('lugar-banners', LugarBannerController::class)->parameters(['lugar-banners' => 'lugarBanner']);

    // Contactos de hotel
    Route::get('hotel-contactos', [AdminHotelContactController::class, 'index'])->name('hotel-contactos.index');

    // Pedidos de hotel
    Route::get('hotel-pedidos', [HotelOrderController::class, 'index'])->name('hotel-pedidos.index');
    Route::get('hotel-pedidos/{hotelOrder}', [HotelOrderController::class, 'show'])->name('hotel-pedidos.show');
    Route::post('hotel-pedidos/{hotelOrder}/completar', [HotelOrderController::class, 'complete'])->name('hotel-pedidos.complete');
    Route::post('hotel-pedidos/{hotelOrder}/cancelar', [HotelOrderController::class, 'cancel'])->name('hotel-pedidos.cancel');

    // Analytics
    Route::get('analytics', [AnalyticsController::class, 'dashboard'])->name('analytics.dashboard');
    Route::post('analytics/settings', [AnalyticsController::class, 'updateSettings'])->name('analytics.settings.update');
    Route::post('analytics/refresh', [AnalyticsController::class, 'refresh'])->name('analytics.refresh');
    Route::delete('analytics/credentials', [AnalyticsController::class, 'deleteCredentials'])->name('analytics.credentials.delete');
});
