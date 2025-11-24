<?php
require_once 'koneksi.php';
require_once 'helpers.php';
require_login();

// ambil list barang
$result = $mysqli->query("SELECT * FROM barang ORDER BY created_at DESC");
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Inventaris Barang</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        /* Reset ringan */
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {
    font-family: Arial, Helvetica, sans-serif;
    background-color: #f3f4f6;
    color: #111827;
    padding: 40px 0;
}

/* Bungkus semua konten di <div class="container"> ... */
.container {
    max-width: 1100px;
    margin: 0 auto;
    background: #ffffff;
    padding: 24px 32px 32px;
    border-radius: 10px;
    box-shadow: 0 10px 25px rgba(15, 23, 42, 0.08);
}

/* Judul halaman */
h1 {
    font-size: 32px;
    margin-bottom: 4px;
}

.subtitle {
    margin-bottom: 16px;
    color: #6b7280;
}

/* Menu link di atas tabel, bungkus di <div class="menu"> */
.menu {
    margin-bottom: 16px;
}

.menu a {
    margin-right: 12px;
    text-decoration: none;
    color: #2563eb;
    font-weight: 600;
}

.menu a:hover {
    text-decoration: underline;
}

/* Tabel utama */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 8px;
    font-size: 14px;
}

th, td {
    padding: 10px 12px;
    border-bottom: 1px solid #e5e7eb;
    text-align: left;
}

th {
    background-color: #f9fafb;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 0.03em;
}

/* Baris bergaris zebra */
tbody tr:nth-child(even) {
    background-color: #f9fafb;
}

/* Efek hover baris */
tbody tr:hover {
    background-color: #eef2ff;
}

/* Kolom aksi, beri class="aksi" pada <td> terakhir */
td.aksi a {
    font-size: 13px;
    text-decoration: none;
    padding: 4px 8px;
    border-radius: 999px;
    border: 1px solid transparent;
    margin-right: 4px;
}

/* Warna untuk masing-masing aksi (opsional) */
td.aksi a:nth-child(1) { /* Edit */
    background-color: #dbeafe;
    border-color: #bfdbfe;
    color: #1d4ed8;
}

td.aksi a:nth-child(2) { /* Hapus */
    background-color: #fee2e2;
    border-color: #fecaca;
    color: #b91c1c;
}

td.aksi a:nth-child(3) { /* Pinjam */
    background-color: #dcfce7;
    border-color: #bbf7d0;
    color: #15803d;
}

td.aksi a:nth-child(4) { /* Kembalikan */
    background-color: #fef3c7;
    border-color: #fde68a;
    color: #92400e;
}

td.aksi a:hover {
    filter: brightness(0.95);
}
    </style>
</head>

<body>
    <div class="container">
        <h1>Inventaris Barang</h1>

        </p>
        <p>
            <a href="barang_add.php">Tambah Barang</a> |
            <a href="transaksi.php">Lihat Transaksi</a> | 
            <a href="logout.php">Logout</a> | 
            <a href="buat_admin.php">Buat Admin</a>
        </p>

        <table border="1" cellpadding="6" cellspacing="0">
            <tr>

                <th>Kode</th>
                <th>Nama</th>
                <th>Deskripsi</th>
                <th>Jumlah</th>
                <th>Tersedia</th>
                <th>Lokasi</th>
                <th>Aksi</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>

                    <td><?= htmlspecialchars($row['kode']) ?></td>
                    <td><?= htmlspecialchars($row['nama']) ?></td>
                    <td><?= nl2br(htmlspecialchars($row['deskripsi'])) ?></td>
                    <td><?= $row['jumlah'] ?></td>
                    <td><?= $row['tersedia'] ?></td>
                    <td><?= htmlspecialchars($row['lokasi']) ?></td>
                    <td>
                        <a href="barang_edit.php?id=<?= $row['id'] ?>">Edit</a> |
                        <a href="barang_delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Hapus barang?')">Hapus</a> |
                        <?php if ($row['tersedia'] > 0): ?>
                            <a href="pinjam.php?id=<?= $row['id'] ?>">Pinjam</a>
                        <?php else: ?>
                            <span style="color:gray">Kosong</span>
                        <?php endif; ?>
                        <?php if ($row['jumlah'] > $row['tersedia']): ?>
                            | <a href="kembalikan.php?id=<?= $row['id'] ?>">Kembalikan</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>

</html>