<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
header('Content-Type: text/html');
?>
<html>
<head>
  <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
  <title>Bridging BPJS Kesehatan</title>
</head>
<body>
<style>
body{}
body {
  min-width: 500px;
  width: 100%;
  margin:0;
  padding: 20px;
  background: #fffffa;
  font-weight: 400;
  font-size: 14px;
  font-family:  -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
}
div {
  border: 1px solid #c8ced3;
  border-radius: .25rem;
  padding: 5px;
  margin:5px 0;
}
ul {list-style-type:none;margin: 0 0 0 -30px;}
li {margin-left: -5 px;}
.ijo{color:green;  padding-right: 10px;}
.mirah{color:red;  padding-right: 10px;}
.warning{padding-right: 10px;}
table {
  border-spacing: 1;
  border-collapse: collapse;
  background: white;
  border-radius: 6px;
  overflow: hidden;
  max-width: 800px;
  width: 100%;
  margin: 0 auto;
  position: relative;
}
table * {
  position: relative;
}
table td,
table th {
  padding-left: 8px;
}
table thead tr {
  height: 60px;
  background: #ffed86;
  font-size: 16px;
}
table tbody tr {
  height: 48px;
  border-bottom: 1px solid #e3f1d5;
}
table tbody tr:last-child {
  border: 0;
}
table td,
table th {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: left;
}
table td.l,
table th.l {
  text-align: right;
}
table td.c,
table th.c {
  text-align: center;
}
table td.r,
table th.r {
  text-align: center;
}
table tr:nth-child(even){background-color: #f2f2f2;}
table tr:hover {background-color: #ddd;}

@media screen and (max-width: 35.5em) {
  table {
    display: block;
  }
  table > *,
table tr,
table td,
table th {
    display: block;
  }
  table thead {
    display: none;
  }
  table tbody tr {
    height: auto;
    padding: 8px 0;
  }
  table tbody tr td {
    padding-left: 45%;
    margin-bottom: 12px;
  }
  table tbody tr td:last-child {
    margin-bottom: 0;
  }
  table tbody tr td:before {
    position: absolute;
    font-weight: 700;
    width: 40%;
    left: 10px;
    top: 0;
  }
  table tbody tr td:nth-child(1):before {
    content: "Code";
  }
  table tbody tr td:nth-child(2):before {
    content: "Stock";
  }
  table tbody tr td:nth-child(3):before {
    content: "Cap";
  }
  table tbody tr td:nth-child(4):before {
    content: "Inch";
  }
  table tbody tr td:nth-child(5):before {
    content: "Box Type";
  }
}
select {
  display: inline-block;
  box-sizing: border-box;
  padding: 0.5em 2em 0.5em 0.5em;
  border: 1px solid #eee;
  font: inherit;
  line-height: inherit;
  -webkit-appearance: none;
  -moz-appearance: none;
  -ms-appearance: none;
  appearance: none;
  background-repeat: no-repeat;
  background-image: linear-gradient(45deg, transparent 50%, currentColor 50%), linear-gradient(135deg, currentColor 50%, transparent 50%);
  background-position: right 15px top 1em, right 10px top 1em;
  background-size: 5px 5px, 5px 5px;
}
label {
  width: 75px;
  display: inline-block;
}
.row {
  
}
button {
  padding: 6px;
  background: #3F51B5;
  color: white;
  border: none;
  border-radius: 6px;
}
input {
  padding: 9px;
  border: 1px solid #e7e7e7;
  width: 250px;
}
#loader{background:#3f51b5;color:white;text-align:center;width:90%;position:fixed;top:15%;display:none;box-shadow: 0 0 20px black;border-radius: 10px;border-color: #1d2f91;}
.span{  background: yellow;padding: 5px;margin-bottom: 18px; display:none}
.tombol{display: inline-block;padding: 3px 6px;border: 1px solid black;border-radius: 3px;background: #efefef;color: black;text-decoration: none;}
</style>

<h1>Bridging BPJS Kesehatan - PCare</h1>
<div style="border: none;width: calc(100vw - 60px);padding: 0;margin: 0;">
  <b>Kepersertaan: </b><div id="peserta"><ul></ul></div>
  <b>Pendaftaran:</b> <div id="pendaftaran"><ul></ul></div>
  <b>Kunjungan:</b> <div id="kunjungan"><span class="span"></span><ul></ul></div>
  <b>Obat:</b><div id="obat"><ul></ul></div>
  <b>Rujukan:</b>
  <div id="rujukan" style="display:none">
    <div class="row">
      <label>Spesialisasi:</label>
      <select id="kdSpesialis">
        <option>-- Pilih Spesialis--</option>
      </select>
      <select id="kdSubSpesialis">
        <option>-- Piih Sub Spesialis--</option>
      </select>
      <br><br>
      <label>Sarana:</label>
      <select id="kdSarana">
        <option>-- Pilih Sarana --</option>
      </select>
      <br><br>
      <label>TACC</label>
      <select id="kdTacc">
        <option value="-1">Tanpa TACC</option>
        <optgroup label="Time">
          <option value="1">&lt; 3 Hari</option>
          <option value="1">&gt;= 3 - 7 Hari</option>
          <option value="1">&gt;= 7 Hari</option>
        </optgroup>
        <optgroup label="Age">
          <option value="2">&lt; 1 Bulan</option>
          <option value="2">&gt;= 1 Bulan s/d &lt; 12 Bulan</option>
          <option value="2">&gt;= 1 Tahun s/d &lt; 5 Tahun</option>
          <option value="2">&gt;= 5 Tahun s/d &lt; 12 Tahun</option>
          <option value="2">&gt;= 12 Tahun s/d &lt; 55 Tahun</option>
          <option value="2">&gt;= 55 Tahun</option>
        </optgroup>
        <optgroup label="Complication">
          <option value="3">kdDiagnosa - NamaDiagnosa</option>
        </optgroup>
        <optgroup label="Comorbidity">
          <option value="4">&lt; 3 Hari</option>
          <option value="4">&gt;= 3 - 7 Hari</option>
          <option value="4">&gt;= 7 Hari</option>
        </optgroup>
      </select>
      <input type="text" id="alasanTacc" value="" style="display:none">
    </div>
    Tgl Rencana Berkunjung
    <select id="tgl">
      <?php for($t = 1; $t <= 31; $t++) : ?>
      <option <?php echo ((date('j') == $t) ? 'selected' : ''); ?> value="<?php echo sprintf("%02d", $t); ?>"><?php echo $t; ?></option>
      <?php endfor; ?>
    </select>
    <select id="bln">
      <?php
      $bln = ['01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'];
      foreach ($bln as $v => $t) {
        echo '<option '. ((date('m') == $v) ? 'selected' : '') .' value="'. $v .'">'. $t .'</option>';
      }
      ?>
    </select>
    <select id="thn">
      <option value="<?php echo date("Y"); ?>"><?php echo date("Y"); ?></option>
    </select>
    <button id="cariFaskes">Cari Faskes Rujukan</button><br>
    <div id="faskes">
      <span class="span"></span>
      <table id="listFaskes">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama Faskes</th>
            <th>Kelas</th>
            <th>Jadwal</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr><td colspan="5"></td></tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
<div id="loader">Loading</div>
<center>
  <!--<button onClick="window.location.reload();">Refresh Page</button> &nbsp; &nbsp; &nbsp;-->
  <button style="background-color:#e91e63"onClick="window.close()">Tutup</button>
</center>
<script type="text/javascript">
//0137B1560123Y000004
$(document).ajaxSend(function(){
    $('#loader').fadeIn(250);
});
$(document).ajaxComplete(function(){
    $('#loader').fadeOut(250);
});
$(document).ready(function () {
  var betul = '<span class="ijo">✔</span> ';
  var salah = '<span class="mirah">❌</span> ';
  var ingat = '<span class="warning">🟡</span>';
  var kdTacc = 0;
  var alasanTacc = null;
  var tglEstRujuk=kdppk=kdSarana=facilitesId=subSpesialis=kdKhusus=kdSubSpesialis=noKunjungan='';
  
  $('#kdSpesialis').on('change', function() {
    $("#kdSubSpesialis").empty();
    $("#kdSarana").empty();
    list('subspesialis', '#kdSubSpesialis', $("#kdSpesialis").val());
  });
  
  $('#kdSubSpesialis').on('change', function() {
    kdSubSpesialis = $("#kdSubSpesialis").val();
    if ($('#kdSpesialis').val().charAt(0) != '*')
      list('fasilitas', '#kdSarana', $("#kdSubSpesialis").val());
  });
  
  $('#cariFaskes').on('click', function() {
    $("#faskes span").hide().html('');
    faskes();
    //list('provider', '#kdSarana', $("#kdSubSpesialis").val());
    console.log("cariFaskes:", $("#kdSubSpesialis").val(), $("#kdSarana").val(), ($("#tgl").val() + '-' + $("#bln").val() + '-' + $("#thn").val()));
  });
  
  $('body').on('click', '.pilihFaskes', function() {
    $("#kunjungan span").hide().html('');
    $("#faskes span").hide().html('');
    subSpesialis = true;
    kdppk = $(this).data('id');
    kdTacc = $("#kdTacc").val();
    alasanTacc = $("#alasanTacc").val();
    if (alasanTacc == '' || alasanTacc == '-1' || alasanTacc == 'Tanpa TACC') alasanTacc = null;
    console.log("pilihFaskes:", tglEstRujuk, kdppk, subSpesialis, kdKhusus, kdSubSpesialis);

    visit();
    //list('provider', '#kdSarana', $("#kdSubSpesialis").val());
  });
  
  $('body').on('change', '#kdTacc', function() {
    let kdTacc = $(this).val();
    let alasan = $("option:selected", this).text();
    if (kdTacc == 3) {
      $("#alasanTacc").show();
      $("#alasanTacc").prop('placeholder', 'contoh : A09 - Diarrhoea and gastroenteritis of presumed infectious origin');
      $("#alasanTacc").val('');
    } else {
      $("#alasanTacc").hide();
      $("#alasanTacc").val(alasan);
    }
    console.log(kdTacc, alasan);
  });
  
  function patient () {
    let peserta = '';
    $.ajax({
      method: "POST",
      url: "https://bridging.aceh-servers.net/pcare/patient",
      data: {token: "<?php echo $enc; ?>", jenisKartu: "noka", noKartu: "<?php echo $p['noKartu']; ?>"},
      success: function ( msg) {
        let aktif = false;
        if (msg.metadata.aktif == true) {
          aktif = true;
        }
        peserta += "<li>" + betul + "Nama: " + msg.metadata.nama + " / " + msg.metadata.tglLahir +"</li>";
        peserta += "<li>" + betul + "No BPJS: " + msg.metadata.noKartu +"</li>";
        peserta += "<li>" + (aktif ? betul: salah) + "Status: " + msg.metadata.ketAktif + "</li>";
        //peserta += JSON.stringify(msg);
        $("#peserta ul").append(peserta);
        if (aktif) {
          let kdProviderPeserta = msg.metadata.kdProviderPst.kdProvider;
          let noKartu = msg.metadata.noKartu;
          registration(kdProviderPeserta, noKartu);
        }
        return false;
      },
      dataType: "json",
      error: function(xhr, status, error) {
        $("#peserta").html(xhr.responseText);
      }
    });
  }
  function registration(kdProviderPeserta, noKartu) {
    let pendaftaran = '';
    $.ajax({
      method: "POST",
      url: "https://bridging.aceh-servers.net/pcare/addRegistration",
      data: {token: "<?php echo $enc; ?>", kdProviderPeserta: kdProviderPeserta, tglDaftar: "<?php echo $p['tglDaftar']; ?>", noKartu: noKartu, kdPoli: "<?php echo $p['kdPoli']; ?>", kdTkp: 10},

      complete: function (xhr, status) {
        msg = JSON.parse(xhr.responseText);
        //console.log(JSON.stringify(msg));
        $("#pendaftaran ul").append('<li>Tanggal Kunjungan: <?php echo $p['tglDaftar']; ?></li>');
        if (msg?.metadata?.noUrut) {
          pendaftaran += '<li>' + betul + 'Nomor Urut: ' + msg.metadata.noUrut + "</li>";
          //pendaftaran += JSON.stringify(msg);
          $("#pendaftaran ul").append(pendaftaran);
          visit();
        } else if (msg.message[0] == 'Peserta sudah di-entri di poli yang sama pada hari yang sama') {
          pendaftaran += '<li>' + ingat + msg.message[0] +"</li>";
          $("#pendaftaran ul").append(pendaftaran);
          //ambil nomor kunjungan
          $.post( "https://bridging.aceh-servers.net/transaction/searchVisit",  
            { token: "<?php echo $enc; ?>", noKartu: "<?php echo $p['noKartu']; ?>", tglDaftar: "<?php echo $p['tglDaftar']; ?>"} )
          .done(function( json ) {
            if (json.noKunjungan !== undefined);
              noKunjungan = json.noKunjungan;
            visit();
          });
        } else if (msg.code == 401) {
          pendaftaran += '<li>' + salah + "Maaf, bridging ke Server BPJS Kesehatan sedang ada gangguan</li>";
          $("#pendaftaran ul").append(pendaftaran);
        } else if (msg.code) {
          pendaftaran += '<li>' + salah + msg.message + "</li>";
          pendaftaran += '<li>' + salah + JSON.stringify(msg.metadata) + "</li>";
          $("#pendaftaran ul").append(pendaftaran);
        }
        return false;
      },
      dataType: "json"
    });
  }
  function visit() {
    if (<?php echo $p['kdStatusPulang']; ?> == 4) {
      if (!subSpesialis) {
        $("#kunjungan span").show().html("Pasien rujukan, Harap isi tujuan rujukan terlebih dahulu");
        $("#rujukan").show('slow');
        list('spesialis', '#kdSpesialis', false);
        return false;
      }
    }
    let kunjungan = '';

    if (<?php echo $p['noKunjungan'] != '' ? 'true' : 'false'; ?>) {
      noKunjungan = "<?php echo $p['noKunjungan'];?>"; 
      urlVisit = 'editVisit';
    } else if (noKunjungan) {
      urlVisit = 'editVisit';
    } else {
      urlVisit = 'visit';
    }

    $.ajax({
      method: "POST",
      url: "https://bridging.aceh-servers.net/pcare/" + urlVisit,
      dataType: "json",
      data: {
        token: "<?php echo $enc; ?>",
        noKunjungan: noKunjungan,
        noKartu: "<?php echo $p['noKartu']; ?>",
        tglDaftar: "<?php echo $p['tglDaftar']; ?>",
        kdPoli: "<?php echo $p['kdPoli']; ?>",
        keluhan: "<?php echo $p['keluhan']; ?>",
        kdSadar: "<?php echo $p['kdSadar']; ?>",
        sistole: "<?php echo $p['sistole']; ?>",
        diastole: "<?php echo $p['diastole']; ?>",
        beratBadan: "<?php echo $p['beratBadan']; ?>",
        tinggiBadan: "<?php echo $p['tinggiBadan']; ?>",
        respRate: "<?php echo $p['respRate']; ?>",
        heartRate: "<?php echo $p['heartRate']; ?>",
        lingkarPerut: "<?php echo $p['lingkarPerut']; ?>",
        kdStatusPulang: "<?php echo $p['kdStatusPulang']; ?>",
        tglPulang: "<?php echo $p['tglPulang']; ?>",
        kdDokter: "<?php echo $p['kdDokter']; ?>",
        kdDiag1: "<?php echo $p['kdDiag1']; ?>",
        kdDiag2: "<?php echo $p['kdDiag2']; ?>",
        kdDiag3: "<?php echo $p['kdDiag3']; ?>",
        kdPoliRujukInternal: "<?php echo $p['kdPoliRujukInternal']; ?>",
        tglEstRujuk: tglEstRujuk,
        kdppk: kdppk,
        kdSarana: kdSarana,
        subSpesialis: subSpesialis,
        kdKhusus: kdKhusus,
        kdSubSpesialis: kdSubSpesialis,
        catatan: 'Peserta rujukan',
        kdTacc: kdTacc,
        alasanTacc: alasanTacc
      },

      complete: function (xhr, status) {
        msg = JSON.parse(xhr.responseText);
        if (status == 'error' || msg.code == 203) {
          for (let i = 0; i < msg.message.length; i++) {
            kunjungan += '<li>' + salah + msg.message[i] + "</li>";
          }
          $("#kunjungan ul").append(kunjungan);
        }
        if (msg.code == 200) {
          for (let i = 0; i < msg.message.length; i++) {
            kunjungan += '<li>' + ingat + 'Nomor Kunjungan: ' + noKunjungan + "</li>";
            kunjungan += '<li>' + ingat + "Update kunjungan peserta</li>";
            kunjungan += '<li>' + betul + msg.message[i] + "</li>";
          }
          if (subSpesialis) {
            kunjungan += ' <a class="tombol" href="<?php echo site_url('transaction/referral/'); ?>' + noKunjungan +'/<?php echo rawurlencode ($enc) ;?>" target="_blank">Print Rujukan</a>';            
          }
          $("#kunjungan ul").append(kunjungan);
          <?php if (isset($p['obatDPHO'][0])) { ?>
          medicine();
          <?php } else { ?>
          $("#obat ul").append('<li>' + ingat + 'Tidak ada obat, transaksi selesai</li>');
          <?php } ?>
        }
        if (msg?.metadata?.noKunjungan) {
          $("#rujukan").hide('slow');
          let printRujukan = '';
          noKunjungan = msg.metadata.noKunjungan;
          if (subSpesialis) 
            printRujukan = ' <a class="tombol" href="<?php echo site_url('transaction/referral/'); ?>' + noKunjungan +'/<?php echo rawurlencode ($enc) ;?>" target="_blank">Print Rujukan</a>';
          
          kunjungan += '<li>' + betul + 'Nomor Kunjungan: ' + noKunjungan + printRujukan + "</li>";
          $("#kunjungan ul").append(kunjungan);
          if (!subSpesialis) {
            <?php if (isset($p['obatDPHO'][0])) { ?>
            medicine();
            <?php } else { ?>
            $("#obat ul").append('<li>' + ingat + 'Tidak ada obat, transaksi selesai</li>');
            <?php } ?>
          }
        }
        return false;
      }
    });
  }
  function referral() {
    let rujukan = '';
    $.ajax({
      method: "POST",
      url: "https://bridging.aceh-servers.net/pcare/" + urlVisit,
      data: {
        token: "<?php echo $enc; ?>",
        noKunjungan: noKunjungan,
        noKartu: "<?php echo $p['noKartu']; ?>",

      },

      complete: function (xhr, status) {
        msg = JSON.parse(xhr.responseText);
        if (status == 'error' || msg.code == 203) {
          for (let i = 0; i < msg.message.length; i++) {
            kunjungan += '<li>' + salah + msg.message[i] + "</li>";
          }
          $("#kunjungan ul").append(kunjungan);
        }
        if (msg.code == 200) {
          for (let i = 0; i < msg.message.length; i++) {
            kunjungan += '<li>' + ingat + "Kunjungan peserta sudah pernah di isi</li>";
            kunjungan += '<li>' + betul + msg.message[i] + "</li>";
          }
          $("#kunjungan ul").append(kunjungan);
          medicine();
        }
        if (msg?.metadata?.noKunjungan) {
          kunjungan += '<li>' + betul + 'Nomor Kunjungan: ' + msg.metadata.noKunjungan + "</li>";
          //kunjungan += JSON.stringify(msg);
          $("#kunjungan ul").append(kunjungan);
          medicine();
        }
        
        return false;
      },
      dataType: "json",
    });
  }

  function list(method, tujuan, var1) {
    let option = _url = _data = '';
    if (method == 'spesialis') {
      _url    = 'specialist';
      _data   = { token: "<?php echo $enc; ?>" };
      option += '<option>--Pilih Spesialisasi</option>\n';
      option += '<optgroup label="KHUSUS">\n';
      option += '  <option value="*IGD">Alih Rawat (IGD)</option>\n';
      option += '  <option value="*HDL">HEMODIALISA</option>\n';
      option += '  <option value="*JIW">JIWA</option>\n';
      option += '  <option value="*KLT">KUSTA</option>\n';
      option += '  <option value="*PAR">TB-MDR</option>\n';
      option += '  <option value="*KEM">SARANA KEMOTERAPI</option>\n';
      option += '  <option value="*RAT">SARANA RADIOTERAPI</option>\n';
      option += '  <option value="*HIV">HIV-ODHA</option>\n';
      option += '  <option value="*THA">THALASEMIA</option>\n';
      option += '  <option value="*HEM">HEMOFILI</option>\n';
      option += '</optgroup>\n';
    } else if (method == 'subspesialis') {
      //KHUSUS
      if (var1.charAt(0) == '*') {
        var1 = var1.substring(1);
        if (var1 == 'THA' || var1 == 'HEM') {
          option += '  <option value="">--Pilih Subspesialisi--</option>\n';
          option += '  <option value="3">PENYAKIT DALAM</option>\n';
          option += '  <option value="8">HEMATOLOGI - ONKOLOGI MEDIK</option>\n';
          option += '  <option value="26">ANAK</option>\n';
          option += '  <option value="30">ANAK HEMATOLOGI ONKOLOGI</option>\n';
        } else {
          option += '  <option value="">-</option>\n';
        }
        $(tujuan).empty();
        $(tujuan).append(option);
        return false;
      }
      
      //UMUM
      _url    = 'subSpecialist';
      _data   = { token: "<?php echo $enc; ?>", kdSpesialis: var1 };
      option += '<option value="">--Pilih Sub Spesialisasi--</option>\n';
    } else if (method == 'fasilitas') {
      _url    = 'facilitiesSpecialist';
      _data   = { token: "<?php echo $enc; ?>", kdSpesialis: var1 };
      option += '<option value="">--Pilih Sarana--</option>\n';
      option += '<option value="1">REKAM MEDIK</option>\n';
    } else if (method == 'provider') {
      _url    = 'provider';
      _data   = { token: "<?php echo $enc; ?>", start: 0, limit: 20 };
      option += '<option value="">--Pilih RS Rujukan--</option>\n';
    }
    $.ajax({
      method: "POST",
      url: "https://bridging.aceh-servers.net/pcare/" + _url,
      data: _data,

      complete: function (xhr, status) {
        if (status != 'success') {
          for (let i = 0; i < msg.message.length; i++) {
            option += '<option>' + salah + msg.message[i] + "</option>";
          }
          $(tujuan).append(kunjungan);
          return false;
        }
        msg = JSON.parse(xhr.responseText);
        if (msg.code == 200) {
          dt = msg.metadata.list;
          if (_url == 'specialist') option += '<optgroup label="SPESIALISASI">\n';
          for (var key in dt) {
            var obj = dt[key];
            i=0
            for (var prop in obj) {
              if (i == 0) option += '<option value="';
              if(obj.hasOwnProperty(prop)){
                option += obj[prop];
                if (i == 0) option += '">';
                i++;
              }
              if (i == 2) option += '</option>\n';
            }
          }
          if (_url == 'specialist') option += '</optgroup>\n';
        }
        if (option) {
          $(tujuan).empty();
          $(tujuan).append(option);
        }
        //return option;
      },
      dataType: "json",
    });
    return false;
  }
  
  function medicine() {
    let obat = '';
    <?php 
    if (isset($p['obatDPHO'][0])) {
      for ($i = 0; $i < count($p['obatDPHO']); $i++) : 
    ?>
    if (<?php echo $p['noKunjungan'] != '' ? 'true' : 'false'; ?>) {
      noKunjungan = "<?php echo $p['noKunjungan'];?>"; 
    }
    setTimeout( function () {
      $.ajax({
        method: "POST",
        url: "https://bridging.aceh-servers.net/pcare/visitAddMedicine",
        data: {
          token: "<?php echo $enc; ?>",
          noKunjungan: noKunjungan,
          racikan: "<?php echo $p['racikan'][$i]; ?>",
          kdRacikan: "<?php echo $p['kdRacikan'][$i]; ?>",
          obatDPHO: "<?php echo $p['obatDPHO'][$i]; ?>",
          kdObat: "<?php echo $p['kdObat'][$i]; ?>",
          signa1: "<?php echo $p['signa1'][$i]; ?>",
          signa2: "<?php echo $p['signa2'][$i]; ?>",
          jmlObat: "<?php echo $p['jmlObat'][$i]; ?>",
          jmlPermintaan: "<?php echo $p['jmlPermintaan'][$i]; ?>",
          nmObatNonDPHO: "<?php echo $p['nmObatNonDPHO'][$i]; ?>",
        },

        complete: function (xhr, status) {
          msg = JSON.parse(xhr.responseText);
          if (status == 'error' || msg.code == 203) {
            for (let i = 0; i < msg.message.length; i++) {
              obat += '<li>' + salah + msg.message[i] + "</li>";
            }
            $("#obat ul").append(obat);
          }
          if (msg.code == 200) {
            for (let i = 0; i < msg.message.length; i++) {
              obat += '<li>' + ingat + "obat <?php echo $p['kdObat'][$i]; ?></li>";
              obat += '<li>' + betul + msg.message[i] + "</li>";
            }
            $("#obat ul").append(obat);
          }
          if (msg.code == 201) {
            for (const isi in msg.metadata) {
              obat += '<li>' + betul + isi + ' = ' + msg.metadata[isi] + '</li>';
              //console.log(`${property}: ${object[property]}`);230146735
            }
            $("#obat ul").append(obat);
          }
          return false;
        },
        dataType: "json",
      });
    }, 1000);
    <?php 
      endfor; 
    } else {
      echo 'return false;';
    }
    ?>
  }

  function faskes() {
    let faskes = '';
    tglEstRujuk = ($("#tgl").val() + '-' + $("#bln").val() + '-' + $("#thn").val());
    kdSubSpesialis = $("#kdSubSpesialis").val();
    kdSarana = $("#kdSarana").val();
    
    if ($('#kdSpesialis').val().charAt(0) == '*') {
      let var1 = $('#kdSpesialis').val().substring(1);
      if (var1 == 'THA' || var1 == 'HEM') {
        url  = "https://bridging.aceh-servers.net/pcare/referralSpecificSubSpecialist";
        data =  {
          token: "<?php echo $enc; ?>",
          kdKhusus: var1,
          kdSubSpesialis: kdSubSpesialis,
          noKartu: "<?php echo $p['noKartu']; ?>",
          tglEstRujuk: tglEstRujuk
        }
      } else {
        url  = "https://bridging.aceh-servers.net/pcare/referralSpecificSpecialist";
        data =  {
          token: "<?php echo $enc; ?>",
          kdKhusus: var1,
          noKartu: "<?php echo $p['noKartu']; ?>",
          tglEstRujuk: tglEstRujuk
        }
      }
    } else {
      url  = "https://bridging.aceh-servers.net/pcare/referralsubSpecialist",
      data = {
        token: "<?php echo $enc; ?>",
        kdSubSpesialis: kdSubSpesialis, 
        kdSarana: kdSarana,
        tglEstRujuk: tglEstRujuk
      };      
    }
    console.log(url, data);
    //kdSubSpesialis = $("#kdSubSpesialis").val();
    $.ajax({
      method: "POST",
      url: url,
      data: data,
      complete: function (xhr, status) {
        $("table#listFaskes tbody").empty();
        console.log(status);
        if (xhr?.responseText) msg = JSON.parse(xhr.responseText);
        if (status == 'error') {
          faskes += salah + msg.message;
          //faskes += salah + "Tidak ditemukan Faskes rujukan berdasarkan Spesialis/Sub Spesialis yang dipilih, silahkan mengganti Spesialis/Sub Spesialis/Sarana lainnya";
          $("#faskes span.span").show().html(faskes);
        } else if (status == 'nocontent') {
          faskes += salah + "Tidak ditemukan Faskes rujukan (204: No Content)";
          $("#faskes span.span").show().html(faskes);
        } else if (msg?.metadata?.count) {
          let f = msg.metadata.list;
          console.log(f);
          for (let i = 0; i < f.length; i++) {

            faskes += '<tr>';
            faskes += '<td style="vertical-align:top">' + (i+1) + '</td>';
            faskes += '<td>' + f[i].nmppk + '<br>' + 
                               f[i].alamatPpk + '<br>' +
                               f[i].telpPpk + '</td>';
            faskes += '<td>' + f[i].kelas + '</td>';
            faskes += '<td>' + (f[i].jadwal?(f[i].jadwal+'<br>'):'') + 
                               'Jumlah/kapasitas/persentase: ' +
                               f[i].jmlRujuk + '/' +
                               f[i].kapasitas + '/' +
                               f[i].persentase + '</td>';
            faskes += '<td><button class="pilihFaskes" data-id="'+ f[i].kdppk +'"> Pilih </button></td>';
            faskes += '</tr>';
          }
          //faskes += JSON.stringify(msg.metadata);
          $("table#listFaskes tbody").append(faskes);
        }
        return false;
      },
      dataType: "json",
      });
  }
  patient();
});
</script>
</body>
</html>
