<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\CrudModel;
use App\Models\UserModel;

class Monitor extends BaseController
{
    public function index()
    {
        $model = new CrudModel;
        // $data['cpns'] = $model->monitorCpns();
        // $data['cpppk'] = $model->monitorPppk();
        // $data['cpppktambahan'] = $model->monitorPppkTambahan();

        $model = new UserModel;
        $data['users'] = $model->where('role',1)->findAll();

        return view('monitor', $data);
    }
}
