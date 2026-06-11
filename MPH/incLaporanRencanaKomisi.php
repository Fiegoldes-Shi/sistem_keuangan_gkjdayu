<?php

if (isset($_SESSION["tahun_aktif"])) {
    $tahun_aktif = $_SESSION["tahun_aktif"];

    $sql = "SELECT id_fiskal FROM fiskal WHERE tahun = $tahun_aktif";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id_fiskal = $row["id_fiskal"];
    }
}

if (empty($_POST["tb_tahun"])) {
    $_POST["tb_tahun"] = 0;
    unset($_SESSION["tahun"]);
}
if (empty($_POST["tb_bulan"])) {
    $_POST["tb_bulan"] = 0;
    unset($_SESSION["bulan"]);
} else {
    $bulan = $_POST["tb_bulan"];
}

if (empty($_POST["komisi"])) {
    $_POST["komisi"] = 0;
    unset($_SESSION["komisi_" . $link]);
} else {
    $id_komisi = $_POST["komisi"];
    $_SESSION["komisi_" . $link] = $id_komisi;
    $sql = "SELECT id_komisi, nama_komisi FROM komisi WHERE id_komisi = $id_komisi";
    $result = $conn->query($sql);
    if ($row = $result->fetch_assoc()) {
        $komisi = $row["nama_komisi"];
    }
}

if (empty($_POST["bidang"])) {
    $_POST["bidang"] = 0;
    unset($_SESSION["bidang_" . $link]);
} else {
    $id_bidang = $_POST["bidang"];
    $_SESSION["bidang_" . $link] = $id_bidang;
    $sql = "SELECT id_bidang, nama_bidang FROM bidang WHERE id_bidang = $id_bidang";
    $result = $conn->query($sql);
    if ($row = $result->fetch_assoc()) {
        $bidang = $row["nama_bidang"];
    }
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <?php _myHeader("document", "Laporan Rencana Komisi", "Laporan"); ?>
        </div>
    </div>

    <div class="section filter-section" style="padding: 20px; margin-bottom: 20px; width: 100%; box-sizing: border-box;">
        <form action="" method="post" autocomplete="off" style="margin: 0;">
            <div class="horizontal filter-horizontal" style="display: flex; align-items: center; justify-content: flex-start; gap: 15px; flex-wrap: wrap;">
                <div class="form-group1" style="display: flex; align-items: center; gap: 10px;">
                    <label for="bidang" class="required" style="white-space: nowrap; font-weight: bold; margin: 0; line-height: 1;">Bidang</label>
                    <select class="form-select" style="width: 350px; margin: 0; padding: 6px 12px; height: 38px;" id="bidang" name="bidang" required>
                        <option value="">-- Pilih Bidang --</option>
                        <?php
                        $sql = "SELECT id_bidang, nama_bidang FROM bidang";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $selected_bidang =
                                    isset($_SESSION["bidang_" . $link]) &&
                                    $_SESSION["bidang_" . $link] ==
                                        $row["id_bidang"]
                                        ? "selected"
                                        : "";
                                echo "<option value='" .
                                    $row["id_bidang"] .
                                    "' $selected_bidang>" .
                                    $row["nama_bidang"] .
                                    "</option>";
                            }
                        } else {
                            echo "<option value=''>Data tidak tersedia</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group1" style="display: flex; align-items: center; gap: 10px;">
                    <label for="komisi" style="white-space: nowrap; font-weight: bold; margin: 0; line-height: 1;">Komisi</label>
                    <select class="form-select" style="width: 350px; margin: 0; padding: 6px 12px; height: 38px;" name="komisi" id="komisi">
                        <option value="">-- Pilih Komisi --</option>
                        <?php
                        $query = "SELECT id_komisi, nama_komisi FROM komisi";
                        $result = mysqli_query($conn, $query);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $selected_komisi =
                                    isset($_SESSION["komisi_" . $link]) &&
                                    $_SESSION["komisi_" . $link] ==
                                        $row["id_komisi"]
                                        ? "selected"
                                        : "";
                                echo "<option value='" .
                                    $row["id_komisi"] .
                                    "' $selected_komisi>" .
                                    $row["nama_komisi"] .
                                    "</option>";
                            }
                        } else {
                            echo "<option value=''>Data tidak tersedia</option>";
                        }
                        ?>
                    </select>
                </div>
                <button name="filterKomisi" style="background-color: #49749C; color:white; border-radius:4px; border:none; padding: 0 20px; height: 38px; cursor: pointer; white-space: nowrap; margin: 0;" type="submit">Pilih</button>
            </div>
        </form>
    </div>

    <br>

    <?php  ?>

    
        <div class="second section filter-section" style="display: flex; justify-content: space-between; align-items: center; width: 100%; color:#003153; font-weight:500; padding: 20px; margin-bottom: 20px; box-sizing: border-box; flex-wrap: wrap; gap: 15px;">
            <div>
                <form action="" method="post" style="margin: 0; display: flex; align-items: center; gap: 10px;">
                <input type="hidden" name="bidang" value="<?= $_SESSION[
                    "bidang_" . $link
                ] ?? "" ?>">
                <input type="hidden" name="komisi" value="<?= $_SESSION[
                    "komisi_" . $link
                ] ?? "" ?>">

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
                </form>
            </div>
            <div style="display: flex; justify-content: center; align-items: center; gap: 10px; flex-wrap: wrap;">

        <a href="../cetak/xlxCetakRencanaKomisi.php" target="" style="background-color:#2e8b57; color:white; padding: 8px 15px; border-radius:4px; text-decoration: none; white-space: nowrap; font-weight: bold;">Cetak Excel</a>
        <a href="../cetak/pdfCetakRencanaKomisi.php" target="_new" style="background-color:#dc143c; color:white; padding: 8px 15px; border-radius:4px; text-decoration: none; white-space: nowrap; font-weight: bold;">Cetak PDF</a>
    </div>
