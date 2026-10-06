<?php
require_once 'includes/functions.php';

$db = Database::getInstance()->getConnection();
$log = $db->query("SELECT * FROM log_aktivitas ORDER BY waktu DESC, id_log DESC LIMIT 50")->fetchAll();

$judul = 'Log Aktivitas';
require_once 'includes/header.php';
?>

<div class="card">
    <div class="card-header">
        <h2>Log Aktivitas</h2>
    </div>

    <div class="tabel-wrap">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Aksi</th>
                    <th>Keterangan</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($log) === 0): ?>
                    <tr>
                        <td colspan="4" class="kosong">Belum ada log</td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($log as $i => $row): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><span class="badge badge-<?= strtolower(htmlspecialchars($row['aksi'])) ?>"><?= htmlspecialchars($row['aksi']) ?></span></td>
                        <td><?= htmlspecialchars($row['keterangan']) ?></td>
                        <td><?= htmlspecialchars($row['waktu']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
