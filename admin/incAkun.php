<?php
include_once "../_function_i/inc_f_object.php"; ?>

<?php
// insert
if (!empty($_POST["savebtn"])) {
    $id_jenisAkun = $_POST["jenis_akun"];

    $insert = new cInsert();

    if (isset($id_jenisAkun) && strpos($id_jenisAkun, "-dataBaru") !== false) {
        $jenis_akun_baru = str_replace("-dataBaru", "", $id_jenisAkun);
        $datafield = ["jenis_akun", "status_aktif"];
        $datavalue = ["'" . $jenis_akun_baru . "'", 1];
        $id_jenisAkun = $insert->vInsertDataArray(
            $datafield,
            "akunjenis",
            $datavalue,
        );
    } else {
        $id_jenisAkun = $_POST["jenis_akun"];
    }

    $datafield = [
        "jenis_akun",
        "kode_akun",
        "nama_akun",
        "jenis_debitKredit",
        "deskripsi",
        "statusAktif",
        "status_input",
    ];
    $datavalue = [
        $id_jenisAkun,
        $_POST["kode_akun"],
        $_POST["nama_akun"],
        $_POST["jenis_debitKredit"],
        $_POST["deskripsi"],
        $_POST["statusAktif"],
        $_POST["status_input"],
    ];
    $insert->fInsertData($datafield, "akun", $datavalue, "");
}

