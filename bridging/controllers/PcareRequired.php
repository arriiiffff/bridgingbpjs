<?php
namespace Pcare;
defined('BASEPATH') OR exit('No direct script access allowed');

class Required {
  
  public function cekVariabel ($method, $data) {
    $hasil = [];
    
    if (method_exists($this, $method)) {
      foreach ($this->$method() as $key => $val) {
        if (!isset($data[$key])) {
          $hasil['code'] = 400;
          $hasil['error'][] = $key . ' is required (' . $val . ')';
        }
      }
    } else {
      $hasil['error'][] = 'Method not found';
      $hasil['code']    = 405;
    }

    return $hasil;
  }
  
  private function token() {
    return [
      'user'   => '(any)',
    ];
  }

  private function diagnosis() {
    return [
      'token'   => '(any)',
      'diagnosa' => 'string (icd10 or diagnosis name)',
      'start' => 'integer (from 0)',
      'limit' => 'integer'
    ];
  }

  private function doctor() {
    return [
      'token'  => '(any)',
      'start' => 'integer (from 0)',
      'limit' => 'integer'
    ];
  }

  private function polyclinic() {
    return [
      'token'  => '(any)',
      'start' => 'integer (from 0)',
      'limit' => 'integer'
    ];
  }

  private function awareness() {
    return ['token' => '(any)'];
  }
  
  private function patient() {
    return [
      'token' => '(any)',
      'jenisKartu' => 'nik: ID Card (16 digits), noka: BPJS Card Number (13 digits)',
      'noKartu' => 'number'
    ];
  }
  
  private function visit() {
    return [
      'token' => '(any)',
      //'noKunjungan' => 'null', // 'noKunjungan': null,
      'noKartu' => 'number', // 'noKartu': '0000043678034',
      'tglDaftar' => 'dd-mm-yyyy', //  'tglDaftar': '13-08-2018',
      'kdPoli' => 'number (default: null)', //  'kdPoli': null,
      'keluhan' => 'text', //  'keluhan': 'keluhan',
      'kdSadar' => 'number', // 'kdSadar': '01',
      'sistole' => 'integer', // 'sistole': 0,
      'diastole' => 'integer', //  'diastole': 0,
      'beratBadan' => 'float', //  'beratBadan': 0,
      'tinggiBadan' => 'int', //  'tinggiBadan': 0,
      'respRate' => 'integer (5 to 70)', //  'respRate': 0,
      'heartRate' => 'integer (30 to 160)', //  'heartRate': 0,
      'lingkarPerut' => 'float', //  'lingkarPerut': 36,
      'kdStatusPulang' => 'number, (3: outpatient, 4: referral, 5: referral internal)', //  'kdStatusPulang': '4',
      'tglPulang' => 'dd-mm-yyyy', //  'tglPulang': '19-05-2016',
      'kdDokter' => 'number', //  'kdDokter': '73229',
      'kdDiag1' => 'ICD-10 code', //  'kdDiag1': 'A01.0',
      'kdDiag2' => 'ICD-10 code (default: null)', //  'kdDiag2': null,
      'kdDiag3' => 'ICD-10 code (default: null)', //  'kdDiag3': null,
      'kdPoliRujukInternal' => 'number (default: null)', //  'kdPoliRujukInternal': null,
      //  'rujukLanjut': {
      'tglEstRujuk' => 'dd-mm-yyyy', //    'tglEstRujuk': '02-10-2018',
      'kdppk' => 'string', //    'kdppk': '0116R028',
      //'subSpesialis' => '(default: null)', //    'subSpesialis': null,
      //    'khusus': {
      'kdSarana' => 'number (default: null)', //      'kdSarana': null,
      'kdSubSpesialis' => 'number (default: null)', //      'kdSubSpesialis': null,
      'kdKhusus' => 'string (default: null)', //      'kdKhusus': 'HDL',
      'catatan' => 'text', //      'catatan': 'peserta sudah biasa hemodialisa'
       //   }
       // },
       'kdTacc' => '-1: without TACC, 0: none (default), 1: Time, 2: Age, 3: Complication, 4: Comorbidity', // 'kdTacc': 0,
       'alasanTacc' => "-1: null, 1: '< 3 Hari' OR '>= 3 - 7 Hari' OR '>= 7 Hari', 2: '< 1 Bulan' OR '>= 1 Bulan s/d < 12 Bulan' OR '>= 1 Tahun s/d < 5 Tahun' OR '>= 5 Tahun s/d < 12 Tahun' OR '>= 12 Tahun s/d < 55 Tahun' OR '>= 55 Tahun', 3: ICD-10 code - Diagnosis Name, 4: '< 3 Hari' OR '>= 3 - 7 Hari' OR '>= 7 Hari'" // 'alasanTacc': null
    ];
  }

