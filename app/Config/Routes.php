<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

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
    // $routes->post('insert', 'Bezzeting::insert');
    // $routes->get('delete/(:any)', 'Bezzeting::delete/$1');
    // $routes->get('deletex/(:any)', 'Bezzeting::deletex/$1');
    $routes->get('getdata/(:any)', 'Bezzeting::getdata/$1');
    $routes->get('getunorchild/(:any)', 'Bezzeting::getUnorChild/$1');
    $routes->post('savependidikan', 'Bezzeting::savependidikan');
});

$routes->group("cpns", ["filter" => "auth"], function ($routes) {
    $routes->get('', 'Cpns::index');
    $routes->get('sub/(:any)', 'Cpns::sub/$1');
    $routes->get('delete/(:any)', 'Cpns::delete/$1');
    $routes->post('insert', 'Cpns::insert');
    $routes->post('edit/(:any)', 'Pppk::editsave/$1');
    $routes->get('status/(:any)/(:any)', 'Pppk::status/$1/$2');
    $routes->get('statuspegawai/(:any)/(:any)', 'Pppk::statuspegawai/$1/$2');
    $routes->get('search', 'Pppk::searchunor');
    $routes->get('searchpendidikan', 'Pppk::searchpendidikan');
    $routes->get('export/(:any)', 'Pppk::export/$1');
});

$routes->group("pppk", ["filter" => "auth"], function ($routes) {
    $routes->get('', 'Pppk::index');
    $routes->get('edit/(:any)', 'Pppk::edit/$1');
    $routes->post('edit/(:any)', 'Pppk::editsave/$1');
    $routes->get('data/(:any)', 'Pppk::data/$1');
    $routes->get('status/(:any)/(:any)', 'Pppk::status/$1/$2');
    $routes->get('statuspegawai/(:any)/(:any)', 'Pppk::statuspegawai/$1/$2');
    $routes->get('search', 'Pppk::searchunor');
    $routes->get('searchpendidikan', 'Pppk::searchpendidikan');
    $routes->get('export/(:any)', 'Pppk::export/$1');
    $routes->get('inject/(:any)', 'Pppk::inject/$1');
});
