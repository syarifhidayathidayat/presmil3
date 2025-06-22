<?php include 'header.php'; ?>
<br>
<style>
  .step {
    display: none;
  }

  .step.active {
    display: block;
  }
</style>

<div class="container" style="padding-top: 100px;">
  <div class="row justify-content-center">
    <div class="col-lg-12">
      <div class="card" style="min-height: 500px">
        <div class="card-body pt-4">
          <center>
            <h4>KUESIONER IBU HAMIL</h4>
            <small>Isilah jawaban pertanyaan di bawah ini sesuai dengan keadaan ibu sebenarnya dengan bisa dengan melihat buku KIA pink.</small>
          </center>
          <br>
          <?php
          if (isset($_GET['alert'])) {
            if ($_GET['alert'] == "sudah") {
              echo "<div class='text-center alert alert-danger'><b>MAAF!</b> <br> ANDA SUDAH PERNAH MENGISI KUESIONER SEBELUMNYA!</div>";
            } else if ($_GET['alert'] == "selesai") {
              echo "<div class='text-center alert alert-success'><b>DATA JAWABAN KUESIONER ANDA TELAH TERSIMPAN</b> <br> TERIMA KASIH TELAH MELUANGKAN WAKTU</div>";
            }
          }
          ?>
          <form action="survey_act.php" method="post">
            <!-- Step 1: Data Ibu Hamil -->
            <div class="step active" id="step-1">
              <h5>Data Ibu Hamil</h5>
              <hr>
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group">
                    <label>Email</label>
                    <input type="text" name="bumil_email" class="form-control" required="required" placeholder="">
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label>No. Telepon</label>
                    <input type="number" name="telp" class="form-control" required="required" placeholder="">
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label>Nama Responden</label>
                    <input type="text" name="bumil_nama" class="form-control" required="required" placeholder="">
                  </div>
                </div>

                <div class="col-lg-6">
                  <div class="form-group">
                    <label>Profesi</label>
                    <select name="bumil_profesi" class="form-control" required="required">
                      <option value="">Pilih Profesi</option>
                      <?php
                      $profesi = mysqli_query($koneksi, "SELECT * FROM profesi order by profesi asc");
                      while ($p = mysqli_fetch_array($profesi)) {
                      ?>
                        <option value="<?php echo $p['profesi_id'] ?>"><?php echo $p['profesi'] ?></option>
                      <?php
                      }
                      ?>
                    </select>
                  </div>
                </div>

                <div class="col-lg-6">
                  <div class="form-group">
                    <label>Nama Wilayah Puskesmas</label>
                    <input type="text" name="wil_puskes" class="form-control" required="required" placeholder="">
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label>Pendidikan Ibu Terakhir (Tamat sekolah)</label>
                    <select name="pendidikan_terakhir" class="form-control" required="required">
                      <option value="">Pilih Pendidikan Terakhir</option>
                      <?php
                      $pendidikan = ["SD", "SLTP", "SLTA", "D3", "S1", "S2", "S3", "Tidak sekolah"];
                      foreach ($pendidikan as $p) {
                      ?>
                        <option value="<?php echo $p; ?>"><?php echo $p; ?></option>
                      <?php
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label>Alamat</label>
                    <input type="text" name="alamat" class="form-control" required="required" placeholder="">
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label>HPHT (Hari Pertama Haid Terakhir)</label>
                    <input type="date" name="hpht" class="form-control" required="required" placeholder="">
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label>Pendapatan keluarga perbulan</label>
                    <input type="text" id="pendapatan_display" class="form-control" required="required" placeholder="">
                    <input type="hidden" id="pendapatan" name="pendapatan">
                  </div>
                </div>

                <script>
                  document.getElementById('pendapatan_display').addEventListener('input', function(e) {
                    // Hapus karakter selain angka
                    let input = e.target.value.replace(/[^0-9]/g, '');
                    // Simpan nilai asli ke input tersembunyi
                    document.getElementById('pendapatan').value = input;
                    // Tambahkan titik sebagai pemisah ribuan
                    input = input.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                    // Perbarui nilai input field yang terlihat
                    e.target.value = input;
                  });
                </script>

                <div class="col-lg-6">
                  <div class="form-group">
                    <label>Apakah ibu sudah pernah periksa hamil sebelumnya</label>
                    <div>
                      <input type="radio" id="periksa_hamil_yes" name="periksa_hamil" value="yes" required>
                      <label for="periksa_hamil_yes">Sudah</label>
                    </div>
                    <div>
                      <input type="radio" id="periksa_hamil_no" name="periksa_hamil" value="no" required>
                      <label for="periksa_hamil_no">Belum</label>
                    </div>
                  </div>
                </div>

                <div class="col-lg-6">
                  <div class="form-group">
                    <label>Berat badan sebelum hamil</label>
                    <input type="number" name="berat_badan_sebelum_hamil" class="form-control" required="required" placeholder="">
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label>Berat badah saat kehamilan sekarang (Kg)</label>
                    <input type="number" name="berat_badan_sekarang" class="form-control" required="required" placeholder="">
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label>Tinggi badan</label>
                    <input type="number" name="tinggi_badan" class="form-control" required="required" placeholder="">
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label>Berapa ukuran Lingkar Lengan Atas ibu (LILA)</label>
                    <input type="number" name="lila" class="form-control" required="required" placeholder="">
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label>Tekanan darah sekarang</label>
                    <input type="text" name="tensi" class="form-control" required="required" placeholder="">
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label>Apakah ibu kehamilan dengan program bayi tabung</label>
                    <div>
                      <input type="radio" id="bayi_tabung_yes" name="bayi_tabung" value="yes" required>
                      <label for="bayi_tabung_yes">Ya</label>
                    </div>
                    <div>
                      <input type="radio" id="bayi_tabung_no" name="bayi_tabung" value="no" required>
                      <label for="bayi_tabung_no">Tidak</label>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label>Apakah ibu hamil rutin minum tablet tambah darah atau vitamin ibu hamil secara teratur sesuai dosis minimal 270 biji selama kehamilan</label>
                    <div>
                      <input type="radio" id="tablet_tambah_darah_ya" name="tablet_tambah_darah" value="Ya" required>
                      <label for="tablet_tambah_darah_ya">Ya</label>
                    </div>
                    <div>
                      <input type="radio" id="tablet_tambah_darah_tidak" name="tablet_tambah_darah" value="Tidak" required>
                      <label for="tablet_tambah_darah_tidak">Tidak</label>
                    </div>
                  </div>
                </div>
              </div>
              <br>
              <button type="button" class="btn btn-primary btn-block" onclick="nextStep()">Next</button>
            </div>
            <!-- Step 2: Pertanyaan Tambahan -->
            <div class="step" id="step-2">
              <h5>Kegiatan yang dilakukan ibu hamil selama kehamilan</h5>
              <hr>
              <div class="form-group">
                <label for="atasi_keluhan">Apa yang dilakukan ibu hamil untuk mengatasi keluhan yang ada</label>
                <textarea name="atasi_keluhan" class="form-control" required></textarea>
              </div>
              <div class="form-group">
                <label>Berapa kali ibu kunjungan ke tenaga kesehatan (Bidan/Dokter kandungan/puskesmas) untuk pemeriksaan kehamilan:</label>
                <div>
                  <label>Usia kehamilan 0-3 bulan:</label>
                  <input type="number" name="kunjungan_0_3_bidan" class="form-control" placeholder="Dengan bidan ... kali" required>
                  <input type="number" name="kunjungan_0_3_dokter" class="form-control" placeholder="Dengan dokter ... kali" required>
                </div>
                <div>
                  <label>Usia kehamilan >3 sampai kurang dari 7 bulan: </label>
                  <input type="number" name="kunjungan_3_7_bidan" class="form-control" placeholder="Dengan bidan ... kali" required>
                </div>
                <div>
                  <label>Usia kehamilan > 7 sampai 9 bulan:</label>
                  <input type="number" name="kunjungan_7_9_bidan" class="form-control" placeholder="Dengan bidan ... kali" required>
                  <input type="number" name="kunjungan_7_9_dokter" class="form-control" placeholder="Dengan dokter ... kali" required>
                </div>
              </div>
              <div class="form-group">
                <label>Apakah ibu sudah memasang stiker P4K didepan rumah:</label>
                <div>
                  <input type="radio" id="stiker_p4k_yes" name="stiker_p4k" value="yes" required>
                  <label for="stiker_p4k_yes">Ya</label>
                </div>
                <div>
                  <input type="radio" id="stiker_p4k_no" name="stiker_p4k" value="no" required>
                  <label for="stiker_p4k_no">Tidak</label>
                </div>
              </div>
              <div class="form-group">
                <label>Apakah Ibu hamil masuk kelas ibu hamil 4x pertemuan:</label>
                <div>
                  <input type="radio" id="kelas_ibu_yes" name="kelas_ibu" value="yes" required>
                  <label for="kelas_ibu_yes">Ya</label>
                </div>
                <div>
                  <input type="radio" id="kelas_ibu_no" name="kelas_ibu" value="no" required>
                  <label for="kelas_ibu_no">Tidak</label>
                </div>
              </div>
              <div class="form-group">
                <label>Apakah ibu hamil rutin minum tablet tambah darah atau vitamin ibu hamil secara teratur sesuai dosis minimal 270 biji selama:</label>
                <div>
                  <input type="radio" id="vitamin_yes" name="vitamin" value="yes" required>
                  <label for="vitamin_yes">Ya</label>
                </div>
                <div>
                  <input type="radio" id="vitamin_no" name="vitamin" value="no" required>
                  <label for="vitamin_no">Tidak</label>
                </div>
              </div>
              <div class="form-group">
                <label>Berapa kali ibu sudah diberikan Imunisasi Tetanus Toxoid?</label>
                <input type="number" name="imunisasi_tt" class="form-control" required>
              </div>
              <div class="form-group">
                <label>Apakah makanan yang ibu konsumsi setiap hari?</label>
                <textarea name="makanan_harian" class="form-control" required></textarea>
              </div>
              <div class="form-group">
                <label>Apakah ibu sudah menyiapkan untuk persiapan persalinan?</label>
                <div>
                  <input type="radio" id="persalinan_yes" name="persalinan" value="yes" required>
                  <label for="persalinan_yes">Ya</label>
                </div>
                <div>
                  <input type="radio" id="persalinan_no" name="persalinan" value="no" required>
                  <label for="persalinan_no">Tidak</label>
                </div>
                <div>
                  <label>Rencanakan melahirkan ditolong oleh dokter atau bidan di fasilitas kesehatan:</label>
                  <div>
                    <input type="radio" id="melahirkan_fasilitas_yes" name="melahirkan_fasilitas" value="yes" required>
                    <label for="melahirkan_fasilitas_yes">Ya</label>
                  </div>
                  <div>
                    <input type="radio" id="melahirkan_fasilitas_no" name="melahirkan_fasilitas" value="no" required>
                    <label for="melahirkan_fasilitas_no">Tidak</label>
                  </div>
                </div>
                <div>
                  <label>Persiapkan tabungan untuk biaya persalinan/biaya lainnya seperti JKN/BPJS:</label>
                  <div>
                    <input type="radio" id="tabungan_yes" name="tabungan" value="yes" required>
                    <label for="tabungan_yes">Ya</label>
                  </div>
                  <div>
                    <input type="radio" id="tabungan_no" name="tabungan" value="no" required>
                    <label for="tabungan_no">Tidak</label>
                  </div>
                </div>
                <div>
                  <label>Siapkan KTP, Kartu Keluarga, dan keperluan lain untuk ibu dan bayi yang akan dilahirkan:</label>
                  <div>
                    <input type="radio" id="identitas_yes" name="identitas" value="yes" required>
                    <label for="identitas_yes">Ya</label>
                  </div>
                  <div>
                    <input type="radio" id="identitas_no" name="identitas" value="no" required>
                    <label for="identitas_no">Tidak</label>
                  </div>
                </div>
                <div>
                  <label>Menyiapkan pendamping persalinan seperti suami/keluarga/lainnya:</label>
                  <div>
                    <input type="radio" id="pendamping_yes" name="pendamping" value="yes" required>
                    <label for="pendamping_yes">Ya</label>
                  </div>
                  <div>
                    <input type="radio" id="pendamping_no" name="pendamping" value="no" required>
                    <label for="pendamping_no">Tidak</label>
                  </div>
                </div>
                <div>
                  <label>Menyiapkan kendaraan untuk mengantar bila mau melahirkan:</label>
                  <div>
                    <input type="radio" id="kendaraan_yes" name="kendaraan" value="yes" required>
                    <label for="kendaraan_yes">Ya</label>
                  </div>
                  <div>
                    <input type="radio" id="kendaraan_no" name="kendaraan" value="no" required>
                    <label for="kendaraan_no">Tidak</label>
                  </div>
                </div>
                <div>
                  <label>Menyiapkan pendonor darah yang memiliki golongan darah yang sama jika diperlukan:</label>
                  <div>
                    <input type="radio" id="pendonor_yes" name="pendonor" value="yes" required>
                    <label for="pendonor_yes">Ya</label>
                  </div>
                  <div>
                    <input type="radio" id="pendonor_no" name="pendonor" value="no" required>
                    <label for="pendonor_no">Tidak</label>
                  </div>
                </div>
              </div>
              <br>
              <button type="button" class="btn btn-secondary" onclick="prevStep()">Back</button>
              <button type="button" class="btn btn-primary btn-block" onclick="nextStep()">Next</button>

            </div>
            <!-- Step 2: Isi Kuesioner -->
            <div class="step" id="step-3">
              <h5>Isi Kuesioner</h5>
              <hr>
              <?php
              $no = 1;
              $pertanyaan = mysqli_query($koneksi, "SELECT * FROM pertanyaan");
              while ($p = mysqli_fetch_array($pertanyaan)) {
                $nox = $no++;
              ?>
                <div class="form-group">
                  <?php echo $nox; ?>.
                  <label><?php echo $p['pertanyaan'] ?></label>
                  <input type="hidden" name="pertanyaan[<?php echo $nox ?>]" value="<?php echo $p['pertanyaan_id'] ?>">
                  <br>
                  <?php
                  $id_pertanyaan = $p['pertanyaan_id'];
                  $jawaban = mysqli_query($koneksi, "SELECT * FROM jawaban WHERE jawaban_pertanyaan='$id_pertanyaan'");
                  while ($j = mysqli_fetch_array($jawaban)) {
                  ?>
                    <input class="ml-3" type="radio" name="jawaban[<?php echo $nox ?>]" value="<?php echo $j['jawaban_id'] ?>" required="required"> <?php echo $j['jawaban'] ?> <br>
                  <?php
                  }
                  ?>
                </div>
              <?php
              }
              ?>
              <br>
              <center>
                <small class="text-muted"><i>Pastikan semua jawaban sudah benar sebelum menyelesaikan kuesioner.</i></small>
                <br>
                <br>
                <input type="checkbox" name="setuju" required="required"> Ya, kuesioner telah diisi dengan benar
              </center>
              <br>
              <br>
              <button type="button" class="btn btn-secondary" onclick="prevStep()">Back</button>

              <input type="submit" value="SELESAI" class="btn btn-primary btn-block">
              <br>
            </div>
          </form>
          <!-- disini script next -->
          <script src="js/next.js"></script>
        </div>
      </div>
    </div>
  </div>
</div>
<br>
<br>
<?php include 'footer.php'; ?>