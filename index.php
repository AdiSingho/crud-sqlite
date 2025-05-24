<?php
require 'db.php';

// CREATE
if (isset($_POST['tambah'])) {
  $sql = 'INSERT INTO tugas(deskripsi, waktu) VALUES(:deskripsi, :waktu)';
  $stmt = $conn->prepare($sql);
  $stmt->execute([
    ':deskripsi' => $_POST['deskripsi'],
    ':waktu' => $_POST['waktu']
  ]);
  header("Location: index.php");
  exit;
}

// DELETE
if (isset($_GET['hapus'])) {
  $sql = 'DELETE FROM tugas WHERE id = :id';
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':id', $_GET['hapus'], PDO::PARAM_INT);
  $stmt->execute();
  header("Location: index.php");
  exit;
}

// UPDATE
if (isset($_POST['ubah'])) {
  $sql = 'UPDATE tugas SET deskripsi = :deskripsi, waktu = :waktu WHERE id = :id';
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
  $stmt->bindParam(':deskripsi', $_POST['deskripsi']);
  $stmt->bindParam(':waktu', $_POST['waktu'], PDO::PARAM_INT);
  $stmt->execute();
  header("Location: index.php");
  exit;
}

// READ
$tugas = $conn->query("SELECT * FROM tugas")->fetchAll(PDO::FETCH_ASSOC);

// DETAIL
$detail = null;
if (isset($_GET['id'])) {
  $stmt = $conn->prepare("SELECT * FROM tugas WHERE id = :id");
  $stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
  $stmt->execute();
  $detail = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<h2>Tambah Tugas</h2>
<form method="post">
  <input type="text" name="deskripsi" placeholder="Deskripsi" required>
  <input type="number" name="waktu" placeholder="Waktu (menit)" required>
  <button type="submit" name="tambah">Tambah</button>
</form>

<hr>

<h2>Daftar Tugas</h2>
<ul>
  <?php foreach ($tugas as $t): ?>
    <li>
      <?= htmlspecialchars($t['deskripsi']) ?> (<?= $t['waktu'] ?> menit)
      [<a href="?id=<?= $t['id'] ?>">Detail</a>]
      [<a href="?hapus=<?= $t['id'] ?>" onclick="return confirm('Hapus tugas ini?')">Hapus</a>]
    </li>
  <?php endforeach; ?>
</ul>

<hr>

<?php if ($detail): ?>
  <h2>Detail & Ubah Tugas</h2>
  <form method="post">
    <input type="hidden" name="id" value="<?= $detail['id'] ?>">
    <input type="text" name="deskripsi" value="<?= htmlspecialchars($detail['deskripsi']) ?>" required>
    <input type="number" name="waktu" value="<?= $detail['waktu'] ?>" required>
    <button type="submit" name="ubah">Ubah</button>
  </form>
<?php endif; ?>
