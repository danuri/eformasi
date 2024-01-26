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

$routes->group("cpns", ["filter" => "satkerauth"], function ($routes) {
    $routes->get('', 'Cpns::index');
    $routes->get('sub/(:any)', 'Cpns::sub/$1');
    $routes->get('delete/(:any)', 'Cpns::delete/$1');
    $routes->post('insert', 'Cpns::insert');
    $routes->get('rekap', 'Cpns::rekap');
    $routes->get('getjabatan/(:any)', 'Cpns::getjabatan/$1');
    $routes->post('save', 'Cpns::save');
    $routes->post('final', 'Cpns::final');
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
    $routes->post('inject/(:any)', 'Pppk::import/$1');
    $routes->get('rekap', 'Pppk::rekap');
    $routes->post('final', 'Pppk::final');

    $routes->get('tambahan', 'Pppk::tambahan');
    $routes->post('tambahan', 'Pppk::tambahansave');
    $routes->get('tambahan/delete/(:any)', 'Pppk::tambahandelete/$1');
    $routes->get('tambahan/rekap', 'Pppk::tambahanrekap');
    $routes->post('tambahan/final', 'Pppk::tambahanfinal');
});

$routes->group("referensi", ["filter" => "auth"], function ($routes) {
    $routes->get('jabatan/pelaksana', 'Referensi::jabatanpelaksana');
});

$routes->group("users", ["filter" => "satkerauth"], function ($routes) {
    $routes->get('', 'Users::index');
    $routes->get('delete/(:any)', 'Users::delete/$1');
    $routes->post('add', 'Users::add');
});
