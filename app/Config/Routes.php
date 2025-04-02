<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\CartController;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Admin::index');
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
    $routes->post('cart/edit/(:num)', 'CartController::updateCart/$1');
    $routes->post('cart/delete/(:num)', 'CartController::deleteCart/$1');
});
