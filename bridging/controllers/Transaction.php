<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction extends CI_Controller {
  public function __construct() {
    parent::__construct();
    $this->load->helper('fungsi');
    $logs = TRUE;
    if ($logs == TRUE) {
      $this->load->model('logs_model');
      $this->logs_model->logs();
    }
  }

  public function index() {
    
    $_POST['user'] = "ayu";
    // kunjungan
    $_POST['noKunjungan'] = ''; //kosongkan bila baru, atau isi untuk edit 0137B1560223Y000001 
    $_POST['noKartu'] = "0001261832477"; //nomor kartu BPJS
    $_POST['tglDaftar'] = "14-10-2023"; // tgl masuk (periksa)
    $_POST['kdPoli'] = '001'; //predefined {base_url}/pcare/polyclinic
    $_POST['keluhan'] = 'sakit coba-coba'; //keluhan
    $_POST['kdSadar'] = '02'; // predefined {base_url}/pcare/awareness
    $_POST['sistole'] = '120'; //gak boleh kosong
    $_POST['diastole'] = '80';//gak boleh kosong
    $_POST['beratBadan'] = '70';//gak boleh kosong
    $_POST['tinggiBadan'] = '170';//gak boleh kosong
    $_POST['respRate'] = '10';//gak boleh kosong 5 - 70
    $_POST['heartRate'] = '150';//gak boleh kosong 30-160
    $_POST['lingkarPerut'] = '36';
    $_POST['kdStatusPulang'] = '3'; // predefined (3: outpatient, 4: referral, 5: referral internal)
    $_POST['tglPulang'] = '14-10-2023'; // tgl pulang
    $_POST['kdDokter'] = '279491'; //predefined {base_url}/pcare/doctor
    $_POST['kdDiag1'] = 'D45'; // icd10
    $_POST['kdDiag2'] = ''; // icd10
    $_POST['kdDiag3'] = ''; // icd10
    $_POST['kdPoliRujukInternal'] = '021'; //predefined {base_url}/pcare/
    //$_POST['rujukLanjut'] = false;
    //$_POST['tglEstRujuk'] = '24-01-2023';
    //$_POST['kdppk'] = '0137R038';
    //$_POST['kdSubSpesialis'] = '8';
    //$_POST['catatan'] = '';
    //$_POST['kdTacc'] = '-1'; //predefined
    //$_POST['alasanTacc'] = '';
    //$_POST['kdKhusus'] = '';
    //obat
    
    $_POST['racikan'][] =  'false';
    $_POST['kdRacikan'][] =  '';
    $_POST['obatDPHO'][] =  'true';
    $_POST['kdObat'][] =  "130102058";
    $_POST['signa1'][] =  '3';
    $_POST['signa2'][] =  '1';
    $_POST['jmlObat'][] =  '10';
    $_POST['jmlPermintaan'][] =  '10';
    $_POST['nmObatNonDPHO'][] =  "";
    /*
    $_POST['racikan'][] =  'false';
    $_POST['kdRacikan'][] =  '';
    $_POST['obatDPHO'][] =  'false';
    $_POST['kdObat'][] =  "";
    $_POST['signa1'][] =  '1';
    $_POST['signa2'][] =  '1';
    $_POST['jmlObat'][] =  '3';
    $_POST['jmlPermintaan'][] =  '3';
    $_POST['nmObatNonDPHO'][] =  "Promag";
    */
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST');
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    $data['p']   = $this->input->post();
    if (!$data['p']) {
       $output = ['code' => 412, 'message' => ['Precondition Failed']];
       $this->output
       ->set_status_header(412)
       //->set_header('Access-Control-Allow-Origin: *')
       //->set_header('Access-Control-Allow-Methods: POST, GET')
       //->set_header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With')
       ->set_content_type('application/json', 'utf-8')
       //->set_content_type('text/plain')
       ->set_output(json_encode($output, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
       ->_display();
       exit;
    }
    $data['enc'] = encdec($data['p']['user']);
    header('Content-Type: text/html');
    $this->load->view("pcare_form", $data);  
  }
  
  public function searchVisit() {
    $nilai = [];
    $fp = fopen('php://input', 'r');
    parse_str(stream_get_contents($fp), $nilai);

    $url     = site_url('pcare/visitByCardNumber');
    $token   = ($nilai['token']) ? $nilai['token'] : "";
    $noKartu = ($nilai['noKartu']) ? $nilai['noKartu'] : "";
    $tanggal = ($nilai['tglDaftar']) ? $nilai['tglDaftar'] : "";
    $post    = array('token' => urldecode($token), 'noKartu' => $noKartu);

    $curl    = curl_init();
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));    
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 0); 
    curl_setopt($curl, CURLOPT_TIMEOUT, 15); //timeout in seconds

    curl_setopt($curl, CURLOPT_VERBOSE, true);
    $verbose = fopen('php://temp', 'w+');
    curl_setopt($curl, CURLOPT_STDERR, $verbose);
    $result = curl_exec($curl);
    curl_close($curl);
    
    $data  = json_decode($result, TRUE);
    $hasil = [];
    if (isset($data['code']) AND $data['code'] == 200) {
      if (isset($data['metadata']['list'])) {
        foreach ($data['metadata']['list'] as $l) {
          if ($l['tglKunjungan'] == $tanggal) {
            $hasil['noKunjungan'] = $l['noKunjungan'];
          }
        }
      }
    }

    header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');
    echo json_encode($hasil);
  }

  public function referral($noKunjungan, $token) {
    header('Access-Control-Allow-Origin: *');
    if (!$noKunjungan) {
      $this->output->set_status_header('404');
      echo "Tidak ditemukan Nomor Kunjungan";
      exit;
    }

  //public function CallAPI($method, $url, $head = false, $data = false, $debug = false) {
    $url = site_url('pcare/visitById');
    $kirim = array('token' => urldecode($token), 'noKunjungan' => $noKunjungan);
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($kirim));    
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 0); 
    curl_setopt($curl, CURLOPT_TIMEOUT, 15); //timeout in seconds

    curl_setopt($curl, CURLOPT_VERBOSE, true);
    $verbose = fopen('php://temp', 'w+');
    curl_setopt($curl, CURLOPT_STDERR, $verbose);
    $result = curl_exec($curl);
    curl_close($curl);
    $data = json_decode($result, TRUE);
    $this->load->view("printRujukan", $data);
  }
}

