<form action="{{ url('/supplier/ajax') }}" method="POST" id="form-tambah-supplier">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Kode Supplier</label>
                    <input type="text" name="supplier_kode" id="supplier_kode" class="form-control" required>
                    <small id="error-supplier_kode" class="text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nama Supplier</label>
                    <input type="text" name="supplier_nama" id="supplier_nama" class="form-control" required>
                    <small id="error-supplier_nama" class="text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Alamat Supplier</label>
                    <textarea name="supplier_alamat" id="supplier_alamat" class="form-control" required></textarea>
                    <small id="error-supplier_alamat" class="text-danger"></small>
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
        $("#form-tambah-supplier").validate({
            rules: {
                supplier_kode: {
                    required: true,
                    minlength: 2,
                    maxlength: 10
                },
                supplier_nama: {
                    required: true,
                    minlength: 3,
                    maxlength: 100
                },
                supplier_alamat: {
                    required: true,
                    minlength: 5,
                    maxlength: 255
                }
            },
            messages: {
                supplier_kode: {
                    required: "Kode Supplier wajib diisi",
                    minlength: "Kode Supplier minimal 2 karakter",
                    maxlength: "Kode Supplier maksimal 10 karakter"
                },
                supplier_nama: {
                    required: "Nama Supplier wajib diisi",
                    minlength: "Nama Supplier minimal 3 karakter",
                    maxlength: "Nama Supplier maksimal 100 karakter"
                },
                supplier_alamat: {
                    required: "Alamat Supplier wajib diisi",
                    minlength: "Alamat minimal 5 karakter",
                    maxlength: "Alamat maksimal 255 karakter"
                }
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            dataSupplier.ajax.reload(); // Reload DataTables supplier
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