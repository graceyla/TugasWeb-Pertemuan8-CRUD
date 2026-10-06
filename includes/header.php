<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($judul) ? htmlspecialchars($judul) . ' - ' : '' ?>Inventaris</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar">
    <div class="container nav-isi">
        <a href="index.php" class="logo">Inventaris</a>
        <div class="menu">
            <a href="index.php">Produk</a>
            <a href="create.php">Tambah Produk</a>
            <a href="log.php">Log</a>
        </div>
    </div>
</nav>

<div class="container">

<?php if (isset($_SESSION['flash'])): ?>
    <div class="alert alert-<?= htmlspecialchars($_SESSION['flash']['tipe']) ?>">
        <?= htmlspecialchars($_SESSION['flash']['pesan']) ?>
    </div>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>
