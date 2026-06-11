<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "cConnect.php";
$connection = new cConnect();
$connection->goConnect();

//untuk ambil komisi per bidang
if (isset($_POST["bidang"])) {
    $bidang = intval($_POST["bidang"]);

    // Pastikan nilai tidak 0 sebelum ditaruh ke query
    $sql = "select * from komisi where id_bidang=$bidang";
    $hasil = mysqli_query($GLOBALS["conn"], $sql);
    echo '<option value="">-- Pilih Komisi --</option>';
    while ($data = mysqli_fetch_array($hasil)) { ?>
        <option value="<?php echo $data["id_komisi"]; ?>"><?php echo $data[
    "nama_komisi"
]; ?></option>
    <?php }
    exit();
}

//untuk ambil program per komisi atau bidang
if (isset($_POST["komisiProgram"]) && isset($_POST["tahun"])) {
    $komisi = intval($_POST["komisiProgram"]);
    $tahun = intval($_POST["tahun"]);

    $sql = "SELECT program.*, fiskal.tahun 
            FROM program 
            JOIN fiskal ON program.id_fiskal = fiskal.id_fiskal 
            WHERE program.id_komisi = $komisi AND fiskal.tahun = $tahun";

    $hasil = mysqli_query($GLOBALS["conn"], $sql);

    echo '<option value="">-- Pilih Program --</option>';
    while ($data = mysqli_fetch_array($hasil)) {
        echo '<option value="' .
            $data["id_program"] .
            '">' .
            $data["nama_program"] .
            "</option>";
    }
} elseif (isset($_POST["bidangProgram"]) && isset($_POST["tahun"])) {
    $bidang = intval($_POST["bidangProgram"]);
    $tahun = intval($_POST["tahun"]);

    $sql = "SELECT program.*, fiskal.tahun 
            FROM program 
            JOIN fiskal ON program.id_fiskal = fiskal.id_fiskal 
            WHERE program.id_bidang = $bidang  AND fiskal.tahun = $tahun";

    $hasil = mysqli_query($GLOBALS["conn"], $sql);

    echo '<option value="">-- Pilih Program --</option>';
    while ($data = mysqli_fetch_array($hasil)) {
        echo '<option value="' .
            $data["id_program"] .
            '">' .
            $data["nama_program"] .
            "</option>";
    }
    exit();
}

//untuk program realisasi bidang/komisi
if (isset($_POST["komisiProgramRealisasi"]) && isset($_POST["tahun"])) {
    $komisi = intval($_POST["komisiProgramRealisasi"]);
    $tahun = intval($_POST["tahun"]);

    $sql = "SELECT program.*, fiskal.tahun 
            FROM program 
            JOIN fiskal ON program.id_fiskal = fiskal.id_fiskal 
            WHERE program.id_komisi = $komisi AND fiskal.tahun = $tahun";

    $hasil = mysqli_query($GLOBALS["conn"], $sql);

    echo '<option value="">-- Pilih Program --</option>';
    echo '<option value="0">Insidental</option>';
    while ($data = mysqli_fetch_array($hasil)) {
        echo '<option value="' .
            $data["id_program"] .
            '">' .
            $data["nama_program"] .
            "</option>";
    }
} elseif (isset($_POST["bidangProgramRealisasi"]) && isset($_POST["tahun"])) {
    $bidang = intval($_POST["bidangProgramRealisasi"]);
    $tahun = intval($_POST["tahun"]);

    $sql = "SELECT program.*, fiskal.tahun 
            FROM program 
            JOIN fiskal ON program.id_fiskal = fiskal.id_fiskal 
            WHERE program.id_bidang = $bidang  AND fiskal.tahun = $tahun";

    $hasil = mysqli_query($GLOBALS["conn"], $sql);

    echo '<option value="">-- Pilih Program --</option>';
    echo '<option value="0">Insidental</option>';
    while ($data = mysqli_fetch_array($hasil)) {
        echo '<option value="' .
            $data["id_program"] .
            '">' .
            $data["nama_program"] .
            "</option>";
    }
    exit();
}

