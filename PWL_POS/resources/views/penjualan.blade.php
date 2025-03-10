<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Penjualan</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .produk, .transaksi { border: 1px solid #ddd; padding: 10px; margin-bottom: 10px; }
        .produk button { margin-left: 10px; }
    </style>
</head>
<body>
    <h2>POS - Penjualan</h2>
    <div class="produk">
        <h3>Daftar Produk</h3>
        <button onclick="tambahKeTransaksi('Produk A', 10000)">Produk A - Rp10.000</button>
        <button onclick="tambahKeTransaksi('Produk B', 15000)">Produk B - Rp15.000</button>
        <button onclick="tambahKeTransaksi('Produk C', 20000)">Produk C - Rp20.000</button>
    </div>
    
    <div class="transaksi">
        <h3>Transaksi</h3>
        <ul id="daftarTransaksi"></ul>
        <p>Total: <span id="totalHarga">Rp0</span></p>
    </div>
    
    <script>
        let total = 0;
        function tambahKeTransaksi(nama, harga) {
            let daftar = document.getElementById('daftarTransaksi');
            let item = document.createElement('li');
            item.textContent = `${nama} - Rp${harga}`;
            daftar.appendChild(item);
            total += harga;
            document.getElementById('totalHarga').textContent = `Rp${total}`;
        }
    </script>
</body>
</html>
