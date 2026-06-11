<?php
if (!empty($_POST["btnproses"])) {
    $tanggalProses = date("Y-m-d");

    $datafield = ["status", "id_pemroses", "tanggal_proses"];
    $datavalue = ["'Diproses'", $id_user, "'" . $tanggalProses . "'"];

    $update = new cUpdate();
    $update->_pengajuanStatus(
        $_POST["hiddenupdatevalue0"],
        $_POST["hiddenupdatevalue1"],
        $_POST["hiddenupdatevalue2"],
        $datafield,
        $datavalue,
    );
} elseif (!empty($_POST["btnsetuju"])) {
    $tanggalSetuju = date("Y-m-d");

    $datafield = ["status"];
    $datavalue = ["'Disetujui'"];

    $update = new cUpdate();
    $update->_pengajuanStatus(
        $_POST["hiddenupdatevalue0"],
        $_POST["hiddenupdatevalue1"],
        $_POST["hiddenupdatevalue2"],
        $datafield,
        $datavalue,
    );
} elseif (!empty($_POST["btntolak"])) {
    $tanggalTolak = date("Y-m-d");

    $datafield = ["status"];
    $datavalue = ["'Tidak Disetujui'"];

    $update = new cUpdate();
    $update->_pengajuanStatus(
        $_POST["hiddenupdatevalue0"],
        $_POST["hiddenupdatevalue1"],
        $_POST["hiddenupdatevalue2"],
        $datafield,
        $datavalue,
    );
}

// bulk proses semua pengajuan yang berstatus Menunggu
if (!empty($_POST["btn_bulk_proses"])) {
    $sql_bulk = "SELECT id_pengajuan FROM pengajuan WHERE id_fiskal = $id_fiskal AND status = 'Menunggu'";
    $view_bulk = new cView();
    $array_bulk = $view_bulk->vViewData($sql_bulk);
    if (!empty($array_bulk)) {
        $ids_bulk = implode(",", array_column($array_bulk, "id_pengajuan"));
        $tanggalProses = date("Y-m-d");
        $datafield_bulk = ["status", "id_pemroses", "tanggal_proses"];
        $datavalue_bulk = ["'Diproses'", $id_user, "'" . $tanggalProses . "'"];
        $update_bulk = new cUpdate();
        $update_bulk->_pengajuanStatus(
            "id_pengajuan",
            $ids_bulk,
            "pengajuan",
            $datafield_bulk,
            $datavalue_bulk,
        );
    } else {
        echo "<script>Swal.fire({position: 'center', width: '25em', icon: 'info', text: 'Tidak ada transaksi yang perlu diproses.'}).then(function(){ window.location = ''; });</script>";
    }
}

