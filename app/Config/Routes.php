<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\CartController;
use App\Controllers\CheckoutController;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Admin::index');
$routes->get('orders', 'Admin::orders');
$routes->get('index', 'Admin::index');
$routes->get('inventory', 'Admin::inventory');
$routes->post('inventory/add', 'Admin::addProduct');
$routes->post('inventory/delete', 'Admin::deleteProduct');
$routes->post('inventory/edit', 'Admin::editProduct');



$routes->group('api', function ($routes) {
    // User API
    $routes->get('users', 'UserController::getUsers');
    $routes->post('users/add', 'UserController::addUser');
    $routes->post('users/edit', 'UserController::editUser');
    $routes->post('users/delete', 'UserController::deleteUser');

    // Product API
    $routes->get('products', 'ProductController::getProducts');
    $routes->post('products/add', 'ProductController::addProduct');
    $routes->post('products/edit', 'ProductController::editProduct');
    $routes->post('products/delete', 'ProductController::deleteProduct');

    // Cart API
    $routes->get('carts', 'CartController::getCarts');
    $routes->get('cart/(:num)', 'CartController::getCart/$1');
    $routes->post('cart', 'CartController::createCart');
    $routes->put('cart/edit/(:num)', 'CartController::updateCart/$1');
    $routes->put('cart/updateStatus', 'CartController::updateCartStatus');
    $routes->delete('cart/delete/(:num)', 'CartController::deleteCart/$1');
    
    // Checkout API
    $routes->get('checkouts', 'CheckoutController::getCheckouts');
    $routes->get('checkout/(:num)', 'CheckoutController::getCheckout/$1');
    $routes->post('checkout', 'CheckoutController::createCheckout');
    $routes->put('checkout/edit/(:num)', 'CheckoutController::updateCheckout/$1');
    $routes->delete('checkout/delete/(:num)', 'CheckoutController::deleteCheckout/$1');
});
