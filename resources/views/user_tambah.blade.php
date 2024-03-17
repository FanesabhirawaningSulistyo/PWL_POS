<!DOCTYPE html>
<html>
<head>
    <title>Form Tambah Data User</title>
</head>
<body>
    <h1>Form Tambah Data User</h1>
    <form method="post" action="tambah_simpan">
        {{ csrf_field() }}
        <label for="username">Username</label>
        <input type="text" id="username" name="username" placeholder="Masukkan Username" required>
        <br><br>
        <label for="nama">Nama</label>
        <input type="text" id="nama" name="nama" placeholder="Masukkan Nama" required>
        <br><br>
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Masukkan Password" required>
        <br><br>
        <label for="level_id">Level ID</label>
        <input type="number" id="level_id" name="level_id" placeholder="Masukkan ID Level" required>
        <br><br>
        <input type="submit" class="btn btn-success" value="Simpan">
    </form>
</body>
</html>
