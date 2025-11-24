<?php
require_once 'koneksi.php';
require_once 'helpers.php';
require_login();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $deskripsi = trim($_POST['deskripsi']);
    $jumlah = (int)$_POST['jumlah'];
    $lokasi = trim($_POST['lokasi']);
    $kode = trim($_POST['kode']);

    if ($nama === '' || $jumlah < 0) $error = 'Nama dan jumlah harus diisi dengan benar.';
    else {
        $tersedia = $jumlah;
        $stmt = $mysqli->prepare("INSERT INTO barang (nama, deskripsi, jumlah, tersedia, lokasi, kode) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssiiss', $nama, $deskripsi, $jumlah, $tersedia, $lokasi, $kode);
        if ($stmt->execute()) {
            header('Location: index.php');
            exit;
        } else {
            $error = "Gagal menyimpan: " . $mysqli->error;
        }
    }
}
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Tambah Barang</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        /* ====== FORM TAMBAH / EDIT BARANG ====== */

body {
    font-family: Arial, Helvetica, sans-serif;
    background-color: #f3f4f6;
    padding: 40px 0;
    color: #1f2937;
}

.container {
    max-width: 600px;
    margin: 0 auto;
    background: #ffffff;
    padding: 28px 32px;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    display: flex;
    justify-content: center;
}

/* Judul halaman */
h1, h2 {
    font-size: 26px;
    margin-bottom: 20px;
    color: #111827;
}

/* Wrapper form */
.form-group {
    margin-bottom: 14px;
}

.form-group label {
    display: block;
    margin-bottom: 4px;
    font-weight: 600;
    font-size: 14px;
    color: #374151;
}

.form-group input[type="text"],
.form-group input[type="number"],
.form-group textarea {
    width: 100%;
    padding: 8px 10px;
    border-radius: 6px;
    border: 1px solid #d1d5db;
    font-size: 14px;
    outline: none;
    background-color: #f9fafb;
}

.form-group textarea {
    min-height: 70px;
    resize: vertical;
}

.form-group input:focus,
.form-group textarea:focus {
    border-color: #2563eb;
    background-color: #ffffff;
    box-shadow: 0 0 0 1px rgba(37, 99, 235, 0.2);
}

/* Tombol */
.btn-row {
    margin-top: 10px;
}

.btn {
    display: inline-block;
    padding: 8px 16px;
    border-radius: 999px;
    border: none;
    font-size: 14px;
    cursor: pointer;
    font-weight: 600;
    margin-right: 8px;
}

.btn-primary {
    background-color: #2563eb;
    color: #ffffff;
}

.btn-secondary {
    background-color: #e5e7eb;
    color: #374151;
}

.btn:hover {
    filter: brightness(0.95);
}
    </style>
</head>

<body>
    <div class="container">
        <div class="albin">
            <h2>Tambah Barang</h2>
            <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
            <form method="post">
                <label>Nama Barang</label><br>
                <input type="text" name="nama" required><br>
                <label>Kode (unik)</label><br>
                <input type="text" name="kode"><br>
                <label>Deskripsi</label><br>
                <textarea name="deskripsi"></textarea><br>
                <label>Jumlah</label><br>
                <input type="number" name="jumlah" value="1" min="0" required><br>
                <label>Lokasi</label><br>
                <input type="text" name="lokasi"><br><br>
                <button type="submit">Simpan</button>
                <button type="submit" onclick="window.location.href='index.php'">Kembali</button>
            </form>
        </div>

    </div>
</body>

</html>