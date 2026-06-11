<?php
// update

// delete
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
            $sql = "SELECT a.id_bidang as bidang, a.id_komisi as komisi, a.id_program as program, a.*, b.id_akun, b.nama_akun,c.id_bidang, c.nama_bidang, 
                    d.id_komisi, d.nama_komisi, e.id_anggaran, e.item, g.id_program, g.nama_program, u1.*,
                    u2.nama AS nama_pemroses, u2.jbtn AS jbtn_pemroses,
                    u3.nama AS nama_validator, u3.jbtn AS jbtn_validator FROM pengajuan a 
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
            <div class='table-responsive'>
                <table id='example' class='table table-condensed w-100'>
                    <thead>
                        <tr class='small'>
                            <td width=''></td>
                            <td width=''>Jenis Kegiatan</td>
                            <td width='20%'>Nama Akun</td>
                            <td width='10%' class="text-end">Jumlah Pengajuan</td>
                            <td width="22%" class="text-center">Status</td>
                            <td width='1%' class="text-center"></td>
                            <td width="1%"></td>
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
                                        <td class="text-center" style="background-color: rgb(223, 240, 248);" width='8%'><?= $firstTanggalRow
                                            ? date("d-m-Y", strtotime($tanggal))
                                            : "" ?></td>
                                        <td style="background-color: rgb(223, 240, 248);" width="20%"><?= $bidang ?></td>
                                        <td style="background-color: rgb(223, 240, 248);"><?= $komisi ?></td>
                                        <td style="background-color: rgb(223, 240, 248);"></td>
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
                                            <td width='8%' class="text-center"><?= $number ?></td>
                                            <td colspan="" style="background-color:#f2f3f4; font-weight: bold;" width="20%">Program : <?= $program ?></td>
                                            <td style="background-color: #f2f3f4;"></td>
                                            <td style="background-color: #f2f3f4;"></td>
                                            <td style="background-color: #f2f3f4;"></td>
                                            <td style="background-color: #f2f3f4;"></td>
                                            <td style="background-color: #f2f3f4;"></td>
                                            <td style="background-color: #f2f3f4;" width='5%'></td>
                                            
                                            
                                        </tr>
                                        <?php
                                        $total = 0;
                                        $id_pengajuan = [];
                                        $status_counts = [];

                                        foreach ($programList as $data) {

                                            $cnourut = $cnourut + 1;
                                            $id_pengajuan[] =
                                                $data["id_pengajuan"];
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
                                                <td></td>
                                                <td></td>
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
                                                            ],
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
                                                            $data[
                                                                "deskripsi_pengajuan"
                                                            ],
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
                                                                "nama_validator"
                                                            ] .
                                                            " - " .
                                                            $data[
                                                                "jbtn_validator"
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
                                        $all_ids_string = implode(
                                            ",",
                                            $id_pengajuan,
                                        );
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
                                                    count($status_counts) == 1
                                                ) {
                                                    $single_status = array_key_first(
                                                        $status_counts,
                                                    );
                                                    $color = "black";
                                                    switch ($single_status) {
                                                        case "Disetujui":
                                                            $color = "#2e8b57";
                                                            break;
                                                        case "Diproses":
                                                            $color = "#007bb8";
                                                            break;
                                                        case "Menunggu":
                                                            $color = "#91a3b0";
                                                            break;
                                                        case "Tidak Disetujui":
                                                            $color = "#b22222";
                                                            break;
                                                        case "Disetujui dan Dana telah Cair":
                                                            $color = "#2453a3";
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
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            
                                            
                                        </tr>
                                        <tr>
                                            <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
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