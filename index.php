<?php
require_once 'includes/functions.php';

$db = Database::getInstance()->getConnection();

// pencarian
$cari = isset($_GET['cari']) ? trim($_GET['cari']) : '';

// pagination
$perHalaman = 5;
$halaman = isset($_GET['hal']) ? (int) $_GET['hal'] : 1;
if ($halaman < 1) {
    $halaman = 1;
}

$where = '';
$params = [];
if ($cari !== '') {
    $where = "WHERE p.nama_produk LIKE :cari1 OR p.kode_produk LIKE :cari2 OR k.nama_kategori LIKE :cari3";
    $params = [
        ':cari1' => "%$cari%",
        ':cari2' => "%$cari%",
        ':cari3' => "%$cari%",
    ];
}

// hitung total data dulu buat pagination
$stmt = $db->prepare("SELECT COUNT(*) FROM produk p
                      JOIN kategori k ON p.id_kategori = k.id_kategori
                      $where");
$stmt->execute($params);
$totalData = (int) $stmt->fetchColumn();

$totalHalaman = max(1, (int) ceil($totalData / $perHalaman));
if ($halaman > $totalHalaman) {
    $halaman = $totalHalaman;
}
$offset = ($halaman - 1) * $perHalaman;

// ambil data produk + JOIN kategori & supplier
$sql = "SELECT p.id_produk, p.kode_produk, p.nama_produk, p.stok, p.harga,
               k.nama_kategori, s.nama_supplier
        FROM produk p
        JOIN kategori k ON p.id_kategori = k.id_kategori
        JOIN supplier s ON p.id_supplier = s.id_supplier
        $where
        ORDER BY p.id_produk DESC
        LIMIT :limit OFFSET :offset";

$stmt = $db->prepare($sql);
foreach ($params as $key => $val) {
    $stmt->bindValue($key, $val);
}
$stmt->bindValue(':limit', $perHalaman, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$produk = $stmt->fetchAll();

$judul = 'Daftar Produk';
require_once 'includes/header.php';
?>

<div class="card">
    <div class="card-header">
        <h2>Daftar Produk</h2>
        <a href="create.php" class="btn btn-primary">+ Tambah Produk</a>
    </div>

    <form method="GET" class="form-cari">
        <input type="text" name="cari" placeholder="Cari nama, kode, atau kategori..." value="<?= htmlspecialchars($cari) ?>">
        <button type="submit" class="btn">Cari</button>
        <?php if ($cari !== ''): ?>
            <a href="index.php" class="btn btn-secondary">Reset</a>
        <?php endif; ?>
    </form>

    <div class="tabel-wrap">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Supplier</th>
                    <th>Stok</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($produk) === 0): ?>
                    <tr>
                        <td colspan="8" class="kosong">Data tidak ditemukan</td>
                    </tr>
                <?php endif; ?>

                <?php $no = $offset + 1; ?>
                <?php foreach ($produk as $row): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['kode_produk']) ?></td>
                        <td><?= htmlspecialchars($row['nama_produk']) ?></td>
                        <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                        <td><?= htmlspecialchars($row['nama_supplier']) ?></td>
                        <td><?= htmlspecialchars($row['stok']) ?></td>
                        <td><?= htmlspecialchars(rupiah($row['harga'])) ?></td>
                        <td class="aksi">
                            <a href="update.php?id=<?= (int) $row['id_produk'] ?>" class="btn btn-sm btn-warning">Edit</a>
                            <form action="delete.php" method="POST" onsubmit="return confirm('Yakin mau hapus produk ini?')">
                                <input type="hidden" name="id" value="<?= (int) $row['id_produk'] ?>">
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php if ($totalHalaman > 1): ?>
        <div class="pagination">
            <?php for ($i = 1; $i <= $totalHalaman; $i++): ?>
                <a href="?hal=<?= $i ?>&cari=<?= urlencode($cari) ?>" class="<?= $i == $halaman ? 'aktif' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>

    <p class="info">Total: <?= $totalData ?> produk</p>
</div>

<?php require_once 'includes/footer.php'; ?>