  private function delVisit() {
    return [
      'token' => '(any)',
      'noKunjungan' => 'string', // 'noKunjungan': null,
    ];
  }


  private function editVisit() {
    return [
      'token' => '(any)',
      'noKunjungan' => 'string', // 'noKunjungan': null,
      'noKartu' => 'number', // 'noKartu': '0000043678034',
      'tglDaftar' => 'dd-mm-yyyy', //  'tglDaftar': '13-08-2018',
      'kdPoli' => 'number (default: null)', //  'kdPoli': null,
      'keluhan' => 'text', //  'keluhan': 'keluhan',
      'kdSadar' => 'number', // 'kdSadar': '01',
      'sistole' => 'integer', // 'sistole': 0,
      'diastole' => 'integer', //  'diastole': 0,
      'beratBadan' => 'float', //  'beratBadan': 0,
      'tinggiBadan' => 'float', //  'tinggiBadan': 0,
      'respRate' => 'integer', //  'respRate': 0,
      'heartRate' => 'integer', //  'heartRate': 0,
      'lingkarPerut' => 'float', //  'lingkarPerut': 36,
      'kdStatusPulang' => 'number, (3: outpatient, 4: referral, 5: referral internal)', //  'kdStatusPulang': '4',
      'tglPulang' => 'dd-mm-yyyy', //  'tglPulang': '19-05-2016',
      'kdDokter' => 'number', //  'kdDokter': '73229',
      'kdDiag1' => 'ICD-10 code', //  'kdDiag1': 'A01.0',
      'kdDiag2' => 'ICD-10 code (default: null)', //  'kdDiag2': null,
      'kdDiag3' => 'ICD-10 code (default: null)', //  'kdDiag3': null,
      //'rujukLanjut' => 'number (default: null)', //  'kdPoliRujukInternal': null,
      //  'rujukLanjut': {
      'tglEstRujuk' => 'dd-mm-yyyy', //    'tglEstRujuk': '02-10-2018',
      'kdppk' => 'string', //    'kdppk': '0116R028',
      'subSpesialis' => '(default: null)', //    'subSpesialis': null,
      //    'khusus': {
      'kdKhusus' => 'string (default: null)', //      'kdKhusus': 'HDL',
      'kdSubSpesialis' => 'number (default: null)', //      'kdSubSpesialis': null,
      'catatan' => 'text', //      'catatan': 'peserta sudah biasa hemodialisa'
       //   }
       // },
       'kdTacc' => '-1: without TACC, 1: Time, 2: Age, 3: Complication, 4: Comorbidity', // 'kdTacc': 0,
       'alasanTacc' => "-1: null, 1: '< 3 Hari' OR '>= 3 - 7 Hari' OR '>= 7 Hari', 2: '< 1 Bulan' OR '>= 1 Bulan s/d < 12 Bulan' OR '>= 1 Tahun s/d < 5 Tahun' OR '>= 5 Tahun s/d < 12 Tahun' OR '>= 12 Tahun s/d < 55 Tahun' OR '>= 55 Tahun', 3: ICD-10 code - Diagnosis Name, 4: '< 3 Hari' OR '>= 3 - 7 Hari' OR '>= 7 Hari'" // 'alasanTacc': null
    ];
  }

  private function visitById() {
    return [
      'token' => '(any)',
      'noKunjungan' => 'string', // 'noKunjungan': null,
    ];
  }

  private function visitByCardNumber() {
    return [
      'token' => '(any)',
      'noKartu' => 'number', // 'noKunjungan': null,
    ];
  }

  private function visitAddMedicine() {
    return [
      'token' => '(any)',
      //'kdObatSK' => 'number',
      'noKunjungan' => 'string',
      'racikan' => 'boolean',
      'kdRacikan' => 'number (default: null)',
      'obatDPHO' => 'boolean',
      'kdObat' => 'number',
      'signa1' => 'integer',
      'signa2' => 'integer',
      'jmlObat' => 'integer',
      'jmlPermintaan' => 'integer',
      'nmObatNonDPHO' => 'string'
    ];
  }

  private function visitDelMedicine() {
    return [
      'token' => '(any)',
      'kdObatSK' => 'number',
      'noKunjungan' => 'string'
    ];
  }

