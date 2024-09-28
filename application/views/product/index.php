<style>
    .dataTables_filter {
        float: right;
        margin-bottom: 10px;
    }

    .dataTables_filter label {
        font-weight: bold;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <a href="#" onclick="add_product()" class="btn btn-primary btn-lg" id="importsaldo">
                            <i class="fas fa-plus"></i> &nbsp;&nbsp; Tambah
                        </a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="mytable1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nama</th>
                                    <th>Deskripsi</th>
                                    <th>Harga</th>
                                    <th>Kategori</th>
                                    <th>Foto</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->

                    <!-- Bootstrap Modal  -->
                    <div class="modal fade" id="productModal" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form id="productForm" enctype="multipart/form-data">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Form Produk</h5>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="id" id="id">
                                        <div class="form-group">
                                            <label>Nama</label>
                                            <input type="text" class="form-control" name="name" id="name" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Deskripsi</label>
                                            <textarea class="form-control" name="description" id="description" required></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Harga</label>
                                            <input type="text" class="form-control" name="price" id="price" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Kategori</label>
                                            <select class="form-control" name="category_id" id="category_id">
                                                <?php foreach ($categories as $category): ?>
                                                    <option value="<?php echo $category->id; ?>"><?php echo $category->name; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Foto</label>
                                            <input class="form-control" type="file" name="photo" id="photo" accept="image/*">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary" id="save_button">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $this->load->view('layout/partials/datatables') ?>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script type="text/javascript">
    let base_url = '<?= base_url() ?>';
</script>
<script>
    function add_product() {
        save_method = 'add';
        // $('#productForm')[0].reset();
        $('#productModal').modal('show');
    }

    function edit_product(id) {
        save_method = 'edit';
        $('#productModal').modal('show');
        $.ajax({
            url: "<?php echo base_url('product/edit/') ?>" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('#id').val(data.id);
                $('#name').val(data.name);
                $('#description').val(data.description);
                $('#price').val(data.price);
                $('#productModal').modal('show');
            },
            error: function() {
                alert('Error fetching product data');
            }
        });
    }

    var save_method;

    $(document).ready(function() {

        $('#productForm').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: "<?php echo base_url('product/store'); ?>",
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    swal({
                            title: response.status,
                            text: "Data berhasil ditambahkan.",
                            icon: "success",
                        })
                        .then((result) => {
                            $('#productModal').modal('hide');
                            // Reload DataTable
                            $('#mytable1').DataTable().ajax.reload();
                        });
                },
                error: function(xhr, status, error) {
                    // Handle error
                    swal({
                        title: 'Error!',
                        text: 'Gagal menghapus data. Silakan coba lagi.',
                        icon: 'error',
                    });
                }
            });
        });
    });

    $(function() {
        $("#mytable1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "fixedColumns": {
                leftColumns: 1,
                rightColumns: 1,
            },
            "oLanguage": {
                "sProcessing": "loading...",
            },
            "lengthMenu": [
                [10, 25, 50, -1],
                ["10", "25", "50", "Show all"]
            ],
            "order": [
                [0, "asc"]
            ],
            "ajax": {
                "url": base_url + "products/list",
                "type": "GET",
                "dataSrc": ""
            },
            "columns": [{
                    "data": "id",
                    "render": function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    "data": "name"
                },
                {
                    "data": "description"
                },
                {
                    "data": "price",
                    "render": $.fn.dataTable.render.number(".", ".", 0, "Rp. ")
                },
                {
                    "data": "category_name"
                },
                {
                    "data": "photo",
                    "render": function(data, type, row) {
                        return `<img src="${base_url}uploads/${row.photo}" alt="${row.name}" width="50" />`;
                    }
                },
                {
                    "data": null,
                    "orderable": false,
                    "searchable": false,
                    "render": function(data, type, row) {
                        return `
                    <a href="#" title="Edit" onclick="edit_product(${data.id})" class="btn btn-warning btn-sm btn-edit-data">
                        <i class="fas fa-pencil-alt"></i>
                    </a>
                    <a href="#" data-id="${data.id}" title="Delete" class="btn btn-danger btn-sm btn-remove-data">
                        <i class="fa fa-trash"></i>
                    </a>`;
                    }
                }
            ],
            "columnDefs": [{
                "targets": 6,
                "orderable": false,
                "searchable": false
            }],
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            dom: '<"top"rf>t<"bottom"ip><"clear">',
        });

        //delete 1 item
        $('body').on('click', '.btn-remove-data', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            let token = $("meta[name='csrf-token']").attr("content");

            swal({
                    title: 'Apakah Anda Yakin?',
                    text: "Akan menghapus data ini",
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        var data = {
                            'id': id,
                        };

                        $.ajax({
                            type: "DELETE",
                            url: base_url + 'product/delete/' + id,
                            success: function(response) {
                                swal({
                                        title: response.status,
                                        text: "Data telah dihapus.",
                                        icon: "success",
                                    })
                                    .then((result) => {
                                        // Reload DataTable
                                        $('#mytable1').DataTable().ajax.reload();
                                    });
                            },
                            error: function(xhr, status, error) {
                                // Handle error
                                swal({
                                    title: 'Error!',
                                    text: 'Gagal menghapus data. Silakan coba lagi.',
                                    icon: 'error',
                                });
                            }
                        });
                    }
                });
        });
    });
</script>