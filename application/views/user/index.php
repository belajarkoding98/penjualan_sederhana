<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <a href="#" onclick="add_user()" class="btn btn-primary btn-lg" id="importsaldo">
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
                                    <th>Username</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->

                    <!-- Bootstrap Modal -->
                    <div class="modal fade" id="userModal" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form id="user_form">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Form User</h5>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="id" id="id">
                                        <div class="form-group">
                                            <label>Nama</label>
                                            <input type="text" class="form-control" name="name" id="name" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Username</label>
                                            <input type="text" class="form-control" name="username" id="username" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Password</label>
                                            <input type="password" class="form-control" name="password" id="password" required>
                                            <label for="" id="message">Kosongkan jika tidak ingin diupdate</label>
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
    function add_user() {
        save_method = 'add';
        $('#user_form')[0].reset();
        $('#userModal').modal('show');
        $('#message').hide();
    }

    function edit_user(id) {
        save_method = 'edit';
        $('#userModal').modal('show');
        $('#message').show();

        $.ajax({
            url: "<?php echo base_url('user/edit/') ?>" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('#id').val(data.id);
                $('#name').val(data.name);
                $('#username').val(data.username);
                $('#productModal').modal('show');
            },
            error: function() {
                alert('Error fetching product data');
            }
        });
    }

    var save_method;

    $(document).ready(function() {

        $('#user_form').on('submit', function(e) {
            e.preventDefault();
            var url;
            if (save_method == 'add') {
                url = "<?php echo base_url('user/store') ?>";
            } else {
                url = "<?php echo base_url('user/update') ?>";
            }
            $.ajax({
                url: url,
                type: "POST",
                data: $(this).serialize(),
                dataType: "JSON",
                success: function(data) {
                    $('#userModal').modal('hide');
                    swal({
                            title: data.title,
                            text: data.message,
                            icon: data.icon,
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
                url: base_url + "users/list",
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
                    data: "username",
                },
                {
                    data: null,
                },
            ],
            columnDefs: [{
                data: {
                    id: "id",
                },
                targets: 3,
                orderable: false,
                searchable: false,
                render: function(data, type, row, meta) {
                    let btn;
                    return `
                    <a href="#" title="edit" onclick="edit_user(${data.id})" class="btn btn-md btn-warning  btn-edit-data">
                    <i class="fas fa-pencil-alt"></i>
                    </a>
                    <a href="#" data-id="${data.id}" title="hapus" class="btn btn-md btn-danger btn3d btn-remove-data">
                    <i class="fa fa-trash"></i>
                    </a>`;
                },
            }, ],
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
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
                            url: base_url + 'user/delete/' + id,
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