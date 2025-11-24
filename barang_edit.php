<?php
require_once 'koneksi.php';
require_once 'helpers.php';
require_login();

$id = (int)($_GET['id'] ?? 0);
$stmt = $mysqli->prepare("SELECT * FROM barang WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
if (!$data = $res->fetch_assoc()) {
    die("Barang tidak ditemukan.");
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $deskripsi = trim($_POST['deskripsi']);
    $jumlah = (int)$_POST['jumlah'];
    $lokasi = trim($_POST['lokasi']);
    $kode = trim($_POST['kode']);

    // adjust 'tersedia' bila total jumlah berubah
    $selisih = $jumlah - $data['jumlah'];
    $tersedia = $data['tersedia'] + $selisih;
    if ($tersedia < 0) $tersedia = 0;

    $stmt = $mysqli->prepare("UPDATE barang SET nama=?, deskripsi=?, jumlah=?, tersedia=?, lokasi=?, kode=? WHERE id=?");
    $stmt->bind_param('ssiissi', $nama, $deskripsi, $jumlah, $tersedia, $lokasi, $kode, $id);
    if ($stmt->execute()) {
        header('Location: index.php');
        exit;
    } else {
        $error = "Gagal update: " . $mysqli->error;
    }
}
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Edit Barang</title>

    <style>
        /* RESET */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            background-color: #f4f4f4;
        }

        /* CONTAINER */
        .container {
            max-width: 500px;
            margin: 40px auto;
            background: #ffffff;
            padding: 28px;
            border-radius: 12px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.12);
        }

        .container h2 {
            margin-bottom: 14px;
            font-size: 24px;
        }

        /* FORM GROUP */
        .form-group {
            margin-bottom: 12px;
        }

        label {
            display: block;
            font-weight: bold;
            font-size: 15px;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 9px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.2s;
        }

        input:focus,
        textarea:focus {
            border-color: #3498db;
            outline: none;
        }

        textarea {
            min-height: 70px;
            resize: vertical;
        }

        /* BUTTON */
        button {
            padding: 9px 20px;
            background-color: #3498db;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
            margin-top: 10px;
        }

        button:hover {
            background-color: #217dbb;
        }

        /* BACK LINK */
        .back-link {
            display: inline-block;
            margin-top: 14px;
            color: #6c3483;
            text-decoration: none;
            font-size: 14px;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>

</head>

<body>
    <div class="container">
        <h2>Edit Barang</h2>

        <?php if ($error): ?>
            <p style="color:red; margin-bottom:10px;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="post">

            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="nama" value="<?= htmlspecialchars($data['nama']) ?>" required>
            </div>

            <div class="form-group">
                <label>Kode</label>
                <input type="text" name="kode" value="<?= htmlspecialchars($data['kode']) ?>">
            </div>

            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi"><?= htmlspecialchars($data['deskripsi']) ?></textarea>
            </div>

            <div class="form-group">
                <label>Jumlah (total)</label>
                <input type="number" name="jumlah" value="<?= $data['jumlah'] ?>" min="0" required>
            </div>

            <div class="form-group">
                <label>Lokasi</label>
                <input type="text" name="lokasi" value="<?= htmlspecialchars($data['lokasi']) ?>">
            </div>

            <button type="submit">Update</button>
        </form>

        <a class="back-link" href="index.php">Kembali</a>
    </div>
</body>

</html>
