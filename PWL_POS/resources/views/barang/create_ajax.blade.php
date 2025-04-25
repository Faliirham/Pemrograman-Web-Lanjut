<form action="{{ url('/barang/ajax') }}" method="POST" id="form-tambah-barang">
    @csrf
    <div id="modal-barang" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Kategori</label>
                    <select name="kategori_id" id="kategori_id" class="form-control" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($kategori as $kat)
                        <option value="{{ $kat->kategori_id }}">{{ $kat->kategori_nama }}</option>
                        @endforeach
                    </select>
                    <small id="error-kategori_id" class="text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Kode Barang</label>
                    <input type="text" name="barang_kode" id="barang_kode" class="form-control" required>
                    <small id="error-barang_kode" class="text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Nama Barang</label>
                    <input type="text" name="barang_nama" id="barang_nama" class="form-control" required>
                    <small id="error-barang_nama" class="text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Harga Beli</label>
                    <input type="number" name="harga_beli" id="harga_beli" class="form-control" required>
                    <small id="error-harga_beli" class="text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Harga Jual</label>
                    <input type="number" name="harga_jual" id="harga_jual" class="form-control" required>
                    <small id="error-harga_jual" class="text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {
        $("#form-tambah-barang").validate({
            rules: {
                kategori_id: {
                    required: true
                },
                barang_kode: {
                    required: true,
                    minlength: 2,
                    maxlength: 10
                },
                barang_nama: {
                    required: true,
                    minlength: 3,
                    maxlength: 100
                },
                harga_beli: {
                    required: true,
                    number: true,
                    min: 1
                },
                harga_jual: {
                    required: true,
                    number: true,
                    min: 1
                }
            },
            messages: {
                kategori_id: {
                    required: "Kategori wajib dipilih"
                },
                barang_kode: {
                    required: "Kode Barang wajib diisi",
                    minlength: "Minimal 2 karakter",
                    maxlength: "Maksimal 10 karakter"
                },
                barang_nama: {
                    required: "Nama Barang wajib diisi",
                    minlength: "Minimal 3 karakter",
                    maxlength: "Maksimal 100 karakter"
                },
                harga_beli: {
                    required: "Harga Beli wajib diisi",
                    number: "Harus berupa angka",
                    min: "Minimal Rp1"
                },
                harga_jual: {
                    required: "Harga Jual wajib diisi",
                    number: "Harus berupa angka",
                    min: "Minimal Rp1"
                }
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.status) {
                            $('#modal-barang').modal('hide'); // Tutup modal
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                //confirmButtonText: 'OK'
                            }).then(() => {
                                location.reload();
                            });
                            dataBarang.ajax.reload(); // Reload DataTables barang
                        } else {
                            $('.text-danger').text('');
                            $.each(response.msgField, function(prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr) {
                        console.log("AJAX Error:", xhr);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error Server',
                            text: 'Terjadi kesalahan pada server, coba lagi nanti.'
                        });
                    }
                });
                return false;
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>