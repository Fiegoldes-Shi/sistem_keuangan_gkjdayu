<?php

$protocol =
    !empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off" ? "https" : "http";
$host = $_SERVER["HTTP_HOST"];
$uploadPath = "/uploads/bukti_transfer/";

// update
if (!empty($_POST["editbtn"])) {
    $targetDir = "../uploads/bukti_transfer/";

    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $bukti_pengeluaranFileName = null;

    if (!empty($_FILES["bukti_pengeluaran"]["name"])) {
        $bukti_pengeluaranFileName =
            time() . "_" . basename($_FILES["bukti_pengeluaran"]["name"]);
        $targetFilePath = $targetDir . $bukti_pengeluaranFileName;

        if ($_FILES["bukti_pengeluaran"]["error"] === UPLOAD_ERR_OK) {
            if (
                move_uploaded_file(
                    $_FILES["bukti_pengeluaran"]["tmp_name"],
                    $targetFilePath,
                )
            ) {
            } else {
                $bukti_pengeluaranFileName = null;
            }
        } else {
            $bukti_pengeluaranFileName = null;
        }
    }

    $datafield = [
        "id_fiskal",
        "id_akun",
        "tanggal_pengeluaran",
        "bukti_pengeluaran",
        "keterangan",
        "jenis_pengeluaran",
        "jumlah",
    ];
    $datavalue = [
        $id_fiskal,
        $_POST["id_akun"],
        $_POST["tanggal_pengeluaran"],
        $bukti_pengeluaranFileName,
        $_POST["keterangan"],
        $_POST["jenis_pengeluaran"],
        $_POST["jumlah"],
    ];

    $datakey = " id_pengeluaran =" . $_POST["id_pengeluaran"];

    $update = new cUpdate();
    $update->fUpdateData(
        $datafield,
        "realisasi_pengeluaran_gereja",
        $datavalue,
        $datakey,
        "",
    );
}

// delete
if (!empty($_POST["btnhapus"])) {
    $delete = new cDelete();
    $delete->_dDeleteData(
        $_POST["hiddendeletevalue0"],
        $_POST["hiddendeletevalue1"],
        $_POST["hiddendeletevalue2"],
    );
}

