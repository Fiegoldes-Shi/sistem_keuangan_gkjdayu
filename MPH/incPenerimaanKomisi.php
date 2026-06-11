<?php
// update
?>

<?php
// delete
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <?php _myHeader(
                "newspaper",
                "Penerimaan Komisi",
                "Data Penerimaan",
            ); ?>
        </div>
    </div>

    <p></p>
    <div class="section filter-section" style="padding: 20px; margin-bottom: 20px; box-sizing: border-box; width: 100%;">
        <form action="" method="post" autocomplete="off" style="margin: 0;">
                <div class="horizontal filter-horizontal" style="display: flex; align-items: center; justify-content: flex-start; gap: 15px; flex-wrap: wrap;">
                    <div class="form-group1" style="display: flex; align-items: center; gap: 10px;">
                        <label for="bidang" style="white-space: nowrap; font-weight: bold; margin: 0; line-height: 1;">Bidang</label>
                        <select class="form-select" style="width: 350px; margin: 0; padding: 6px 12px; height: 38px;" id="bidang" name="bidang" required>
                            <option value="">-- Pilih Bidang --</option>
                            <?php
                            $sql = "SELECT id_bidang, nama_bidang FROM bidang";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $selected = "";
                                    if (
                                        isset($_POST["bidang"]) &&
                                        $_POST["bidang"] == $row["id_bidang"]
                                    ) {
                                        $selected = "selected";
                                    }
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
                            $query =
                                "SELECT id_komisi, nama_komisi FROM komisi";
                            $result = mysqli_query($conn, $query);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $selected = "";
                                    if (
                                        isset($_POST["komisi"]) &&
                                        $_POST["komisi"] == $row["id_komisi"]
                                    ) {
                                        $selected = "selected";
                                    }
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
    <p></p>

    <div class="row">
        <div class="col-md-12">
            <?php
            $sql =
                "SELECT a.id_bidang as bidang, a.id_komisi as komisi, a.id_program as program, a.*, b.*,c.*, d.*, f.*, f1.*, f2.nama AS nama_validator, f2.jbtn AS jbtn_validator ";
            $sql .= "FROM realisasi_penerimaan_komisi a ";
            $sql .=
                "LEFT JOIN akun b ON a.id_akun = b.id_akun LEFT JOIN bidang c ON a.id_bidang = c.id_bidang LEFT JOIN komisi d ON a.id_komisi = d.id_komisi  LEFT JOIN program f ON a.id_program = f.id_program LEFT JOIN user f1 ON a.id_user = f1.id_user  LEFT JOIN user f2 ON a.id_validator = f2.id_user ";
            $sql .= "WHERE a.id_fiskal = " . $id_fiskal . " ";

            if (!empty($_POST["bidang"]) || !empty($_POST["komisi"])) {
                $sql =
                    "SELECT a.id_bidang as bidang, a.id_komisi as komisi, a.id_program as program, a.*, b.*,c.*, d.*, f.*, f1.*, f2.nama AS nama_validator, f2.jbtn AS jbtn_validator ";
                $sql .= "FROM realisasi_penerimaan_komisi a ";
                $sql .=
                    "LEFT JOIN akun b ON a.id_akun = b.id_akun LEFT JOIN bidang c ON a.id_bidang = c.id_bidang LEFT JOIN komisi d ON a.id_komisi = d.id_komisi LEFT JOIN program f ON a.id_program = f.id_program LEFT JOIN user f1 ON a.id_user = f1.id_user  LEFT JOIN user f2 ON a.id_validator = f2.id_user ";

                if (!empty($_POST["komisi"])) {
                    $sql .=
                        "WHERE a.id_fiskal = " .
                        $id_fiskal .
                        " AND a.id_komisi = " .
                        intval($_POST["komisi"]);
                } else {
                    $sql .=
                        "WHERE a.id_fiskal = " .
                        $id_fiskal .
                        " AND a.id_bidang = " .
                        intval($_POST["bidang"]);
                }
            }
            $sql .=
                " ORDER BY c.id_bidang, d.id_komisi, a.tanggal_penerimaan ASC";

            $view = new cView();
            $array = $view->vViewData($sql);
            ?>
            <div id="" class='table-responsive'>
                <table id='example' class='table table-condensed-border w-100'>
                    <thead>
                        <tr class='small'>
                            <td width='3%' class="text-center">No</td>
                            <td width='10%' class="text-center" style="white-space:nowrap;">Tanggal Penerimaan</td>
                            <td width=''>Jenis Penerimaan/Kegiatan</td>
                            <td width='3%'>Vol</td>
                            <td width='11%' class="text-center">Satuan</td>
                            <td width='7%' class="text-end">Jumlah</td>
                            <td width='7%' class="text-end">Dana Gereja</td>
                            <td width='7%' class="text-end">Dana Swadaya</td>
                            <td width='7%' class="text-end">SubTotal</td>
                            <td width='10%' class="text-center">Status</td>
                            <td width='5%' class="text-center">DETAIL</td>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $cnourut = 0;
                        $groupedData = [];

                        foreach ($array as $data) {
                            $tanggal = $data["tanggal_penerimaan"];
                            $bidang = $data["nama_bidang"];
                            $komisi = $data["nama_komisi"];
                            $program = $data["nama_program"];

                            $groupedData[$bidang][$komisi][$program][
                                $tanggal
                            ][] = $data;
                        }

                        foreach ($groupedData as $bidang => $bidangList) {
                            foreach ($bidangList as $komisi => $komisiList) {
                                $firstBidangRow = true; ?>
                                <tr style="font-weight: bold;">
                                    <td style="background-color: rgb(223, 240, 248);"></td>
                                    <td style="background-color: rgb(223, 240, 248);"></td>
                                    <td style="background-color: rgb(223, 240, 248);"><div style="max-width: 250px; white-space: normal;"><?= $bidang .
                                        " - " .
                                        $komisi ?></div></td>
                                    <td style="background-color: rgb(223, 240, 248);"></td>
                                    <td style="background-color: rgb(223, 240, 248);"></td>
                                    <td style="background-color: rgb(223, 240, 248);"></td>
                                    <td style="background-color: rgb(223, 240, 248);"></td>
                                    <td style="background-color: rgb(223, 240, 248);"></td>
                                    <td style="background-color: rgb(223, 240, 248);"></td>
                                    <td style="background-color: rgb(223, 240, 248);"></td>
                                    <td style="background-color: rgb(223, 240, 248);"></td>
                                    
                                    
                                </tr>
                                <?php
                                $count = 0;

                                $lastStatus = null;
                                $number = 0;

                                $totalAll_jumlah = 0;
                                $totalAll_danaGereja = 0;
                                $totalAll_danaSwadaya = 0;
                                $totalAll_sumberDana = 0;

                                foreach (
                                    $komisiList
                                    as $program => $programList
                                ) { ?>
                                    <tr style="font-weight: bold;">
                                        <td style="background-color:#f2f3f4; color: #1a2e62"></td>
                                        <td style="background-color:#f2f3f4; color: #1a2e62"></td>
                                        <td style="background-color:#f2f3f4; color: #1a2e62;"><div style="max-width: 250px; white-space: normal;">Program : <?= $program ==
                                        null
                                            ? "Insidental"
                                            : $program ?></div></td>
                                        <td style="background-color:#f2f3f4; color: #1a2e62"></td>
                                        <td style="background-color:#f2f3f4; color: #1a2e62"></td>
                                        <td style="background-color:#f2f3f4; color: #1a2e62"></td>
                                        <td style="background-color:#f2f3f4; color: #1a2e62"></td>
                                        <td style="background-color:#f2f3f4; color: #1a2e62"></td>
                                        <td style="background-color:#f2f3f4; color: #1a2e62"></td>
                                        <td style="background-color:#f2f3f4; color: #1a2e62"></td>
                                        <td style="background-color:#f2f3f4; color: #1a2e62"></td>
                                        
                                        
                                    </tr>
                                    <?php foreach (
                                        $programList
                                        as $tanggal => $tanggalList
                                    ) {

                                        $total = 0;
                                        $total_danaGereja = 0;
                                        $total_danaSwadaya = 0;
                                        $subTotal = 0;
                                        $id_penerimaan = [];
                                        $status_counts = [];

                                        foreach ($tanggalList as $data) {

                                            if (
                                                $data["status"] ==
                                                "Belum Tervalidasi"
                                            ) {
                                                $id_penerimaan[] =
                                                    $data["id_penerimaan"];
                                            }

                                            $st = $data["status"];
                                            if (!isset($status_counts[$st])) {
                                                $status_counts[$st] = 0;
                                            }
                                            $status_counts[$st]++;
                                            $total +=
                                                $data["jumlah_penerimaan"];
                                            $total_danaGereja +=
                                                $data["dana_gereja"];
                                            $total_danaSwadaya +=
                                                $data["dana_swadaya"];
                                            $subTotal +=
                                                $data["jumlah_penerimaan"];
                                            $cnourut++;
                                            $number++;
                                            ?>
                                            <tr>
                                                <td class="text-center"><?= $number ?></td>
                                                <td class="text-center" style="white-space:nowrap;"><?= date(
                                                    "d-m-Y",
                                                    strtotime(
                                                        $data[
                                                            "tanggal_penerimaan"
                                                        ],
                                                    ),
                                                ) ?></td>
                                                <td><?= $data[
                                                    "jenis_penerimaan"
                                                ] ?></td>
                                                <td><?= $data["volume"] ?></td>
                                                <td class="text-start" style="">Rp. <?= number_format(
                                                    $data["harga_satuan"],
                                                    0,
                                                    ",",
                                                    ".",
                                                ) ?>/<?= $data["satuan"] ?></td>
                                                <td width="8%" class="text-end"><?= number_format(
                                                    $data["jumlah_penerimaan"],
                                                    0,
                                                    ",",
                                                    ".",
                                                ) ?></td>
                                                <td width="8%" class="text-end"><?= number_format(
                                                    $data["dana_gereja"],
                                                    0,
                                                    ",",
                                                    ".",
                                                ) ?></td>
                                                <td width="8%" class="text-end"><?= number_format(
                                                    $data["dana_swadaya"],
                                                    0,
                                                    ",",
                                                    ".",
                                                ) ?></td>
                                                <td width="8%" class="text-end"><?= number_format(
                                                    $data["dana_gereja"] +
                                                        $data["dana_swadaya"],
                                                    0,
                                                    ",",
                                                    ".",
                                                ) ?></td>
                                                <td class="text-center" style="vertical-align: middle; font-size: 0.9em;">
                                                    <span style="display:block; margin-bottom:5px;"><?= $data[
                                                        "status"
                                                    ] ?></span>
                                                </td>
                                                <td class="text-center" width="5%" style="vertical-align: middle;">
                                                    <?php
                                                    $datadetail = [
                                                        [
                                                            "Bidang",
                                                            ":",
                                                            $data[
                                                                "nama_bidang"
                                                            ],
                                                            1,
                                                            "",
                                                        ],
                                                        [
                                                            "Komisi",
                                                            ":",
                                                            $data[
                                                                "nama_komisi"
                                                            ],
                                                            1,
                                                            "",
                                                        ],
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
                                                            "Program",
                                                            ":",
                                                            $data[
                                                                "nama_program"
                                                            ],
                                                            1,
                                                            "",
                                                        ],
                                                        [
                                                            "Jenis Penerimaan/Kegiatan",
                                                            ":",
                                                            $data[
                                                                "jenis_penerimaan"
                                                            ],
                                                            1,
                                                            "",
                                                        ],
                                                        [
                                                            "Volume",
                                                            ":",
                                                            $data["volume"],
                                                            1,
                                                            "",
                                                        ],
                                                        [
                                                            "Satuan",
                                                            ":",
                                                            "Rp. " .
                                                            number_format(
                                                                $data[
                                                                    "harga_satuan"
                                                                ],
                                                                0,
                                                                ",",
                                                                ".",
                                                            ) .
                                                            "/" .
                                                            $data["satuan"],
                                                            1,
                                                            "",
                                                        ],
                                                        [
                                                            "Jumlah",
                                                            ":",
                                                            "Rp. " .
                                                            number_format(
                                                                $data[
                                                                    "jumlah_penerimaan"
                                                                ],
                                                                0,
                                                                ",",
                                                                ".",
                                                            ),
                                                            1,
                                                            "",
                                                        ],
                                                        [
                                                            "Sumber Dana - Dana Gereja",
                                                            ":",
                                                            "Rp. " .
                                                            number_format(
                                                                $data[
                                                                    "dana_gereja"
                                                                ],
                                                                0,
                                                                ",",
                                                                ".",
                                                            ),
                                                            1,
                                                            "",
                                                        ],
                                                        [
                                                            "Sumber Dana - Dana Swadaya",
                                                            ":",
                                                            "Rp. " .
                                                            number_format(
                                                                $data[
                                                                    "dana_swadaya"
                                                                ],
                                                                0,
                                                                ",",
                                                                ".",
                                                            ),
                                                            1,
                                                            "",
                                                        ],
                                                        [
                                                            "Diinput oleh",
                                                            ":",
                                                            $data["nama"] .
                                                            " - " .
                                                            $data["jbtn"],
                                                            2,
                                                            "",
                                                        ],
                                                        [
                                                            "Tanggal Pencatatan",
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
                                                        ],
                                                        [
                                                            "Divalidasi oleh",
                                                            ":",
                                                            $data[
                                                                "nama_validator"
                                                            ] .
                                                            " - " .
                                                            $data[
                                                                "jbtn_validator"
                                                            ],
                                                            2,
                                                            "",
                                                        ],
                                                    ];
                                                    // $number, $type, $name, $button, $width, $height, $title, $acaption, $afield, $value, $linkurl
                                                    _CreateWindowModalDetil(
                                                        $cnourut,
                                                        "view",
                                                        "viewsasaran-form",
                                                        "viewsasaran-button",
                                                        "lg",
                                                        600,
                                                        "Detail Data Realisasi Penerimaan Komisi #Data Penerimaan $cnourut : " .
                                                            $data[
                                                                "jenis_penerimaan"
                                                            ],
                                                        "",
                                                        $datadetail,
                                                        "",
                                                        "29",
                                                        "",
                                                    );
                                                    ?>
                                                </td>

                                            </tr>
                                        <?php
                                        }
                                        $totalAll_jumlah += $total;
                                        $totalAll_danaGereja += $total_danaGereja;
                                        $totalAll_danaSwadaya += $total_danaSwadaya;
                                        $totalAll_sumberDana +=
                                            $total_danaGereja +
                                            $total_danaSwadaya;
                                        ?>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td style="color:#5B90CD; font-weight:bolder">Total</td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-end" style="color:#5B90CD; font-weight:bolder"><?= number_format(
                                                (float) $total,
                                                0,
                                                ",",
                                                ".",
                                            ) ?></td>
                                            <td class="text-end" style="color:#5B90CD; font-weight:bolder"><?= number_format(
                                                (float) $total_danaGereja,
                                                0,
                                                ",",
                                                ".",
                                            ) ?></td>
                                            <td class="text-end" style="color:#5B90CD; font-weight:bolder"><?= number_format(
                                                (float) $total_danaSwadaya,
                                                0,
                                                ",",
                                                ".",
                                            ) ?></td>
                                            <td class="text-end" style="color:#5B90CD; font-weight:bolder"><?= number_format(
                                                (float) $subTotal,
                                                0,
                                                ",",
                                                ".",
                                            ) ?></td>
                                            <td class="text-center" style="font-weight:650; white-space:nowrap; border-right:none;">
                                            <?php if (
                                                count($status_counts) == 1
                                            ) {
                                                $single_status = array_key_first(
                                                    $status_counts,
                                                );
                                                $color = "black";
                                                switch ($single_status) {
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
                                                echo "<span style='color: $color;'>$single_status</span>";
                                            } else {
                                                foreach (
                                                    $status_counts
                                                    as $st => $count
                                                ) {
                                                    $color = "black";
                                                    switch ($st) {
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
                                                    echo "<div style='color: $color; font-size:0.9em; margin-bottom:2px;'>$count $st</div>";
                                                }
                                            } ?>
                                            </td>
                                            <td style="border-left:none;"></td>
                                            
                                            
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            
                                            
                                        </tr>
                                <?php
                                $countprogram = 0;
                                $firstBidangRow = false;

                                    }}
                                ?>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td style="color:#2b3e66; font-weight:bolder;"><div style="max-width: 250px; white-space: normal;">Total Penerimaan <?= $komisi ?></div></td>
                                    <td></td>
                                    <td></td>
                                    <td style="color:#322E7D; font-weight:bolder;" class="text-end"><?= number_format(
                                        (float) ($totalAll_jumlah ?? 0),
                                        0,
                                        ",",
                                        ".",
                                    ) ?></td>
                                    <td style="color:#322E7D; font-weight:bolder;" class="text-end"><?= number_format(
                                        (float) ($totalAll_danaGereja ?? 0),
                                        0,
                                        ",",
                                        ".",
                                    ) ?></td>
                                    <td style="color:#322E7D; font-weight:bolder;" class="text-end"><?= number_format(
                                        (float) ($totalAll_danaSwadaya ?? 0),
                                        0,
                                        ",",
                                        ".",
                                    ) ?></td>
                                    <td style="color:#322E7D; font-weight:bolder;" class="text-end"><?= number_format(
                                        (float) ($totalAll_sumberDana ?? 0),
                                        0,
                                        ",",
                                        ".",
                                    ) ?></td>
                                    <td></td>
                                    <td class="text-center"></td>
                                    
                                    
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
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
</script>