<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//include_once (dirname(__FILE__) . "/Users.php"); // user udah pake database
include_once (dirname(__FILE__) . "/PcareRequired.php");
include_once (dirname(__FILE__) . "/Api.php");
include_once APPPATH . 'vendor/autoload.php';

//use Users\Akses;
use Pcare\Required;

class Pcare extends Api {
  private $url    = 'https://apijkn-dev.bpjs-kesehatan.go.id/pcare-rest-dev/';
  //private $url    = 'https://dvlp.bpjs-kesehatan.go.id:9081/pcare-rest-v3.0/';
  private $data   = FALSE;
  private $tStamp = FALSE;
  private $translate = FALSE;
      
  public function __construct() {
    parent::__construct();
    $this->load->helper('fungsi');
    $this->load->database();
    //cek user
    //$akses   = new Akses;
    $cekUser = FALSE;
    $method  = $this->input->server('REQUEST_METHOD');
    
    if ($method == 'POST' or $method == 'DELETE' or $method == 'PUT')  {
      $fp = fopen('php://input', 'r');
      parse_str(stream_get_contents($fp), $this->data);
      if ($this->data['token']) {
        $user = encdec($this->data['token'], FALSE);
        if ($user) {
          $qu   = $this->db->get_where('credential', ['user' => $user]);
          if ($qu->num_rows()) {
            $this->cekUser = $qu->result_array()[0];
            if ($this->cekUser['production'] == 1)
              $this->url = 'https://apijkn.bpjs-kesehatan.go.id/pcare-rest/';
          }
        }
      }
      if (!$this->cekUser) {
        $hasil['error'][] = 'Access denied';
        $hasil['code'] = 401;
        $this->tampil($hasil);
        exit;
      }
    } elseif ($method == 'GET') { 
    
      
    } else {
      $hasil['code']    = 405;
      $hasil['error'][] = 'METHOD NOT ALLOWED - GUNAKAN METHOD POST';
      $this->tampil($hasil);
      exit;
    }
  }
  
  private function header () {
    // Header tambahan BPJS
    date_default_timezone_set('UTC');
    $this->tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
    $signature = hash_hmac('sha256', $this->cekUser['cons_id']."&".$this->tStamp, $this->cekUser['secretKey'], true);
    $encodedSignature = base64_encode($signature);

    $auth = base64_encode($this->cekUser['username'] .':'. $this->cekUser['password'] .':'. $this->cekUser['kdApp']);

    $header = array("X-cons-id: " . $this->cekUser['cons_id'],
                    "X-timestamp: " . $this->tStamp,
                    "X-signature: " . $encodedSignature,
                    "X-authorization: Basic " . $auth, 
                    "user_key: " . $this->cekUser['userkey'],
                    "Content-Type: text/plain"
                  );
    return $header;
  }

