<?php
include_once "../_function_i/inc_f_object.php"; ?>

<?php
//untuk ganti urlbase berdasarkan pilihan role
$role = $_POST["role"] ?? 0;
$urlbase_value = "";
$sqlRole = "SELECT urlbase FROM role WHERE id_role = $role";

$view = new cView();

$result = $view->vViewData($sqlRole);

if (!empty($result) && is_array($result) && count($result) > 0) {
    $row = $result[0];
    $urlbase_value = $row["urlbase"];
}
?>
<?php // insert
if (!empty($_POST["savebtn"])) {
    $datafield = [
        "nama",
        "username",
        "password",
        "role",
        "jbtn",
        "urlbase",
        "status_aktif",
    ];
    $datavalue = [
        "'" . $_POST["nama"] . "'",
        "'" . $_POST["username"] . "'",
        "'" . md5($_POST["password"]) . "'",
        $_POST["role"],
        "'" . $_POST["jbtn"] . "'",
        "'" . $urlbase_value . "'",
        $_POST["status_aktif"],
    ];
    $insert = new cInsert();
    $insert->vInsertData($datafield, "user", $datavalue);
} ?>

<?php // update
if (!empty($_POST["editbtn"])) {
    $linkurl = "";
    $datafield = [
        "id_user",
        "nama",
        "username",
        "role",
        "jbtn",
        "urlbase",
        "status_aktif",
    ];
    $datavalue = [
        $_POST["id_user"],
        "'" . $_POST["nama"] . "'",
        "'" . $_POST["username"] . "'",
        $_POST["role"],
        "'" . $_POST["jbtn"] . "'",
        "'" . $urlbase_value . "'",
        $_POST["status_aktif"],
    ];
    $datakey = " id_user =" . $_POST["id_user"] . "";
    $update = new cUpdate();
    $update->vUpdateData($datafield, "user", $datavalue, $datakey, $linkurl);
} ?>

<?php // delete


