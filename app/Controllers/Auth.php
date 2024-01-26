<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Auth extends BaseController
{

  public function index()
  {
    $this->login();
  }

  public function login()
  {
    if (!session()->get('isLoggedIn')) {
      return redirect()->to($_ENV['SSO_SIGNIN'].'?appid='.$_ENV['SSO_APPID']);
    }else{
      return redirect()->to('formasi');
    }
  }

  public function callback()
  {
    $request = \Config\Services::request();
    $client = \Config\Services::curlrequest();

    $token = $request->getVar('token') ?? '';
    if($token){
      $verify_url = $_ENV['SSO_VERIFY'];

      $response = $client->request('POST', $verify_url, [
        'headers' => [
            'Accept'        => 'application/json',
            'Authorization' => 'Bearer '. $token,
        ],
        'verify' => false
      ]);

      $data = json_decode($response->getBody());

      if($data->status == 200){
        $data = $data->pegawai;

          $model = new UserModel;

          $cek = $model->where('nip',$data->NIP)->first();
          if($cek){
            $ses_data = [
              'nip'        => $data->NIP,
              'niplama'    => $data->NIP_LAMA,
              'nama'       => $data->NAMA,
              'pangkat'    => $data->PANGKAT,
              'golongan'    => $data->GOLONGAN,
              'jabatan'    => $data->JABATAN,
              'satker4'    => $data->KODE_SATKER_4,
              'role'       => $cek->role,
              'idsatker'  => $cek->id,
              'kodesatker' => $cek->kode_satker,
              'satker'     => $cek->satuan_kerja,
              'unorid'     => $cek->unor_id,
              'isLoggedIn' => TRUE
            ];
            session()->set($ses_data);

            // if($cek->role == 1){
            // }
            return redirect()->to('home');
          }else{
            return redirect()->to('auth/forbidden');
          }

      }else{
        echo $data->msg;
      }
    }else{
      echo 'no Token';
    }
  }

  public function forbidden()
  {
    echo 'Tidak ada akses';
  }

  public function logout()
  {
    $session = session();
    $session->destroy();
    return redirect()->to($_ENV['SSO_SIGNIN'].'?appid='.$_ENV['SSO_APPID']);
  }
}