  private function terjemahVariabel($json) {
    if (!$this->translate) return $json;
    
    $terjemah = [
      'noKartu' => 'noKartu',
      'nama' => 'name',
      'nmPst' => 'name',
      'peserta' => 'patient',
      'hubunganKeluarga' => 'familyRelationship',
      'tglLahir' => 'dateBirth',
      'tglMulaiAktif' => 'dateStartActive',
      'tglAkhirBerlaku' => 'dateEndEffective',
      'tglDaftar' => 'tglDaftar',
      'providerPelayanan' => 'provider',
      'kdProviderPst' => 'kdProviderPeserta',
      'kdProvider' => 'providerId',
      'nmProvider' => 'providerName',
      'kdProviderGigi' => 'providerDentistId',
      'ppk' => 'provider',
      'kdPPK' => 'providerId',
      'nmPPK' => 'providerName',
      'alamat' => 'address',
      'telp' => 'phoneNumber', 
      'kc' => 'subDistrict',
      'kdKC' => 'subDistrictId',
      'nmKC' => 'subDistrictName',
      'dati' => 'district',
      'kdProp' => 'provinceId',
      'kdDati' => 'districtId',
      'nmDati' => 'districtName',
      'kdKR' => 'branchOfficeId',
      'nmKR' => 'branchOfficeaName',
      'tglKunjungan' => 'dateVisit',
      'keluhan' => 'keluhan',
      'kunjSakit' => 'visit',
      'sistole' => 'sistole',
      'beratBadan' => 'beratBadan',
      'tinggiBadan' => 'tinggiBadan',
      'lingkarPerut' => 'lingkarPerut',
      'kdTkp' => 'kdTkp',
      'nmTkp' => 'tkpName',
      'poliSakit' => 'polyclinic', // ??
      'nokaPst' => 'noKartu', // apalagi ni
      'kdDiag' => 'kdDiag',
      'nmDiag' => 'diagnosisName',
      'catatan' => 'catatan',
      'dokter' => 'doctor',
      'kdDokter' => 'kdDokter',
      'nmDokter' => 'doctorName',
      'nmTacc' => 'taccName',
      'alasanTacc' => 'taccReason',
      'infoDenda' => 'fineInformation',
      'catatanRujuk' => 'referralNotes',
      'tglEstRujuk' => 'tglEstRujuk',
      'tglAkhirRujuk' => 'dateEndReferral',
      'ppkRujuk' => 'referralProvider',
      'jnsKelas' => 'classType',
      'jnsPeserta' => 'patientType',
      'golDarah' => 'bloodType',
      'noHP' => 'phoneNumber',
      'noKTP' => 'idCard',
      'aktif' => 'active',
      'ketAktif' => 'activeDescription',
      'asuransi' => 'insurance',
      'kdAsuransi' => 'insuranceId',
      'nmAsuransi' => 'insuranceName',
      'noAsuransi' => 'cardNumberInsurance',
      'tunggakan' => 'arrears',
      'noUrut' => 'noUrut',
      'noRujukan' => 'referralId',
      'kdSpesialis' => 'kdSpesialis',
      'nmSpesialis' => 'specialistName',
      'kdSubSpesialis' => 'kdSubSpesialis',
      'nmSubSpesialis' => 'subSpecialistName',
      'kdPoliRujuk' => 'referralPolyclinicId',
      'kdSarana' => 'kdSarana',
      'nmSarana' => 'facilitiesName',
      'kdKhusus' => 'kdKhusus',
      'nmKhusus' => 'specificName',
      'kdppk' => 'kdppk',
      'nmppk' => 'medicalFacilityName',
      'alamatPpk' => 'medicalFacilityAddr',
      'telpPpk' => 'medicalFacilityPhone',
      'kelas' => 'class',
      'nmkc' => 'subDistric',
      'jadwal' => 'schedule',
      'jmlRujuk' => 'numReferrals',
      'kapasitas' => 'capacity',
      'persentase' => 'percentage',
      'kdObatSK' => 'kdObatSK',
      'kdObat' => 'kdObat',
      'nmObat' => 'medicineName',
      'sedia' => 'ready',
      'kdRacikan' => 'kdRacikan',
      'obat' => 'medicine',
      'jmlObat' => 'jmlObat',
      'kekuatan' => 'strength',
      'jmlHari' => 'daysNumber',
      'jmlPermintaan' => 'jmlPermintaan',
      'jmlObatRacikan' => 'amountracikan',
      'noKunjungan' => 'noKunjungan',
      'diagnosa' => 'diagnosa',
      'nonSpesialis' => 'nonSpecialist',
      'kesadaran' => 'awareness',
      'kdSadar' => 'kdSadar',
      'nmSadar' => 'awarenessName',
      'rujukBalik' => 'rujukBalik',
      'providerAsalRujuk' => 'referralOriginProvider',
      'providerRujukLanjut' => 'referralProvider',
      'pemFisikLain' => 'anotherExamination',
      'statusPulang' => 'returnStatus',
      'kdStatusPulang' => 'kdStatusPulang',
      'nmStatusPulang' => 'returnStatusName',
      'PoliRujukInternal' => 'PolyReferralInternal',
      'PoliRujukLanjut' => 'PolyReferral',
      'tglPulang' => 'tglPulang',
      'kdProgram' => 'programID',
      'nmProgram' => 'programName',
      'poli' => 'polyclinic',
      'kdPoli' => 'kdPoli',
      'nmPoli' => 'polyclinicName',
      'kode' => 'id',
      'kdProviderPeserta' => 'kdProviderPeserta',
      'kdTindakanSk' => 'kdTindakanSK',
      'kdTindakan' => 'kdTindakan',
      'biaya' => 'biaya',
      'keterangan' => 'keterangan',
      'hasil' => 'hasil',
      
      
    ];
    return strtr($json, $terjemah);
  }
  
