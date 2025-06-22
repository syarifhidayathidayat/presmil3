<?php include 'header.php'; ?>
<!-- commit test  -->
<div class="container">

	<div class="mb-4 survey">
		<h4>Data Kuesioner Ibu Hamil</h4>
		<small>Detail data kuesioner Ibu hamil.</small>
	</div>

	<!-- Tautan print ke PDF  --->
	<div class="row mb-3">
		<div class="col-lg-12">
			<a class="btn btn-primary btn-sm" href="bumil.php">
				<i class="fa fa-arrow-left"></i> &nbsp Kembali
			</a>
			<a class="btn btn-success btn-sm" href="print_pdf.php?id=<?php echo $id; ?>" target="_blank">
				<i class="fa fa-file-pdf"></i> &nbsp Cetak ke PDF
			</a>
		</div>
	</div>
	<!-- Selesai tautan print -->

	<div class="card">
		<div class="card-body">
			<div class="row">
				<div class="col-lg-12">
					<?php
					$id = $_GET['id'];
					$data = mysqli_query($koneksi, "SELECT * FROM bumil,profesi WHERE profesi=profesi_id and bumil_id='$id'");
					while ($d = mysqli_fetch_array($data)) {
					?>
						<table class="table table-bordered ">
							<tr>
								<td>Nama</td>
								<td><?php echo $d['bumil_nama']; ?></td>
							</tr>
							<tr>
								<td>No.Telepon</td>
								<td><?php echo $d['telp']; ?></td>
							</tr>
							<tr>
								<td>Profesi</td>
								<td><?php echo $d['bumil_profesi']; ?></td>
							</tr>
							<tr>
								<td>Email</td>
								<td><?php echo $d['bumil_email']; ?></td>
							</tr>
							<!-- <tr>
								<td>Saran Aplikasi</td>
								<td><?php echo $d['saran']; ?></td>
							</tr> -->
						</table>
					<?php
					}
					?>
				</div>



				<!-- data kuesioner -->
				<div class="col-lg-6">
					<h5>Data Kuesioner</h5>
					<?php
					$no = 1;
					$polling = mysqli_query($koneksi, "SELECT * FROM polling,bumil,pertanyaan,jawaban WHERE bumil_id=polling_bumil AND bumil_id='$id' AND polling_pertanyaan=pertanyaan_id AND polling_jawaban=jawaban_id");
					while ($p = mysqli_fetch_array($polling)) {
					?>
						<table class="table table-bordered table-striped">
							<tr>
								<td width="1%"><?php echo $no++ ?></td>
								<td><?php echo $p['pertanyaan']; ?></td>
							</tr>
							<tr>
								<td></td>
								<td>
									<?php
									// Tampilkan jawaban
									echo $p['jawaban'];

									// Tentukan kondisi beresiko
									$beresiko_conditions = array(
										array('pertanyaan_id' => 7, 'jawaban' => '< 20 Tahun'),
										array('pertanyaan_id' => 7, 'jawaban' => '> 35'),
										array('pertanyaan_id' => 8, 'jawaban' => 'Hamil ke- 3'),
										array('pertanyaan_id' => 8, 'jawaban' => 'Hamil pertama ≥ 4 tahun'),
										array('pertanyaan_id' => 10, 'jawaban' => 'Anak terkecil ≥ 10 Tahun'),
										array('pertanyaan_id' => 10, 'jawaban' => 'Anak terkecil ≤ 2 Tahun'),
										array('pertanyaan_id' => 11, 'jawaban' => 'LILA < 23,5 cm'),
										// Tambahkan kondisi  sesuai dengan pertanyaan dan jawaban yang beresiko
									);

									// Inisialisasi variabel flag
									$beresiko_flag = false;

									// Periksa apakah jawaban termasuk dalam kondisi beresiko
									foreach ($beresiko_conditions as $condition) {
										if ($p['pertanyaan_id'] == $condition['pertanyaan_id'] && $p['jawaban'] == $condition['jawaban']) {
											$beresiko_flag = true;
											break;
										}
									}

									// Tampilkan keterangan "Beresiko" jika flag masih bernilai true
									if ($beresiko_flag) {
										echo ' <span class="badge badge-danger">Beresiko</span>';
									} else {
										echo ' <i class="fa fa-check" style="color: green;"></i>';
									}
									?>
								</td>
							</tr>
						</table>
					<?php
					}
					?>
				</div>

				<!-- Tambahkan tabel tambahan untuk saran berdasarkan kondisi beresiko -->
				<div class="col-lg-6">
					<h5>Saran Berdasarkan Kondisi Beresiko</h5>
					<?php
					// Reset nomor
					$no = 1;

					// Kembali ke awal data polling
					mysqli_data_seek($polling, 0);

					while ($p = mysqli_fetch_array($polling)) {
						// Tentukan kondisi beresiko untuk tabel tambahan
						$beresiko_conditions = array(
							array('pertanyaan_id' => 7, 'jawaban' => '< 20 Tahun'),
							array('pertanyaan_id' => 7, 'jawaban' => '> 35'),
							array('pertanyaan_id' => 8, 'jawaban' => 'Hamil ke- 3'),
							array('pertanyaan_id' => 8, 'jawaban' => 'Hamil pertama ≥ 4 tahun'),
							array('pertanyaan_id' => 10, 'jawaban' => 'Anak terkecil ≥ 10 Tahun'),
							array('pertanyaan_id' => 10, 'jawaban' => 'Anak terkecil ≤ 2 Tahun'),
							array('pertanyaan_id' => 11, 'jawaban' => 'LILA < 23,5 cm'),
							// Tambahkan kondisi berikut sesuai dengan pertanyaan dan jawaban yang beresiko
						);

						// Inisialisasi variabel flag untuk tabel tambahan
						$beresiko_flag_tambahan = false;

						// Periksa apakah jawaban termasuk dalam kondisi beresiko untuk tabel tambahan
						foreach ($beresiko_conditions as $condition) {
							if ($p['pertanyaan_id'] == $condition['pertanyaan_id'] && $p['jawaban'] == $condition['jawaban']) {
								$beresiko_flag_tambahan = true;
								break;
							}
						}

						// Tampilkan tabel tambahan hanya untuk jawaban yang beresiko
						if ($beresiko_flag_tambahan) {
					?>
							<div class="card">
								<div class="card-body">
									<h5 class="card-title"><?php echo $p['pertanyaan']; ?></h5>
									<p class="card-text">
										<!-- Tampilkan jawaban beresiko -->
										<?php echo $p['jawaban']; ?>
										<span class="badge badge-danger">Beresiko</span>
									</p>
									<!-- Tampilkan saran berdasarkan kondisi beresiko -->
									<?php
									$saran = '';
									switch ($p['pertanyaan_id']) {
										case 7:
											if ($p['jawaban'] == '< 20 Tahun') {
												$saran = 'Umur ibu sekarang < 20 Tahun, penting untuk mendapatkan perawatan kesehatan yang baik selama kehamilan. Konsultasikan dengan profesional kesehatan untuk memastikan kehamilan yang sehat dan untuk mendapatkan nasihat tentang aspek-aspek kesehatan yang perlu diperhatikan.';
											} else if ($p['jawaban'] == '> 35') {
												$saran = 'Ibu hamil yang berusia di atas 35 tahun mungkin berisiko mengalami komplikasi kehamilan. Konsultasikan dengan dokter atau profesional kesehatan untuk pemantauan yang lebih intensif dan saran khusus. Tes medis tambahan dan perawatan prenatal yang lebih cermat mungkin diperlukan.';
											} else {
												$saran = 'Selamat Anda tidak beresiko. tetap konsumsi makanan sehat, jaga kesehatan, tidur yang cukup, jaga kebiasaan baik agar kesehatan janin dan ibu selalu terjaga.';
											}
											break;
										case 8:
											if ($p['jawaban'] == 'Hamil ke- 3') {
												$saran = 'Kehamilan ketiga atau lebih mungkin meningkatkan risiko komplikasi. Penting untuk mendapatkan perawatan prenatal yang cermat dan dipantau secara ketat oleh profesional kesehatan. Pemeriksaan rutin dan konsultasi dengan dokter dapat membantu mengelola risiko potensial.';
											} else if ($p['jawaban'] == 'Hamil pertama ≥ 4 tahun') {
												$saran = 'Jika ini adalah kehamilan pertama dan ibu hamil telah menunggu lebih dari empat tahun sejak kehamilan sebelumnya, risiko kesehatan tertentu mungkin timbul. Perawatan prenatal yang baik, pemantauan reguler, dan konsultasi dengan dokter atau profesional kesehatan adalah penting untuk memastikan kesehatan ibu dan bayi.';
											} else {
												$saran = 'Selamat Anda tidak beresiko. tetap konsumsi makanan sehat, jaga kesehatan, tidur yang cukup, jaga kebiasaan baik agar kesehatan janin dan ibu selalu terjaga.';
											}
											break;
										case 10:
											if ($p['jawaban'] == 'Anak terkecil ≥ 10 Tahun') {
												$saran = 'Jarak waktu yang panjang antara kehamilan saat ini dan kelahiran anak terakhir dapat membawa risiko tertentu. Perlu mendapatkan perawatan prenatal yang lebih cermat dan dipantau secara intensif oleh profesional kesehatan. Risiko tertentu, seperti masalah kesehatan ibu dan bayi, mungkin lebih tinggi dalam kasus ini.';
											} else if ($p['jawaban'] == 'Anak terkecil ≤ 2 Tahun') {
												$saran = 'Jarak waktu yang sangat pendek antara kehamilan dapat meningkatkan risiko komplikasi. Diperlukan perawatan prenatal yang cermat dan pemantauan ketat untuk memitigasi risiko kesehatan ibu dan bayi. Konsultasikan dengan dokter untuk rencana perawatan yang sesuai.';
											} else {
												$saran = 'Selamat Anda tidak beresiko. tetap konsumsi makanan sehat, jaga kesehatan, tidur yang cukup, jaga kebiasaan baik agar kesehatan janin dan ibu selalu terjaga.';
											}
											break;
										case 11:
											$saran = '<ol>
                                                        <li>
                                                            <strong>Konsultasi dengan Profesional Kesehatan:</strong> Penting untuk segera berkonsultasi dengan dokter atau profesional kesehatan untuk mengevaluasi penyebab dari LILA yang rendah.
                                                        </li>
                                                        <li>
                                                            <strong>Pemeriksaan Kesehatan:</strong> Melakukan pemeriksaan kesehatan lebih lanjut untuk menilai kondisi kesehatan ibu dan mencari penyebab potensial dari masalah gizi.
                                                        </li>
                                                        <li>
                                                            <strong>Perencanaan Gizi:</strong> Membuat rencana gizi yang sesuai untuk memastikan ibu mendapatkan nutrisi yang cukup selama kehamilan.
                                                        </li>
                                                        <li>
                                                            <strong>Suplementasi Gizi:</strong> Jika diperlukan, dokter atau profesional kesehatan mungkin merekomendasikan suplemen gizi atau vitamin untuk memenuhi kebutuhan nutrisi.
                                                        </li>
                                                        <li>
                                                            <strong>Pemantauan Prenatal:</strong> Memantau pertumbuhan janin dan kesehatan ibu secara cermat selama kehamilan.
                                                        </li>
                                                        <li>
                                                            <strong>Perubahan Gaya Hidup:</strong> Mungkin perlu melakukan perubahan dalam pola makan dan gaya hidup untuk mendukung kesehatan ibu dan perkembangan janin.
                                                        </li>
                                                        </ol>
                                                        ';
											break;

											// Tambahkan case sesuai dengan pertanyaan_id yang beresiko
									}
									?>
									<!-- Tampilkan saran dengan gaya Bootstrap 4 -->
									<div class="alert alert-success mt-2">
										<strong>Saran:</strong><br> <?php echo $saran; ?>
									</div>
								</div>
							</div>


					<?php
						}
					}
					?>
					<!-- saran end  -->
				</div>
			</div>
			<!-- end  -->



		</div>
	</div>
</div>
<?php include 'footer.php'; ?>