</div>
</div>
<br>
<div class="sub-title" style=" justify-content: space-between; align-items: center; ">

    <?php
    $nama_bulan = [
        1 => "JANUARI",
        2 => "FEBRUARI",
        3 => "MARET",
        4 => "APRIL",
        5 => "MEI",
        6 => "JUNI",
        7 => "JULI",
        8 => "AGUSTUS",
        9 => "SEPTEMBER",
        10 => "OKTOBER",
        11 => "NOVEMBER",
        12 => "DESEMBER",
    ];

    if (isset($_SESSION["komisi_" . $link])) {
        if (isset($_POST["tb_tahun"])) {
            if ($_POST["tb_tahun"] == 0) {
                echo "<p>LAPORAN RENCANA " .
                    strtoupper($komisi) .
                    "  TAHUN $tahun_aktif</p>";
            } else {
                $waktu = $_POST["tb_tahun"];
                echo "<p>LAPORAN RENCANA " .
                    strtoupper($komisi) .
                    "  TAHUN $waktu</p>";
            }
        }
    } elseif (isset($_SESSION["bidang_" . $link])) {
        if (isset($_POST["tb_tahun"])) {
            if ($_POST["tb_tahun"] == 0) {
                echo "<p>LAPORAN RENCANA " .
                    strtoupper($bidang) .
                    "  TAHUN $tahun_aktif</p>";
            } else {
                $waktu = $_POST["tb_tahun"];
                echo "<p>LAPORAN RENCANA " .
                    strtoupper($bidang) .
                    "  TAHUN $waktu</p>";
            }
        }
    } else {
        if (isset($_POST["tb_tahun"])) {
            if ($_POST["tb_tahun"] == 0) {
                echo "<p>LAPORAN RENCANA KOMISI TAHUN $tahun_aktif</p>";
            } else {
                $waktu = $_POST["tb_tahun"];
                echo "<p>LAPORAN RENCANA KOMISI TAHUN $waktu</p>";
            }
        }
    }
    ?>

</div>
<br>