  public function index ($method = FALSE)
  {
    if ($method == FALSE) {
      $this->tampil([]);
      exit;
    }

    $hasil       = false;
    $required    = new Required;
    $cekVariabel = $required->cekVariabel($method, $this->data);
    if (isset($cekVariabel['error'][0])) {
      $this->tampil($cekVariabel);
      exit;
    }

    $header      = $this->header();

    if (count($cekVariabel) == 0) {
      $data  = $this->$method();
      $this->debug("Sent API", $data);
      $kirim = $this->CallAPI($data['method'], $data['url'], $header, $data['data'], FALSE);
      $this->debug("CallAPI: ", $kirim);

      $json = json_decode($kirim, true);
      if (trim(strtolower($kirim)) == '{"response":null,"metadata":{"message":null,"code":0}}') {
        $json = null;
        $hasil['code'] = 412;
        $hasil['error'][] = 'Precondition failed, please check the data type you submitted';
      } elseif (trim(strtolower($kirim)) == 'authentication failed') {
        $json = null;
        $hasil['error'][] = 'Forbidden, authentication failed!';
      }
      $this->debug("JSON: ", $json);
      
      if($json == null) {
        $this->debug("JSON is NULL");
        $hasil[] = $kirim;
      } else {
        // variabel dari bpjs gak standar, metaData dan metadata,
        // sedangkan variabel itu case-sensitive
        $metaData = "metaData";
        if (!array_key_exists($metaData, $json)) {
          $metaData = "metadata";
        }

        $code = "code";
        if (!array_key_exists($code, $json[$metaData])) {
          $code = "Code";
        }

        if (!array_key_exists("response", $json)) {
          $this->debug("No Response");

          $hasil['code'] = $json[$metaData][$code];
          $hasil['error'] = $json[$metaData]['message'];
        } else {
          $key = $this->cekUser['cons_id'] . $this->cekUser['secretKey'] . $this->tStamp;
          $this->debug("JSON Response: ", $json['response']);

          if (!is_array($json['response']) AND strpos($json['response'], ' ') === FALSE) {
            $this->debug("JSON Response is Encrypted", $kirim);
            $encrypt_method = 'AES-256-CBC';
            
            // hash
            $key_hash = hex2bin(hash('sha256', $key));
      
            // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
            $iv     = substr(hex2bin(hash('sha256', $key)), 0, 16);      
            $output = openssl_decrypt(base64_decode($json['response']), $encrypt_method, $key_hash, OPENSSL_RAW_DATA, $iv);
      
            $hasil[] = $this->terjemahVariabel(\LZCompressor\LZString::decompressFromEncodedURIComponent($output));
            $this->debug("Hasil Decrypt: ", $hasil);

          } elseif (!is_array($json['response'])) {
            $this->debug("JSON Response is String and Not Encrypted");
            $json[$metaData]['message'] = $json['response'];
          } else {
            $this->debug("JSON Response is an Array with code: " . $json[$metaData][$code]);
            if (substr($json[$metaData][$code],0,1) == '4') { // code 4xx
              if (isset($json['response']['field']) AND 
                  isset($json['response']['message'])) {
                $hasil['error'][] = $json[$metaData]['message'];
                $hasil['error'][] = $json['response']['field'] .' '. $json['response']['message'];
              } elseif (isset($json['response'][0]['field']) AND 
                        isset($json['response'][0]['message'])) {
                $hasil['error'][] = $json[$metaData]['message'];
                foreach($json['response'] as $jr) {
                  $hasil['error'][] = $jr['field'] .' '. $jr['message'];
                }
              } elseif (isset($json['response'][0]['message'])) {
                if (isset($json[$metaData]['message']))
                  $hasil['error'][] = $json[$metaData]['message'];
                $hasil['error'][] = $json['response'][0]['message'];
              }
            } else { // code 2xx ok/created
              if (isset($json['response']['field']) AND 
                  isset($json['response']['message'])) {
                $hasil[$json['response']['field']] =  $json['response']['message'];
              } elseif (isset($json['response'][0]['field']) AND 
                        isset($json['response'][0]['message'])) {
                foreach($json['response'] as $jr) {
                  $hasil[$jr['field']] =  $jr['message'];
                }
              }

            }
          }
          
          $hasil['code'] = $json[$metaData][$code];
          if (isset($hasil[0]) AND $this->isJson($hasil[0])) {
            $h = json_decode($hasil[0], TRUE);
            $this->debug("hasil[0] is json: ", $hasil[0]);
            $this->debug("decode: ", $h, $h['field'][0]);

            //briding bpjs akan mengeluarkan error ketika tipe data yang salah dimasukkan
            //field = null, message = null => buat error precondition failed
            //bila mengeluarkan variabel maka formatnya:
            //field = "namafield"
            //message = "isifield"
            //diubah menjadi: namafield = isifield
            if ($hasil[0] == '{"field":null,"message":null}') {
              $hasil['error'][] = 'Precondition failed, please check the data type you submitted';
            } elseif ($hasil[0] == '{"field":"","message":""}') {
              $hasil['error'][] = $json[$metaData]['message'];
            } elseif (isset($h[0]['field'])) { // kalo multi array,
              $this->debug("h[field] is multiarray");
              foreach ($h as $m) {
                $hasil[$m['field']] = $m['message'];
              }
            } elseif (isset($h['field'])) { //kalo satu
              $this->debug("h[field] is array");
              $hasil[$h['field']] = $h['message'];
            } else {
              $this->debug("hasil = h ");
              $hasil = $h + $hasil;
            }
          }
          
          if (!isset($hasil['error'])) {
            $this->debug("No Error ");
            $hasil['message'] = $json[$metaData]['message'];
          }
        }     
      }
      if (isset($hasil[0]))
        unset($hasil[0]);
    }
    $this->debug("End Result: ", $hasil);
    if (!$hasil) {
      $hasil['code'] = 503;
      $hasil['message'][] = 'The BPJS Kesehatan server returned an empty, unknown, or unexpected response .';
    }
    //print_r($hasil);
    $this->tampil($hasil);
  }

