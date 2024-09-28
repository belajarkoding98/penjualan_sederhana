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
                        <a href="#" onclick="add_sales()" class="btn btn-primary btn-lg" id="importsaldo">
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
                                    <th>Tanggal</th>
                                    <th>Total</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->

                    <!-- Bootstrap Modal  -->
                    <div class="modal fade" id="salesModal" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="salesModalLabel">Form Penjualan Baru</h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <form id="salesForm" method="post">
                                        <!-- Pilih Pelanggan -->
                                        <div class="mb-3">
                                            <label for="customer_id" class="form-label">Pilih Pelanggan</label>
                                            <select id="customer_id" name="customer_id" class="form-select form-control" required>
                                                <option value="">Pilih Pelanggan</option>
                                                <!-- Fetch customer data dynamically from database -->
                                                <?php foreach ($customers as $customer): ?>
                                                    <option value="<?= $customer->id ?>"><?= $customer->name ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <!-- Pilih Produk -->
                                        <div class="mb-3">
                                            <label for="product_id" class="form-label">Pilih Produk</label>
                                            <select id="product_id" name="product_id" class="form-select form-control" required>
                                                <option value="">Pilih Produk</option>
                                                <!-- Fetch product data dynamically from database -->
                                                <?php foreach ($products as $product): ?>
                                                    <option value="<?= $product->id ?>"><?= $product->name ?> - Rp. <?= number_format($product->price, 2) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <!-- Kuantitas -->
                                        <div class="mb-3">
                                            <label for="quantity" class="form-label">Kuantitas</label>
                                            <input type="number" id="quantity" name="quantity" class="form-control" min="1" required>
                                        </div>

                                        <!-- Harga (diambil dari harga produk, bisa diubah manual jika diperlukan) -->
                                        <div class="mb-3">
                                            <label for="price" class="form-label">Total Harga (Rp)</label>
                                            <input type="number" id="price" name="price" class="form-control" required>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                            <button type="submit" class="btn btn-primary">Simpan Penjualan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="saleModal" tabindex="-1" aria-labelledby="saleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="saleModalLabel">Detail Penjualan</h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <div>
                                        <strong>Tanggal Penjualan: </strong> <span id="sale_date"></span><br>
                                        <strong>Nama Pelanggan: </strong> <span id="customer_name"></span><br>
                                        <strong>Total Pembelian: </strong> <span id="total_amount"></span>
                                    </div>
                                    <hr>
                                    <h5>Detail Produk:</h5>
                                    <table class="table table-bordered" id="sale_details">
                                        <thead>
                                            <tr>
                                                <th>Nama Produk</th>
                                                <th>Jumlah</th>
                                                <th>Harga</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Isi dari detail produk akan dimasukkan di sini melalui AJAX -->
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                </div>
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
    function add_sales() {
        save_method = 'add';
        $('#salesForm')[0].reset();
        $('#salesModal').modal('show');
    }

    var save_method;

    $(document).ready(function() {

        var productPrice = 0;

        $('#product_id').change(function() {
            var selectedProduct = $(this).find(':selected').text();
            productPrice = selectedProduct.split('Rp. ')[1].replace(',', '').trim();
            $('#quantity').trigger('input');
        });

        $('#quantity').on('input', function() {
            var quantity = $(this).val();
            var totalPrice = productPrice * quantity;
            $('#price').val(totalPrice);
        });

        $('#salesForm').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: "<?php echo base_url('sale/store'); ?>",
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    swal({
                            title: 'Sukses',
                            text: "Data berhasil ditambahkan.",
                            icon: "success",
                        })
                        .then((result) => {
                            $('#salesModal').modal('hide');
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

    function show_sales(sale_id) {
        $.ajax({
            url: '<?= base_url("sale/show/") ?>' + sale_id, // URL menuju controller untuk mengambil detail penjualan
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data.status == 'success') {
                    // Masukkan data ke dalam elemen modal
                    $('#sale_date').text(data.sale.sale_date);
                    $('#customer_name').text(data.sale.customer_name);
                    $('#total_amount').text('Rp. ' + data.sale.total_amount);

                    // Kosongkan dan tambahkan data sale details
                    $('#sale_details tbody').empty();
                    $.each(data.details, function(index, detail) {
                        $('#sale_details tbody').append(
                            '<tr>' +
                            '<td>' + detail.product_name + '</td>' +
                            '<td>' + detail.quantity + '</td>' +
                            '<td>' + 'Rp. ' + detail.price + '</td>' +
                            '</tr>'
                        );
                    });

                    // Tampilkan modal
                    $('#saleModal').modal('show');
                } else {
                    alert(data.message);
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert('Error fetching sale details');
            }
        });
    }

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
                "url": base_url + "sale/list",
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
                    "data": "customer_name"
                },
                {
                    "data": "sale_date"
                },
                {
                    "data": "total_amount",
                    "render": $.fn.dataTable.render.number(".", ".", 0, "Rp. ")
                },
                {
                    "data": null,
                    "orderable": false,
                    "searchable": false,
                    "render": function(data, type, row) {
                        return `
                    <a href="#" title="Detail" onclick="show_sales(${data.id})" class="btn btn-info btn-sm">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="#" data-id="${data.id}" title="Delete" class="btn btn-danger btn-sm btn-remove-data">
                        <i class="fa fa-trash"></i>
                    </a>`;
                    }
                }
            ],
            "columnDefs": [{
                "targets": 4,
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
                            url: base_url + 'sale/delete/' + id,
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