//untuk form pencairan pemilihan program
if (isset($_POST["komisiPencairan"]) && isset($_POST["tahun"])) {
    $komisi = intval($_POST["komisiPencairan"]);
    $tahun = intval($_POST["tahun"]);

    $sql = "SELECT pengajuan.*, program.nama_program, fiskal.tahun, cair.total_cair, total_pengajuan.total_pengajuan
            FROM pengajuan
            JOIN fiskal ON pengajuan.id_fiskal = fiskal.id_fiskal 
            JOIN program ON pengajuan.id_program = program.id_program

            -- Total pencairan per program
            LEFT JOIN ( SELECT id_program, SUM(jumlah_pencairan) AS total_cair FROM pencairan
            GROUP BY id_program) AS cair ON cair.id_program = pengajuan.id_program

            -- Total pengajuan per program
            JOIN (SELECT id_program, SUM(jumlah_pengajuan) AS total_pengajuan FROM pengajuan
            GROUP BY id_program) AS total_pengajuan ON total_pengajuan.id_program = pengajuan.id_program

            WHERE pengajuan.id_komisi = $komisi AND fiskal.tahun = $tahun
            AND (pengajuan.status = 'Disetujui' OR (
                pengajuan.status = 'Disetujui dan Dana telah Cair' 
                AND (cair.total_cair IS NULL OR cair.total_cair < total_pengajuan.total_pengajuan)
            )
        )
        GROUP BY program.id_program ORDER BY program.nama_program ASC";

    $hasil = mysqli_query($GLOBALS["conn"], $sql);

    echo '<option value="">-- Pilih Program --</option>';
    echo '<option value="0">Insidental</option>';
    while ($data = mysqli_fetch_array($hasil)) {
        echo '<option value="' .
            $data["id_program"] .
            '">' .
            $data["nama_program"] .
            "</option>";
    }
    exit();
} elseif (isset($_POST["bidangPencairan"]) && isset($_POST["tahun"])) {
    $bidang = intval($_POST["bidangPencairan"]);
    $tahun = intval($_POST["tahun"]);

    $sql = "SELECT pengajuan.*, program.nama_program, fiskal.tahun, cair.total_cair, total_pengajuan.total_pengajuan
            FROM pengajuan
            JOIN fiskal ON pengajuan.id_fiskal = fiskal.id_fiskal 
            JOIN program ON pengajuan.id_program = program.id_program

            -- Total pencairan per program
            LEFT JOIN ( SELECT id_program, SUM(jumlah_pencairan) AS total_cair FROM pencairan
            GROUP BY id_program) AS cair ON cair.id_program = pengajuan.id_program

            -- Total pengajuan per program
            JOIN (SELECT id_program, SUM(jumlah_pengajuan) AS total_pengajuan FROM pengajuan
            GROUP BY id_program) AS total_pengajuan ON total_pengajuan.id_program = pengajuan.id_program

            WHERE pengajuan.id_bidang = $bidang AND fiskal.tahun = $tahun
            AND (pengajuan.status = 'Disetujui' OR (
                pengajuan.status = 'Disetujui dan Dana telah Cair' 
                AND (cair.total_cair IS NULL OR cair.total_cair < total_pengajuan.total_pengajuan)
            )
        )
        GROUP BY program.id_program ORDER BY program.nama_program ASC";

    $hasil = mysqli_query($GLOBALS["conn"], $sql);

    echo '<option value="">-- Pilih Program --</option>';
    echo '<option value="0">Insidental</option>';
    while ($data = mysqli_fetch_array($hasil)) {
        echo '<option value="' .
            $data["id_program"] .
            '">' .
            $data["nama_program"] .
            "</option>";
    }
    exit();
}