  //function
  private function value($data, $string = FALSE) {
    $nasil = '';
    if (isset($data)) {
      if ((strtolower($data) == "null" ) OR ($data=="")) {
        $hasil = NULL;
      } elseif (strtolower($data) == "true") {
        $hasil = TRUE;
      } elseif (strtolower($data) == "false") {
        $hasil = FALSE;
      } elseif (strlen($data) == 1 AND $data == '0') {
        $hasil = 0;
      } elseif (is_numeric($data) AND substr($data, 0, 1) != '0') {
        if ($string)
          $hasil = (string) $data;
        else
          $hasil = (int) $data;
      } else {
        $hasil = $data;
      }
    } else {
      $hasil = null;
    }
    return $hasil;
  }
  
	private function diagnosis() {
	  $r['method'] = 'GET';
	  $r['url']    = $this->url .
	                 'diagnosa/' .
	                 $this->data['diagnosa'] . '/' .
	                 $this->data['start'] . '/' .
	                 $this->data['limit'];
	  $r['data']   = null;
    return $r;
	}

	private function doctor() {
	  $r['method'] = 'GET';
	  $r['url']    = $this->url .
	                 'dokter/' .
	                 $this->data['start'] . '/' .
	                 $this->data['limit'];
	  $r['data']   = null;
    return $r;
	}

