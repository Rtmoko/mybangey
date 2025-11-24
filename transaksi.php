<?php
require_once 'koneksi.php';
require_once 'helpers.php';
require_login();

$sql = "SELECT t.*, b.nama AS nama_barang FROM transaksi t JOIN barang b ON t.barang_id = b.id ORDER BY t.tanggal DESC";
$res = $mysqli->query($sql);
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Transaksi</title>
    <style>
        /* Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, Helvetica, sans-serif;
    background-color: #f3f4f6;
    padding: 40px 0;
    color: #1f2937;
}

/* Container */
.container {
    max-width: 1100px;
    margin: 0 auto;
    background: #ffffff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
}

/* Judul */
h1 {
    font-size: 28px;
    margin-bottom: 15px;
    color: #111827;
}

/* Tombol kembali */
.back a {
    color: #2563eb;
    text-decoration: none;
    font-weight: 600;
}

.back a:hover {
    text-decoration: underline;
}

/* Tabel */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td {
    padding: 12px 14px;
    border-bottom: 1px solid #e5e7eb;
    text-align: left;
    font-size: 14px;
}

th {
    background-color: #f1f5f9;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 0.03em;
}

/* Zebra striping */
tbody tr:nth-child(even) {
    background-color: #f9fafb;
}

/* Hover effect */
tbody tr:hover {
    background-color: #eef2ff;
}

/* Kolom catatan */
td.catatan {
    max-width: 350px;
    word-wrap: break-word;
}
    </style>
</head>

<body>
    <div class="container">
        <h2>Daftar Transaksi</h2>
        <p><a href="index.php">Kembali</a></p>
        <table border="1" cellpadding="6" cellspacing="0">
            <tr>

                <th>Tanggal</th>
                <th>Barang</th>
                <th>Peminjam</th>
                <th>Jenis</th>
                <th>Jumlah</th>
                <th>Catatan</th>
            </tr>
            <?php while ($row = $res->fetch_assoc()): ?>
                <tr>

                    <td><?= $row['tanggal'] ?></td>
                    <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                    <td><?= htmlspecialchars($row['peminjam']) ?></td>
                    <td><?= $row['jenis'] ?></td>
                    <td><?= $row['jumlah'] ?></td>
                    <td><?= htmlspecialchars($row['catatan']) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>

</html>