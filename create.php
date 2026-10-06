<?php
require_once 'includes/functions.php';

$db = Database::getInstance()->getConnection();

// data buat dropdown
$kategori = $db->query("SELECT id_kategori, nama_kategori FROM kategori ORDER BY nama_kategori")->fetchAll();
$supplier = $db->query("SELECT id_supplier, nama_supplier FROM supplier ORDER BY nama_supplier")->fetchAll();

$errors = [];
$data = [
    'kode_produk' => '',
    'nama_produk' => '',
    'id_kategori' => '',
    'id_supplier' => '',
    'stok' => '',
    'harga' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($data as $key => $val) {
        $data[$key] = trim($_POST[$key] ?? '');
    }

    // validasi
    if ($data['kode_produk'] === '') {
        $errors[] = 'Kode produk wajib diisi';
    }
    if ($data['nama_produk'] === '') {
        $errors[] = 'Nama produk wajib diisi';
    }
    if ($data['id_kategori'] === '') {
        $errors[] = 'Kategori harus dipilih';
    }
    if ($data['id_supplier'] === '') {
        $errors[] = 'Supplier harus dipilih';
    }
    if (!is_numeric($data['stok']) || $data['stok'] < 0) {
        $errors[] = 'Stok harus angka dan tidak boleh minus';
    }
    if (!is_numeric($data['harga']) || $data['harga'] < 0) {
        $errors[] = 'Harga harus angka dan tidak boleh minus';
    }

    if (empty($errors)) {
        try {
            $stmt = $db->prepare("INSERT INTO produk (kode_produk, nama_produk, id_kategori, id_supplier, stok, harga)
                                  VALUES (:kode, :nama, :kategori, :supplier, :stok, :harga)");
            $stmt->execute([
                ':kode' => $data['kode_produk'],
                ':nama' => $data['nama_produk'],
                ':kategori' => $data['id_kategori'],
                ':supplier' => $data['id_supplier'],
                ':stok' => (int) $data['stok'],
                ':harga' => $data['harga'],
            ]);

            setFlash('sukses', 'Produk "' . $data['nama_produk'] . '" berhasil ditambahkan');
            redirect('index.php');
        } catch (PDOException $e) {
            // 23000 = duplicate / pelanggaran constraint
            if ($e->getCode() == 23000) {
                $errors[] = 'Kode produk sudah dipakai atau kategori/supplier tidak valid';
            } else {
                setFlash('gagal', 'Gagal menambahkan produk');
                redirect('index.php');
            }
        }
    }
}

$judul = 'Tambah Produk';
require_once 'includes/header.php';
?>

<div class="card card-form">
    <h2>Tambah Produk</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-gagal">
            <ul>
                <?php foreach ($errors as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label>Kode Produk</label>
            <input type="text" name="kode_produk" maxlength="20" value="<?= htmlspecialchars($data['kode_produk']) ?>" required>
        </div>

        <div class="form-group">
            <label>Nama Produk</label>
            <input type="text" name="nama_produk" maxlength="100" value="<?= htmlspecialchars($data['nama_produk']) ?>" required>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Kategori</label>
                <select name="id_kategori" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php foreach ($kategori as $k): ?>
                        <option value="<?= (int) $k['id_kategori'] ?>" <?= $data['id_kategori'] == $k['id_kategori'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($k['nama_kategori']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Supplier</label>
                <select name="id_supplier" required>
                    <option value="">-- Pilih Supplier --</option>
                    <?php foreach ($supplier as $s): ?>
                        <option value="<?= (int) $s['id_supplier'] ?>" <?= $data['id_supplier'] == $s['id_supplier'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($s['nama_supplier']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Stok</label>
                <input type="number" name="stok" min="0" value="<?= htmlspecialchars($data['stok']) ?>" required>
            </div>

            <div class="form-group">
                <label>Harga (Rp)</label>
                <input type="number" name="harga" min="0" step="any" value="<?= htmlspecialchars($data['harga']) ?>" required>
            </div>
        </div>

        <div class="form-aksi">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>
