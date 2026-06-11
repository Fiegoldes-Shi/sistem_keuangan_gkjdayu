<?php

$protocol =
    !empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off" ? "https" : "http";
$host = $_SERVER["HTTP_HOST"];
$uploadPath = "/uploads/bukti_penerimaan/";

// update
if (!empty($_POST["editbtn"])) {
    $datafield = [
        "id_akun",
        "id_bank",
        "jumlah_penerimaan",
        "tanggal_penerimaan",
        "tanggal_pencatatan",
        "jenis_penerimaan",
        "deskripsi_penerimaan",
    ];
    $datavalue = [
        $_POST["id_akun"],
        $_POST["id_bank"],
        $_POST["jumlah_penerimaan"],
        $_POST["tanggal_penerimaan"],
        $_POST["tanggal_pencatatan"],
        $_POST["jenis_penerimaan"],
        $_POST["deskripsi_penerimaan"],
    ];

    $datakey = " id_penerimaan =" . $_POST["id_penerimaan"];

    $update = new cUpdate();
    $update->fUpdateData(
        $datafield,
        "realisasi_penerimaan_gereja",
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

// valid
if (!empty($_POST["btnsetuju"])) {
    $datafield = ["status", "id_validator"];
    $datavalue = ["'Tervalidasi'", $id_user];

    $update = new cUpdate();
    $update->_functionStatus(
        $_POST["hiddenupdatevalue0"],
        $_POST["hiddenupdatevalue1"],
        $_POST["hiddenupdatevalue2"],
        $datafield,
        $datavalue,
    );
} elseif (!empty($_POST["btntolak"])) {
    $datafield = ["status", "id_validator"];
    $datavalue = ["'Tidak Valid'", $id_user];

    $update = new cUpdate();
    $update->_functionStatus(
        $_POST["hiddenupdatevalue0"],
        $_POST["hiddenupdatevalue1"],
        $_POST["hiddenupdatevalue2"],
        $datafield,
        $datavalue,
    );
}

// bulk validasi semua yang belum tervalidasi
if (!empty($_POST["btn_bulk_validasi"])) {
    $sql_bulk = "SELECT id_penerimaan FROM realisasi_penerimaan_gereja WHERE id_fiskal = $id_fiskal AND status = 'Belum Tervalidasi'";

    $view_bulk = new cView();
    $array_bulk = $view_bulk->vViewData($sql_bulk);
    if (!empty($array_bulk)) {
        $ids_bulk = implode(",", array_column($array_bulk, "id_penerimaan"));
        $datafield_bulk = ["status", "id_validator"];
        $datavalue_bulk = ["'Tervalidasi'", $id_user];
        $update_bulk = new cUpdate();
        $update_bulk->_functionStatus(
            "id_penerimaan",
            $ids_bulk,
            "realisasi_penerimaan_gereja",
            $datafield_bulk,
            $datavalue_bulk,
        );
    } else {
        echo "<script>
            Swal.fire({
                position: 'center', width: '25em', icon: 'info',
                text: 'Tidak ada transaksi yang perlu divalidasi.',
            }).then(function(){ window.location = ''; });
        </script>";
    }
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
                "Penerimaan Gereja",
                "Data Penerimaan",
            ); ?>
        </div>
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
            <?php if ($status_aktif_fiskal == 1): ?>
            <form action="" method="post" style="display:inline-flex; align-items:center; margin:0;" onsubmit="return confirm('Validasi semua transaksi penerimaan yang belum tervalidasi?');">
                <button type="submit" name="btn_bulk_validasi" value="1" style="background-color:#2e8b57; color:white; border-radius:4px; border:none; padding: 0 20px; height: 38px; margin:0; cursor:pointer; white-space: nowrap;">✔ Validasi Semua</button>
            </form>
            <?php endif; ?>
        </div>
    </div>
    <p></p>

    <div class="row">
        <div class="col-md-12">
            <?php
            $sql =
                "SELECT a.*, b.*, e.*, f1.*, f2.nama AS nama_validator, f2.jbtn AS jbtn_validator ";
            $sql .= "FROM realisasi_penerimaan_gereja a ";
            $sql .=
                "LEFT JOIN akun b ON a.id_akun = b.id_akun LEFT JOIN bank e ON a.id_bank = e.id_bank LEFT JOIN user f1 ON a.id_user = f1.id_user  LEFT JOIN user f2 ON a.id_validator = f2.id_user ";
            $sql .= "WHERE a.id_fiskal = " . $id_fiskal . " ";
            if ($_POST["tb_bulan"] == 0) {
                $sql .= " ";
            } else {
                $sql .=
                    " AND month(a.tanggal_penerimaan) = " .
                    intval($bulan) .
                    "  ";
            }
            $sql .= " ORDER BY a.tanggal_penerimaan ASC";

            $view = new cView();
            $array = $view->vViewData($sql);
            ?>
            <div id="" class='table-responsive'>
                <table id='example' class='table table-condensed w-100'>
                    <thead>
                        <tr class='small'>
                            <td width='3%' class="text-right">No</td>
                            <td width='7%'>Tanggal</td>
                            <td width='15%'>Jenis Penerimaan</td>
                            <td width='25%'>Nama Akun</td>
                            <td width='20%' class="text-end">Jumlah Penerimaan</td>
                            <td width='15%' class="text-center">Status</td>
                            <td width='5%' class="text-center">DETAIL</td>
                            <td width='5%' class="text-center">EDIT</td>
                            <td width='5%' class="text-center">HAPUS</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $cnourut = 0;

                        $groupedData = [];
                        $total_penerimaan = 0;

                        foreach ($array as $data) {
                            $tanggal = $data["tanggal_penerimaan"];
                            $jenis = $data["jenis_penerimaan"];
                            $groupedData[$tanggal][$jenis][] = $data;
                        }

                        foreach ($groupedData as $tanggal => $jenisList) {

                            $firstDateRow = true;

                            $editId = 0;
                            foreach ($jenisList as $jenis => $dataList) {

                                $total = 0;
                                $firstJenisRow = true;

                                $number = 0;
                                $id_penerimaan = [];
                                foreach ($dataList as $index => $data) {

                                    $cnourut++;
                                    $number++;
                                    $id_penerimaan[] = $data["id_penerimaan"];
                                    ?>
                                    <tr>
                                        <td><?= $number ?></td>
                                        <td><?= $firstDateRow
                                            ? date("d-m-Y", strtotime($tanggal))
                                            : "" ?></td> <!-- Tanggal hanya di baris pertama -->
                                        <td style="font-weight: bold;"><?= $firstJenisRow
                                            ? $jenis
                                            : "" ?></td> <!-- Jenis Penerimaan hanya di baris pertama -->
                                        <td><?= $data["nama_akun"] ?></td>
                                        <td class="text-end"><?= number_format(
                                            $data["jumlah_penerimaan"],
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
                                            $datadetail = [
                                                [
                                                    "Tanggal Penerimaan",
                                                    ":",
                                                    date(
                                                        "d-m-Y",
                                                        strtotime(
                                                            $data[
                                                                "tanggal_penerimaan"
                                                            ],
                                                        ),
                                                    ),
                                                    1,
                                                    "",
                                                ],
                                                [
                                                    "Jenis Penerimaan",
                                                    ":",
                                                    $data["jenis_penerimaan"],
                                                    1,
                                                    "",
                                                ],
                                                [
                                                    "Bank Penerimaan",
                                                    ":",
                                                    $data["nama_bank"] .
                                                    " - " .
                                                    $data["nama_rekening"],
                                                    1,
                                                    "",
                                                ],
                                            ];
                                            if (
                                                !empty(
                                                    $data["bukti_penerimaan"]
                                                )
                                            ) {
                                                $buktiFile = htmlspecialchars(
                                                    $data["bukti_penerimaan"] ??
                                                        "",
                                                    ENT_QUOTES,
                                                    "UTF-8",
                                                );
                                                $datadetail[] = [
                                                    "Bukti Penerimaan",
                                                    ":",
                                                    "<a href='{$protocol}://{$host}{$uploadPath}{$buktiFile}' target='_blank'>{$buktiFile}</a>",
                                                    1,
                                                ];
                                            }
                                            $datadetail[] = [
                                                "Tanggal Penginputan",
                                                ":",
                                                date(
                                                    "d-m-Y",
                                                    strtotime(
                                                        $data[
                                                            "tanggal_pencatatan"
                                                        ],
                                                    ),
                                                ),
                                                1,
                                                "",
                                            ];
                                            $datadetail[] = [
                                                "Diinput oleh",
                                                ":",
                                                $data["nama"] .
                                                " - " .
                                                $data["jbtn"],
                                                1,
                                                "",
                                            ];
                                            $datadetail[] = [
                                                "Status Validasi",
                                                ":",
                                                $data["status"],
                                                1,
                                                "",
                                            ];
                                            $datadetail[] = [
                                                "Divalidasi oleh",
                                                ":",
                                                $data["nama_validator"] .
                                                " - " .
                                                $data["jbtn_validator"],
                                                1,
                                                "",
                                            ];
                                            $datadetail[] = [
                                                " ",
                                                "",
                                                "",
                                                1,
                                                "",
                                            ];
                                            $datadetail[] = [
                                                "Akun",
                                                ":",
                                                $data["nama_akun"],
                                                1,
                                                "",
                                            ];
                                            $datadetail[] = [
                                                "Jumlah Penerimaan",
                                                ":",
                                                "Rp. " .
                                                number_format(
                                                    $data["jumlah_penerimaan"],
                                                    0,
                                                    ",",
                                                    ".",
                                                ),
                                                1,
                                                "",
                                            ];
                                            $datadetail[] = [
                                                "Keterangan",
                                                ":",
                                                !empty(
                                                    $data[
                                                        "deskripsi_penerimaan"
                                                    ]
                                                )
                                                    ? $data[
                                                        "deskripsi_penerimaan"
                                                    ]
                                                    : "-",
                                                1,
                                                "",
                                            ];

                                            _CreateWindowModalDetil(
                                                $cnourut,
                                                "view",
                                                "viewsasaran-form",
                                                "viewsasaran-button",
                                                "lg",
                                                600,
                                                "Detail Realisasi Penerimaan Gereja#Data Penerimaan " .
                                                    $cnourut,
                                                "",
                                                $datadetail,
                                                "",
                                                "27",
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
                                                    "ID Penerimaaan",
                                                    "id_penerimaan",
                                                    $data["id_penerimaan"],
                                                    2,
                                                    "",
                                                ],
                                                [
                                                    "Tanggal Pencatatan",
                                                    "tanggal_pencatatan",
                                                    $data["tanggal_pencatatan"],
                                                    14,
                                                    "",
                                                ],
                                                [
                                                    "Tanggal Penerimaan",
                                                    "tanggal_penerimaan",
                                                    $data["tanggal_penerimaan"],
                                                    14,
                                                    "",
                                                ],
                                                [
                                                    "Jenis Penerimaan",
                                                    "jenis_penerimaan",
                                                    $data["jenis_penerimaan"],
                                                    1,
                                                    "",
                                                ],
                                                [
                                                    "Nama Akun",
                                                    "id_akun",
                                                    $data["id_akun"],
                                                    5,
                                                    "select id_akun field1, nama_akun field2 from akun WHERE jenis_debitKredit = 'Kredit' AND status_input = 1 ORDER BY kode_akun ASC",
                                                ],
                                                [
                                                    "Nama Bank",
                                                    "id_bank",
                                                    $data["id_bank"],
                                                    51,
                                                    "select id_bank field1, CONCAT(nama_bank, ' - ', nama_rekening) field2 from bank",
                                                ],
                                                [
                                                    "Jumlah Penerimaan",
                                                    "jumlah_penerimaan",
                                                    $data["jumlah_penerimaan"],
                                                    1,
                                                    "",
                                                ],
                                                [
                                                    "Keterangan",
                                                    "deskripsi_penerimaan",
                                                    $data[
                                                        "deskripsi_penerimaan"
                                                    ],
                                                    17,
                                                    "",
                                                ],
                                            ];
                                            // $number, $type, $name, $button, $width, $height, $title, $acaption, $afield, $value, $linkurl
                                            _CreateWindowModalUpdate(
                                                "edit" . $cnourut,
                                                "edit",
                                                "edit-form",
                                                "edit-button",
                                                "lg",
                                                "",
                                                "Edit Realisasi Penerimaan Gereja#Data Penerimaan " .
                                                    $cnourut,
                                                "",
                                                $dataupdate,
                                                "",
                                                "27",
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
                                                    "id_penerimaan",
                                                    $data["id_penerimaan"],
                                                    "realisasi_penerimaan_gereja",
                                                ],
                                            ];
                                            _CreateWindowModalDelete(
                                                $cnourut,
                                                "del",
                                                "del-form",
                                                "del-button",
                                                "md",
                                                500,
                                                "Hapus Realisasi Penerimaan Gereja#Data Penerimaan " .
                                                    $cnourut,
                                                "",
                                                $datadelete,
                                                "27",
                                                $disabled,
                                            );
                                            ?>
                                        </td>
                                    </tr>
                                <?php
                                $total = $total + $data["jumlah_penerimaan"];
                                $firstDateRow = false;
                                $firstJenisRow = false;

                                }
                                $total_penerimaan = $total_penerimaan + $total;
                                ?>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td style="font-weight: bold;">Total</td>
                                    <td class="text-end" style="font-weight: bold; "><?= number_format(
                                        $total,
                                        0,
                                        ",",
                                        ".",
                                    ) ?></td>

                                    <td class="text-center">
                                        <?php
                                        $caption =
                                            "Apakah Transaksi Penerimaan ini Valid ?";
                                        $all_ids_string = implode(
                                            ",",
                                            $id_penerimaan,
                                        );

                                        $disabled =
                                            $status_aktif_fiskal == 1 &&
                                            $data["status"] != "Tervalidasi"
                                                ? false
                                                : true;
                                        $datavalid = [
                                            [
                                                "id_penerimaan",
                                                $all_ids_string,
                                                "realisasi_penerimaan_gereja",
                                            ],
                                        ];
                                        _CreateWindowModalValid(
                                            $cnourut,
                                            "val",
                                            "val-form",
                                            "val-button",
                                            "md",
                                            200,
                                            "Validasi Transaksi Penerimaan# " .
                                                $data["jenis_penerimaan"] .
                                                " : Rp. " .
                                                number_format(
                                                    $total,
                                                    0,
                                                    ",",
                                                    ".",
                                                ),
                                            $datavalid,
                                            "27",
                                            "Validasi",
                                            $caption,
                                            $disabled,
                                        );
                                        ?>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>

                                    <td style="font-weight: bold;"></td>
                                    <td class="text-end" style="font-weight: bold; "></td>
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            <?php
                            }
                            ?>
                        <?php
                        }
                        ?>
                    </tbody>
                    <?php
                    $query = "SELECT SUM(jumlah_penerimaan) AS jumlah FROM realisasi_penerimaan_gereja WHERE status = 'Tervalidasi' AND id_fiskal = $id_fiskal";
                    $view = new cView();
                    $array = $view->vViewData($query);
                    if (!empty($array)) {
                        $saldo_tervalidasi = $array[0]["jumlah"];
                    }
                    ?>
                    <tr>
                        <td colspan="10"></td>
                    </tr>
                    <tr>
                        <td colspan="3" style="color:#5B90CD; font-weight:bolder">Total Penerimaan Keseluruhan</td>
                        <td class="text-end" style="color:#483d8b; font-weight:bolder"><?= number_format(
                            (float) ($total_penerimaan ?? 0),
                            0,
                            ",",
                            ".",
                        ) ?></td>
                        <td colspan="6"></td>
                    </tr>
                    <tr>
                        <td colspan="3" style="color:#2e8b57; font-weight:bolder">Total Penerimaan Tervalidasi</td>
                        <td class="text-end" style="color:#2e8b57; font-weight:bolder"><?= number_format(
                            (float) ($saldo_tervalidasi ?? 0),
                            0,
                            ",",
                            ".",
                        ) ?></td>
                        <td colspan="6"></td>
                    </tr>
                    <tr>
                        <td colspan="3" style="color:#808080; font-weight:bolder">Total Penerimaan Belum Tervalidasi</td>
                        <td class="text-end" style="color:#808080; font-weight:bolder"><?= number_format(
                            (float) ($total_penerimaan - $saldo_tervalidasi ??
                                0),
                            0,
                            ",",
                            ".",
                        ) ?></td>
                        <td colspan="6"></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>