if (empty($_POST["tb_bulan"])) {
    $_POST["tb_bulan"] = 0;
} else {
    $bulan = $_POST["tb_bulan"];
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <?php _myHeader(
                "newspaper",
                "Pengeluaran Gereja",
                "Data pengeluaran",
            ); ?>
        </div>
    </div>
    <div class="row" style="width:12%">
        <?php if ($status_aktif_fiskal == 1): ?>
            <a href="281">
                <button type="button" class="button-add">
                    <ion-icon name="add-circle"></ion-icon> &nbsp; &nbsp; Tambah Data</button>
            </a>
        <?php else: ?>
            <a style="pointer-events: none;">
                <button type="button" class="button-add" style="background-color: #c4c3d0 " disabled>
                    <ion-icon name="add-circle"></ion-icon> &nbsp;&nbsp; Tambah Data
                </button>
            </a>
        <?php endif; ?>
    </div>
    <p></p>
    <div class="second">
        <div class="filter" style="display:flex; align-items:center; justify-content:flex-start; gap:16px; flex-wrap:wrap;">
            <form action="" method="post" autocomplete="off" style="display:inline-flex; align-items:center; gap:10px; margin:0;">
                <label for="tb_bulan" style="white-space: nowrap; font-weight: bold; margin: 0; line-height: 1;">Bulan</label>
                <select style="border-radius: 4px; border: 1px solid #676892; padding: 6px 12px; height: 38px; margin: 0;" name="tb_bulan">
                    <option value=""> -- Pilih -- </option>
                    <option value="1">Januari</option>
                    <option value="2">Februari</option>
                    <option value="3">Maret</option>
                    <option value="4">April</option>
                    <option value="5">Mei</option>
                    <option value="6">Juni</option>
                    <option value="7">Juli</option>
                    <option value="8">Agustus</option>
                    <option value="9">September</option>
                    <option value="10">Oktober</option>
                    <option value="11">November</option>
                    <option value="12">Desember</option>
                </select>
                <button style="background-color: #49749C; color:white; border-radius:4px; border:none; padding: 0 20px; height: 38px; margin:0;" type="submit">Filter</button>
            </form>
        </div>
    </div>
    <p></p>
    <div class="row">
        <div class="col-md-12">
            <?php
            $sql = "SELECT a.*, a.status AS status_pengeluaran,
             b.*,
             u1.*,
             u2.nama AS nama_validator, u2.jbtn AS jbtn_validator
             FROM realisasi_pengeluaran_gereja a 
             LEFT JOIN akun b ON a.id_akun = b.id_akun 
             LEFT JOIN user u1 ON a.id_user = u1.id_user 
             LEFT JOIN user u2 ON a.id_validator = u2.id_user ";

            $sql .= "WHERE a.id_fiskal = " . $id_fiskal . " ";

            if ($_POST["tb_bulan"] == 0) {
                $sql .= " ";
            } else {
                $sql .=
                    " AND month(a.tanggal_pengeluaran) = " .
                    intval($bulan) .
                    "  ";
            }
            $sql .= " ORDER BY a.status, a.tanggal_pengeluaran ASC";

            $view = new cView();
            $array = $view->vViewData($sql);
            ?>
            <div class='table-responsive'>
                <table id='example' class='table table-condensed w-100'>
                    <thead>
                        <tr class='small'>
                            <td width='2%' class="text-right">No</td>
                            <td width='12%'>Tanggal Pengeluaran</td>
                            <td width=''>Jenis Pengeluaran</td>
                            <td width=''>Akun</td>
                            <td width='13%' class="text-end">Jumlah Pengeluaran</td>
                            <td width='10%' class="text-center">Status</td>
                            <td width='5%' class="text-center">DETAIL</td>
                            <td width='5%' class="text-center">EDIT</td>
                            <td width='5%' class="text-center">HAPUS</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $cnourut = 0;
                        $total_keseluruhan = 0;

                        $groupedData = [];

                        foreach ($array as $data) {
                            $status = $data["status"];
                            $groupedData[$status][] = $data;
                        }

                        foreach ($groupedData as $status => $statusList) {

                            $id_pengeluaran = [];
                            $total = 0;
                            foreach ($statusList as $data) {

                                $cnourut = $cnourut + 1;
                                $id_pengeluaran[] = $data["id_pengeluaran"];
                                ?>
                                <tr class=''>
                                    <td class="text-right"><?= $cnourut ?></td>
                                    <td class=""><?= date(
                                        "d-m-Y",
                                        strtotime($data["tanggal_pengeluaran"]),
                                    ) ?></td>
                                    <td><?= $data["jenis_pengeluaran"] ?></td>
                                    <td><?= $data["nama_akun"] ?></td>
                                    <td class="text-end"> <?= number_format(
                                        $data["jumlah"],
                                        0,
                                        ",",
                                        ".",
                                    ) ?></td>
                                    <?php
                                    $color = "black";
                                    switch ($data["status"]) {
                                        case "Tervalidasi":
                                            $color = "#008000";
                                            break;
                                        case "Belum Tervalidasi":
                                            $color = "#808080";
                                            break;
                                        case "Tidak Valid":
                                            $color = "#a52a2a";
                                            break;
                                    }
                                    ?>
                                    <td class="text-center" style="font-weight:650; color: <?= $color ?>;"><?= $data[
    "status"
] ?></td>
                                    <td class="text-center">
                                        <?php
                                        $buktiFile = htmlspecialchars(
                                            $data["bukti_pengeluaran"] ?? "",
                                            ENT_QUOTES,
                                            "UTF-8",
                                        );
                                        $datadetail = [
                                            [
                                                "Tanggal Pengeluaran",
                                                ":",
                                                date(
                                                    "d-m-Y",
                                                    strtotime(
                                                        $data[
                                                            "tanggal_pengeluaran"
                                                        ],
                                                    ),
                                                ),
                                                1,
                                            ],
                                            [
                                                "Jenis Pengeluaran",
                                                ":",
                                                $data["jenis_pengeluaran"],
                                                1,
                                                "",
                                            ],
                                            [
                                                "Akun",
                                                ":",
                                                $data["nama_akun"],
                                                1,
                                                "",
                                            ],
                                            [
                                                "Jumlah Pengeluaran",
                                                ":",
                                                "Rp. " .
                                                number_format(
                                                    $data["jumlah"],
                                                    0,
                                                    ",",
                                                    ".",
                                                ),
                                                1,
                                            ],
                                            [
                                                "Keterangan",
                                                ":",
                                                $data["keterangan"],
                                                1,
                                            ],
                                            [
                                                "Bukti Pengeluaran",
                                                ":",
                                                "<a href='{$protocol}://{$host}{$uploadPath}{$buktiFile}' target='_blank'>{$buktiFile}</a>",
                                                1,
                                            ],
                                            [
                                                "Diinput oleh",
                                                ":",
                                                $data["nama"] .
                                                " - " .
                                                $data["jbtn"],
                                                1,
                                            ],
                                            [
                                                "Tanggal Pencatatan",
                                                ":",
                                                date(
                                                    "d-m-Y",
                                                    strtotime(
                                                        $data["tanggal_catat"],
                                                    ),
                                                ),
                                                1,
                                            ],
                                            [
                                                "Divalidasi oleh",
                                                ":",
                                                $data["nama_validator"] .
                                                " - " .
                                                $data["jbtn_validator"],
                                                1,
                                            ],
                                        ];
                                        _CreateWindowModalDetil(
                                            $cnourut,
                                            "view",
                                            "viewsasaran-form",
                                            "viewsasaran-button",
                                            "lg",
                                            600,
                                            "Detail Realisasi Pengeluaran Gereja#Data Pengeluaran  $cnourut",
                                            "",
                                            $datadetail,
                                            "",
                                            "271",
                                            "",
                                        );
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        $disabled =
                                            $status_aktif_fiskal == 1 &&
                                            $data["status"] != "Tervalidasi"
                                                ? false
                                                : true;
                                        $dataupdate = [
                                            [
                                                "ID",
                                                "id_pengeluaran",
                                                $data["id_pengeluaran"],
                                                2,
                                                "",
                                            ],
                                            [
                                                "Tanggal Pengeluaran",
                                                "tanggal_pengeluaran",
                                                $data["tanggal_pengeluaran"],
                                                14,
                                            ],
                                            [
                                                "Akun",
                                                "id_akun",
                                                $data["id_akun"],
                                                5,
                                                "select id_akun field1, nama_akun field2 from akun WHERE jenis_debitKredit = 'Debet' AND status_input = 1 ORDER BY kode_akun ASC",
                                            ],
                                            [
                                                "Jenis Pengeluaran",
                                                "jenis_pengeluaran",
                                                $data["jenis_pengeluaran"],
                                                1,
                                            ],
                                            [
                                                "Jumlah",
                                                "jumlah",
                                                $data["jumlah"],
                                                111,
                                            ],
                                            [
                                                "Keterangan",
                                                "keterangan",
                                                $data["keterangan"],
                                                17,
                                            ],
                                            [
                                                "Bukti Pengeluaran",
                                                "bukti_pengeluaran",
                                                $data["bukti_pengeluaran"],
                                                13,
                                                "",
                                            ],
                                        ];
                                        // $number, $type, $name, $button, $width, $height, $title, $acaption, $afield, $value, $linkurl
                                        _CreateWindowModalUpdate(
                                            "edit" . $cnourut,
                                            "edit",
                                            "edit-form",
                                            "edit-button",
                                            "",
                                            "",
                                            "Edit Realisasi Pengeluaran Gereja#Data Pengeluaran " .
                                                $cnourut,
                                            "",
                                            $dataupdate,
                                            "",
                                            "271",
                                            $disabled,
                                        );
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        $disabled =
                                            $status_aktif_fiskal == 1 &&
                                            $data["status"] != "Tervalidasi"
                                                ? false
                                                : true;
                                        $datadelete = [
                                            [
                                                "id_pengeluaran",
                                                $data["id_pengeluaran"],
                                                "realisasi_pengeluaran_gereja",
                                            ],
                                        ];
                                        _CreateWindowModalDelete(
                                            $cnourut,
                                            "del",
                                            "del-form",
                                            "del-button",
                                            "md",
                                            200,
                                            "Hapus Realisasi Pengeluaran Gereja#Data Pengeluaran $cnourut",
                                            "",
                                            $datadelete,
                                            "271",
                                            $disabled,
                                        );
                                        ?>
                                    </td>
                                </tr>
                            <?php $total += $data["jumlah"];
                            }
                            $total_keseluruhan += $total;
                            ?>
                            <tr>
                                <td width='2%' class="text-right"></td>
                                <td width=''></td>
                                <td style=" font-weight:bolder" width=''>Total</td>
                                <td width=''></td>
                                <td style=" font-weight:bolder" width='' class="text-end"><?= number_format(
                                    (float) $total,
                                    0,
                                    ",",
                                    ".",
                                ) ?></td>
                                <td class="text-center"></td>
                                <td width='5%' class="text-center"></td>
                                <td width='5%' class="text-center"></td>
                                <td width='5%' class="text-center"></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                    <?php
                    $query = "SELECT SUM(jumlah) AS jumlah FROM realisasi_pengeluaran_gereja WHERE status = 'Tervalidasi' AND id_fiskal = $id_fiskal";
                    $view = new cView();
                    $array = $view->vViewData($query);
                    if (!empty($array)) {
                        $saldo_tervalidasi = $array[0]["jumlah"];
                    }
                    ?>
                    <tr>
                        <td colspan="9"></td>
                    </tr>
                    <tr>
                        <td colspan="3" style="color:#5B90CD; font-weight:bolder">Total Pengeluaran Keseluruhan</td>
                        <td class="text-end" style="color:#483d8b; font-weight:bolder"><?= number_format(
                            (float) ($total_keseluruhan ?? 0),
                            0,
                            ",",
                            ".",
                        ) ?></td>
                        <td colspan="5"></td>
                    </tr>
                    <tr>
                        <td colspan="3" style="color:#2e8b57; font-weight:bolder">Total Pengeluaran Tervalidasi</td>
                        <td class="text-end" style="color:#2e8b57; font-weight:bolder"><?= number_format(
                            (float) ($saldo_tervalidasi ?? 0),
                            0,
                            ",",
                            ".",
                        ) ?></td>
                        <td colspan="5"></td>
                    </tr>
                    <tr>
                        <td colspan="3" style="color:#808080; font-weight:bolder">Total Pengeluaran Belum Tervalidasi</td>
                        <td class="text-end" style="color:#808080; font-weight:bolder"><?= number_format(
                            (float) ($total_keseluruhan - $saldo_tervalidasi ??
                                0),
                            0,
                            ",",
                            ".",
                        ) ?></td>
                        <td colspan="5"></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>