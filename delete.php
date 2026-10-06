<?php
require_once 'includes/functions.php';

// hapus cuma boleh lewat POST (dari tombol hapus di index)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

$db = Database::getInstance()->getConnection();
$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

try {
    // pakai transaction, jadi hapus produk + catat log harus sukses dua-duanya
    $db->beginTransaction();

    $stmt = $db->prepare("SELECT kode_produk, nama_produk FROM produk WHERE id_produk = :id");
    $stmt->execute([':id' => $id]);
    $produk = $stmt->fetch();

    if (!$produk) {
        $db->rollBack();
        setFlash('gagal', 'Produk tidak ditemukan');
        redirect('index.php');
    }

    $stmt = $db->prepare("DELETE FROM produk WHERE id_produk = :id");
    $stmt->execute([':id' => $id]);

    $stmt = $db->prepare("INSERT INTO log_aktivitas (aksi, keterangan) VALUES ('DELETE', :ket)");
    $stmt->execute([
        ':ket' => 'Produk ' . $produk['kode_produk'] . ' - ' . $produk['nama_produk'] . ' dihapus',
    ]);

    $db->commit();
    setFlash('sukses', 'Produk "' . $produk['nama_produk'] . '" berhasil dihapus');
} catch (PDOException $e) {
    if ($db->inTransaction()) {
        $db->rollBack();
    }
    setFlash('gagal', 'Gagal menghapus produk');
}

redirect('index.php');
