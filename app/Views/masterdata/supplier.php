<?= $this->extend('layout/default') ?>

<?= $this->section('title') ?>
    <title><?= $title ?> &mdash; Empower Biz</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <style>
        .label-container {
            display: flex;
            justify-content: space-between;
        }
        .label-text {
            text-align: left;
            flex-grow: 1;
        }
        .label-colon {
            text-align: right;
            padding-left: 5px;
        }
        .show_line_colon {
            display: inline-block;
            border-bottom: 1px solid #000; /* Add underline */
            padding-bottom: 2px;
            min-width: 100px;
            font-style: italic;
        }
    </style>
    <script src="<?= base_url()?>/assets/js/simple-datatables.min.js"></script>
    <script type="text/javascript">
        var dataTable1;
        var dataTable2;

        $(function() {
            // dataTables = new simpleDatatables.DataTable("#datatablesSimple");
            get_list_supplier();
            reset_form();

            $('#reload').click(function(){
                reset_form();
                get_list_supplier();
            });

            $('#add').click(function() {
                reset_form();
                $('#add_modal').modal('show');
                $('.modal-title').html('Tambah Data')
            });

            $('#reload_contact').click(function(){
                detail_supplier($('#id_supplier_contact').val(id));
                reset_form();
                get_list_supplier();
            });

            $('#add_contact').click(function() {
                $('#add_contact_modal').modal('show');

                $('#detail_modal').modal('hide');
                $('.modal-title').html('Tambah Kontak')
            });
        });

        function reset_form() {
            $('.add_dep').val('');
            $('.add_contact').val('');
        }

        function get_list_supplier() {
            if (dataTable1) {
                dataTable1.destroy();
            }

            $('.table-supplier tbody').empty();
            
            $.ajax({
                url: '<?= base_url('api/masterdata/listSupplier') ?>',
                type: 'GET',
                dataType: 'json',
                beforeSend: function() {
                    showLoading();
                    reset_form();
                },
                success: function(response) {
                    let str = ''; let status = ''; let badgeStatus = '';

                    if(response.data.jumlah != 0) {
                        $.each(response.data, function(i, v) {
                            badgeStatus = v.active == 1 ? 'bg-green-soft text-green' : 'bg-red-soft text-red';
                            status = v.active == 1 ? 'Aktif' : 'Non-Aktif'

                            str = '<tr>'+
                                    '<td>'+
                                        '<div class="d-flex align-items-center">'+
                                            '<div class="avatar me-2"><i data-feather="smile"></i></div>'+
                                            v.name+
                                        '</div>'+
                                    '</td>'+
                                    '<td>'+v.address+'</td>'+
                                    '<td><span class="badge '+badgeStatus+'">'+status+'</span></td>'+
                                    '<td>'+
                                        '<button type="button" class="btn btn-datatable btn-icon btn-transparent-dark me-2" title="Detail Kontak Supplier" onclick="detail_supplier('+v.id+', \''+v.name+'\', \''+v.address+'\')"><i data-feather="eye"></i></button>'+    
                                        '<button type="button" class="btn btn-datatable btn-icon btn-transparent-dark me-2" onclick="edit_supplier('+v.id+')"><i data-feather="edit"></i></button>'+    
                                        '<button type="button" class="btn btn-datatable btn-icon btn-transparent-dark" onclick="delete_supplier('+v.id+')"><i data-feather="trash-2"></i></button>'+
                                    '</td>'+
                                '</tr>';
                            $('.table-supplier tbody').append(str);
                        });
                    } else {
                        console.log('KOSONG')

                        str = '<tr><td class="datatable-empty" colspan="5">No entries found</td></tr>';
                        $('.table-supplier tbody').append(str);
                    }

                    feather.replace();

                    dataTable1 = new simpleDatatables.DataTable("#datatablesSimple");
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: "Access Failed",
                        text: "Internal Server Error",
                        icon: "error"
                    });
                },
                complete: function() {
                    hideLoading();
                }
            });
        }
        
        function save_supplier() {
            let addForm = $('#add_form').serialize();

            $.ajax({
                type : 'POST',
                url: '<?= base_url("api/masterdata/supplier") ?>',
                data: addForm,
                cache: false,
                dataType : 'json',
                beforeSend: function() {
                    showLoading();
                },
                success: function(data) {
                    $('#add_modal').modal('hide');
                    get_list_supplier()

                    Swal.fire({
                        title: "Berhasil",
                        text: "Data Berhasil Simpan",
                        icon: "success"
                    });

                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: "Access Failed",
                        text: "Internal Server Error",
                        icon: "error"
                    });
                },
                complete: function() {
                    reset_form();
                    hideLoading();
                }
            });
        }

        function delete_supplier(id) {
            if(id == '' || id == null) {
                return false;
            }

            Swal.fire({
                icon: "question",
                title: "Anda yakin untuk hapus data ?",
                showCancelButton: true,
                confirmButtonText: "Ya",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type : 'DELETE',
                        url: '<?= base_url("api/masterdata/supplier") ?>?id='+id,
                        cache: false,
                        dataType : 'json',
                        beforeSend: function() {
                            showLoading();
                        },
                        success: function(data) {
                            $('#add_modal').modal('hide');
                            get_list_supplier()

                            Swal.fire("Berhasil", "Data Berhasil Hapus", "success");
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                title: "Access Failed",
                                text: "Internal Server Error",
                                icon: "error"
                            });
                        },
                        complete: function() {
                            reset_form();
                            hideLoading();
                        }
                    });
                    
                }
            });

            
        }

        function edit_supplier(id) {
            if(id == '' || id == null) {
                return false;
            }

            $.ajax({
                type : 'GET',
                url: '<?= base_url("api/masterdata/supplier")?>?id='+id,
                cache: false,
                dataType : 'json',
                beforeSend: function() {
                    showLoading();
                },
                success: function(data) {
                    $('#id_supplier').val(id);
                    $('#name_supplier').val(data.data.name);
                    $('#address_supplier').val(data.data.address);
                    $('#active_supplier').val(data.data.active);

                    $('.modal-title').html('Edit Data')
                    $('#add_modal').modal('show');
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: "Access Failed",
                        text: "Internal Server Error",
                        icon: "error"
                    });
                },
                complete: function() {
                    hideLoading();
                }
            });
        }

        function detail_supplier(id, name, address) {
            if(id == '' || id == null) {
                return false;
            }

            $('#name_sup').html(name);
            $('#address_sup').html(address);
            $('.modal-title').html('Detail Data Supplier')
            if (dataTable2) {
                dataTable2.destroy();
            }
            $('.table-contact tbody').empty();

            $.ajax({
                type: 'GET',
                url: '<?= base_url('api/masterdata/listSupplierContact') ?>',
                data: 'id_supplier='+id,
                dataType: 'json',
                beforeSend: function() {
                    showLoading();
                    reset_form();
                },
                success: function(response) {
                    let str = ''; let status = ''; let badgeStatus = '';

                    if(response.data.length != 0) {
                        $.each(response.data, function(i, v) {
                            badgeStatus = v.active == 1 ? 'bg-green-soft text-green' : 'bg-red-soft text-red';
                            status = v.active == 1 ? 'Aktif' : 'Non-Aktif'

                            str = '<tr>'+
                                    '<td>'+
                                        '<div class="d-flex align-items-center">'+
                                            '<div class="avatar me-2"><i data-feather="smile"></i></div>'+
                                            v.name+
                                        '</div>'+
                                    '</td>'+
                                    '<td>'+v.email+'</td>'+
                                    '<td>'+v.phone+'</td>'+
                                    '<td><span class="badge '+badgeStatus+'">'+status+'</span></td>'+
                                    '<td>'+
                                        '<button type="button" class="btn btn-datatable btn-icon btn-transparent-dark me-2" onclick="edit_contact('+v.id+')"><i data-feather="edit"></i></button>'+    
                                        '<button type="button" class="btn btn-datatable btn-icon btn-transparent-dark" onclick="delete_contact('+v.id+')"><i data-feather="trash-2"></i></button>'+
                                    '</td>'+
                                '</tr>';
                                
                            $('.table-contact tbody').append(str);
                        });
                    } else {
                        str = '';
                        $('.table-contact tbody').append(str);
                    }

                    feather.replace();
                    dataTable2 = new simpleDatatables.DataTable("#datatablesSimple2");

                    $('#id_supplier_contact').val(id);
                    $('#name_supplier_contact').val(name);

                    $('#detail_modal').modal('show');

                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: "Access Failed",
                        text: "Internal Server Error",
                        icon: "error"
                    });
                },
                complete: function() {
                    hideLoading();
                }
            });
        }

        function save_contact() {
            let addForm = $('#add_contact_form').serialize();
            let idSupplier = $('#id_supplier_contact').val();

            $.ajax({
                type : 'POST',
                url: '<?= base_url("api/masterdata/supplierContact") ?>',
                data: addForm,
                cache: false,
                dataType : 'json',
                beforeSend: function() {
                    showLoading();
                },
                success: function(data) {
                    $('#add_contact_modal').modal('hide');
                    get_list_supplier();
                    detail_supplier(idSupplier);

                    Swal.fire({
                        title: "Berhasil",
                        text: "Data Berhasil Simpan",
                        icon: "success"
                    });

                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: "Access Failed",
                        text: "Internal Server Error",
                        icon: "error"
                    });
                },
                complete: function() {
                    reset_form();
                    hideLoading();
                }
            });
        }

        function edit_contact(id) {
            if(id == '' || id == null) {
                return false;
            }

            $.ajax({
                type : 'GET',
                url: '<?= base_url("api/masterdata/supplierContact")?>?id='+id,
                cache: false,
                dataType : 'json',
                beforeSend: function() {
                    showLoading();
                },
                success: function(data) {
                    $('#id_contact').val(id);
                    $('#id_supplier_contact').val(data.data.id_supplier);
                    $('#name_supplier_contact').val(data.data.name_supplier);

                    $('#name_contact').val(data.data.name);
                    $('#email_contact').val(data.data.email);
                    $('#phone_contact').val(data.data.phone);
                    $('#active_contact').val(data.data.active);

                    $('.modal-title').html('Edit Data Kontak')
                    $('#detail_modal').modal('hide');

                    $('#add_contact_modal').modal('show');
                    
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: "Access Failed",
                        text: "Internal Server Error",
                        icon: "error"
                    });
                },
                complete: function() {
                    hideLoading();
                }
            });
        }

        function delete_contact(id) {
            if(id == '' || id == null) {
                return false;
            }

            Swal.fire({
                icon: "question",
                title: "Anda yakin untuk hapus data kontak ?",
                showCancelButton: true,
                confirmButtonText: "Ya",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type : 'DELETE',
                        url: '<?= base_url("api/masterdata/supplierContact") ?>?id='+id,
                        cache: false,
                        dataType : 'json',
                        beforeSend: function() {
                            showLoading();
                        },
                        success: function(data) {
                            $('#detail_modal').modal('hide');
                            Swal.fire("Berhasil", "Data Berhasil Hapus", "success");
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                title: "Access Failed",
                                text: "Internal Server Error",
                                icon: "error"
                            });
                        },
                        complete: function() {
                            reset_form();
                            hideLoading();
                        }
                    });
                    
                }
            });

            
        }

    </script>
    
    <main>
        <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
            <div class="container-fluid px-4">
                <div class="page-header-content">
                    <div class="row align-items-center justify-content-between pt-3">
                        <div class="col-auto mb-3">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i data-feather="list"></i></div>
                                <?= $title ?>
                            </h1>
                        </div>
                        <div class="col-12 col-xl-auto mb-3">
                            <button type="button" class="btn btn-sm btn-light text-primary" id="reload">
                                <i class="me-1" data-feather="refresh-ccw"></i>
                                Reload
                            </button>
                            <button type="button" class="btn btn-sm btn-light text-primary" id="add">
                                <i class="me-1" data-feather="user-plus"></i>
                                Tambah Data
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="container-fluid px-4">
            <div class="card">
                <div class="card-body">
                    <table id="datatablesSimple" class="table-supplier">
                        <thead>
                            <tr>
                                <th>Nama Supplier</th>
                                <th>Alamat</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Nama Supplier</th>
                                <th>Alamat</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </tfoot>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
        
   
    <div class="modal fade" id="detail_modal">
        <div class="modal-dialog" role="document" style="--bs-modal-width: 90%">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Data Supplier</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row" style="padding: 0px 20px 0px 20px">
                        
                        <div class="col-xl-4">
                            <div class="card mb-4 mb-xl-0">
                                <div class="card-header">Data Supplier</div>
                                <div class="card-body">
                                    <div class="container">
                                        <div class="row mb-3 align-items-center">
                                            <div class="col-lg-3 label-container">
                                                <span class="label-text">Supplier</span>
                                                <span class="label-colon">:</span>
                                            </div>
                                            <div class="col-lg-9">
                                                <span class="show_line_colon show_sup" style="min-width: 100%;" id="name_sup"></span>
                                            </div>
                                        </div>
                                        <div class="row mb-3 align-items-center">
                                            <div class="col-lg-3 label-container">
                                                <span class="label-text">Alamat</span>
                                                <span class="label-colon">:</span>
                                            </div>
                                            <div class="col-lg-9">
                                                <span class="show_line_colon show_sup" style="min-width: 100%;" id="address_sup"></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-8">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <div class="row align-items-center justify-content-between">
                                        <div class="col-auto"> List Kontak Supplier </div>
                                        <div class="col-12 col-xl-auto ">
                                            <button type="button" class="btn btn-sm btn-light text-primary" id="reload_contact">
                                                <i class="me-1" data-feather="refresh-ccw"></i>
                                                Reload
                                            </button>
                                            <button type="button" class="btn btn-sm btn-light text-primary" id="add_contact">
                                                <i class="me-1" data-feather="user-plus"></i>
                                                Tambah Kontak
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="container-fluid px-4">
                                        <div class="page-header-content">
                                            
                                        </div>
                                    </div>
                                    <div class="container">
                                        <table id="datatablesSimple2" class="table-contact">
                                            <thead>
                                                <tr>
                                                    <th>Nama</th>
                                                    <th>Email</th>
                                                    <th>No.Telp</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light btn-sm" type="button" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i></i> &nbsp; Keluar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="add_modal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row" style="padding: 0px 20px 0px 20px">
                        <form id="add_form">
                            <input type="hidden" class="form-control add_dep" id="id_supplier" name="id">
                            <div class="row gx-3 mb-3">
                                <div class="col-md-12">
                                    <label class="small mb-12" for="name_supplier">Nama Supplier</label>
                                    <input class="form-control add_dep" id="name_supplier" name="name" type="text" placeholder="Supplier"/>
                                </div>
                            </div>
                            <div class="row gx-3 mb-3">
                                <div class="col-md-12">
                                    <label class="small mb-12" for="address_supplier">Alamat</label>
                                    <textarea class="form-control add_dep" id="address_supplier" name="address" type="text" placeholder="Alamat Supplier"></textarea>
                                </div>
                            </div>
                            <div class="row gx-3 mb-3">
                                <div class="col-md-12">
                                    <label class="small mb-12" for="active_supplier">Status</label>
                                    <select class="form-control add_dep" id="active_supplier" name="active">
                                        <option value="" disabled selected>Pilih Status...</option>
                                        <option value="1" >Aktif</option>
                                        <option value="0" >Non-Aktif</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light btn-sm" type="button" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i></i> &nbsp; Keluar</button>
                    <button class="btn btn-light btn-sm" type="button" onclick="save_supplier()"><i class="fa-regular fa-floppy-disk"></i> &nbsp; Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="add_contact_modal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row" style="padding: 0px 20px 0px 20px">
                        <form id="add_contact_form">
                            <input type="hidden" class="form-control add_contact" id="id_contact" name="id">
                            <input type="hidden" class="form-control add_contact" id="id_supplier_contact" name="id_supplier">
                            <div class="row gx-3 mb-3">
                                <div class="col-md-12">
                                    <label class="small mb-12" for="name_supplier_contact">Supplier</label>
                                    <input class="form-control form-control-solid add_contact" id="name_supplier_contact" type="text" placeholder="Supplier" readonly/>
                                </div>
                            </div>
                            <div class="row gx-3 mb-3">
                                <div class="col-md-12">
                                    <label class="small mb-12" for="name_supplier">Nama</label>
                                    <input class="form-control add_contact" id="name_contact" name="name" type="text" placeholder="Nama"/>
                                </div>
                            </div>
                            <div class="row gx-3 mb-3">
                                <div class="col-md-12">
                                    <label class="small mb-12" for="email_contact">Email</label>
                                    <input class="form-control add_contact" id="email_contact" name="email" type="email" placeholder="Email"/>
                                </div>
                            </div>
                            <div class="row gx-3 mb-3">
                                <div class="col-md-12">
                                    <label class="small mb-12" for="phone_contact">No. Telp</label>
                                    <input class="form-control add_contact" id="phone_contact" name="phone" type="text" placeholder="No Telfon">
                                </div>
                            </div>
                            <div class="row gx-3 mb-3">
                                <div class="col-md-12">
                                    <label class="small mb-12" for="active_contact">Status</label>
                                    <select class="form-control add_contact" id="active_contact" name="active">
                                        <option value="" disabled selected>Pilih Status...</option>
                                        <option value="1" >Aktif</option>
                                        <option value="0" >Non-Aktif</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light btn-sm" type="button" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i></i> &nbsp; Keluar</button>
                    <button class="btn btn-light btn-sm" type="button" onclick="save_contact()"><i class="fa-regular fa-floppy-disk"></i> &nbsp; Simpan</button>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>