<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\CrudModel;

class Monitor extends BaseController
{
    public function index()
    {
        $model = new CrudModel;
        $data['cpns'] = $model->monitorCpns();
        $data['cpppk'] = $model->monitorPppk();
        $data['cpppktambahan'] = $model->monitorPppkTambahan();

        return view('monitor', $data);
    }
}