if (!empty($_POST["btnhapus"])) {
    $delete = new cDelete();
    $delete->_dDeleteData(
        $_POST["hiddendeletevalue0"],
        $_POST["hiddendeletevalue1"],
        $_POST["hiddendeletevalue2"],
    );
} // reset password
if (!empty($_POST["btnreset"])) {
    $datafield = ["password"];
    $datavalue = ["'" . md5("123") . "'"];
    $datakey =
        "" .
        $_POST["hiddenresetvalue0"] .
        "=" .
        $_POST["hiddenresetvalue1"] .
        "";
    $update = new cUpdate();
    $update->vUpdateData(
        $datafield,
        $_POST["hiddenresetvalue2"],
        $datavalue,
        $datakey,
        "",
    );
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <?php _myHeader("Folder", "User", "Data User"); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php
            $afield = [
                ["Nama", "nama", "", 19],
                ["Username", "username", "", 1],
                ["Password", "password", "", 1],
                [
                    "Role",
                    "role",
                    "",
                    4,
                    "select id_role field1, nama_role field2 from role",
                ],
                ["Jabatan", "jbtn", "", 1],
                ["URL Base", "urlbase", "", 2],
                ["", "status_aktif", 1, 2],
            ];
            $caption = ["Data User", "Entri Data User"];
            // $number, $type, $name, $button, $width, $height, $title, $acaption, $afield, $value, $linkurl
            _CreateModalInsert(
                0,
                "insert",
                "insert-form",
                "insert-button",
                "lg",
                "",
                "Tambah Data",
                $caption,
                $afield,
                "",
                "14",
            );
            ?>
        </div>
    </div>
    <p></p>
    <div class="row">
        <div class="col-md-12">
            <?php
            $sql =
                "SELECT a.*, b.* FROM user a LEFT OUTER JOIN role b ON a.role = b.id_role";
            $view = new cView();
            $array = $view->vViewData($sql);
            ?>
            <div id="" class='table-responsive'>
                <table id='example' class='table table-condensed w-100'>
                    <thead>
                        <tr class='small'>
                            <td width='5%' class="text-right">No</td>
                            <td width=''>Nama</td>
                            <td width=''>Role</td>
                            <td width=''>Jabatan</td>
                            <td width='20%' class="text-center">Status Aktif</td>
                            <td width='5%' class="text-center">DETAIL</td>
                            <td width='5%' class="text-center">EDIT</td>
                            <td width='5%' class="text-center">HAPUS</td>
                            <td width='9%' class="text-center"></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $cnourut = 0;
                        foreach ($array as $data) {
                            $cnourut = $cnourut + 1; ?>
                            <tr class=''>
                                <td class="text-right"><?= $cnourut ?></td>
                                <td><?= $data["nama"] ?></td>
                                <td><?= $data["nama_role"] ?></td>
                                <td><?= $data["jbtn"] ?></td>
                                <td class="text-center" style="font-weight: bold; color: #474747"><?= $data[
                                    "status_aktif"
                                ] == 1
                                    ? "Aktif"
                                    : "Tidak Aktif" ?></td>
                                <td class="text-center">
                                    <?php
                                    $datadetail = [
                                        ["Nama", ":", $data["nama"], 1, ""],
                                        [
                                            "Username",
                                            ":",
                                            $data["username"],
                                            1,
                                            "",
                                        ],
                                        [
                                            "Role",
                                            ":",
                                            $data["nama_role"],
                                            1,
                                            "",
                                        ],
                                        ["Jabatan", ":", $data["jbtn"], 1, ""],
                                        [
                                            "URL Base",
                                            ":",
                                            $data["urlbase"],
                                            1,
                                            "",
                                        ],
                                        [
                                            "Status Aktif",
                                            ":",
                                            $data["status_aktif"] == 1
                                                ? "Aktif"
                                                : "Tidak Aktif",
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
                                        "Detail Data User#Data User $cnourut : " .
                                            $data["nama"],
                                        "",
                                        $datadetail,
                                        "",
                                        "14",
                                        "",
                                    );
                                    ?>
                                </td>
                                <td class="text-center">
                                    <?php
                                    $dataupdate = [
                                        [
                                            "ID",
                                            "id_user",
                                            $data["id_user"],
                                            2,
                                            "",
                                        ],
                                        ["Nama", "nama", $data["nama"], 19, ""],
                                        [
                                            "Username",
                                            "username",
                                            $data["username"],
                                            1,
                                        ],
                                        [
                                            "Role",
                                            "role",
                                            $data["role"],
                                            4,
                                            "select id_role field1, nama_role field2 from role",
                                        ],
                                        ["Jabatan", "jbtn", $data["jbtn"], 1],
                                        [
                                            "Status Aktif",
                                            "status_aktif",
                                            $data["status_aktif"],
                                            7,
                                        ],
                                    ]; // $number, $type, $name, $button, $width, $height, $title, $acaption, $afield, $value, $linkurl
                                    _CreateWindowModalUpdate(
                                        "edit" . $cnourut,
                                        "edit",
                                        "edit-form",
                                        "edit-button",
                                        "",
                                        "",
                                        "Edit Data User#Data User $cnourut : " .
                                            $data["nama"],
                                        "",
                                        $dataupdate,
                                        "",
                                        "14",
                                    );
                                    ?>
                                </td>
                                <td class="text-center">
                                    <?php
                                    $datadelete = [
                                        ["id_user", $data["id_user"], "user"],
                                    ]; //$number, $type, $name, $button, $width, $height, $title, $acaption, $value, $linkurl
                                    _CreateWindowModalDelete(
                                        $cnourut,
                                        "del",
                                        "del-form",
                                        "del-button",
                                        "md",
                                        200,
                                        "Hapus Data User#Data User $cnourut : " .
                                            $data["nama"],
                                        "",
                                        $datadelete,
                                        "14",
                                    );
                                    ?>
                                </td>
                                <td class="text-center">
                                    <?php
                                    $datareset = [
                                        ["id_user", $data["id_user"], "user"],
                                    ]; //$number, $type, $name, $button, $width, $height, $title, $acaption, $value, $linkurl
                                    _CreateWindowModalReset(
                                        $cnourut,
                                        "reset",
                                        "reset-form",
                                        "reset-button",
                                        "md",
                                        200,
                                        "Reset Password#Data User $cnourut : " .
                                            $data["nama"],
                                        "",
                                        $datareset,
                                        "",
                                    );
                                    ?>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>