<!doctype html>
<html lang="en">
<head>
<title>Rujukan - Bridging BPJS</title>

<style type="text/css">
body {
  margin: 0;
  padding: 0;
  font: 8.5pt "Tahoma";
}

* {
  box-sizing: border-box;
  -moz-box-sizing: border-box;
}

.page {
  width: 21cm;
  min-height: 29.7cm;
  padding: 0;
  margin: 1cm;
}

@page {
  size: A4;
  margin: 1cm;
}

@media print {
  .page {
    margin: 0;
    border: initial;
    border-radius: initial;
    width: initial;
    min-height: initial;
    box-shadow: initial;
    background: initial;
    page-break-after: always;
  }
}
.clear {
	clear: both;
}
.left5 {
	width: 5%; 
	float: left;
}
.left10 {
	width: 10%; 
	float: left;
}
.left20 {
	width: 20%; 
	float: left;
}
.left30 {
	width: 30%; 
	float: left;
}
.left40 {
	width: 40%; 
	float: left;
}
.left50 {
	width: 50%; 
	float: left;
}
.left60 {
	width: 60%; 
	float: left;
}
.left70 {
	width: 70%; 
	float: left;
}
.pd1 {
	padding: 0.2cm 0;
}
.pd2 {
	padding: 0.2cm 0;
}
.pd5 {
	padding: 0.5cm 0;
}
.kotak {
	border: 1px #000 solid; 
	padding: 0.1cm;
}
.kotakbox {
	border: 1px #000 solid;
    padding: 0.1cm 0.5cm;
    margin-right: 0.2cm;
}
</style>
<script src="<?php echo site_url('barcode.min.js'); ?>" type="text/javascript"></script>
</head>

<body>
<div class="page">
	<div class="kopsurat">
		<div class="left50"><img style="width:180px" src="/logo-bpjskesehatan.png"></div>
		<div class="left50">
			<div class="left30">Kedeputian Wilayah</div>
			<div class="left70">: <?php echo $metadata['ppk']['kc']['kdKR']['nmKR']; ?></div>
			
			<div class="left30">Kantor Cabang</div>
			<div class="left70">: <?php echo $metadata['ppk']['kc']['nmKC']; ?></div>
		</div>
		<div style="width: 100%; clear: both; padding: 0.3cm 0"><center><strong><span style="font-size:1.2em">Surat Rujukan FKTP</strong></center></div>
	</div>

	<div style="border: 1px #000 solid; padding: 0.2cm;">
		<div style="border: 1px #000 solid; padding: 0.2cm 0 0.2cm 1cm;">
			<div class="left60">
				<div class="left40 pd1">No. Rujukan</div>
				<div class="left60 pd1">: <?php echo $metadata['noRujukan']; ?></div>
				
				<div class="left40 pd1">FKTP</div>
				<div class="left60 pd1">: <?php echo $metadata['ppk']['nmPPK'] . '(' . $metadata['ppk']['kdPPK'] .')'; ?></div>
				
				<div class="left40 pd1">Kabupaten/Kota</div>
				<div class="left60 pd1">: <?php echo $metadata['ppk']['kc']['dati']['nmDati'] . '(' . $metadata['ppk']['kc']['dati']['kdDati'] .')'; ?></div>
			</div>
			<div class="left40 pd5" style="text-align:center">
				<img id="barcode" />
			</div>
			<div class="clear"></div>
		</div>
		
		<div style="padding: 0.2cm 0 0.2cm 1cm;">
			<div class="left50">
				<div class="left40 pd1">Kepada Yth. TS Dokter</div>
				<div class="left60 pd1">: <?php echo $metadata['poli']['nmPoli']; ?></div>
				
				<div class="left40 pd1">Di</div>
				<div class="left60 pd1">: <?php echo $metadata['ppkRujuk']['nmPPK']; ?></div>
			</div>

			<div class="clear pd5">Mohon pemeriksaan dan penangan lebih lanjut pasien:</div>

			<div class="left20 pd1">Nama</div>
			<div class="left30 pd1">: <?php echo $metadata['nmPst']; ?></div>
