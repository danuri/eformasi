<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('auth', 'Auth::login');
$routes->get('auth/login', 'Auth::login');
$routes->get('auth/logout', 'Auth::logout');
$routes->get('auth/callback', 'Auth::callback');
$routes->get('auth/forbidden', 'Auth::forbidden');

$routes->get('/', 'Home::index',['filter' => 'auth']);
$routes->get('home', 'Home::index',['filter' => 'auth']);

$routes->get('rekapitulasi', 'Rekapitulasi::index',['filter' => 'auth']);
$routes->group("bezzeting", ["filter" => "auth"], function ($routes) {
    $routes->get('', 'Bezzeting::index');
    $routes->get('dxuf', 'Bezzeting::dxuf');
    $routes->post('insert', 'Bezzeting::insert');
    $routes->get('delete/(:any)', 'Bezzeting::delete/$1');
    $routes->get('deletex/(:any)', 'Bezzeting::deletex/$1');
    $routes->get('getdata/(:any)', 'Bezzeting::getdata/$1');
    $routes->get('getunorchild/(:any)', 'Bezzeting::getUnorChild/$1');
    $routes->post('savependidikan', 'Bezzeting::savependidikan');
});

$routes->group("pppk", ["filter" => "auth"], function ($routes) {
    $routes->get('', 'Pppk::index');
    $routes->get('edit/(:any)', 'Pppk::edit/$1');
    $routes->post('edit/(:any)', 'Pppk::editsave/$1');
    $routes->get('data/(:any)', 'Pppk::data/$1');
    $routes->get('status/(:any)/(:any)', 'Pppk::status/$1/$2');
    $routes->get('search', 'Pppk::searchunor');
    $routes->get('searchpendidikan', 'Pppk::searchpendidikan');
    $routes->get('export/(:any)', 'Pppk::export/$1');
});

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
