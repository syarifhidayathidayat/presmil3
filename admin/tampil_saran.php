<?php include 'header.php'; ?>
<div class="container-fluid">
    <div class="mb-4">
        <h4>Data Responden & Kuesioner</h4>
        <small>Responden yang sudah mendaftar & mengisi kuesioner</small>
    </div>
    <div class="row mb-3">
        <div class="col-lg-12">
            <a class="btn btn-primary btn-sm" target="_blank" href="print.php">
                <i class="fa fa-print"></i> &nbsp Print
            </a>
            <a class="btn btn-primary btn-sm" href="saran-excel.php">
                <i class="fa fa-print"></i> &nbsp Export Saran
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
                            <th>Nama</th>
                            <th>No.Telp</th>
                            <th>Saran</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $data = mysqli_query($koneksi, "SELECT * FROM bumil,profesi WHERE profesi=profesi_id");
                        while ($d = mysqli_fetch_array($data)) {
                        ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $d['bumil_nama']; ?></td>
                                <td><?php echo $d['telp']; ?></td>
                                <td><?php echo $d['saran']; ?></td>
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