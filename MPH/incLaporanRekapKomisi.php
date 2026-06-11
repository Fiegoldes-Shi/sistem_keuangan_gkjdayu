<?php
if (isset($_SESSION["tahun_aktif"])) {
    $tahun_aktif = $_SESSION["tahun_aktif"];
}

if (empty($_POST["tb_tahun"])) {
    $_POST["tb_tahun"] = 0;
    unset($_SESSION["tahun"]);
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <?php _myHeader("document", "Rekapitulasi Komisi", "Laporan"); ?>
        </div>
    </div>


    
        <div class="second section filter-section" style="display: flex; justify-content: space-between; align-items: center; width: 100%; color:#003153; font-weight:500; padding: 20px; margin-bottom: 20px; box-sizing: border-box; flex-wrap: wrap; gap: 15px;">
            <div class="" style="width:60%">
                <form action="" method="post" style="margin: 0; display: flex; align-items: center; gap: 10px;">
                <div style="display: flex; align-items: center;">
                    <label for="tahun" style="white-space: nowrap; font-weight: bold; margin: 0; line-height: 1;">Pilih Tahun : </label> &nbsp;
                    <select name="tb_tahun" id="tb_tahun" class="form-select" style="width: 150px; margin: 0; padding: 6px 12px; height: 38px;">
                        <option value="">-- Pilih --</option>
                        <?php
                        $sql = "SELECT tahun FROM fiskal ORDER BY tahun ASC";
                        $result = $GLOBALS["conn"]->query($sql);
                        while ($row = $result->fetch_assoc()) {
                            $selected_tahun =
                                isset($_POST["tb_tahun"]) &&
                                $_POST["tb_tahun"] == $row["tahun"]
                                    ? "selected"
                                    : "";
                            echo "<option value='" .
                                $row["tahun"] .
                                "' $selected_tahun>" .
                                $row["tahun"] .
                                "</option>";
                        }
                        ?>
                    </select>
                    &nbsp;
                    <button style="background-color: #49749C; color:white; border-radius:4px; border:none; padding: 0 20px; height: 38px; cursor: pointer; margin: 0;" name="filter-bttn" type="submit">Filter</button>
                </div>
                </form>
            </div>

    </form>


    <div style="display: flex; justify-content: flex-end; align-items: center; gap: 10px; flex-wrap: wrap; flex: 1;">

        <a href="../cetak/xlxCetakRekapKomisi.php" target="" style="background-color:#2e8b57; color:white; padding: 8px 15px; border-radius:4px; text-decoration: none; white-space: nowrap; font-weight: bold;">Cetak Excel</a>
        <a href="../cetak/pdfCetakRekapKomisi.php" target="_new" style="background-color:#dc143c; color:white; padding: 8px 15px; border-radius:4px; text-decoration: none; white-space: nowrap; font-weight: bold;">Cetak PDF</a>
    </div>


</div>
<br>
<div class="sub-title" style=" justify-content: space-between; align-items: center; ">
    <p>REKAPITULASI KOMISI

        <?php
        if (!isset($_POST["tb_tahun"])) {
            echo "<span>TAHUN $tahun_aktif</span>";
        }
        if (isset($_POST["tb_tahun"])) {
            if ($_POST["tb_tahun"] == 0) {
                echo "<span>TAHUN $tahun_aktif</span>";
            } else {
                $waktu = $_POST["tb_tahun"];
                echo "<span>TAHUN $waktu</span>";
            }
        }
        ?>
    </p>
</div>

<br>

<div class="row">
    <div class="col-md-12">
        <?php
        $sql = "SELECT * FROM _saldo_komisi where tahun = $tahun_aktif";

        if (isset($_POST["tb_tahun"])) {
            if ($_POST["tb_tahun"] == 0) {
                $sql .= " ";
            } else {
                $sql =
                    "SELECT * FROM _saldo_komisi where tahun = " .
                    $_POST["tb_tahun"];
            }
        }

        $view = new cView();
        $array = $view->vViewData($sql);
        ?>

        <div id="" class='table-responsive'>
            <table id='' class='table table-condensed table-bordered w-100'>
                <thead>
                    <tr class='small'>
                        <td width='3%' class="text-right">No</td>
                        <td width=''>Bidang</td>
                        <td width=''>Komisi</td>
                        <td class="text-end">Saldo Awal</td>
                        <td class="text-end" width=''>Jumlah Penerimaan</td>
                        <td class="text-end" width=''>Jumlah Pengeluaran</td>
                        <td class="text-end" width=''>Saldo Akhir</td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $cnourut = 0;
                    $total_saldoAwal = 0;
                    $total_penerimaan = 0;
                    $total_pengeluaran = 0;
                    $total_saldoAkhir = 0;

                    foreach ($array as $data) {

                        $cnourut = $cnourut + 1;
                        $total_saldoAwal += $data["saldo_awal"];
                        $total_penerimaan += $data["jumlah_penerimaan"];
                        $total_pengeluaran += $data["jumlah_pengeluaran"];
                        $total_saldoAkhir += $data["saldo_akhir"];
                        ?>
                        <tr class=''>
                            <td class="text-right"><?= $cnourut ?></td>
                            <td><?= $data["nama_bidang"] ?></td>
                            <td><?= $data["nama_komisi"] ?></td>
                            <td class="text-end"><?= number_format(
                                $data["saldo_awal"],
                                0,
                                ",",
                                ".",
                            ) ?></td>
                            <td class="text-end"><?= number_format(
                                $data["jumlah_penerimaan"],
                                0,
                                ",",
                                ".",
                            ) ?></td>
                            <td class="text-end"><?= number_format(
                                $data["jumlah_pengeluaran"],
                                0,
                                ",",
                                ".",
                            ) ?></td>
                            <td class="text-end"><?= number_format(
                                $data["saldo_akhir"],
                                0,
                                ",",
                                ".",
                            ) ?></td>
                          
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
                <tr>
                    <td></td>
                    <td colspan="2" style="color:#5B90CD; font-weight:bolder">T O T A L</td>
                    <td class="text-end" style="color:#5B90CD; font-weight:bolder"> <?= number_format(
                        $total_saldoAwal,
                        0,
                        ",",
                        ".",
                    ) ?></td>
                    <td class="text-end" style="color:#5B90CD; font-weight:bolder"> <?= number_format(
                        $total_penerimaan,
                        0,
                        ",",
                        ".",
                    ) ?></td>
                    <td class="text-end" style="color:#5B90CD; font-weight:bolder"> <?= number_format(
                        $total_pengeluaran,
                        0,
                        ",",
                        ".",
                    ) ?></td>
                    <td class="text-end" style="color:#5B90CD; font-weight:bolder"> <?= number_format(
                        $total_saldoAkhir,
                        0,
                        ",",
                        ".",
                    ) ?></td>
                </tr>

            </table>
        </div>
    </div>


</div>
</div>

<script>
    $(document).ready(function() {
        function handleChange(selector, type) {
            $(selector).change(function() {
                var value = $(this).val();

                $.ajax({
                    url: "../_function_i/ambilData.php",
                    type: "POST",
                    data: {
                        [type]: value
                    },
                    success: function(response) {
                    },
                    error: function(xhr, status, error) {
                        console.error("Error untuk " + type + ": " + error);
                    },
                });
            });
        }
        // Tangani perubahan untuk tb_bulan dan tb_tahun
        handleChange("#tb_tahun", "tahun");
    });
</script>