<div class="row">
    <div class="col-md-12">
        <?php if (
            isset($_SESSION["komisi_" . $link]) ||
            isset($_SESSION["bidang_" . $link])
        ) {

            if (isset($_SESSION["komisi_" . $link])) {
                $sql =
                    "SELECT * from v_rencanakomisi WHERE id_fiskal = $id_fiskal AND id_komisi =" .
                    $_POST["komisi"] .
                    "";
                if (isset($_POST["tb_tahun"])) {
                    if ($_POST["tb_tahun"] == 0) {
                        $sql .= " ";
                    } else {
                        $sql =
                            "SELECT * from v_rencanakomisi WHERE id_komisi =" .
                            $_POST["komisi"];
                        $sql .= " AND tahun = " . $_POST["tb_tahun"];
                    }
                }
            } elseif (isset($_SESSION["bidang_" . $link])) {
                $sql =
                    "SELECT * from v_rencanakomisi WHERE id_fiskal = $id_fiskal AND id_bidang =" .
                    $_POST["bidang"] .
                    "";
                if (isset($_POST["tb_tahun"])) {
                    if ($_POST["tb_tahun"] == 0) {
                        $sql .= " ";
                    } else {
                        $sql =
                            "SELECT * from v_rencanakomisi WHERE id_bidang =" .
                            $_POST["bidang"];
                        $sql .= " AND tahun = " . $_POST["tb_tahun"];
                    }
                }
            }

            $sql .= " ORDER BY jenis ASC, id ASC";

            $view = new cView();
            $array = $view->vViewData($sql);
            ?>

            <div id="" class='table-responsive'>
                <table id='' class='table table-condensed w-100'>
                    <thead>
                        <tr class='small'>
                            <td width='2%' class="text-right">No</td>
                            <td width='30%'>Jenis Kegiatan</td>
                            <td width='' class="text-center">Vol</td>
                            <td width='10%' class="text-end">Satuan</td>
                            <td width='' class="text-end">Jumlah</td>
                            <td width='' class="text-end">Dana Gereja</td>
                            <td width='' class="text-end">Dana Swadaya</td>
                            <td width='' class="text-end">Subtotal</td>
                            <td width=''></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $cnourut = 0;
                        $groupedData = [];

                        foreach ($array as $data) {
                            $bidang = $data["nama_bidang"];
                            $komisi = $data["nama_komisi"];
                            $jenis = $data["jenis"]; // Realisasi Penerimaan atau Pengeluaran
                            $program = $data["nama_program"];

                            $groupedData[$bidang][$komisi][$jenis][
                                $program
                            ][] = $data;
                        }

                        foreach ($groupedData as $bidang => $bidangList) { ?>
                            <tr style="font-weight: bold; background-color: #dcdcdc;">

                            </tr>

                            <?php foreach (
                                $bidangList
                                as $komisi => $komisiList
                            ) { ?>
                                <tr style="font-weight: bold; background-color: #d3d3d3">
                                    <td colspan="10" style="font-weight: bold; background-color: #f5f5f5"><?= $bidang .
                                        " - " .
                                        $komisi ?></td>
                                </tr>

                                <?php foreach (
                                    $komisiList
                                    as $jenis => $jenisList
                                ) { ?>
                                    <tr>
                                        <td colspan="10" style="font-weight: bold; background-color: #bcd4e6;"><?= $jenis ?></td>
                                    </tr>

                                    <?php
                                    $total1 = 0;
                                    $total2 = 0;
                                    $total3 = 0;
                                    $total4 = 0;

                                    foreach (
                                        $jenisList
                                        as $program => $programList
                                    ) { ?>
                                        <tr>
                                            <td></td>
                                            <td colspan="9" style="font-weight: bold; background-color: #f2f3f4;">Program: <?= $program ?></td>
                                        </tr>

                                        <?php
                                        $total = 0;
                                        $total_danaGereja = 0;
                                        $total_danaSwadaya = 0;
                                        $subtotal = 0;
                                        $cnourut = 0;

                                        foreach ($programList as $data) {

                                            $cnourut++;
                                            $total += $data["jumlah"];
                                            $total_danaGereja +=
                                                $data["dana_gereja"];
                                            $total_danaSwadaya +=
                                                $data["dana_swadaya"];
                                            $subtotal +=
                                                $data["dana_gereja"] +
                                                $data["dana_swadaya"];
                                            ?>

                                            <tr>
                                                <td><?= $cnourut ?></td>
                                                <td><?= $data[
                                                    "jenis_kegiatan"
                                                ] ?></td>
                                                <td class="text-center"><?= $data[
                                                    "volume"
                                                ] ?></td>
                                                <td class="text-end"><?= number_format(
                                                    $data["harga_satuan"],
                                                    0,
                                                    ",",
                                                    ".",
                                                ) ?>/<?= $data["satuan"] ?></td>
                                                <td class="text-end"><?= number_format(
                                                    $data["jumlah"],
                                                    0,
                                                    ",",
                                                    ".",
                                                ) ?></td>
                                                <td class="text-end"><?= number_format(
                                                    $data["dana_gereja"],
                                                    0,
                                                    ",",
                                                    ".",
                                                ) ?></td>
                                                <td class="text-end"><?= number_format(
                                                    $data["dana_swadaya"],
                                                    0,
                                                    ",",
                                                    ".",
                                                ) ?></td>
                                                <td class="text-end"><?= number_format(
                                                    $data["dana_gereja"] +
                                                        $data["dana_swadaya"],
                                                    0,
                                                    ",",
                                                    ".",
                                                ) ?></td>
                                                <td></td>
                                            </tr>
                                        <?php
                                        }

                                        // Tampilkan subtotal untuk program
                                        ?>
                                        <tr style="font-weight: bold; background-color: #d9edf7;">
                                            <td></td>

                                            <td>Total</td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-end"><?= number_format(
                                                $total,
                                                0,
                                                ",",
                                                ".",
                                            ) ?></td>
                                            <td class="text-end"><?= number_format(
                                                $total_danaGereja,
                                                0,
                                                ",",
                                                ".",
                                            ) ?></td>
                                            <td class="text-end"><?= number_format(
                                                $total_danaSwadaya,
                                                0,
                                                ",",
                                                ".",
                                            ) ?></td>
                                            <td class="text-end"><?= number_format(
                                                $subtotal,
                                                0,
                                                ",",
                                                ".",
                                            ) ?></td>
                                            <td></td>
                                        </tr>
                                    <?php
                                    $total1 += $total;
                                    $total2 += $total_danaGereja;
                                    $total3 += $total_danaSwadaya;
                                    $total4 += $subtotal;
                                    }
                                    ?>
                                    <tr style="font-weight: bold; background-color: #d9edf7;">
                                        <td></td>

                                        <td style="color: #1C0F80">Total <?= $jenis ?></td>
                                        <td style="color: #1C0F80"></td>
                                        <td style="color: #1C0F80"></td>
                                        <td style="color: #1C0F80" class="text-end"><?= number_format(
                                            $total1,
                                            0,
                                            ",",
                                            ".",
                                        ) ?></td>
                                        <td style="color: #1C0F80" class="text-end"><?= number_format(
                                            $total2,
                                            0,
                                            ",",
                                            ".",
                                        ) ?></td>
                                        <td style="color: #1C0F80" class="text-end"><?= number_format(
                                            $total3,
                                            0,
                                            ",",
                                            ".",
                                        ) ?></td>
                                        <td style="color: #1C0F80" class="text-end"><?= number_format(
                                            $total4,
                                            0,
                                            ",",
                                            ".",
                                        ) ?></td>
                                        <td style="color: #1C0F80"></td>
                                    </tr>

                                    <tr>
                                        <td colspan="9"></td>
                                    </tr>
                        <?php }}}
                        ?>


                    </tbody>
                </table>
            </div>

        <?php
        } else {

            $sql = "SELECT
                    b.nama_bidang,
                    COALESCE(k.nama_komisi, '-') AS nama_komisi,
                    SUM(CASE WHEN v.jenis COLLATE utf8mb4_unicode_ci = 'Rencana Penerimaan' THEN v.dana_gereja ELSE 0 END) AS penerimaan_dana_gereja,
                    SUM(CASE WHEN v.jenis COLLATE utf8mb4_unicode_ci = 'Rencana Penerimaan' THEN v.dana_swadaya ELSE 0 END) AS penerimaan_dana_swadaya,
                    SUM(CASE WHEN v.jenis COLLATE utf8mb4_unicode_ci = 'Rencana Penerimaan' THEN v.jumlah ELSE 0 END) AS jumlah_penerimaan,
                    SUM(CASE WHEN v.jenis COLLATE utf8mb4_unicode_ci = 'Rencana Pengeluaran' THEN v.dana_gereja ELSE 0 END) AS pengeluaran_dana_gereja,
                    SUM(CASE WHEN v.jenis COLLATE utf8mb4_unicode_ci = 'Rencana Pengeluaran' THEN v.dana_swadaya ELSE 0 END) AS pengeluaran_dana_swadaya,
                    SUM(CASE WHEN v.jenis COLLATE utf8mb4_unicode_ci = 'Rencana Pengeluaran' THEN v.jumlah ELSE 0 END) AS jumlah_pengeluaran
             FROM bidang b
LEFT JOIN komisi k ON k.id_bidang = b.id_bidang
 LEFT JOIN v_rencanakomisi v 
                            ON v.id_bidang = b.id_bidang 
                             AND (
                            (v.id_komisi IS NULL AND (k.id_komisi IS NULL OR k.id_komisi = 0)) 
                            OR v.id_komisi = k.id_komisi)
    AND v.tahun = $tahun_aktif";

            //filter query
            if (isset($_POST["tb_tahun"])) {
                if ($_POST["tb_tahun"] == 0) {
                    $sql .= " ";
                } else {
                    $sql =
                        "SELECT
                             b.nama_bidang, k.nama_komisi,
                            SUM(CASE WHEN jenis COLLATE utf8mb4_unicode_ci = 'Rencana Penerimaan' THEN dana_gereja ELSE 0 END) AS penerimaan_dana_gereja,
                            SUM(CASE WHEN jenis COLLATE utf8mb4_unicode_ci = 'Rencana Penerimaan' THEN dana_swadaya ELSE 0 END) AS penerimaan_dana_swadaya,
                            SUM(CASE WHEN jenis COLLATE utf8mb4_unicode_ci = 'Rencana Penerimaan' THEN jumlah ELSE 0 END) AS jumlah_penerimaan,
                            SUM(CASE WHEN jenis COLLATE utf8mb4_unicode_ci = 'Rencana Pengeluaran' THEN dana_gereja ELSE 0 END) AS pengeluaran_dana_gereja,
                            SUM(CASE WHEN jenis COLLATE utf8mb4_unicode_ci = 'Rencana Pengeluaran' THEN dana_swadaya ELSE 0 END) AS pengeluaran_dana_swadaya,
                            SUM(CASE WHEN jenis COLLATE utf8mb4_unicode_ci = 'Rencana Pengeluaran' THEN jumlah ELSE 0 END) AS jumlah_pengeluaran
                            FROM bidang b
                            LEFT JOIN komisi k ON k.id_bidang = b.id_bidang
                            LEFT JOIN v_rencanakomisi v 
                            ON v.id_bidang = b.id_bidang 
                             AND (
                            (v.id_komisi IS NULL AND (k.id_komisi IS NULL OR k.id_komisi = 0)) 
                            OR v.id_komisi = k.id_komisi)
                            AND v.tahun = " .
                        $_POST["tb_tahun"] .
                        "";
                }
            }

            $sql .=
                "  GROUP BY b.id_bidang, k.id_komisi ORDER BY b.id_bidang, k.id_komisi";
            $view = new cView();
            $array = $view->vViewData($sql);
            ?>

            <div id="" class='table-responsive'>
                <table id="" class="table table-bordered table-condensed w-100">
                    <thead>
                        <tr class='small'>
                            <td rowspan="2" width='2%' class="text-center">No</td>
                            <td rowspan="2" width='' class="text-center">Bidang</td>
                            <td rowspan='2' class="text-center">Komisi</td>
                            <td colspan="3" width='' class="text-center">Rencana Penerimaan</td>
                            <td colspan="3" width='' class="text-center">Rencana Pengeluaran</td>
                        </tr>
                        <tr class='small'>
                            <td class="text-end">Dana Gereja</td>
                            <td class="text-end">Dana Swadaya</td>
                            <td class="text-end">SubTotal</td>
                            <td class="text-end">Dana Gereja</td>
                            <td class="text-end">Dana Swadaya</td>
                            <td class="text-end">Subtotal</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $cnourut = 0;
                        $penerimaan_danaGereja = 0;
                        $penerimaan_danaSwadaya = 0;
                        $penerimaan_subtotal = 0;
                        $pengeluaran_danaGereja = 0;
                        $pengeluaran_danaSwadaya = 0;
                        $pengeluaran_subtotal = 0;

                        foreach ($array as $data) {
                            $cnourut++; ?>
                            <tr>
                                <td><?= $cnourut ?></td>
                                <td><?= $data["nama_bidang"] ?></td>
                                <td><?= $data["nama_komisi"] ?></td>
                                <td class="text-end"><?= number_format(
                                    $data["penerimaan_dana_gereja"],
                                    0,
                                    ",",
                                    ".",
                                ) ?></td>
                                <td class="text-end"> <?= number_format(
                                    $data["penerimaan_dana_swadaya"],
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
                                    $data["pengeluaran_dana_gereja"],
                                    0,
                                    ",",
                                    ".",
                                ) ?></td>
                                <td class="text-end"><?= number_format(
                                    $data["pengeluaran_dana_swadaya"],
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
                            </tr>
                        <?php
                        $penerimaan_danaGereja +=
                            $data["penerimaan_dana_gereja"];
                        $penerimaan_danaSwadaya +=
                            $data["penerimaan_dana_swadaya"];
                        $penerimaan_subtotal += $data["jumlah_penerimaan"];
                        $pengeluaran_danaGereja +=
                            $data["pengeluaran_dana_gereja"];
                        $pengeluaran_danaSwadaya +=
                            $data["pengeluaran_dana_swadaya"];
                        $pengeluaran_subtotal += $data["jumlah_pengeluaran"];

                        }
                        ?>
                    </tbody>
                    <tr class=''>
                        <td></td>
                        <td colspan="2">Total</td>
                        <td class="text-end" style="color:#5B90CD; font-weight:bolder"><?= number_format(
                            $penerimaan_danaGereja,
                            0,
                            ",",
                            ".",
                        ) ?> </td>
                        <td class="text-end" style="color:#5B90CD; font-weight:bolder"><?= number_format(
                            $penerimaan_danaSwadaya,
                            0,
                            ",",
                            ".",
                        ) ?> </td>
                        <td class="text-end" style="color:#5B90CD; font-weight:bolder"><?= number_format(
                            $penerimaan_subtotal,
                            0,
                            ",",
                            ".",
                        ) ?> </td>
                        <td class="text-end" style="color:#5B90CD; font-weight:bolder"><?= number_format(
                            $pengeluaran_danaGereja,
                            0,
                            ",",
                            ".",
                        ) ?> </td>
                        <td class="text-end" style="color:#5B90CD; font-weight:bolder"><?= number_format(
                            $pengeluaran_danaSwadaya,
                            0,
                            ",",
                            ".",
                        ) ?> </td>
                        <td class="text-end" style="color:#5B90CD; font-weight:bolder"><?= number_format(
                            $pengeluaran_subtotal,
                            0,
                            ",",
                            ".",
                        ) ?> </td>
                    </tr>
                </table>
            </div>
        <?php
        } ?>
    </div>
</div>
</div>

<script>
    $("#bidang").change(function() {
        var id_bidang = $("#bidang").val();
        $.ajax({
            type: "POST",
            dataType: "html",
            url: "../_function_i/ambilData.php",
            data: "bidang=" + id_bidang,
            success: function(data) {
                $("#komisi").html(data);
            },
        });
    });

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

        handleChange("#tb_bulan", "bulan");
        handleChange("#tb_tahun", "tahun");
        handleChange("#bidang", "bidang");
        handleChange("#komisi", "komisi");
    });
</script>