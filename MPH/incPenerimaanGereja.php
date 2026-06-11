<?php
$protocol =
    !empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off" ? "https" : "http";
$host = $_SERVER["HTTP_HOST"];
$uploadPath = "/uploads/bukti_penerimaan/";

// Pastikan aman dari null

// update
?>

<?php // delete

//filter bulan
if (empty($_POST["tb_bulan"])) {
    $_POST["tb_bulan"] = 0;
} else {
    $bulan = $_POST["tb_bulan"];
} ?>

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
            <?php  ?>
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
                $nomorBulan = intval($bulan);
                $sql .=
                    " AND month(a.tanggal_penerimaan) = " . $nomorBulan . "  ";
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
                        <td colspan="7"></td>
                    </tr>
                    <tr>
                        <td colspan="3" style="color:#5B90CD; font-weight:bolder">Total Penerimaan Keseluruhan</td>
                        <td class="text-end" style="color:#483d8b; font-weight:bolder"><?= number_format(
                            (float) ($total_penerimaan ?? 0),
                            0,
                            ",",
                            ".",
                        ) ?></td>
                        <td colspan="3"></td>
                    </tr>
                    <tr>
                        <td colspan="3" style="color:#2e8b57; font-weight:bolder">Total Penerimaan Tervalidasi</td>
                        <td class="text-end" style="color:#2e8b57; font-weight:bolder"><?= number_format(
                            (float) ($saldo_tervalidasi ?? 0),
                            0,
                            ",",
                            ".",
                        ) ?></td>
                        <td colspan="3"></td>
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
                        <td colspan="3"></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <p><br></p>
        </div>
    </div>