// update
if (!empty($_POST["editbtn"])) {
    $linkurl = "";

    $datafield = [
        "jenis_akun",
        "kode_akun",
        "nama_akun",
        "deskripsi",
        "jenis_debitKredit",
        "statusAktif",
        "status_input",
    ];
    $datavalue = [
        $_POST["jenis_akun"],
        $_POST["kode_akun"],
        "'" . $_POST["nama_akun"] . "'",
        "'" . $_POST["deskripsi"] . "'",
        "'" . $_POST["jenis_debitKredit"] . "'",
        $_POST["statusAktif"],
        $_POST["status_input"],
    ];

    $datakey = " id_akun =" . $_POST["id_akun"] . "";

    $update = new cUpdate();
    $update->vUpdateData($datafield, "akun", $datavalue, $datakey, $linkurl);
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
            <?php _myHeader("folder", "Akun (COA)", "Data Akun (COA)"); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6">
                    <?php
                    $afield = [
                        [
                            "Jenis Akun",
                            "jenis_akun",
                            "",
                            224,
                            "select id_jenisAkun as field1, jenis_akun as field2 from akunjenis",
                        ],
                        ["Kode Akun", "kode_akun", "", 111],
                        ["Nama Akun", "nama_akun", "", 1],
                        [
                            "Debet / Kredit",
                            "jenis_debitKredit",
                            "",
                            5,
                            "select 'Debet' as field1, 'Debet' as field2 UNION select 'Kredit' as field1, 'Kredit' as field2",
                        ],
                        ["Deskripsi", "deskripsi", "", 17],
                        ["Status Aktif", "statusAktif", "", 7],
                        ["Status Input", "status_input", "", 7],
                    ];
                    $caption = ["Data Akun", "Entri Data Akun"];
                    _CreateModalInsert(
                        0,
                        "insert",
                        "insert-form",
                        "insert-button",
                        800,
                        550,
                        "Tambah Data",
                        $caption,
                        $afield,
                        "",
                        "",
                    );
                    ?>
                </div>

                <div class="col-md-6 d-flex justify-content-end align-items-center">
                    <div style="display: flex; align-items: center; gap: 0px; color: #002e63;">
                        <p style="margin: 0;"><b>Daftar Pos Akun:</b></p>
                        <a href="121"
                            style=" display: inline-flex; align-items: center; justify-content: center; padding: 6px 15px; background-color: transparent; border-radius: 4px; text-decoration: underline; font-weight: 600;">
                            Data Pos Akun
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<div style="border-radius:7px; width:100%; padding: 10px 0; justify-content:center; border:1px solid #00008b; font-weight:bold; color:#003153">
    <form action="" method="post" style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 0;">
        <div style="display: flex; gap: 50px; align-items: center; margin-left:20px;  ">
            <label>
                <input type="radio" name="filter" value="penerimaan"
                    <?php echo isset($_POST["filter"]) &&
                    $_POST["filter"] == "penerimaan"
                        ? "checked"
                        : ""; ?>
                    onchange="this.form.submit()"> Penerimaan
            </label>
            <label>
                <input type="radio" name="filter" value="pengeluaran"
                    <?php echo isset($_POST["filter"]) &&
                    $_POST["filter"] == "pengeluaran"
                        ? "checked"
                        : ""; ?>
                    onchange="this.form.submit()"> Pengeluaran
            </label>
        </div>
    </form>
</div>

<p></p>
<div class="row">
    <div class="col-md-12">
        <?php
        if (isset($_POST["filter"]) && $_POST["filter"] == "penerimaan") {
            $sql =
                "SELECT a.*, b.jenis_akun as jenis FROM akun a  JOIN akunjenis b on a.jenis_akun = b.id_jenisAkun WHERE jenis_debitKredit = 'Kredit' ORDER BY kode_akun ASC";
        } elseif (
            isset($_POST["filter"]) &&
            $_POST["filter"] == "pengeluaran"
        ) {
            $sql =
                "SELECT a.*, b.jenis_akun as jenis FROM akun a  JOIN akunjenis b on a.jenis_akun = b.id_jenisAkun WHERE jenis_debitKredit = 'Debet' ORDER BY kode_akun ASC";
        } elseif (!isset($_POST["filter"])) {
            $sql =
                "SELECT a.*, b.jenis_akun as jenis FROM akun a  JOIN akunjenis b on a.jenis_akun = b.id_jenisAkun ORDER BY kode_akun ASC";
        }

        $view = new cView();
        $array = $view->vViewData($sql);
        ?>

        <div id="" class='table-responsive'>
            <table id='example' class='table table-condensed w-100'>
                <thead>
                    <tr class='small'>
                        <td width='5%' class="text-right">No</td>
                        <td width=''>Kode Akun</td>
                        <td width=''>Nama Akun</td>
                        <td width=''>Debet /Kredit</td>
                        <td width=''>Status Aktif</td>
                        <td width=''>Status Input</td>
                        <td width='5%' class="text-center">DETAIL</td>
                        <td width='5%' class="text-center">EDIT</td>
                        <td width='5%' class="text-center">HAPUS</td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $cnourut = 0;
                    $groupedData = [];

                    foreach ($array as $data) {
                        $jenis_debitKredit = $data["jenis_debitKredit"];
                        $jenis = $data["jenis"];
                        $groupedData[$jenis_debitKredit][$jenis][] = $data;
                    }

                    foreach (
                        $groupedData
                        as $jenis_debitKredit => $debetKredit
                    ) { ?>
                        <tr style="font-weight: bold;">
                            <td style="background-color: #dcdcdc;"></td>
                            <td style="background-color: #dcdcdc;" width="10%"><?= $jenis_debitKredit ===
                            "Kredit"
                                ? "Penerimaan"
                                : "Pengeluaran" ?></td>
                            <td style="background-color: #dcdcdc;"></td>
                            <td style="background-color: #dcdcdc;"></td>
                            <td style="background-color: #dcdcdc;"></td>
                            <td style="background-color: #dcdcdc;"></td>
                            <td style="background-color: #dcdcdc;"></td>
                            <td style="background-color: #dcdcdc;"></td>
                            <td style="background-color: #dcdcdc;"></td>
                        </tr>
                        <?php foreach (
                            $debetKredit
                            as $jenis => $jenisAkun
                        ) { ?>
                            <tr style="font-weight: bold;">
                                <td></td>
                                <td style="background-color: #f4f0ec;" width="10%"><?= $jenis ?></td>
                                <td style="background-color: #f4f0ec;"></td>
                                <td style="background-color: #f4f0ec;"></td>
                                <td style="background-color: #f4f0ec;"></td>
                                <td style="background-color: #f4f0ec;"></td>
                                <td style="background-color: #f4f0ec;"></td>
                                <td style="background-color: #f4f0ec;"></td>
                                <td style="background-color: #f4f0ec;"></td>
                            </tr>
                            <?php foreach ($jenisAkun as $data) {
                                $cnourut = $cnourut + 1; ?>
                                <tr class=''>
                                    <td class="text-right"><?= $cnourut ?></td>
                                    <td><?= $data["kode_akun"] ?></td>
                                    <td><?= $data["nama_akun"] ?></td>
                                    <td><?= $data["jenis_debitKredit"] ?></td>
                                    <td><?= $data["statusAktif"] == 1
                                        ? "Aktif"
                                        : "Tidak Aktif" ?></td>
                                    <td><?= $data["status_input"] == 1
                                        ? "Ya"
                                        : "Tidak" ?></td>
                                    <td class="text-center">
                                        <?php
                                        $datadetail = [
                                            [
                                                "Kode Akun",
                                                ":",
                                                $data["kode_akun"],
                                                1,
                                                "",
                                            ],
                                            [
                                                "Nama Akun",
                                                ":",
                                                $data["nama_akun"],
                                                1,
                                                "",
                                            ],
                                            [
                                                "Jenis Akun",
                                                ":",
                                                $data["jenis"],
                                                1,
                                                "",
                                            ],
                                            [
                                                "Debet/Kredit",
                                                ":",
                                                $data["jenis_debitKredit"],
                                                1,
                                                "",
                                            ],
                                            [
                                                "Deskripsi",
                                                ":",
                                                $data["deskripsi"],
                                                3,
                                                "",
                                            ],
                                            [
                                                "Status Aktif",
                                                ":",
                                                $data["statusAktif"] == 1
                                                    ? "Aktif"
                                                    : "Tidak Aktif",
                                                1,
                                                "",
                                            ],
                                            [
                                                "Status Input",
                                                ":",
                                                $data["status_input"] == 1
                                                    ? "Ya"
                                                    : "Tidak",
                                                1,
                                                "",
                                            ],
                                        ];
                                        _CreateWindowModalDetil(
                                            $cnourut,
                                            "view",
                                            "viewsasaran-form",
                                            "viewsasaran-button",
                                            "",
                                            600,
                                            "Detail Data Akun#Data Akun $cnourut : " .
                                                $data["nama_akun"],
                                            "",
                                            $datadetail,
                                            "",
                                            "12",
                                            "",
                                        );
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        $dataupdate = [
                                            [
                                                "id",
                                                "id_akun",
                                                $data["id_akun"],
                                                2,
                                                "",
                                            ],
                                            [
                                                "Jenis Akun",
                                                "jenis_akun",
                                                $data["jenis_akun"],
                                                5,
                                                "select id_jenisAkun as field1, jenis_akun as field2 from akunjenis",
                                            ],
                                            [
                                                "Kode Akun",
                                                "kode_akun",
                                                $data["kode_akun"],
                                                1,
                                                "",
                                            ],
                                            [
                                                "Nama Akun",
                                                "nama_akun",
                                                $data["nama_akun"],
                                                1,
                                                "",
                                            ],
                                            [
                                                "Debet / Kredit",
                                                "jenis_debitKredit",
                                                $data["jenis_debitKredit"],
                                                5,
                                                "select 'Debet' as field1, 'Debet' as field2 UNION select 'Kredit' as field1, 'Kredit' as field2",
                                            ],
                                            [
                                                "Deskripsi",
                                                "deskripsi",
                                                $data["deskripsi"],
                                                17,
                                                "",
                                            ],
                                            [
                                                "Status Aktif",
                                                "statusAktif",
                                                $data["statusAktif"],
                                                7,
                                                "",
                                            ],
                                            [
                                                "Status Input",
                                                "status_input",
                                                $data["status_input"],
                                                7,
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
                                            "Edit Data Akun#Data Akun $cnourut : " .
                                                $data["nama_akun"],
                                            "",
                                            $dataupdate,
                                            "",
                                            "12",
                                        );
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        $datadelete = [
                                            [
                                                "id_akun",
                                                $data["id_akun"],
                                                "akun",
                                            ],
                                        ];
                                        _CreateWindowModalDelete(
                                            $cnourut,
                                            "del",
                                            "del-form",
                                            "del-button",
                                            "md",
                                            200,
                                            "Hapus Data Akun#Data Akun  $cnourut : " .
                                                $data["nama_akun"],
                                            "",
                                            $datadelete,
                                            "",
                                        );
                                        ?>
                                    </td>
                                </tr>
                    <?php
                            }}}
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
</div>