// bulk setujui semua pengajuan yang berstatus Diproses
if (!empty($_POST["btn_bulk_setuju"])) {
    $sql_bulk = "SELECT id_pengajuan FROM pengajuan WHERE id_fiskal = $id_fiskal AND status = 'Diproses'";
    $view_bulk = new cView();
    $array_bulk = $view_bulk->vViewData($sql_bulk);
    if (!empty($array_bulk)) {
        $ids_bulk = implode(",", array_column($array_bulk, "id_pengajuan"));
        $datafield_bulk = ["status"];
        $datavalue_bulk = ["'Disetujui'"];
        $update_bulk = new cUpdate();
        $update_bulk->_pengajuanStatus(
            "id_pengajuan",
            $ids_bulk,
            "pengajuan",
            $datafield_bulk,
            $datavalue_bulk,
        );
    } else {
        echo "<script>Swal.fire({position: 'center', width: '25em', icon: 'info', text: 'Tidak ada transaksi yang perlu disetujui.'}).then(function(){ window.location = ''; });</script>";
    }
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <?php _myHeader("newspaper", "Pengajuan", "Data Pengajuan"); ?>
        </div>
    </div>

    <p></p>
    <div class="row">
        <div class="col-md-12">
            <?php
            $sql = "SELECT a.id_program as program, a.*, b.id_akun, b.nama_akun,c.id_bidang, c.nama_bidang, 
                    d.id_komisi, d.nama_komisi, e.id_anggaran, e.item, g.id_program, g.nama_program, u1.*,
                    u2.nama AS nama_pemroses, u2.jbtn AS jbtn_pemroses,
                    u3.nama AS nama_penyetuju, u3.jbtn AS jbtn_penyetuju FROM pengajuan a 
                    LEFT JOIN akun b ON a.id_akun = b.id_akun 
                    LEFT JOIN bidang c ON a.id_bidang = c.id_bidang 
                    LEFT JOIN komisi d ON a.id_komisi = d.id_komisi 
                    LEFT JOIN rencana_pengeluaran_komisi e ON a.id_anggaran = e.id_anggaran  
                    LEFT JOIN program g ON e.id_program = g.id_program 
                    LEFT JOIN user u1 ON a.id_user = u1.id_user 
                    LEFT JOIN user u2 ON a.id_pemroses = u2.id_user 
                    LEFT JOIN user u3 ON a.id_penyetuju = u3.id_user ";

            $sql .=
                "WHERE a.id_fiskal = " .
                $id_fiskal .
                " ORDER BY tanggal_pengajuan DESC";

            $view = new cView();
            $array = $view->vViewData($sql);
            ?>
            <?php if ($status_aktif_fiskal == 1): ?>
            <?php
            // Hitung jumlah Menunggu dan Diproses untuk disable tombol
            $jml_menunggu = 0;
            $jml_diproses = 0;
            if (!empty($array)) {
                foreach ($array as $row) {
                    if ($row["status"] == "Menunggu") {
                        $jml_menunggu++;
                    }
                    if ($row["status"] == "Diproses") {
                        $jml_diproses++;
                    }
                }
            }
            ?>
            <div style="margin-bottom: 10px;">
                <form action="" method="post" style="display:inline;" <?= $jml_menunggu >
                0
                    ? "onsubmit=\"return confirm('Proses semua pengajuan yang berstatus Menunggu?');\""
                    : "" ?>>
                    <button type="submit" name="btn_bulk_proses" value="1" <?= $jml_menunggu ==
                    0
                        ? "disabled"
                        : "" ?> style="background-color: <?= $jml_menunggu > 0
     ? "#49749C"
     : "#9ca3af" ?>; color:white; border-radius:4px; border:none; padding:6px 12px; <?= $jml_menunggu >
0
    ? "cursor:pointer;"
    : "cursor:not-allowed;" ?>">
                        Proses Semua
                    </button>
                </form>
                <form action="" method="post" style="display:inline; margin-left: 5px;" <?= $jml_diproses >
                0
                    ? "onsubmit=\"return confirm('Setujui semua pengajuan yang berstatus Diproses?');\""
                    : "" ?>>
                    <button type="submit" name="btn_bulk_setuju" value="1" <?= $jml_diproses ==
                    0
                        ? "disabled"
                        : "" ?> style="background-color: <?= $jml_diproses > 0
     ? "#2e8b57"
     : "#9ca3af" ?>; color:white; border-radius:4px; border:none; padding:6px 12px; <?= $jml_diproses >
0
    ? "cursor:pointer;"
    : "cursor:not-allowed;" ?>">
                        ✔ Setujui Semua
                    </button>
                </form>
            </div>
            <?php endif; ?>
            <div class='table-responsive'>
                <table id='example' class='table table-condensed w-100'>
                    <thead>
                        <tr class='small'>
                            <td width='3%'></td>
                            <td width=''>Jenis Kegiatan</td>
                            <td width='25%'>Nama Akun</td>
                            <td width='15%' class="text-end">Jumlah Pengajuan</td>
                            <td width="15%" class="text-center">Status</td>
                            <td width="10%" class="text-center"></td>
                            <td width='5%' class="text-center">DETAIL</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $cnourut = 0;
                        $groupedData = [];

                        foreach ($array as $data) {
                            $tanggal = $data["tanggal_pengajuan"];
                            $bidang = $data["nama_bidang"];
                            $komisi = $data["nama_komisi"];
                            $program = $data["nama_program"];

                            if ($program == 0) {
                                $program = "Insidental";
                            }

                            $groupedData[$tanggal][$bidang][$komisi][
                                $program
                            ][] = $data;
                        }

                        $number = 0;
                        foreach ($groupedData as $tanggal => $tanggalList) {
                            $firstTanggalRow = true;
                            foreach ($tanggalList as $bidang => $bidangList) {
                                foreach (
                                    $bidangList
                                    as $komisi => $komisiList
                                ) { ?>
                                    <tr style="font-weight: bold;">
                                        <td class="text-center" style="background-color: rgb(223, 240, 248);" width='7%'><?= $firstTanggalRow
                                            ? date("d-m-Y", strtotime($tanggal))
                                            : "" ?></td>
                                        <td style="background-color: rgb(223, 240, 248);" width="20%"><?= $bidang ?></td>
                                        <td style="background-color: rgb(223, 240, 248);"><?= $komisi ?></td>
                                        <td style="background-color: rgb(223, 240, 248);"></td>
                                        <td style="background-color: rgb(223, 240, 248);"></td>
                                        <td style="background-color: rgb(223, 240, 248);"></td>
                                        <td style="background-color: rgb(223, 240, 248);" width='5%'></td>
                                    </tr>
                                    <?php foreach (
                                        $komisiList
                                        as $program => $programList
                                    ) {
                                        $number = $number + 1; ?>
                                        <tr>
                                            <td width='5%' class="text-center"><?= $number ?></td>
                                            <td style="background-color:#f2f3f4; font-weight: bold;" width="20%">Program : <?= $program ?></td>
                                            <td style="background-color: #f2f3f4;"></td>
                                            <td style="background-color: #f2f3f4;"></td>
                                            <td style="background-color: #f2f3f4;"></td>
                                            <td style="background-color: #f2f3f4;"></td>
                                            <td style="background-color: #f2f3f4;" width='5%'></td>
                                        </tr>
                                        <?php
                                        $total = 0;
                                        $status_counts = [];

                                        $id_pengajuan_menunggu = [];
                                        $id_pengajuan_diproses = [];
                                        foreach ($programList as $data) {

                                            $cnourut = $cnourut + 1;
                                            if ($data["status"] == "Menunggu") {
                                                $id_pengajuan_menunggu[] =
                                                    $data["id_pengajuan"];
                                            } elseif (
                                                $data["status"] == "Diproses"
                                            ) {
                                                $id_pengajuan_diproses[] =
                                                    $data["id_pengajuan"];
                                            }
                                            $st = $data["status"];
                                            if (!isset($status_counts[$st])) {
                                                $status_counts[$st] = 0;
                                            }
                                            $status_counts[$st]++;
                                            ?>
                                            <tr class=''>

                                                <td></td>
                                                <td>
                                                    <?php if (
                                                        $data["nama_program"] ==
                                                        0
                                                    ) {
                                                        echo $data[
                                                            "jenis_kegiatan"
                                                        ];
                                                    } else {
                                                        echo $data["item"];
                                                    } ?>
                                                </td>
                                                <td><?= $data[
                                                    "nama_akun"
                                                ] ?></td>
                                                <td class="text-end"><?= number_format(
                                                    $data["jumlah_pengajuan"],
                                                    0,
                                                    ",",
                                                    ".",
                                                ) ?></td>
                                                <td class="text-center" style="font-size: 0.9em; vertical-align: middle;">
                                                    <span style="font-weight:650; color: <?= $data[
                                                        "status"
                                                    ] == "Disetujui"
                                                        ? "#2e8b57"
                                                        : ($data["status"] ==
                                                        "Diproses"
                                                            ? "#007bb8"
                                                            : ($data[
                                                                "status"
                                                            ] == "Menunggu"
                                                                ? "#91a3b0"
                                                                : ($data[
                                                                    "status"
                                                                ] ==
                                                                "Tidak Disetujui"
                                                                    ? "#b22222"
                                                                    : "#2453a3"))) ?>;"><?= $data[
    "status"
] ?></span>
                                                </td>
                                                <td class="text-center">
                                                    <?php if (
                                                        $data["status"] ==
                                                        "Disetujui dan Dana telah Cair"
                                                    ): ?>
                                                        <!-- No action needed -->
                                                    <?php else: ?>
                                                        <?php
                                                        $datavalid_indiv = [
                                                            [
                                                                "id_pengajuan",
                                                                $data[
                                                                    "id_pengajuan"
                                                                ],
                                                                "pengajuan",
                                                            ],
                                                        ];
                                                        if (
                                                            $data["status"] ==
                                                            "Menunggu"
                                                        ) {
                                                            $caption =
                                                                "Ingin proses pengajuan ini ?";
                                                            $disabled =
                                                                $status_aktif_fiskal ==
                                                                1
                                                                    ? false
                                                                    : true;
                                                            _CreateWindowModalProses(
                                                                "indiv_" .
                                                                    $cnourut,
                                                                "val",
                                                                "val-form",
                                                                "val-button",
                                                                "sm",
                                                                200,
                                                                "Proses Pengajuan# " .
                                                                    $data[
                                                                        "nama_akun"
                                                                    ] .
                                                                    " : Rp. " .
                                                                    number_format(
                                                                        $data[
                                                                            "jumlah_pengajuan"
                                                                        ],
                                                                        0,
                                                                        ",",
                                                                        ".",
                                                                    ),
                                                                $datavalid_indiv,
                                                                "24",
                                                                $disabled,
                                                                "Proses",
                                                                $caption,
                                                            );
                                                        } elseif (
                                                            $data["status"] ==
                                                            "Diproses"
                                                        ) {
                                                            $caption =
                                                                "Ingin menyetujui pengajuan ini ?";
                                                            $disabled =
                                                                $status_aktif_fiskal ==
                                                                1
                                                                    ? false
                                                                    : true;
                                                            _CreateWindowModalValid(
                                                                "indiv_" .
                                                                    $cnourut,
                                                                "val",
                                                                "val-form",
                                                                "val-button",
                                                                "sm",
                                                                200,
                                                                "Persetujuan Pengajuan# " .
                                                                    $data[
                                                                        "nama_akun"
                                                                    ] .
                                                                    " : Rp. " .
                                                                    number_format(
                                                                        $data[
                                                                            "jumlah_pengajuan"
                                                                        ],
                                                                        0,
                                                                        ",",
                                                                        ".",
                                                                    ),
                                                                $datavalid_indiv,
                                                                "24",
                                                                "Setujui",
                                                                $caption,
                                                                $disabled,
                                                            );
                                                        } else {
                                                            $caption =
                                                                "Ingin menyetujui pengajuan ini ?";
                                                            _CreateWindowModalValid(
                                                                "indiv_" .
                                                                    $cnourut,
                                                                "val",
                                                                "val-form",
                                                                "val-button",
                                                                "sm",
                                                                200,
                                                                "Persetujuan Pengajuan# " .
                                                                    $data[
                                                                        "nama_akun"
                                                                    ] .
                                                                    " : Rp. " .
                                                                    number_format(
                                                                        $data[
                                                                            "jumlah_pengajuan"
                                                                        ],
                                                                        0,
                                                                        ",",
                                                                        ".",
                                                                    ),
                                                                $datavalid_indiv,
                                                                "24",
                                                                "Setujui",
                                                                $caption,
                                                                true,
                                                            );
                                                        }
                                                        ?>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php
                                                    $datadetail = [
                                                        [
                                                            "Tanggal Pengajuan",
                                                            ":",
                                                            date(
                                                                "d-m-Y",
                                                                strtotime(
                                                                    $data[
                                                                        "tanggal_pengajuan"
                                                                    ],
                                                                ),
                                                            ),
                                                            1,
                                                            "",
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
                                                            ] ?? "-",
                                                            1,
                                                            "",
                                                        ],
                                                        [
                                                            "Program",
                                                            ":",
                                                            $data[
                                                                "nama_program"
                                                            ] == 0
                                                                ? "Insidental"
                                                                : $data[
                                                                    "nama_program"
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
                                                            "Jenis Kegiatan",
                                                            ":",
                                                            $data[
                                                                "nama_program"
                                                            ] == 0
                                                                ? $data[
                                                                    "jenis_kegiatan"
                                                                ]
                                                                : $data["item"],
                                                            1,
                                                            "",
                                                        ],
                                                        [
                                                            "Jumlah Pengajuan",
                                                            ":",
                                                            "Rp. " .
                                                            number_format(
                                                                $data[
                                                                    "jumlah_pengajuan"
                                                                ],
                                                                0,
                                                                ",",
                                                                ".",
                                                            ),
                                                            1,
                                                            "",
                                                        ],
                                                        [
                                                            "Penanggung Jawab",
                                                            ":",
                                                            $data[
                                                                "penanggungJawab_pengajuan"
                                                            ],
                                                            1,
                                                            "",
                                                        ],
                                                        [
                                                            "Keterangan",
                                                            ":",
                                                            !empty(
                                                                $data[
                                                                    "deskripsi_pengajuan"
                                                                ]
                                                            )
                                                                ? $data[
                                                                    "deskripsi_pengajuan"
                                                                ]
                                                                : "-",
                                                            1,
                                                            "",
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
                                                            "Status",
                                                            ":",
                                                            $data["status"],
                                                            1,
                                                            "",
                                                        ],
                                                        [
                                                            "Tanggal Proses",
                                                            ":",
                                                            $data[
                                                                "tanggal_proses"
                                                            ] == "0000-00-00"
                                                                ? "-"
                                                                : date(
                                                                    "d-m-Y",
                                                                    strtotime(
                                                                        $data[
                                                                            "tanggal_proses"
                                                                        ],
                                                                    ),
                                                                ),
                                                            1,
                                                            ":",
                                                        ],
                                                        [
                                                            "Diproses oleh",
                                                            ":",
                                                            $data[
                                                                "nama_pemroses"
                                                            ] .
                                                            " - " .
                                                            $data[
                                                                "jbtn_pemroses"
                                                            ],
                                                            1,
                                                        ],
                                                        [
                                                            "Tanggal Pencairan",
                                                            ":",
                                                            $data[
                                                                "tanggal_transfer"
                                                            ] == "0000-00-00"
                                                                ? "-"
                                                                : date(
                                                                    "d-m-Y",
                                                                    strtotime(
                                                                        $data[
                                                                            "tanggal_transfer"
                                                                        ],
                                                                    ),
                                                                ),
                                                            1,
                                                            "",
                                                        ],
                                                        [
                                                            "Dicairkan oleh",
                                                            ":",
                                                            $data[
                                                                "nama_penyetuju"
                                                            ] .
                                                            " - " .
                                                            $data[
                                                                "jbtn_penyetuju"
                                                            ],
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
                                                        "Detail Data Pengajuan#Data Pengajuan $cnourut",
                                                        "",
                                                        $datadetail,
                                                        "",
                                                        "23",
                                                        "",
                                                    );
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php $total +=
                                            $data["jumlah_pengajuan"];
                                        }
                                        ?>
                                        
                                        <tr>

                                            <td></td>
                                            <td style="color:#5B90CD; font-weight:bolder">Total</td>
                                            <td></td>

                                            <td class="text-end" style="color:#5B90CD; font-weight:bolder"><?= number_format(
                                                $total,
                                                0,
                                                ",",
                                                ".",
                                            ) ?></td>
                                            <?php
                                            $query =
                                                "SELECT pengajuan.status, 
                                            (SELECT SUM(pencairan.jumlah_pencairan) FROM pencairan
                                            WHERE pencairan.id_program = pengajuan.id_program
                                            AND pencairan.id_fiskal = pengajuan.id_fiskal
                                            AND (pencairan.id_komisi = pengajuan.id_komisi OR 
                                            (pengajuan.id_komisi IS NULL AND pencairan.id_bidang = pengajuan.id_bidang))
                                            ) AS jumlah_pencairan
                                            FROM pengajuan
                                            WHERE pengajuan.id_program = " .
                                                intval($data["program"]) .
                                                "
                                            AND pengajuan.id_fiskal = $id_fiskal";

                                            if (!empty($data["komisi"])) {
                                                $query .=
                                                    " AND pengajuan.id_komisi = " .
                                                    intval($data["komisi"]);
                                            } elseif (!empty($data["bidang"])) {
                                                $query .=
                                                    " AND pengajuan.id_bidang = " .
                                                    intval($data["bidang"]);
                                            }

                                            $query .= " LIMIT 1";

                                            $view = new cView();
                                            $array = $view->vViewData($query);
                                            if (!empty($array)) {
                                                $jumlah_pencairan =
                                                    $array[0][
                                                        "jumlah_pencairan"
                                                    ];
                                                $status_pengajuan =
                                                    $data["status"];
                                            }
                                            ?>

                                            <td class="text-center" style="font-weight:650; white-space:nowrap; border-right:none;">
                                                <?php if (
                                                    $status_pengajuan ==
                                                    "Disetujui dan Dana telah Cair"
                                                ): ?>
                                                    <span style="color: #2453a3;">Rp. <?= number_format(
                                                        $jumlah_pencairan,
                                                        0,
                                                        ",",
                                                        ".",
                                                    ) ?></span>
                                                <?php else: ?>
                                                    <?php if (
                                                        count($status_counts) ==
                                                        1
                                                    ) {
                                                        $single_status = array_key_first(
                                                            $status_counts,
                                                        );
                                                        $color = "black";
                                                        switch (
                                                            $single_status
                                                        ) {
                                                            case "Disetujui":
                                                                $color =
                                                                    "#2e8b57";
                                                                break;
                                                            case "Diproses":
                                                                $color =
                                                                    "#007bb8";
                                                                break;
                                                            case "Menunggu":
                                                                $color =
                                                                    "#91a3b0";
                                                                break;
                                                            case "Tidak Disetujui":
                                                                $color =
                                                                    "#b22222";
                                                                break;
                                                            case "Disetujui dan Dana telah Cair":
                                                                $color =
                                                                    "#2453a3";
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
                                                                case "Disetujui":
                                                                    $color =
                                                                        "#2e8b57";
                                                                    break;
                                                                case "Diproses":
                                                                    $color =
                                                                        "#007bb8";
                                                                    break;
                                                                case "Menunggu":
                                                                    $color =
                                                                        "#91a3b0";
                                                                    break;
                                                                case "Tidak Disetujui":
                                                                    $color =
                                                                        "#b22222";
                                                                    break;
                                                                case "Disetujui dan Dana telah Cair":
                                                                    $color =
                                                                        "#2453a3";
                                                                    break;
                                                            }
                                                            echo "<div style='color: $color; font-size:0.9em; margin-bottom:2px;'>$count $st</div>";
                                                        }
                                                    } ?>
                                                <?php endif; ?>
                                            </td>
                                            <td style="border-left:none;"></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-center" style="white-space:nowrap; border-right:none;">
                                                <?php if (
                                                    $status_pengajuan !=
                                                    "Disetujui dan Dana telah Cair"
                                                ): ?>
                                                    <?php
                                                    $has_menunggu =
                                                        isset(
                                                            $status_counts[
                                                                "Menunggu"
                                                            ],
                                                        ) &&
                                                        $status_counts[
                                                            "Menunggu"
                                                        ] > 0;
                                                    $has_diproses =
                                                        isset(
                                                            $status_counts[
                                                                "Diproses"
                                                            ],
                                                        ) &&
                                                        $status_counts[
                                                            "Diproses"
                                                        ] > 0;

                                                    if ($has_menunggu) {
                                                        $datavalid = [
                                                            [
                                                                "id_pengajuan",
                                                                implode(
                                                                    ",",
                                                                    $id_pengajuan_menunggu,
                                                                ),
                                                                "pengajuan",
                                                            ],
                                                        ];
                                                        $caption =
                                                            "Ingin proses pengajuan ini ?";
                                                        $disabled =
                                                            $status_aktif_fiskal ==
                                                            1
                                                                ? false
                                                                : true;
                                                        _CreateWindowModalProses(
                                                            $cnourut,
                                                            "val",
                                                            "val-form",
                                                            "val-button",
                                                            "sm",
                                                            200,
                                                            "Proses Pengajuan# Program : " .
                                                                $data[
                                                                    "nama_program"
                                                                ] .
                                                                "#Total Pengajuan: Rp. " .
                                                                number_format(
                                                                    $total,
                                                                    0,
                                                                    ",",
                                                                    ".",
                                                                ),
                                                            $datavalid,
                                                            "24",
                                                            $disabled,
                                                            "Proses",
                                                            $caption,
                                                        );
                                                    } elseif ($has_diproses) {
                                                        $datavalid = [
                                                            [
                                                                "id_pengajuan",
                                                                implode(
                                                                    ",",
                                                                    $id_pengajuan_diproses,
                                                                ),
                                                                "pengajuan",
                                                            ],
                                                        ];
                                                        $caption =
                                                            "Ingin menyetujui pengajuan ini ?";
                                                        $disabled =
                                                            $status_aktif_fiskal ==
                                                            1
                                                                ? false
                                                                : true;
                                                        _CreateWindowModalValid(
                                                            $cnourut,
                                                            "val",
                                                            "val-form",
                                                            "val-button",
                                                            "sm",
                                                            200,
                                                            "Persetujuan Pengajuan# Program : " .
                                                                $data[
                                                                    "nama_program"
                                                                ] .
                                                                "#Total Pengajuan: Rp. " .
                                                                number_format(
                                                                    $total,
                                                                    0,
                                                                    ",",
                                                                    ".",
                                                                ),
                                                            $datavalid,
                                                            "24",
                                                            "Setujui",
                                                            $caption,
                                                            $disabled,
                                                        );
                                                    } else {
                                                        $datavalid = [
                                                            [
                                                                "id_pengajuan",
                                                                "",
                                                                "pengajuan",
                                                            ],
                                                        ];
                                                        $caption =
                                                            "Ingin menyetujui pengajuan ini ?";
                                                        _CreateWindowModalValid(
                                                            $cnourut,
                                                            "val",
                                                            "val-form",
                                                            "val-button",
                                                            "sm",
                                                            200,
                                                            "Persetujuan Pengajuan# Program : " .
                                                                $data[
                                                                    "nama_program"
                                                                ] .
                                                                "#Total Pengajuan: Rp. " .
                                                                number_format(
                                                                    $total,
                                                                    0,
                                                                    ",",
                                                                    ".",
                                                                ),
                                                            $datavalid,
                                                            "24",
                                                            "Setujui",
                                                            $caption,
                                                            true,
                                                        );
                                                    }
                                                    ?>
                                                <?php endif; ?>
                                            </td>
                                            <td style="border-left:none;"></td>
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
                                        </tr>
                        <?php
                                    }}
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>