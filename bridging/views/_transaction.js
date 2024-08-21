$(document).ready(function () {
  var betul = '<span class="ijo">✔</span> ';
  var salah = '<span class="mirah">❌</span> ';
  var ingat = '<span class="warning">🟡</span>';

  function patient () {
    let peserta = '';
    $.ajax({
      type: "POST",
      url: "https://bridging.aceh-servers.net/pcare/patient",
      data: {token: "<?php echo $enc; ?>", cardType: "noka", cardNumber: "<?php echo $p['cardNumber']; ?>"},
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
          let providerPatientId = msg.metadata.kdProviderPst.kdProvider;
          let cardNumber = msg.metadata.noKartu;
          registration(providerPatientId, cardNumber);
        }
        return false;
      },
      dataType: "json",
      error: function(xhr, status, error) {
        $("#peserta").html(xhr.responseText);
      }
    });
  }
  function registration(providerPatientId, cardNumber) {
    let pendaftaran = '';
    $.ajax({
      type: "POST",
      url: "https://bridging.aceh-servers.net/pcare/addRegistration",
      data: {token: "<?php echo $enc; ?>", providerPatientId: providerPatientId, dateRegistration: "<?php echo $p['dateRegistration']; ?>", cardNumber: cardNumber, polyclinicId: "001", tkpId: 10},

      complete: function (xhr, status) {
        msg = JSON.parse(xhr.responseText);
        //console.log(JSON.stringify(msg));
        if (msg?.metadata?.noUrut) {
          pendaftaran += '<li>' + betul + 'Nomor Urut: ' + msg.metadata.noUrut + "</li>";
          //pendaftaran += JSON.stringify(msg);
          $("#pendaftaran ul").append(pendaftaran);
          visit();
        } else if (msg.message[0] == 'Peserta sudah di-entri di poli yang sama pada hari yang sama') {
          pendaftaran += '<li>' + ingat + msg.message[0] +"</li>";
          $("#pendaftaran ul").append(pendaftaran);
          visit();
        } else if (msg.code == 401) {
          pendaftaran += '<li>' + salah + "Maaf, bridging ke Server BPJS Kesehatan sedang ada gangguan</li>";
          $("#pendaftaran ul").append(pendaftaran);
        }
        return false;
      },
      dataType: "json"
    });
  }
  function visit() {
    let kunjungan = '';
    <?php
    if ($p['visitId']) {
      $varVisit = 'visitId: "' . $p['visitId'] .'",' . PHP_EOL; 
      $urlVisit = 'editVisit';
    } else {
      $varVisit = '';
      $urlVisit = 'visit';
    }
    ?>
    $.ajax({
      type: "POST",
      url: "https://bridging.aceh-servers.net/pcare/<?php echo $urlVisit; ?>",
      data: {
        token: "<?php echo $enc; ?>",
        <?php echo $varVisit; ?>
        cardNumber: "<?php echo $p['cardNumber']; ?>",
        dateRegistration: "<?php echo $p['dateRegistration']; ?>",
        polyclinicId: "<?php echo $p['polyclinicId']; ?>",
        complain: "<?php echo $p['complain']; ?>",
        awarenessId: "<?php echo $p['awarenessId']; ?>",
        systole: "<?php echo $p['systole']; ?>",
        diastole: "<?php echo $p['diastole']; ?>",
        weight: "<?php echo $p['weight']; ?>",
        height: "<?php echo $p['height']; ?>",
        respRate: "<?php echo $p['respRate']; ?>",
        heartRate: "<?php echo $p['heartRate']; ?>",
        abdominalCircumference: "<?php echo $p['abdominalCircumference']; ?>",
        returnStatusId: "<?php echo $p['returnStatusId']; ?>",
        dateOut: "<?php echo $p['dateOut']; ?>",
        doctorId: "<?php echo $p['doctorId']; ?>",
        diagnosisId1: "<?php echo $p['diagnosisId1']; ?>",
        diagnosisId2: "<?php echo $p['diagnosisId2']; ?>",
        diagnosisId3: "<?php echo $p['diagnosisId3']; ?>",
        polyReferralInternalId: "<?php echo $p['polyReferralInternalId']; ?>",
        dateReferral: "<?php echo $p['dateReferral']; ?>",
        medicalFacilityId: "<?php echo $p['medicalFacilityId']; ?>",
        subSpecialist: "<?php echo $p['subSpecialist']; ?>",
        specificId: "<?php echo $p['specificId']; ?>",
        subSpecialistId: "<?php echo $p['subSpecialistId']; ?>",
        notes: "<?php echo $p['notes']; ?>",
        idTacc: "<?php echo $p['idTacc']; ?>",
        reasonTacc: "<?php echo $p['reasonTacc']; ?>",
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
  function referral() {
    let rujukan = '';
    $.ajax({
      type: "POST",
      url: "https://bridging.aceh-servers.net/pcare/<?php echo $urlVisit; ?>",
      data: {
        token: "<?php echo $enc; ?>",
        <?php echo $varVisit; ?>
        cardNumber: "<?php echo $p['cardNumber']; ?>",
        dateRegistration: "<?php echo $p['dateRegistration']; ?>",
        polyclinicId: "<?php echo $p['polyclinicId']; ?>",
        complain: "<?php echo $p['complain']; ?>",
        awarenessId: "<?php echo $p['awarenessId']; ?>",
        systole: "<?php echo $p['systole']; ?>",
        diastole: "<?php echo $p['diastole']; ?>",
        weight: "<?php echo $p['weight']; ?>",
        height: "<?php echo $p['height']; ?>",
        respRate: "<?php echo $p['respRate']; ?>",
        heartRate: "<?php echo $p['heartRate']; ?>",
        abdominalCircumference: "<?php echo $p['abdominalCircumference']; ?>",
        returnStatusId: "<?php echo $p['returnStatusId']; ?>",
        dateOut: "<?php echo $p['dateOut']; ?>",
        doctorId: "<?php echo $p['doctorId']; ?>",
        diagnosisId1: "<?php echo $p['diagnosisId1']; ?>",
        diagnosisId2: "<?php echo $p['diagnosisId2']; ?>",
        diagnosisId3: "<?php echo $p['diagnosisId3']; ?>",
        polyReferralInternalId: "<?php echo $p['polyReferralInternalId']; ?>",
        dateReferral: "<?php echo $p['dateReferral']; ?>",
        medicalFacilityId: "<?php echo $p['medicalFacilityId']; ?>",
        subSpecialist: "<?php echo $p['subSpecialist']; ?>",
        specificId: "<?php echo $p['specificId']; ?>",
        subSpecialistId: "<?php echo $p['subSpecialistId']; ?>",
        notes: "<?php echo $p['notes']; ?>",
        idTacc: "<?php echo $p['idTacc']; ?>",
        reasonTacc: "<?php echo $p['reasonTacc']; ?>",
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
  
  function specialist() {
    let spesialis = '';
    $.ajax({
      type: "POST",
      url: "https://bridging.aceh-servers.net/pcare/specialist",
      data: { token: "<?php echo $enc; ?>" },

      complete: function (xhr, status) {
        msg = JSON.parse(xhr.responseText);
        if (status == 'error' || msg.code == 203) {
          for (let i = 0; i < msg.message.length; i++) {
            spesialis += '<option>' + salah + msg.message[i] + "</option>";
          }
          $("#spesialis select").append(kunjungan);
        }
        if (msg.code == 200) {
          for (let i = 0; i < msg.message.length; i++) {
            spesialis += `<option value=>${msg.message[i]}</option>`;
          }
          $("#spesialis select").append(spesialis);
          medicine();
        }
        return false;
      },
      dataType: "json",
    });
  }
  
  function medicine() {
    let obat = '';
    <?php for ($i = 0; $i < count($p['dphoMedicine']); $i++) : ?>
    setTimeout( function () {
      $.ajax({
        type: "POST",
        url: "https://bridging.aceh-servers.net/pcare/visitAddMedicine",
        data: {
          token: "<?php echo $enc; ?>",
          visitId: "<?php echo $p['visitId']; ?>",
          concoction: "<?php echo $p['concoction'][$i]; ?>",
          concoctionId: "<?php echo $p['concoctionId'][$i]; ?>",
          dphoMedicine: "<?php echo $p['dphoMedicine'][$i]; ?>",
          medicineId: "<?php echo $p['medicineId'][$i]; ?>",
          signa1: "<?php echo $p['signa1'][$i]; ?>",
          signa2: "<?php echo $p['signa2'][$i]; ?>",
          amount: "<?php echo $p['amount'][$i]; ?>",
          requestAmount: "<?php echo $p['requestAmount'][$i]; ?>",
          medicineNonDphoName: "<?php echo $p['medicineNonDphoName'][$i]; ?>",
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
              obat += '<li>' + ingat + "obat <?php echo $p['medicineId'][$i]; ?></li>";
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
    <?php endfor; ?>
  }
  patient();
});