  private function addRegistration() {
    return [
      'token'   => '(any)',
      'kdProviderPeserta' => 'alphanumeric', //'kdProviderPeserta': '0114A026',
      'tglDaftar' => 'dd-mm-yyyy', //'tglDaftar': '12-08-2015',
      'noKartu' => 'number', //'noKartu': '0001113569638',
      'kdPoli' => 'number', //'kdPoli': '001',
      /*
      'keluhan' => 'text', //'keluhan': null,
      'visit' => 'boolean', //'kunjSakit': true,
      'sistole' => 'integer', //'sistole': 0,
      'diastole' => 'integer', //'diastole': 0,
      'beratBadan' => 'float', //'beratBadan': 0,
      'tinggiBadan' => 'float', //'tinggiBadan': 0,
      'respRate' => 'integer', //'respRate': 0,
      'lingkarPerut' => 'float', //'lingkarPerut': 0,
      'heartRate' => 'integer', //'heartRate': 0,
      'rujukBalik' => 'boolean', //'rujukBalik': 0,
      */
      'kdTkp' => 'integer (10: outpatien, 20: inpatient, 50: health promotion)' //'kdTkp': '10'
      /*
      tkp': [{ 'kdTkp': '10', 'nmTkp': 'RJTP' }, { 'kdTkp': '20', 'nmTkp': 'RITP' }, { 'kdTkp': '50', 'nmTkp': 'Promotif' }]
      RJTP: Rawat Jalan Tingkat Pertama
      RITP: Rawat Inap Tingkat Pertama
      Promotif: Promosi Kesehatan
      */
    ];
  }
  
  private function delRegistration() {
    return [
      'token'    => '(any)',
      'noKartu' => 'number',
      'kdPoli' => 'number',
      'tglDaftar' => 'dd-mm-yyyy',
      'noUrut'   => 'string'
    ];
  }
  
  private function getRegistrationNumber() {
    return [
      'token'    => '(any)',
      'tglDaftar' => 'dd-mm-yyyy',
      'noUrut'   => 'string'
    ];
  }
  
  private function getRegistrationProvider() {
    return [
      'token'    => '(any)',
      'tglDaftar' => 'dd-mm-yyyy',
      'start' => 'integer (from 0)',
      'limit'   => 'integer'
    ];
  }
  
  private function specialist() {
    return ['token' => '(any)'];
  }

  
  private function subSpecialist() {
    return [
      'token' => '(any)',
      'kdSpesialis' => 'String (3 characters)'
    ];
  }

  private function facilitiesSpecialist() {
    return ['token' => '(any)'];
  }

  private function specificSpecialist() {
    return ['token' => '(any)'];
  }

  private function referralSubSpecialist() {
    return [
      'token' => '(any)',
      'kdSubSpesialis' => 'number',
      'kdSarana' => 'number',
      'tglEstRujuk' => 'dd-mm-yyyy'  
    ];
  }

  private function referralSpecificSpecialist() {
    return [
      'token' => '(any)',
      'kdKhusus' => 'IGD: emergency, HDL: hemodialysis, JIW: psychiatric, KLT: leprosy, PAR: tuberculosis, KEM: chemotherapy, RAT: radiotherapy, HIV: HIV-AIDS',
      'noKartu' => 'number (13 digits)',
      'tglEstRujuk' => 'dd-mm-yyyy'  
    ];
  }

  private function referralSpecificSubSpecialist() {
    return [
      'token' => '(any)',
      'kdKhusus' => 'THA: thalassemia, HEM: hemophilia',
      'kdSubSpesialis' => 'number (3: Internist, 8: Hematology, 26: child, 30: child hematology oncology)',
      'noKartu' => 'number (13 digits)',
      'tglEstRujuk' => 'dd-mm-yyyy'  
    ];
  }

  private function medicineSearch() {
    return [
      'token' => '(any)',
      'cari' => 'string (nameMedicine or medicineId)',
      'start' => 'integer (from 0)',
      'limit' => 'integer'  
    ];
  }

  private function medicineByvisitId() {
    return [
      'token' => '(any)',
      'noKunjungan' => 'string' 
    ];
  }

  private function provider() {
    return [
      'token' => '(any)',
      'start' => 'integer (from 0)',
      'limit' => 'integer'  
    ];
  }

  private function action() {
    return [
      'token' => '(any)',
      'kdTkp' => 'integer (10: outpatien, 20: inpatient, 50: health promotion)',
      'start' => 'integer (from 0)',
      'limit' => 'integer'  
    ];
  }
  
  private function actionVisit() {
    return [
      'token' => '(any)',
      'noKunjungan' => 'string',
    ];
  }
  
  private function addAction() {
    return [
      'token' => '(any)',
      //'kdTindakanSK' => 'string',
      'noKunjungan' => 'string',
      'kdTindakan' => 'number',
      'biaya' => 'integer',
      'keterangan' => 'string',
      'hasil' => 'string',
    ];
  }

  private function editAction() {
    return [
      'token' => '(any)',
      'kdTindakanSK' => 'integer',
      'noKunjungan' => 'string',
      'kdTindakan' => 'number',
      'biaya' => 'integer',
      'keterangan' => 'string',
      'hasil' => 'string',
    ];
  }

  private function delAction() {
    return [
      'token' => '(any)',
      'kdTindakanSK' => 'integer',
      'noKunjungan' => 'string'
    ];
  }

  private function test() {
    return [
      'test' => 'ok'
    ];
  }
}
/* EOC */
