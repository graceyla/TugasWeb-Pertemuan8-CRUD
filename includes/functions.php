<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/Database.php';

// simpan pesan ke session, nanti ditampilin di halaman tujuan
function setFlash($tipe, $pesan)
{
    $_SESSION['flash'] = [
        'tipe' => $tipe,
        'pesan' => $pesan,
    ];
}

function redirect($url)
{
    header("Location: $url");
    exit;
}

function rupiah($angka)
{
    return 'Rp ' . number_format($angka, 0, ',', '.');
}