<?php
$bulan = [
  '01' => ['Jan', 'Januari'],
  '02' => ['Feb', 'Februari'],
  '03' => ['Mar', 'Maret'],
  '04' => ['Apr', 'April'],
  '05' => ['Mei', 'Mei'],
  '06' => ['Jun', 'Juni'],
  '07' => ['Jul', 'Juli'],
  '08' => ['Agt', 'Agustus'],
  '09' => ['Sep', 'September'],
  '10' => ['Okt', 'Oktober'],
  '11' => ['Nov', 'November'],
  '12' => ['Des', 'Desember']
];
$pecah    = explode('-', $metadata['tglLahir']);
$pecah2   = explode('-', $metadata['tglEstRujuk']);
$pecah3   = explode('-', $metadata['tglKunjungan']);
$pecah4   = explode('-', $metadata['tglAkhirRujuk']);
$umur     = '';
$tglLahir = new DateTime($pecah[2].'-'.$pecah[1].'-'.$pecah[0]);
$hariIni  = new DateTime("today");
if ($tglLahir > $hariIni) $umur = '0';
else $umur = $hariIni->diff($tglLahir)->y;
?>
			<div class="left10 pd1">Umur: </div>
			<div class="left5 pd1"><?php echo $umur; ?></div>
				
			<div class="left20 pd1">Tahun :</div>
			<div class="left10 pd1"><?php echo $pecah[0] .'-'. $bulan[$pecah[1]][0] .'-'. $pecah[2]; ?></div>
			<div class="clear"></div>

			<div class="left20 pd1">No. Kartu BPJS</div>
			<div class="left30 pd1">: <?php echo $metadata['nokaPst']; ?></div>
			
			<div class="left10 pd1">Status:</div>
			<div class="left5"><span class="kotak"> <?php echo $metadata['pisa']; ?> </span></div>
				
			<div class="left20 pd1">Utama/Tanggungan</div>
			<div class="left10"><span class="kotak"> <?php echo $metadata['sex']; ?> </span> (L / P) </div>
			<div class="clear"></div>

			<div class="left20 pd1">Diagnosa</div>
			<div class="left30 pd1">: <?php echo $metadata['diag1']['nmDiag']; ?> (<?php echo $metadata['diag1']['kdDiag']; ?>)</div>
				
			<div class="left20 pd1">Catatan :</div>
			<div class="left30 pd1"> </div>
			<div class="clear"></div>

			<div class="left20 pd1" style="min-height: 2cm;">Telah diberikan</div>
			<div class="left70 pd1">: </div>
			<div class="clear"></div>
	
			<div class="left70">		
				<div class="pd5">Atas bantuannya, diucapkan terima kasih</div>
				<div>Tgl. Rencana Berkunjung: <?php echo $pecah2[0] .'-'. $bulan[$pecah2[1]][0] .'-'. $pecah2[2]; ?></div>

				<div class="pd1">Jadwal Praktek: <?php echo $metadata['jadwal']; ?></div>
			
				<div class="pd1">Surat rujukan berlaku 1 (satu) kali kunjungan, berlaku sampai dengan : <?php echo $pecah4[0] .'-'. $bulan[$pecah4[1]][0] .'-'. $pecah4[2]; ?></div>		
			</div>
	
			<div class="left30" style="text-align:center">
				<div>Salam sejawat,</div>
				<div style="min-height: 2.3cm;"><?php echo $pecah3[0] .' '. $bulan[$pecah3[1]][1] .' '. $pecah3[2]; ?></div>
				
				<div><?php echo $metadata['dokter']['nmDokter']; ?></div>
			</div>
			<div class="clear"></div>
		
		</div>
		
		<div style="width: 100%; padding: 0.3cm 0; border-top: 1px solid #000;font-size:1.2em"><center><strong><u>SURAT RUJUKAN BALIK</u></strong></center></div>
			
		<div style="padding: 0.2cm 0 0.2cm 1cm;">
	
			<div class="clear">Teman sejawat Yth.</div>
			<div class="clear pd1">Mohon kontrol selanjutnya penderita :</div>

		<div style="padding: 0.5cm 1cm;">
			<div class="left20 pd1">Nama</div>
			<div class="left70 pd1">: <?php echo $metadata['nmPst']; ?></div>
				
			<div class="left20 pd1">Diagnosa</div>
			<div class="left70 pd1">: .........................................................................................................................</div>
				
			<div class="left20 pd1">Terapi</div>
			<div class="left70 pd1">: .........................................................................................................................</div>
		
		<div class="clear"></div>
		</div>

		<div class="clear">Tindak lanjut yang dianjurkan</div>
			
		<div class="left60">
			<div class="pd2"><span class="kotakbox"> </span>Pengobatan dengan obat-obatan :</div>
			<div class="pd2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;............................................................................</div>
			<div class="pd2"><span class="kotakbox"> </span>Kontrol kembali ke RS tanggal : .............................</div>
			<div class="pd2"><span class="kotakbox"> </span>Lain-lain: ..............................................................</div>
		</div>
		<div class="left40">
			<div class="pd2"><span class="kotakbox"> </span>Perlu rawat inap</div>
			<div class="pd2"><span class="kotakbox"> </span>Konsultasi selesai</div>
			
			<div class="pd2">.......................tgl................................</div>
			<div style="min-height: 1.5cm;text-align: center;margin-left: 35px;">Dokter RS,</div>
			<div class="pd2" style="text-align: center;margin-left: 34px;">(..................................................)</div>
			
		</div>

	<div class="clear"></div>
	</div>
</div>
<script type="text/javascript">JsBarcode("#barcode", "<?php echo $metadata['noRujukan']; ?>", {
  width: 1, height: 30, displayValue: false
});</script>
</body>
</html>