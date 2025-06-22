<?php include 'header.php'; ?>

<div class="container">

	<center>
		<h5>GRAFIK PROFESI BERDASARKAN RESPONDEN</h5>
	</center>
	<br>
	<div class="card mb-4">
		<div class="card-body">

			<br>
			<div class="chart-container" style="position: relative; height:auto; width:100%">
				<canvas id="grafik_profesi"></canvas>
			</div>

		</div>
	</div>

	<!-- !-- Tambahkan elemen canvas untuk grafik risiko -->
	<div class="row mt-4">
		<div class="col-lg-12">
			<h5>Grafik Kategori Risiko Ibu Hamil</h5>
			<canvas id="grafik_risiko" width="400" height="200"></canvas>
		</div>
	</div>
	<!-- Selesai elemen canvas untuk grafik risiko -->

	<br>
	<center>
		<h5>Grafik Polling</h5>
	</center>
	<br>
	<div class="row">
		<?php

		function stringToColorCode($str)
		{
			$code = dechex(crc32($str));
			$code = substr($code, 0, 6);
			return "#" . $code;
		}

		$pertanyaan = mysqli_query($koneksi, "SELECT * FROM pertanyaan");
		while ($p = mysqli_fetch_array($pertanyaan)) {
		?>

			<div class="col-lg-4">

				<center>
					<div class="card mb-4">
						<div class="card-body">

							<h6><?php echo $p['pertanyaan'] ?></h6>
							<br>
							<div class="chart-container" style="position: relative; height:auto; width:100%">
								<canvas id="grafik<?php echo $p['pertanyaan_id']; ?>"></canvas>
							</div>


							<?php
							$id_pertanyaan = $p['pertanyaan_id'];
							$jawaban = mysqli_query($koneksi, "SELECT * FROM jawaban WHERE jawaban_pertanyaan='$id_pertanyaan'");
							while ($j = mysqli_fetch_array($jawaban)) {
								$id_jawaban = $j['jawaban_id'];
								$jj = mysqli_query($koneksi, "SELECT * FROM polling WHERE polling_jawaban='$id_jawaban'");
								$jjj = mysqli_num_rows($jj);
								echo "<small class='badge badge-primary' style='background:" . stringToColorCode($id_jawaban) . "'>" . $j['jawaban'] . ' = ' . $jjj . "</small>";
							?>
							<?php
							}
							?>


						</div>
					</div>
				</center>

			</div>

		<?php
		}
		?>
	</div>

</div>


<?php include 'footer.php'; ?>