//untuk jumlah pengajuan per program di halaman pencairan
if (isset($_POST["programPencairan"]) && isset($_POST["tahun"])) {
    $id_program = intval($_POST["programPencairan"]);
    $tahun = intval($_POST["tahun"]);

    if (isset($_POST["komisiCair"])) {
        $komisi = intval($_POST["komisiCair"]);

        $sql = "SELECT 
                    (SELECT SUM(jumlah_pengajuan) FROM pengajuan 
                     LEFT JOIN fiskal ON pengajuan.id_fiskal = fiskal.id_fiskal
                     WHERE id_program = $id_program AND id_komisi = $komisi AND fiskal.tahun = $tahun 
                     AND (status = 'Disetujui' OR status = 'Disetujui dan Dana telah Cair')) AS total_pengajuan,
                    (SELECT SUM(jumlah_pencairan) FROM pencairan 
                     LEFT JOIN fiskal ON pencairan.id_fiskal = fiskal.id_fiskal
                     WHERE id_program = $id_program AND id_komisi = $komisi AND fiskal.tahun = $tahun) AS total_pencairan";
    } elseif (isset($_POST["bidangCair"])) {
        $bidang = intval($_POST["bidangCair"]);

        $sql = "SELECT 
                    (SELECT SUM(jumlah_pengajuan) FROM pengajuan 
                     LEFT JOIN fiskal ON pengajuan.id_fiskal = fiskal.id_fiskal
                     WHERE id_program = $id_program AND id_bidang = $bidang AND fiskal.tahun = $tahun 
                     AND (status = 'Disetujui' OR status = 'Disetujui dan Dana telah Cair')) AS total_pengajuan,
                    (SELECT SUM(jumlah_pencairan) FROM pencairan 
                     LEFT JOIN fiskal ON pencairan.id_fiskal = fiskal.id_fiskal
                     WHERE id_program = $id_program AND id_bidang = $bidang AND fiskal.tahun = $tahun) AS total_pencairan";
    }

    // Eksekusi query jika query sudah terbentuk
    if (isset($sql)) {
        $hasil = mysqli_query($GLOBALS["conn"], $sql);
        $data = mysqli_fetch_array($hasil);

        $total_pengajuan = $data["total_pengajuan"]
            ? $data["total_pengajuan"]
            : 0;
        $total_pencairan = $data["total_pencairan"]
            ? $data["total_pencairan"]
            : 0;
        $sisa_pencairan = $total_pengajuan - $total_pencairan;

        echo $sisa_pencairan;
        exit();
    }
}

//untuk form pengajuan

//pemilihan jenis anggaran berdasarkan program untuk halaman pengajuan
if (isset($_POST["program"])) {
    $program = intval($_POST["program"]);

    $sql = "select * from rencana_pengeluaran_komisi where id_program = $program";

    $hasil = mysqli_query($GLOBALS["conn"], $sql);
    $no = 0;
    echo '<option value="">-- Pilih Jenis Kegiatan --</option>'; // Opsi default
    while ($data = mysqli_fetch_array($hasil)) { ?>
        <option value="<?php echo $data["id_anggaran"]; ?>"><?php echo $data[
    "item"
]; ?></option>
<?php }
    exit();
}

//pemilihan jumlah pengajuan berdasarkan rencana anggaran
if (isset($_POST["anggaran"])) {
    $id_anggaran = intval($_POST["anggaran"]);

    $sql = "SELECT dana_gereja FROM rencana_pengeluaran_komisi WHERE id_anggaran = $id_anggaran";

    $hasil1 = mysqli_query($GLOBALS["conn"], $sql);
    $data = mysqli_fetch_array($hasil1);

    echo $data["dana_gereja"];

    exit();
}

//bagian laporan
if (isset($_POST["filter"])) {
    $filter = $_POST["filter"];
    $_SESSION["filter"] = $filter;
    echo "filter yang diterima: " . $filter;
} else {
    echo "filter tidak diterima";
}

if (isset($_POST["bulan"])) {
    $bulan = $_POST["bulan"];
    $_SESSION["bulan"] = $bulan;
    echo "Bulan yang diterima: " . $bulan;
} else {
    echo "Bulan tidak diterima";
}

if (isset($_POST["tahun"])) {
    $tahun = $_POST["tahun"];
    $_SESSION["tahun"] = $tahun;
    echo "tahun yang diterima: " . $tahun;
} else {
    echo "tahun tidak diterima";
}

if (isset($_POST["tahun_aktif"])) {
    $tahun_aktif = $_POST["tahun_aktif"];
    $_SESSION["tahun_aktif"] = $tahun_aktif;
    echo "tahun_aktif yang diterima: " . $tahun_aktif;
} else {
    echo "tahun_aktif tidak diterima";
}

//untuk session bidang dan komisi di laporan
if (isset($_POST["bidang"])) {
    $bidang = $_POST["bidang"];
    $_SESSION["bidang"] = $bidang;
    echo "bidang yang diterima: " . $bidang;
} else {
    echo "bidang tidak diterima";
}

?>
