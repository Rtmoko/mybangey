<?php
require_once 'koneksi.php';
require_once 'helpers.php';
require_login();

$id = (int)($_GET['id'] ?? 0);
$stmt = $mysqli->prepare("SELECT * FROM barang WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
if (!$data) die('Barang tidak ditemukan.');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $peminjam = trim($_POST['peminjam']);
    $jumlah = (int)$_POST['jumlah'];
    $catatan = trim($_POST['catatan']);

    if ($peminjam === '' || $jumlah <= 0) {
        $error = 'Isi peminjam dan jumlah dengan benar.';
    } else {

        // Insert transaksi kembali
        $stmt = $mysqli->prepare("INSERT INTO transaksi (barang_id, peminjam, jenis, jumlah, catatan) VALUES (?, ?, 'kembali', ?, ?)");
        $stmt->bind_param('isis', $id, $peminjam, $jumlah, $catatan);

        if ($stmt->execute()) {

            // Tambahkan stok namun tidak boleh melebihi jumlah total
            $stmt2 = $mysqli->prepare("UPDATE barang SET tersedia = LEAST(jumlah, tersedia + ?) WHERE id = ?");
            $stmt2->bind_param('ii', $jumlah, $id);
            $stmt2->execute();

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
    <title>Kembalikan Barang</title>

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
            max-width: 480px;
            margin: 40px auto;
            background: #ffffff;
            padding: 28px;
            border-radius: 12px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.12);
        }

        h2 {
            margin-bottom: 12px;
            font-size: 24px;
        }

        /* LABEL */
        label {
            display: block;
            font-weight: bold;
            font-size: 14px;
            margin-top: 10px;
            margin-bottom: 4px;
        }

        /* INPUT & TEXTAREA */
        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 9px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            margin-bottom: 8px;
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
            margin-top: 12px;
        }

        button:hover {
            background-color: #217dbb;
        }

        /* ERROR MESSAGE */
        .error {
            color: red;
            margin-bottom: 10px;
        }

        /* LINK */
        a {
            color: #6c3483;
            text-decoration: none;
            font-size: 14px;
            display: inline-block;
            margin-top: 14px;
        }

        a:hover {
            text-decoration: underline;
        }

    </style>

</head>

<body>
    <div class="container">

        <h2>Kembalikan: <?= htmlspecialchars($data['nama']) ?></h2>

        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="post">

            <label>Nama Pengembali (atau nama peminjam)</label>
            <input type="text" name="peminjam" required>

            <label>Jumlah yang Dikembalikan</label>
            <input type="number" name="jumlah" value="1" min="1" required>

            <label>Catatan</label>
            <textarea name="catatan"></textarea>

            <button type="submit">Kembalikan</button>

        </form>

        <a href="index.php">Kembali</a>

    </div>
</body>

</html>