	private function polyclinic() {
	  $r['method'] = 'GET';
	  $r['url']    = $this->url .
	                 'poli/fktp/' .
	                 $this->data['start'] . '/' .
	                 $this->data['limit'];
	  $r['data']   = null;
    return $r;
	}

	private function awareness() {
	  $r['method'] = 'GET';
	  $r['url']    = $this->url .
	                 'kesadaran/';
	  $r['data']   = null;
    return $r;
	}

	private function patient() {
	  $r['method'] = 'GET';
	  $r['url']    = $this->url .
	                 'peserta/' .
	                 $this->data['jenisKartu'] . '/' .
	                 $this->data['noKartu'];
	  $r['data']   = null;
    return $r;
	}

	private function specialist() {
	  $r['method'] = 'GET';
	  $r['url']    = $this->url . 'spesialis';
	  $r['data']   = null;
    return $r;
	}

	private function subSpecialist() {
	  $r['method'] = 'GET';
	  $r['url']    = $this->url .
	                'spesialis/' .
	                $this->data['kdSpesialis'] . '/' .
	                'subspesialis';
	  $r['data']   = null;
    return $r;
	}

	private function facilitiesSpecialist() {
	  $r['method'] = 'GET';
	  $r['url']    = $this->url . 'spesialis/sarana';
	  $r['data']   = null;
    return $r;
	}

	private function specificSpecialist() {
	  $r['method'] = 'GET';
	  $r['url']    = $this->url . 'spesialis/khusus';
	  $r['data']   = null;
    return $r;
	}

	private function referralSubSpecialist() {
	  $r['method'] = 'GET';
	  $r['url']    = $this->url .
	                'spesialis/rujuk/subspesialis/' .
	                $this->data['kdSubSpesialis'] .
	                '/sarana/' .
	                $this->data['kdSarana'] .
	                '/tglEstRujuk/' .
	                $this->data['tglEstRujuk'];
	  $r['data']   = null;
    return $r;
	}
	
	private function referralSpecificSpecialist() {
	  $r['method'] = 'GET';
	  $r['url']    = $this->url .
	                'spesialis/rujuk/khusus/' .
	                $this->data['kdKhusus'] . '/' .
	                'noKartu/' .
	                $this->data['noKartu'] . '/' .
	                'tglEstRujuk/' .
	                $this->data['tglEstRujuk'];
	  $r['data']   = null;
    return $r;
	}

	private function referralSpecificSubSpecialist() {
	  $r['method'] = 'GET';
	  $r['url']    = $this->url .
	                'spesialis/rujuk/khusus/' .
	                $this->data['kdKhusus'] . '/' .
	                'subSpesialis/' .
	                $this->data['kdSubSpesialis'] . '/' .
	                'noKartu/' .
	                $this->data['noKartu'] . '/' .
	                'tglEstRujuk/' .
	                $this->data['tglEstRujuk'];
	  $r['data']   = null;
    return $r;
	}

  private function addRegistration() {
	  $r['method'] = 'POST';
	  $r['url']    = $this->url . 'pendaftaran';
	  $kirimData   = json_encode([
	    'kdProviderPeserta' => $this->value($this->data['kdProviderPeserta'], TRUE),
	    'tglDaftar' => $this->value($this->data['tglDaftar']),
	    'noKartu' => $this->value($this->data['noKartu']),
	    'kdPoli' => $this->value($this->data['kdPoli']),
	    'keluhan' => NULL, //$this->value($this->data['keluhan']),
	    'kunjSakit' => TRUE, //$this->value($this->data['visit']),
	    'sistole' => 0, //$this->value($this->data['sistole']),
	    'diastole' => 0, //$this->value($this->data['diastole']),
	    'beratBadan' => 0, //$this->value($this->data['beratBadan']),
	    'tinggiBadan' => 0, //$this->value($this->data['tinggiBadan']),
	    'respRate' => 0, //$this->value($this->data['respRate']),
	    'lingkarPerut' => 0, //$this->value($this->data['lingkarPerut']),
	    'heartRate' => 0, //$this->value($this->data['heartRate']),
	    'rujukBalik' => 0, //$this->value($this->data['rujukBalik']),
	    'kdTkp' => $this->value($this->data['kdTkp'], TRUE) 
	  ]);
	  $r['data']   = $kirimData;
    return $r;
    
  }

