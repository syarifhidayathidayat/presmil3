<?php include 'header.php'; ?>

<div class="container-fluid">

	<div class="mb-4">
		<h4>Data Responden & Kuesioner</h4>
		<small>Responden yang sudah mendaftar & mengisi kuesioner</small>
	</div>

	<div class="row mb-3">
		<div class="col-lg-12">
			<a class="btn btn-primary btn-sm" target="_blank" href="print_biodata.php">
				<i class="fa fa-print"></i> &nbsp Print
			</a>
			<a class="btn btn-primary btn-sm" href="excel.php">
				<i class="fa fa-print"></i> &nbsp Export Excel
			</a>
		</div>
	</div>

	<div class="card">
		<div class="card-body">

			<div class="table-responsive">
				<table class="table table-bordered table-striped" id="table-datatable">
					<thead>
						<tr>
							<th width="1%">NO</th>
							<th>Email</th>
							<th>Nama</th>
							<th>No. Telp</th>
							<th>Profesi</th>
							<th>Wilayah Puskes</th>
							<th>Alamat</th>
							<th>HPHT</th>
							<th>Pendapatan</th>
							<th>Periksa Hamil</th>
							<th>LILA</th>
							<th>Tensi</th>
							<th>Bayi Tabung</th>
							<th>Tambah darah atau Vitamin</th>
							<th width="8%">Opsi</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						$query = "SELECT * FROM bumil INNER JOIN profesi ON bumil.bumil_profesi = profesi.profesi_id";
						$data = mysqli_query($koneksi, $query);
						if (!$data) {
							die("Query Error: " . mysqli_error($koneksi));
						}

						while ($d = mysqli_fetch_array($data)) {
						?>
							<tr>
								<td><?php echo $no++; ?></td>
								<td><?php echo $d['bumil_email']; ?></td>
								<td><?php echo $d['bumil_nama']; ?></td>
								<td><?php echo $d['telp']; ?></td>
								<td><?php echo $d['profesi']; ?></td>
								<td><?php echo $d['wil_puskes']; ?></td>
								<td><?php echo $d['alamat']; ?></td>
								<td><?php echo $d['hpht']; ?></td>
								<td>Rp. <?php echo number_format($d['pendapatan'], 0, ',', '.'); ?></td>
								<td><?php echo $d['periksa_hamil'] == 1 ? 'Sudah' : 'Belum'; ?></td>
								<td><?php echo $d['lila']; ?></td>
								<td><?php echo $d['tensi']; ?></td>
								<td><?php echo $d['bayi_tabung'] == 1 ? 'Ya' : 'Tidak'; ?></td>
								<td><?php echo $d['tablet_tambah_darah'] == 1 ? 'Ya' : 'Tidak'; ?></td>
								<td>
									<a href="bumil_survey.php?id=<?php echo $d['bumil_id'] ?>" class="btn btn-info btn-sm">
										<i class="fa fa-heartbeat  "></i>
									</a>
									<a href="keg_survey.php?id=<?php echo $d['bumil_id'] ?>" class="btn btn-warning btn-sm">
										<i class="fa fa-blind"></i>
									</a>

									<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#hapus_bumil_<?php echo $d['bumil_id'] ?>">
										<i class="fa fa-trash"></i>
									</button>

									<!-- modal hapus -->
									<div class="modal fade" id="hapus_bumil_<?php echo $d['bumil_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
										<div class="modal-dialog" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title" id="exampleModalLabel">Peringatan!</h5>
													<button type="button" class="close" data-dismiss="modal" aria-label="Close">
														<span aria-hidden="true">&times;</span>
													</button>
												</div>
												<div class="modal-body">

													<p>Yakin ingin menghapus data ini ?</p>
													<small>Semua data yang terhubung dengan data ini akan ikut di hapus</small>

												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-close"></i> Tidak</button>
													<a href="bumil_hapus.php?id=<?php echo $d['bumil_id'] ?>" class="btn btn-success btn-sm"><i class="fa fa-check"></i> Hapus</a>
												</div>
											</div>
										</div>
									</div>

								</td>
							</tr>
						<?php
						}
						?>
					</tbody>
				</table>
			</div>

		</div>
	</div>

</div>

<?php include 'footer.php'; ?>