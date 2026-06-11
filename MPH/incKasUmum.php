<?php
$tahun_filter = $tahun_aktif;
if (!empty($_POST["tb_tahun"])) {
    $tahun_filter = intval($_POST["tb_tahun"]);
}

$sql_saldo = "SELECT s.tahun, s.saldo_awal, s.jumlah_penerimaan, s.jumlah_pengeluaran, s.saldo_akhir
              FROM v_saldo s
              WHERE s.tahun = $tahun_filter";
$view = new cView();
$arr_saldo = $view->vViewData($sql_saldo);
$saldo_row = !empty($arr_saldo)
    ? $arr_saldo[0]
    : [
        "saldo_awal" => 0,
        "jumlah_penerimaan" => 0,
        "jumlah_pengeluaran" => 0,
        "saldo_akhir" => 0,
    ];

$sql_bank =
    "SELECT b.id_bank, b.nama_rekening, b.no_rekening, b.nama_bank, b.jabatan, b.keterangan FROM bank b ORDER BY b.id_bank ASC";
$arr_bank = $view->vViewData($sql_bank);

$sql_tahun = "SELECT tahun FROM fiskal ORDER BY tahun ASC";
$arr_tahun = $view->vViewData($sql_tahun);
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <?php _myHeader("cash", "Kas Umum", "Laporan"); ?>
        </div>
    </div>

    <form action="" method="post" autocomplete="off">
        <div class="second" style="display:flex; justify-content:space-between; align-items:center; width:100%; color:#003153; font-weight:500;">
            <div>
                <label for="tb_tahun" style="white-space: nowrap; font-weight: bold; margin-bottom: 0;">Pilih Tahun : </label>&nbsp;
                <select name="tb_tahun" id="tb_tahun" style="border-radius:4px; border:1px solid #676892;">
                    <option value="">-- Pilih --</option>
                    <?php foreach ($arr_tahun as $row): ?>
                        <option value="<?= $row[
                            "tahun"
                        ] ?>" <?= $tahun_filter == $row["tahun"]
    ? "selected"
    : "" ?>>
                            <?= $row["tahun"] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                &nbsp;
                <button style="background-color:#49749C; color:white; border-radius:4px; border:none; padding:4px 16px;" name="filter-bttn" type="submit">Filter</button>
            </div>
        </div>
    </form>

    <br>
    <div class="sub-title">
        <p>POSISI KAS UMUM &mdash; TAHUN <?= $tahun_filter ?></p>
    </div>
    <br>

    <!-- Ringkasan Saldo -->
    <div class="row mb-4" style="gap:30px; padding-left:20px;">
        <div class="col-md-2" style="background-color:#E5F7FF; border-radius:12px; padding:16px;">
            <div style="color:#6F98C8; font-weight:700;">Saldo Awal</div>
            <div style="color:#3F3E65; font-weight:700; font-size:18px;">Rp <?= number_format(
                $saldo_row["saldo_awal"],
                0,
                ",",
                ".",
            ) ?></div>
        </div>
        <div class="col-md-2" style="background-color:#E5FFE9; border-radius:12px; padding:16px;">
            <div style="color:#3A7D44; font-weight:700;">Total Penerimaan</div>
            <div style="color:#1B5E20; font-weight:700; font-size:18px;">Rp <?= number_format(
                $saldo_row["jumlah_penerimaan"],
                0,
                ",",
                ".",
            ) ?></div>
        </div>
        <div class="col-md-2" style="background-color:#FFF3E5; border-radius:12px; padding:16px;">
            <div style="color:#B25C00; font-weight:700;">Total Pengeluaran</div>
            <div style="color:#7B3800; font-weight:700; font-size:18px;">Rp <?= number_format(
                $saldo_row["jumlah_pengeluaran"],
                0,
                ",",
                ".",
            ) ?></div>
        </div>
        <div class="col-md-2" style="background-color:#EDE7FF; border-radius:12px; padding:16px;">
            <div style="color:#4A2DAA; font-weight:700;">Saldo Akhir</div>
            <div style="color:#2A0D8C; font-weight:700; font-size:18px;">Rp <?= number_format(
                $saldo_row["saldo_akhir"],
                0,
                ",",
                ".",
            ) ?></div>
        </div>
    </div>

    <!-- Daftar Rekening Bank -->
    <div class="row">
        <div class="col-md-10">
            <div class="sub-title" style="font-size:16px; margin-bottom:10px; padding:8px;">Daftar Rekening Bank Gereja</div>
            <div class="table-responsive">
                <table class="table table-bordered table-condensed" style="border:1px #a9a9a9 solid;">
                    <thead>
                        <tr class="small">
                            <td width="5%" class="text-center">No.</td>
                            <td width="20%" class="text-center">Nama Rekening</td>
                            <td width="20%" class="text-center">No. Rekening</td>
                            <td width="20%" class="text-center">Nama Bank</td>
                            <td width="20%" class="text-center">Jabatan</td>
                            <td width="15%" class="text-center">Keterangan</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($arr_bank)): ?>
                            <?php foreach ($arr_bank as $i => $b): ?>
                                <tr>
                                    <td class="text-center"><?= $i + 1 ?></td>
                                    <td><?= htmlspecialchars(
                                        $b["nama_rekening"],
                                    ) ?></td>
                                    <td><?= htmlspecialchars(
                                        $b["no_rekening"],
                                    ) ?></td>
                                    <td><?= htmlspecialchars(
                                        $b["nama_bank"],
                                    ) ?></td>
                                    <td><?= htmlspecialchars(
                                        $b["jabatan"],
                                    ) ?></td>
                                    <td><?= htmlspecialchars(
                                        $b["keterangan"] ?? "-",
                                    ) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="6" class="text-center">Belum ada data rekening bank.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