  private function delRegistration() {
    $r['method'] = 'DELETE';
    $r['url']    = $this->url . 
                   'pendaftaran/peserta/' . 
                   $this->data['noKartu'] . 
                   '/tglDaftar/' . 
                   $this->data['tglDaftar'] . 
                   '/noUrut/' . 
                   $this->data['noUrut'] . 
                   '/kdPoli/' . 
                   $this->data['kdPoli'];
    $r['data']   = NULL;
    return $r;
  }

  private function getRegistrationNumber() {
	  $r['method'] = 'GET';
	  $r['url']    = $this->url . 
	                 'pendaftaran/noUrut/' .
	                 $this->data['noUrut'] . '/' .
                   'tglDaftar/' .
	                 $this->data['tglDaftar'];
	  $r['data']   = null;
    return $r;
	}
	
  private function getRegistrationProvider() {
	  $r['method'] = 'GET';
	  $r['url']    = $this->url . 
	                 'pendaftaran/tglDaftar/' .
	                 $this->data['tglDaftar'] . '/' .
	                 $this->data['start'] . '/' .
	                 $this->data['limit'];
	  $r['data']   = null;
    return $r;
	}
	
  private function visit() {
	  $r['method'] = 'POST';
	  $r['url']    = $this->url . 'kunjungan';
	  $kirimData   = [
	    'noKunjungan' => NULL,
	    'noKartu' => $this->value($this->data['noKartu']),
	    'tglDaftar' => $this->value($this->data['tglDaftar']),
	    'kdPoli' => $this->value($this->data['kdPoli']),
	    'keluhan' => $this->value($this->data['keluhan']),
	    'kdSadar' => $this->value($this->data['kdSadar']),
	    'sistole' => $this->value($this->data['sistole']),
	    'diastole' => $this->value($this->data['diastole']),
	    'beratBadan' => $this->value($this->data['beratBadan']),
	    'tinggiBadan' => $this->value($this->data['tinggiBadan']),
	    'respRate' => $this->value($this->data['respRate']),
	    'heartRate' => $this->value($this->data['heartRate']),
	    'lingkarPerut' => $this->value($this->data['lingkarPerut']),
	    'kdStatusPulang' => $this->value($this->data['kdStatusPulang'], TRUE),
	    'tglPulang' => $this->value($this->data['tglPulang']),
	    'kdDokter' => $this->value($this->data['kdDokter'], TRUE),
	    'kdDiag1' => $this->value($this->data['kdDiag1']),
	    'kdDiag2' => $this->value($this->data['kdDiag2']),
	    'kdDiag3' => $this->value($this->data['kdDiag3']),
	    'kdPoliRujukInternal' => $this->value($this->data['kdPoliRujukinternal']),
	    'rujukLanjut' => [
  	    'tglEstRujuk' => $this->value($this->data['tglEstRujuk']),
	      'kdppk' => $this->value($this->data['kdppk']),
        'subSpesialis' => null,
        'khusus' => null,
	    ],
	    'kdTacc' => $this->value($this->data['kdTacc'], TRUE),
	    'alasanTacc' => $this->value($this->data['alasanTacc']) 
	  ];
    
    if ($kdKhusus) {
      $kirimData['khusus'] = [
          'kdKhusus' => $this->value($this->data['kdKhusus']),
	        'kdSubSpesialis' => $this->value($this->data['kdSubSpesialis'], TRUE),
	        'catatan' => $this->value($this->data['catatan']) 
	      ];
    } else {
      $kirimData['subSpesialis'] = [
	        'kdSubSpesialis1' => $this->value($this->data['kdSubSpesialis'], TRUE),
	        'kdSarana' => $this->value($this->data['kdSarana']) 
	    ];
    }
	  $r['data']   = json_encode($kirimData);
    return $r;
    
  }

