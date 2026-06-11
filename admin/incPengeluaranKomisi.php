<?php

$protocol =
    !empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off" ? "https" : "http";
$host = $_SERVER["HTTP_HOST"];
$uploadPath = "//uploads/lpj/";

// update
if (!empty($_POST["editbtn"])) {
    $targetDir = "../uploads/lpj/";

    // Pastikan folder ada
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $FileName = null;

    if (!empty($_FILES["lpj"]["name"])) {
        $FileName = time() . "_" . basename($_FILES["lpj"]["name"]);
        $targetFilePath = $targetDir . $FileName;

        if ($_FILES["lpj"]["error"] === UPLOAD_ERR_OK) {
            if (
                move_uploaded_file($_FILES["lpj"]["tmp_name"], $targetFilePath)
            ) {
            } else {
                $FileName = null;
            }
        } else {
            $FileName = null;
        }
    }

    $id_program = intval($_POST["id_program"] ?? 0);

    $datafield = [
        "id_akun",
        "id_bidang",
        "id_komisi",
        "id_program",
        "tanggal_pengeluaran",
        "item",
        "volume",
        "satuan",
        "harga_satuan",
        "jumlah",
        "dana_gereja",
        "dana_swadaya",
        "lpj",
    ];
    $datavalue = [
        $_POST["id_akun"],
        $_POST["id_bidang"],
        $_POST["id_komisi"],
        $id_program,
        "'" . $_POST["tanggal_pengeluaran"] . "'",
        "'" . $_POST["item"] . "'",
        $_POST["volume"],
        "'" . $_POST["satuan"] . "'",
        "'" . $_POST["harga_satuan"] . "'",
        "'" . $_POST["jumlah"] . "'",
        "'" . $_POST["dana_gereja"] . "'",
        "'" . $_POST["dana_swadaya"] . "'",
        "'" . $FileName . "'",
    ];

    $datakey = " id_pengeluaran =" . $_POST["id_pengeluaran"];

    $update = new cUpdate();
    $update->vUpdateData(
        $datafield,
        "realisasi_pengeluaran_komisi",
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
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <?php _myHeader(
                "newspaper",
                "Pengeluaran Komisi",
                "Data pengeluaran",
            ); ?>
        </div>
    </div>
    <div class="row" style="width:12%">
        <?php if ($status_aktif_fiskal == 1): ?>
            <a href="36">
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
            <div class='table-responsive'>
                <table id='example' class='table table-condensed  w-100'>
                    <thead>
                        <tr class='small'>
                            <td width='3%' class="text-center">No</td>
                            <td width='10%' class="text-center" style="white-space:nowrap;">Tanggal Pengeluaran</td>
                            <td width=''>Jenis Pengeluaran/Kegiatan</td>
                            <td width='3%' class="text-center">Vol</td>
                            <td width='11%' class="text-center">Satuan</td>
                            <td width='7%' class="text-end">Jumlah</td>
                            <td width='7%' class="text-end">Dana Gereja</td>
                            <td width='7%' class="text-end">Dana Swadaya</td>
                            <td width='7%' class="text-end">Subtotal</td>
                            <td width='10%' class="text-center">Status</td>
                            <td width='5%' class="text-center">DETAIL</td>
                            <td width='5%' class="text-center">EDIT</td>
                            <td width='5%' class="text-center">HAPUS</td>
                        </tr>
                    </thead>
                    <?php
                    $sql = "SELECT a.id_bidang as bidang, a.id_komisi as komisi, a.id_program as program, a.*, a.status AS status_pengeluaran,
                    b.*, c.*, d.*, e.*, f.*, u1.*, u2.nama AS nama_validator, u2.jbtn AS jbtn_validator
             FROM realisasi_pengeluaran_komisi a
             LEFT JOIN akun b ON a.id_akun = b.id_akun 
             LEFT JOIN bidang c ON a.id_bidang = c.id_bidang 
             LEFT JOIN komisi d ON a.id_komisi = d.id_komisi 
             LEFT JOIN program e ON a.id_program = e.id_program 
             LEFT JOIN fiskal f ON a.id_fiskal = f.id_fiskal 
             LEFT JOIN user u1 ON a.id_user = u1.id_user 
             LEFT JOIN user u2 ON a.id_validator = u2.id_user 
             WHERE a.id_fiskal = $id_fiskal
             ORDER BY a.tanggal_pengeluaran ASC;
             ";

                    if (!empty($_POST["bidang"]) || !empty($_POST["komisi"])) {
                        $sql = "SELECT a.id_bidang as bidang, a.id_komisi as komisi, a.id_program as program, a.*, a.status AS status_pengeluaran,
                        b.*,
                        c.*,
                        d.*,
                        e.*,
                        f.*,
                        u1.*,
                        u2.nama AS nama_validator, u2.jbtn AS jbtn_validator
                        FROM realisasi_pengeluaran_komisi a 
                        LEFT JOIN akun b ON a.id_akun = b.id_akun 
                        LEFT JOIN bidang c ON a.id_bidang = c.id_bidang 
                        LEFT JOIN komisi d ON a.id_komisi = d.id_komisi 
                   
                      
                        LEFT JOIN program e ON a.id_program = e.id_program 
                        LEFT JOIN fiskal f ON a.id_fiskal = f.id_fiskal 
                        LEFT JOIN user u1 ON a.id_user = u1.id_user 
                        LEFT JOIN user u2 ON a.id_validator = u2.id_user ";

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
                    $view = new cView();
                    $array = $view->vViewData($sql);
                    ?>
                    <tbody>
                        <?php
                        $cnourut = 0;
                        $total_keseluruhan = 0;

                        $groupedData = [];

                        foreach ($array as $data) {
                            $tanggal = $data["tanggal_pengeluaran"];
                            $bidang = $data["nama_bidang"];
                            $komisi = $data["nama_komisi"];
                            $program = $data["nama_program"];

                            $groupedData[$bidang][$komisi][$program][
                                $tanggal
                            ][] = $data;
                        }

                        foreach ($groupedData as $bidang => $bidangList) {
                            $total_komisi = 0;

                            foreach ($bidangList as $komisi => $komisiList) { ?>
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
                                    <td style="background-color: rgb(223, 240, 248);"></td>
                                    <td style="background-color: rgb(223, 240, 248);"></td>
                                </tr>

                                <?php
                                $totalAll_jumlah = 0;
                                $totalAll_danaGereja = 0;
                                $totalAll_danaSwadaya = 0;
                                $totalAll_sumberDana = 0;
                                $total_pakai = 0;

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
                                        <td style="background-color:#f2f3f4; color: #1a2e62"></td>
                                        <td style="background-color:#f2f3f4; color: #1a2e62"></td>
                                    </tr>
                                    <?php
                                    $count = 0;
                                    $number = 0;

                                    foreach (
                                        $programList
                                        as $tanggal => $tanggalList
                                    ) {

                                        $total = 0;
                                        $total_danaGereja = 0;
                                        $total_danaSwadaya = 0;
                                        $subTotal = 0;
                                        $id_pengeluaran = [];
                                        $status_counts = [];

                                        foreach ($tanggalList as $data) {

                                            $st = $data["status_pengeluaran"];
                                            if (!isset($status_counts[$st])) {
                                                $status_counts[$st] = 0;
                                            }
                                            $status_counts[$st]++;
                                            $cnourut = $cnourut + 1;
                                            $number = $number + 1;

                                            $total += $data["jumlah"];
                                            $total_danaGereja +=
                                                $data["dana_gereja"];
                                            $total_danaSwadaya +=
                                                $data["dana_swadaya"];
                                            $subTotal += $data["jumlah"];
                                            if (
                                                $data["status_pengeluaran"] ==
                                                "Belum Tervalidasi"
                                            ) {
                                                $id_pengeluaran[] =
                                                    $data["id_pengeluaran"];
                                            }
                                            ?>
                                            <tr>
                                                <td class="text-center"><?= $number ?></td>
                                                <td class="text-center" style="white-space:nowrap;"><?= date(
                                                    "d-m-Y",
                                                    strtotime(
                                                        $data[
                                                            "tanggal_pengeluaran"
                                                        ],
                                                    ),
                                                ) ?></td>

                                                <td><?= $data["item"] ?></td>
                                                <td class="text-center"><?= $data[
                                                    "volume"
                                                ] ?></td>
                                                <td class="text-start" style="">Rp. <?= number_format(
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
                                                <?php
                                                $color = "black";

                                                switch (
                                                    $data["status_pengeluaran"]
                                                ) {
                                                    case "Tervalidasi":
                                                        $color = "#3cb371";
                                                        break;
                                                    case "Belum Tervalidasi":
                                                        $color = "#808080";
                                                        break;
                                                    case "Tidak Valid":
                                                        $color = "#a52a2a";
                                                        break;
                                                }
                                                ?>
                                                <td class="text-center" style="vertical-align: middle; font-size: 0.9em;">
                                                    <span style="display:block; margin-bottom:5px; font-weight:650; color: <?= $color ?>;"><?= $data[
    "status_pengeluaran"
] ?></span>
                                                </td>
                                                <td class="text-center" width="5%" style="vertical-align: middle;">
                                                    <?php
                                                    $lpjFile = htmlspecialchars(
                                                        $data["lpj"] ?? "",
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
                                                            "Akun",
                                                            ":",
                                                            $data["nama_akun"],
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
                                                            "Jenis Pengeluaran/Kegiatan",
                                                            ":",
                                                            $data["item"],
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
                                                                $data["jumlah"],
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
                                                            "File LPJ",
                                                            ":",
                                                            !empty($lpjFile)
                                                                ? "<a href='{$protocol}://{$host}{$uploadPath}{$lpjFile}' target='_blank'>{$lpjFile}</a>"
                                                                : "Tidak ada",
                                                            1,
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
                                                                        "tanggal_catat"
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
                                                    _CreateWindowModalDetil(
                                                        $cnourut,
                                                        "view",
                                                        "viewsasaran-form",
                                                        "viewsasaran-button",
                                                        "lg",
                                                        600,
                                                        "Detail Data Realisasi Pengeluaran Komisi #Data Pengeluaran $cnourut : " .
                                                            $data["item"],
                                                        "",
                                                        $datadetail,
                                                        "",
                                                        "26",
                                                        "",
                                                    );
                                                    ?>
                                                </td>
                                                <td class="text-center" width="5%" style="vertical-align: middle;">
                                                    <?php
                                                    $disabled =
                                                        $status_aktif_fiskal ==
                                                            1 &&
                                                        $data[
                                                            "status_pengeluaran"
                                                        ] != "Tervalidasi"
                                                            ? false
                                                            : true;
                                                    $dataupdate = [
                                                        [
                                                            "ID Pengeluaran",
                                                            "id_pengeluaran",
                                                            $data[
                                                                "id_pengeluaran"
                                                            ],
                                                            2,
                                                            "",
                                                        ],
                                                        [
                                                            "Bidang",
                                                            "id_bidang",
                                                            $data[
                                                                "nama_bidang"
                                                            ],
                                                            5,
                                                            "select id_bidang field1, nama_bidang field2 from bidang",
                                                        ],
                                                        [
                                                            "Komisi",
                                                            "id_komisi",
                                                            $data[
                                                                "nama_komisi"
                                                            ],
                                                            51,
                                                            "select id_komisi field1, nama_komisi field2 from komisi",
                                                        ],
                                                        [
                                                            "Akun",
                                                            "id_akun",
                                                            $data["nama_akun"],
                                                            5,
                                                            "select id_akun field1, nama_akun field2 from akun",
                                                        ],
                                                        [
                                                            "Program",
                                                            "id_program",
                                                            $data["id_program"],
                                                            51,
                                                            "SELECT program.id_program field1, CONCAT(komisi.nama_komisi, ' - ', program.nama_program) field2 FROM program JOIN komisi ON program.id_komisi = komisi.id_komisi WHERE program.id_fiskal = $id_fiskal",
                                                        ],
                                                        [
                                                            "Tanggal Pengeluaran",
                                                            "tanggal_pengeluaran",
                                                            $data[
                                                                "tanggal_pengeluaran"
                                                            ],
                                                            14,
                                                            "",
                                                        ],
                                                        [
                                                            "Jenis Pengeluaran/Kegiatan",
                                                            "item",
                                                            $data["item"],
                                                            1,
                                                            "",
                                                        ],
                                                        [
                                                            "Volume",
                                                            "volume",
                                                            $data["volume"],
                                                            112,
                                                            'oninput="hitungOtomatis(this)"',
                                                        ],
                                                        [
                                                            "Satuan",
                                                            "satuan",
                                                            $data["satuan"],
                                                            1,
                                                        ],
                                                        [
                                                            "Harga Satuan",
                                                            "harga_satuan",
                                                            $data[
                                                                "harga_satuan"
                                                            ],
                                                            112,
                                                            'oninput="hitungOtomatis(this)"',
                                                        ],
                                                        [
                                                            "Jumlah Pengeluaran",
                                                            "jumlah",
                                                            $data["jumlah"],
                                                            112,
                                                            'onkeydown="return false"',
                                                        ],
                                                        [
                                                            "Dana Gereja",
                                                            "dana_gereja",
                                                            $data[
                                                                "dana_gereja"
                                                            ],
                                                            1,
                                                            "",
                                                        ],
                                                        [
                                                            "Dana Swadaya",
                                                            "dana_swadaya",
                                                            $data[
                                                                "dana_swadaya"
                                                            ],
                                                            1,
                                                            "",
                                                        ],
                                                        [
                                                            "File LPJ",
                                                            "lpj",
                                                            $data["lpj"],
                                                            13,
                                                            "",
                                                        ],
                                                    ];
                                                    _CreateWindowModalUpdate(
                                                        "edit" . $cnourut,
                                                        "edit",
                                                        "edit-form",
                                                        "edit-button",
                                                        "lg",
                                                        "",
                                                        "Edit Realisasi Pengeluaran Komisi #Data Pengeluaran " .
                                                            $cnourut,
                                                        "",
                                                        $dataupdate,
                                                        "",
                                                        "26",
                                                        $disabled,
                                                    );
                                                    ?>
                                                </td>
                                                <td class="text-center" width="5%" style="vertical-align: middle;">
                                                    <?php
                                                    $disabled =
                                                        $status_aktif_fiskal ==
                                                            1 &&
                                                        $data[
                                                            "status_pengeluaran"
                                                        ] != "Tervalidasi"
                                                            ? false
                                                            : true;
                                                    $datadelete = [
                                                        [
                                                            "id_pengeluaran",
                                                            $data[
                                                                "id_pengeluaran"
                                                            ],
                                                            "realisasi_pengeluaran_komisi",
                                                        ],
                                                    ];
                                                    _CreateWindowModalDelete(
                                                        $cnourut,
                                                        "del",
                                                        "del-form",
                                                        "del-button",
                                                        "md",
                                                        500,
                                                        "Hapus Realisasi Pengeluaran Komisi #Data Pengeluaran " .
                                                            $cnourut,
                                                        "",
                                                        $datadelete,
                                                        "26",
                                                        $disabled,
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
                                        $total_pakai += $total_danaGereja;
                                        $total_keseluruhan += $total_danaGereja;
                                        ?>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td style=" font-weight:bolder">Total</td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-end" style="font-weight:bolder"> <?= number_format(
                                                $total,
                                                0,
                                                ",",
                                                ".",
                                            ) ?></td>
                                            <td class="text-end" style=" font-weight:bolder"><?= number_format(
                                                $total_danaGereja,
                                                0,
                                                ",",
                                                ".",
                                            ) ?></td>
                                            <td class="text-end" style="font-weight:bolder"><?= number_format(
                                                $total_danaSwadaya,
                                                0,
                                                ",",
                                                ".",
                                            ) ?></td>
                                            <td class="text-end" style="font-weight:bolder"><?= number_format(
                                                $subTotal,
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
                                                        $color = "#3cb371";
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
                                                            $color = "#3cb371";
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
                                            <td></td>
                                            <td></td>
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
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    <?php
                                    }

                                    $query =
                                        "SELECT SUM(jumlah_pencairan) AS jumlah 
                                    FROM pencairan 
                                    WHERE id_program = " .
                                        intval($data["program"]) .
                                        "  
                                    AND id_fiskal = " .
                                        intval($id_fiskal) .
                                        "
                                    AND id_bidang = " .
                                        intval($data["bidang"]) .
                                        "
                                    AND id_komisi = " .
                                        intval($data["komisi"]);

                                    $view = new cView();
                                    $array = $view->vViewData($query);

                                    $jumlah_pencairan = 0;
                                    if (!empty($array)) {
                                        $jumlah_pencairan = $array[0]["jumlah"];
                                    }
                                    ?>
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
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td style="color:#5B90CD; font-weight:bolder;"><div style="max-width: 250px; white-space: normal;">Jumlah Pencairan Dana Gereja (Per Program)</div></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-end" style="color:#5B90CD; font-weight:bolder"> <?= number_format(
                                            (float) $jumlah_pencairan,
                                            0,
                                            ",",
                                            ".",
                                        ) ?></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td style="color:#5B90CD; font-weight:bolder;"><div style="max-width: 250px; white-space: normal;">Total Pengeluaran <?= $program ?></div></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-end" style="color:#5B90CD; font-weight:bolder"><?= number_format(
                                            (float) ($totalAll_danaGereja ?? 0),
                                            0,
                                            ",",
                                            ".",
                                        ) ?></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <?php
                                        $total_pakai =
                                            $jumlah_pencairan -
                                            $totalAll_danaGereja;
                                        $total_komisi += $total_pakai;
                                        ?>
                                        <td style="color:#a52a2a; font-weight:bolder;"><div style="max-width: 250px; white-space: normal;">Sisa / Kurang</div></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-end" style="color:#a52a2a; font-weight:bolder"><?= number_format(
                                            (float) ($total_pakai ?? 0),
                                            0,
                                            ",",
                                            ".",
                                        ) ?></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                <?php }

                                $query =
                                    "SELECT SUM(jumlah_pencairan) AS jumlah 
                                    FROM pencairan 
                                    WHERE id_komisi = " .
                                    intval($data["komisi"]) .
                                    "  
                                    AND id_fiskal = " .
                                    intval($id_fiskal);

                                $view = new cView();
                                $array = $view->vViewData($query);

                                if (!empty($array)) {
                                    $jumlah_pencairan = $array[0]["jumlah"]; // Ambil data dari baris pertama
                                }
                                ?>
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
                                    <td></td>
                                    <td></td>
                                </tr>
                                  <tr>
                                    <td style="background-color: #f2f9fb"></td>
                                    <td style="background-color: #f2f9fb"></td>
                                    <td style="color:#322E7D; font-weight:bolder; background-color: #f2f9fb;"><div style="max-width: 250px; white-space: normal;">Total Keseluruhan Program</div></td>
                                    <td style="background-color: #f2f9fb"></td>
                                    <td style="background-color: #f2f9fb"></td>
                                    <td style="color:#322E7D; font-weight:bolder; background-color: #f2f9fb" class="text-end"><?= number_format(
                                        (float) ($totalAll_jumlah ?? 0),
                                        0,
                                        ",",
                                        ".",
                                    ) ?></td>
                                    <td style="color:#322E7D; font-weight:bolder; background-color: #f2f9fb" class="text-end"><?= number_format(
                                        (float) ($totalAll_danaGereja ?? 0),
                                        0,
                                        ",",
                                        ".",
                                    ) ?></td>
                                    <td style="color:#322E7D; font-weight:bolder; background-color: #f2f9fb" class="text-end"><?= number_format(
                                        (float) ($totalAll_danaSwadaya ?? 0),
                                        0,
                                        ",",
                                        ".",
                                    ) ?></td>
                                    <td style="color:#322E7D; font-weight:bolder; background-color: #f2f9fb" class="text-end"><?= number_format(
                                        (float) ($totalAll_sumberDana ?? 0),
                                        0,
                                        ",",
                                        ".",
                                    ) ?></td>
                                    <td style="background-color: #f2f9fb"></td>
                                    <td style="background-color: #f2f9fb"></td>
                                    <td style="background-color: #f2f9fb"></td>
                                    <td style="background-color: #f2f9fb"></td>
                                </tr>
                                <tr>
                                    <td style="background-color: #f2f9fb"></td>
                                    <td style="background-color: #f2f9fb"></td>
                                    <td style="background-color: #f2f9fb; color:#2b3e66; font-weight:bolder;"><div style="max-width: 250px; white-space: normal;">Jumlah Pencairan Dana <?= $komisi ?></div></td>
                                    <td style="background-color: #f2f9fb"></td>
                                    <td style="background-color: #f2f9fb"></td>
                                    <td style="background-color: #f2f9fb"></td>
                                    <td style="background-color: #f2f9fb"></td>
                                    <td style="background-color: #f2f9fb"></td>
                                    <td style="background-color: #f2f9fb; color:#2b3e66; font-weight:bolder" class="text-end"><?= number_format(
                                        (float) ($jumlah_pencairan ?? 0),
                                        0,
                                        ",",
                                        ".",
                                    ) ?></td>
                                    <td style="background-color: #f2f9fb"></td>
                                    <td style="background-color: #f2f9fb"></td>
                                    <td style="background-color: #f2f9fb"></td>
                                    <td style="background-color: #f2f9fb"></td>
                                </tr>
                                <tr>
                                    <td style="background-color: #f2f9fb"></td>
                                    <td style="background-color: #f2f9fb"></td>
                                    <td style="background-color: #f2f9fb; color:#2b3e66; font-weight:bolder;"><div style="max-width: 250px; white-space: normal;">Jumlah Pemakaian Dana Pencairan <?= $komisi ?></div></td>
                                    <td style="background-color: #f2f9fb"></td>
                                    <td style="background-color: #f2f9fb"></td>
                                    <td style="background-color: #f2f9fb"></td>
                                    <td style="background-color: #f2f9fb"></td>
                                    <td style="background-color: #f2f9fb"></td>
                                    <td style="background-color: #f2f9fb; color:#2b3e66; font-weight:bolder" class="text-end"><?= number_format(
                                        (float) ($total_pakai ?? 0),
                                        0,
                                        ",",
                                        ".",
                                    ) ?></td>
                                    <td style="background-color: #f2f9fb"></td>
                                    <td style="background-color: #f2f9fb" class="text-center"></td>
                                    <td style="background-color: #f2f9fb"></td>
                                    <td style="background-color: #f2f9fb"></td>
                                </tr>
                        <?php }
                        }
                        ?>
                    </tbody>

                    <?php  ?>

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