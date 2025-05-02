@if(empty($kategori))
 <div id="modal-master" class="modal-dialog modal-lg" role="document">
     <div class="modal-content">
         <div class="modal-header">
             <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                 <span aria-hidden="true">&times;</span>
             </button>
         </div>
         <div class="modal-body">
             <div class="alert alert-danger">
                 <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                 Data yang anda cari tidak ditemukan
             </div>
             <a href="{{ url('/kategori') }}" class="btn btn-warning">Kembali</a>
         </div>
     </div>
 </div>
@else
 <form action="{{ url('/kategori/' . $kategori->kategori_id . '/delete_ajax') }}" method="POST" id="form-delete-kategori">
     @csrf
     @method('DELETE')
     <div id="modal-master" class="modal-dialog modal-lg" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="exampleModalLabel">Hapus Kategori</h5>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div class="modal-body">
                 <div class="alert alert-warning">
                     <h5><i class="icon fas fa-ban"></i> Konfirmasi !!!</h5>
                     Apakah Anda ingin menghapus data seperti di bawah ini?
                 </div>
                 <table class="table table-sm table-bordered table-striped">
                     <tr>
                         <th class="text-right col-3">Kode Kategori :</th>
                         <td class="col-9">{{ $kategori->kategori_kode }}</td>
                     </tr>
                     <tr>
                         <th class="text-right col-3">Nama Kategori :</th>
                         <td class="col-9">{{ $kategori->kategori_nama }}</td>
                     </tr>
                 </table>
             </div>
             <div class="modal-footer">
                 <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                 <button type="submit" class="btn btn-primary">Ya, Hapus</button>
             </div>
         </div>
     </div>
 </form>
 
 <script>
     $(document).ready(function() {
         $("#form-delete-kategori").validate({
             submitHandler: function(form) {
                 Swal.fire({
                     title: 'Apakah Anda yakin?',
                     text: "Data yang dihapus tidak bisa dikembalikan!",
                     icon: 'warning',
                     showCancelButton: true,
                     confirmButtonColor: '#d33',
                     cancelButtonColor: '#3085d6',
                     confirmButtonText: 'Ya, hapus!',
                     cancelButtonText: 'Batal'
                 }).then((result) => {
                     if (result.isConfirmed) {
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
                                     if (typeof dataKategori !== 'undefined') {
                                         dataKategori.ajax.reload();
                                     }
                                 } else {
                                     Swal.fire({
                                         icon: 'error',
                                         title: 'Terjadi Kesalahan',
                                         text: response.message
                                     });
                                 }
                             },
                             error: function(xhr, status, error) {
                                 Swal.fire({
                                     icon: 'error',
                                     title: 'Terjadi Kesalahan',
                                     text: 'Terjadi kesalahan pada server. Silakan coba lagi nanti.'
                                 });
                                 console.error(xhr.responseText);
                             }
                         });
                     }
                 });
                 return false;
             }
         });
     });
 </script>
@endif