  private function editVisit() {
	  $r['method'] = 'PUT';
	  $r['url']    = $this->url . 'kunjungan';
	  $kirimData   = [
	    'noKunjungan' => $this->value($this->data['noKunjungan']),
	    'noKartu' => $this->value($this->data['noKartu']),
	    'tglDaftar' => $this->value($this->data['tglDaftar']),
	    'kdPoli' => $this->value($this->data['kdPoli']),
	    'keluhan' => $this->value($this->data['keluhan']),
	    'kdSadar' => $this->value($this->data['kdSadar']),
	    'sistole' => $this->value($this->data['sistole']),
	    'diastole' => $this->value($this->data['diastole']),
	    'beratBadan' => $this->value($this->data['beratBadan']),
	    'tinggiBadan' => $this->value($this->data['tinggiBadan']),
	    'respRate' => $this->value($this->data['respRate']),
	    'heartRate' => $this->value($this->data['heartRate']),
	    'lingkarPerut' => $this->value($this->data['lingkarPerut']),
	    'kdStatusPulang' => $this->value($this->data['kdStatusPulang'], TRUE),
	    'tglPulang' => $this->value($this->data['tglPulang']),
	    'kdDokter' => $this->value($this->data['kdDokter'], TRUE),
	    'kdDiag1' => $this->value($this->data['kdDiag1']),
	    'kdDiag2' => $this->value($this->data['kdDiag2']),
	    'kdDiag3' => $this->value($this->data['kdDiag3']),
	    'kdPoliRujukInternal' => $this->value($this->data['kdPoliRujukInternal']),
	    'rujukLanjut' => [
  	    'tglEstRujuk' => $this->value($this->data['tglEstRujuk']),
	      'kdppk' => $this->value($this->data['kdppk']),
        'subSpesialis' => null,
        'khusus' => null
	    ],
	    'kdTacc' => $this->value($this->data['kdTacc'], TRUE),
	    'alasanTacc' => $this->value($this->data['alasanTacc']) 
	  ];
    
    if ($kdKhusus) {
      $kirimData['khusus'] = [
          'kdKhusus' => $this->value($this->data['kdKhusus']),
	        'kdSubSpesialis' => $this->value($this->data['kdSubSpesialis'], TRUE),
	        'catatan' => $this->value($this->data['catatan']) 
	      ];
    } else {
      $kirimData['subSpesialis'] = [
	        'kdSubSpesialis1' => $this->value($this->data['kdSubSpesialis'], TRUE),
	        'kdSarana' => $this->value($this->data['kdSarana']) 
	    ];
    }
	  $r['data']   = json_encode($kirimData);
    
    return $r;
  }

  private function delVisit() {
	  $r['method'] = 'DELETE';
	  $r['url']    = $this->url . 
                   'kunjungan/' . 
                   $this->data['noKunjungan'];
    $r['data']   = NULL;
    return $r;
  }

  private function visitById() {
	  $r['method'] = 'GET';
	  $r['url']    = $this->url . 
                   'kunjungan/rujukan/' . 
                   $this->data['noKunjungan'];
    $r['data']   = NULL;
    return $r;
  }

  private function visitByCardNumber() {
	  $r['method'] = 'GET';
	  $r['url']    = $this->url . 
                   'kunjungan/peserta/' . 
                   $this->data['noKartu'];
    $r['data']   = NULL;
    return $r;
  }

