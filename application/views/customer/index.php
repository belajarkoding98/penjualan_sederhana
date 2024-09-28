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
                        <a href="#" onclick="add_customer()" class="btn btn-primary btn-lg" id="importsaldo">
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
                                    <th>Email</th>
                                    <th>No Telepon</th>
                                    <th>Alamat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->

                    <!-- Bootstrap Modal -->
                    <div class="modal fade" id="customerModal" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form id="consumerForm">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalLabel">Form Konsumen</h5>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" id="id" name="id">
                                        <div class="form-group">
                                            <label>Nama</label>
                                            <input type="text" class="form-control" id="name" name="name" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" class="form-control" id="email" name="email" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Telepon</label>
                                            <input type="text" class="form-control" id="phone" name="phone" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Alamat</label>
                                            <textarea class="form-control" id="address" name="address" required></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
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
    function add_customer() {
        save_method = 'add';
        $('#consumerForm')[0].reset();
        $('#customerModal').modal('show');
    }

    function edit_customer(id) {
        save_method = 'edit';
        $('#customerModal').modal('show');
        $.ajax({
            url: "<?php echo base_url('customer/edit/') ?>" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('#id').val(data.id);
                $('#name').val(data.name);
                $('#email').val(data.email);
                $('#phone').val(data.phone);
                $('#address').val(data.address);
                $('#customerModal').modal('show');
            },
            error: function() {
                alert('Error fetching product data');
            }
        });
    }

    var save_method;

    $(document).ready(function() {

        $('#consumerForm').on('submit', function(e) {
            e.preventDefault();
            var url;
            if (save_method == 'add') {
                url = "<?php echo base_url('customer/store') ?>";
            } else {
                url = "<?php echo base_url('customer/update') ?>";
            }
            $.ajax({
                url: url,
                type: "POST",
                data: $(this).serialize(),
                dataType: "JSON",
                success: function(data) {
                    $('#customerModal').modal('hide');
                    swal({
                            title: 'Sukses',
                            text: data.message,
                            icon: "success",
                        })
                        .then((result) => {
                            // Reload DataTable
                            $('#mytable1').DataTable().ajax.reload();
                        });
                },
                error: function() {
                    swal({
                        title: 'Error!',
                        text: 'Gagal menyimpan data. Silakan coba lagi.',
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
            fixedColumns: {
                left: 1,
                right: 1,
            },
            oLanguage: {
                sProcessing: "loading...",
            },
            lengthMenu: [
                [10, 25, 50, -1],
                ["10", "25", "50", "Show all"],
            ],
            order: [
                [0, "asc"]
            ],
            ajax: {
                url: base_url + "customers/list",
                type: "POST",
            },
            columns: [{
                    data: "id",
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    data: "name",
                },
                {
                    data: "email",
                },
                {
                    data: "phone",
                },
                {
                    data: "address",
                },
                {
                    data: null,
                },
            ],
            columnDefs: [{
                data: {
                    id: "id",
                },
                targets: 5,
                orderable: false,
                searchable: false,
                render: function(data, type, row, meta) {
                    let btn;
                    return `
                    <a href="#" title="edit" onclick="edit_customer(${data.id})" class="btn btn-md btn-warning  btn-edit-data">
                    <i class="fas fa-pencil-alt"></i>
                    </a>
                    <a href="#" data-id="${data.id}" title="hapus" class="btn btn-md btn-danger btn3d btn-remove-data">
                    <i class="fa fa-trash"></i>
                    </a>`;
                },
            }, ],
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            dom: '<"top"rf>t<"bottom"ip><"clear">',
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

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
                            url: base_url + 'customer/delete/' + id,
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