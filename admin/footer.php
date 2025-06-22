</div>
<nav class="p-3 bg-white border mt-5">
  <center>
    <small><?php echo date('Y') ?> - Prediksi & Pencegahan Resiko Ibu Hamil Berbasis Online.</small>
  </center>
</nav>

<script src="../assets/js/jquery.min.js"></script>
<script src="../assets/js/bootstrap.min.js"></script>
<script src="../assets/plugin/datatables/js/jquery.dataTables.min.js"></script>
<script src="../assets/plugin/datatables/js/dataTables.bootstrap4.min.js"></script>

<script src="../assets/plugin/chart.js/Chart.min.js"></script>


<script>
  $(document).ready(function() {

    $('#table-datatable').DataTable({
      'paging': true,
      'lengthChange': false,
      'searching': true,
      'ordering': false,
      'info': true,
      'autoWidth': true,
      "pageLength": 20
    });

  });
</script>

<script type="text/javascript">
  var randomScalingFactor = function() {
    return Math.round(Math.random() * 100)
  };

  var barChartData = {
    labels: [
      <?php
      $profesi = mysqli_query($koneksi, "SELECT * FROM profesi");
      while ($p = mysqli_fetch_array($profesi)) {
        echo "'" . $p['profesi'] . "',";
      }
      ?>
    ],
    datasets: [{
      label: 'Bumil',
      fillColor: "rgba(51, 240, 113, 0.61)",
      strokeColor: "rgba(11, 246, 88, 0.61)",
      highlightFill: "rgba(220,220,220,0.75)",
      highlightStroke: "rgba(220,220,220,1)",

      data: [
        <?php
        $profesi = mysqli_query($koneksi, "SELECT * FROM profesi");
        while ($p = mysqli_fetch_array($profesi)) {
          $id_profesi = $p['profesi_id'];
          $jumlah = mysqli_query($koneksi, "SELECT * FROM bumil WHERE bumil_profesi='$id_profesi'");
          echo "'" . mysqli_num_rows($jumlah) . "',";
        }
        ?>
      ]
    }]

  }






  window.onload = function() {
    var ctx = document.getElementById("grafik_profesi").getContext("2d");
    window.myBar = new Chart(ctx).Bar(barChartData, {
      responsive: true,
      animation: true,
      barValueSpacing: 5,
      barDatasetSpacing: 1,
      tooltipFillColor: "rgba(0,0,0,0.8)",
      multiTooltipTemplate: "<%= datasetLabel %> - Rp.<%= value.toLocaleString() %>,-"
    });

    <?php
    $pertanyaan = mysqli_query($koneksi, "SELECT * FROM pertanyaan");
    while ($pp = mysqli_fetch_array($pertanyaan)) {
    ?>
      var ctx = document.getElementById("grafik<?php echo $pp['pertanyaan_id'] ?>").getContext("2d");
      window.myPie = new Chart(ctx).Pie(pollingPieData_<?php echo $pp['pertanyaan_id']; ?>);
    <?php
    }
    ?>

  }


  <?php
  $pertanyaan = mysqli_query($koneksi, "SELECT * FROM pertanyaan");
  while ($pp = mysqli_fetch_array($pertanyaan)) {
    $id_pertanyaan = $pp['pertanyaan_id'];
  ?>
    var pollingPieData_<?php echo $pp['pertanyaan_id']; ?> = [

      <?php
      $jawaban = mysqli_query($koneksi, "SELECT * FROM jawaban WHERE jawaban_pertanyaan='$id_pertanyaan'");
      while ($j = mysqli_fetch_array($jawaban)) {
        $id_jawaban = $j['jawaban_id'];
        $jj = mysqli_query($koneksi, "SELECT * FROM polling WHERE polling_jawaban='$id_jawaban'");
        $jjj = mysqli_num_rows($jj);
      ?> {
          value: <?php echo $jjj ?>,
          color: "<?php echo stringToColorCode($id_jawaban); ?>",
          highlight: "<?php echo stringToColorCode($id_jawaban); ?>",
          label: "<?php echo $j['jawaban'] ?>"
        },
      <?php
      }
      ?>

    ];
  <?php
  }
  ?>
</script>

</body>

</html>