  private function visitAddMedicine() {
	  $r['method'] = 'POST';
	  $r['url']    = $this->url . 'obat/kunjungan';
	  $kirimData   = json_encode([
      "kdObatSK"=> 0,
      "noKunjungan"=> $this->value($this->data['noKunjungan']),
      "racikan"=> $this->value($this->data['racikan']),
      "kdRacikan"=> $this->value($this->data['kdRacikan']),
      "obatDPHO"=>  $this->value($this->data['obatDPHO']),
      "kdObat"=>  $this->value($this->data['kdObat'], TRUE),
      "signa1"=>  $this->value($this->data['signa1']),
      "signa2"=>  $this->value($this->data['signa2']),
      "jmlObat"=>  $this->value($this->data['jmlObat']),
      "jmlPermintaan"=>  $this->value($this->data['jmlPermintaan']),
      "nmObatNonDPHO"=>  $this->value($this->data['nmObatNonDPHO'])
    ]);
	  $r['data']   = $kirimData;
    return $r;
  }

  private function visitDelMedicine() {
	  $r['method'] = 'DELETE';
	  $r['url']    = $this->url . 
                   'obat/' . 
                   $this->data['kdObatSK'] . '/' .
                   'kunjungan/' . 
                   $this->data['noKunjungan'];
    $r['data']   = NULL;
    return $r;
  }
 
  private function medicineSearch() {
	  $r['method'] = 'GET';
	  $r['url']    = $this->url . 
                   'obat/dpho/' . 
                   $this->data['cari'] . '/' .
                   $this->data['start'] . '/' .
                   $this->data['limit'];
    $r['data']   = NULL;
    return $r;
  }

  private function medicineByvisitId() {
	  $r['method'] = 'GET';
	  $r['url']    = $this->url . 
                   'obat/kunjungan/' . 
                   $this->data['noKunjungan'];
    $r['data']   = NULL;
    return $r;
  }

  private function provider() {
	  $r['method'] = 'GET';
	  $r['url']    = $this->url . 
                   'provider/' . 
                   $this->data['start'] . '/' .
                   $this->data['limit'];
    $r['data']   = NULL;
    return $r;
  }

  private function action() {
	  $r['method'] = 'GET';
	  $r['url']    = $this->url . 
                   'tindakan/kdTkp/' . 
                   $this->data['kdTkp'] . '/' .
                   $this->data['start'] . '/' .
                   $this->data['limit'];
    $r['data']   = NULL;
    return $r;
  }

  private function actionVisit() {
	  $r['method'] = 'GET';
	  $r['url']    = $this->url . 
                   'tindakan/kunjungan/' . 
                   $this->data['noKunjungan'];
    $r['data']   = NULL;
    return $r;
  }

  private function addAction() {
	  $r['method'] = 'POST';
	  $r['url']    = $this->url . 'tindakan';
	  $kirimData   = json_encode([
      "kdTindakanSK"=> 0,
      "noKunjungan"=> $this->value($this->data['noKunjungan']),
      "kdTindakan"=> $this->value($this->data['kdTindakan'], TRUE),
      "biaya"=> $this->value($this->data['biaya']),
      "keterangan"=>  $this->value($this->data['keterangan']),
      "hasil"=>  $this->value($this->data['hasil'])
    ]);
	  $r['data']   = $kirimData;
    return $r;
  }

  private function editAction() {
	  $r['method'] = 'PUT';
	  $r['url']    = $this->url . 'tindakan';
	  $kirimData   = json_encode([
      "kdTindakanSK"=> $this->value($this->data['kdTindakanSK']),
      "noKunjungan"=> $this->value($this->data['noKunjungan']),
      "kdTindakan"=> $this->value($this->data['kdTindakan'], TRUE),
      "biaya"=> $this->value($this->data['biaya']),
      "keterangan"=>  $this->value($this->data['keterangan']),
      "hasil"=>  $this->value($this->data['hasil'])
    ]);
	  $r['data']   = $kirimData;
    return $r;
  }

  private function delAction() {
	  $r['method'] = 'DELETE';
	  $r['url']    = $this->url . 
                   'tindakan/' . 
                   $this->data['kdTindakanSK'];
                   '/kunjungan/' . 
                   $this->data['noKunjungan'];
    $r['data']   = NULL;
    return $r;
  }


}
/* EOC */