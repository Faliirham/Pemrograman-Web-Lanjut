<!DOCTYPE html>
<html>
    <head>
        <title>Tambah Data User</title>
    </head>
    <body>
        <h1>Form Tambah Data User</h1>

        <form method="POST" action="{{url('/user/tambah_simpan')}}">
        
        {{csrf_field()}}

        <label>Username</label>
        <input type="text" name="username" placeholder="Masukkan Username"><br>
        <label>Nama</label>
        <input type="text" name="nama" placeholder="Masukkan Nama"><br>
        <label>Password</label>
        <input type="password" name="password" placeholder="Masukkan Password"><br>
        <label>Level ID</label>
        <input type="number" name="level_id" placeholder="Masukkan Level ID"><br>
        <input type="submit" value="Simpan" class="btn btn-succes">

        </form>
    </body>
</html>