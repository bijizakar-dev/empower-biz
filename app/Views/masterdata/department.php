<?= $this->extend('layout/default') ?>

<?= $this->section('title') ?>
    <title><?= $title ?> &mdash; Empower Biz</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <style type="text/css">
        #add {
            display: none;
        }
    </style>

    <script src="<?= base_url()?>/assets/js/simple-datatables.min.js"></script>
    <script type="text/javascript">
        let permissions = <?= json_encode($permissions) ?>

        let permission_add = false;
        let permission_edit = false;
        let permission_delete = false;
        if (permissions.length != 0 ) {
            let permission = permissions[0];

            if (permission.add == 1) {
                permission_add = true;
            }

            if (permission.edit == 1) {
                permission_edit = true;
            }

            if (permission.delete == 1) {
                permission_delete = true;
            }
        }

        let dataTable1;
        let dataTable2;

        $(function() {
            
            // CHECK ROLE PERMISSIONS USER 
            if (permission_add == true) {
                $('#add').show();
            }


            // dataTables = new simpleDatatables.DataTable("#datatablesSimple");
            get_list_deparment();
            reset_form();

            $('#reload').click(function(){
                reset_form();
                get_list_deparment();
            });

            $('#add').click(function() {
                reset_form();
                $('#add_modal').modal('show');
                $('.modal-title').html('Tambah Data')
            });
        });

        function reset_form() {
            $('.add_dep').val('');
        }

        function get_list_deparment() {
            if (dataTable1) {
                dataTable1.destroy();
            }

            $('.table-department tbody').empty();
            
            $.ajax({
                url: '<?= base_url('api/masterdata/listDepartment') ?>',
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
                            // Permission 
                            let btn_edit    = '-';
                            let btn_delete  = '-';

                            if (permission_edit == true) {
                                btn_edit = '<button type="button" class="btn btn-datatable btn-icon btn-transparent-dark me-2" onclick="edit_department('+v.id+')"><i data-feather="edit"></i></button> ';
                            }

                            if (permission_delete == true) {
                                btn_delete = '<button type="button" class="btn btn-datatable btn-icon btn-transparent-dark" onclick="delete_department('+v.id+')"><i data-feather="trash-2"></i></button>';
                            }
                            // Permission 

                            badgeStatus = v.active == 1 ? 'bg-green-soft text-green' : 'bg-red-soft text-red';
                            status = v.active == 1 ? 'Aktif' : 'Non-Aktif'

                            str = '<tr>'+
                                    '<td>'+
                                        '<div class="d-flex align-items-center">'+
                                            '<div class="avatar me-2"><i data-feather="smile"></i></div>'+
                                            v.name+
                                        '</div>'+
                                    '</td>'+
                                    '<td>'+v.description+'</td>'+
                                    '<td><span class="badge '+badgeStatus+'">'+status+'</span></td>'+
                                    '<td>'+
                                        btn_edit+    
                                        btn_delete+
                                    '</td>'+
                                '</tr>';
                            $('.table-department tbody').append(str);
                        });
                    } else {
                        console.log('KOSONG')

                        str = '<tr><td class="datatable-empty" colspan="5">No entries found</td></tr>';
                        $('.table-department tbody').append(str);
                    }

                    feather.replace();

                    dataTable = new simpleDatatables.DataTable("#datatablesSimple");
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
        
        function save_department() {
            let addForm = $('#add_form').serialize();

            $.ajax({
                type : 'POST',
                url: '<?= base_url("api/masterdata/department") ?>',
                data: addForm,
                cache: false,
                dataType : 'json',
                beforeSend: function() {
                    showLoading();
                },
                success: function(data) {
                    $('#add_modal').modal('hide');
                    get_list_deparment()

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

        function delete_department(id) {
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
                        url: '<?= base_url("api/masterdata/department") ?>?id='+id,
                        cache: false,
                        dataType : 'json',
                        beforeSend: function() {
                            showLoading();
                        },
                        success: function(data) {
                            $('#add_modal').modal('hide');
                            get_list_deparment()

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

        function edit_department(id) {
            if(id == '' || id == null) {
                return false;
            }

            $.ajax({
                type : 'GET',
                url: '<?= base_url("api/masterdata/department")?>?id='+id,
                cache: false,
                dataType : 'json',
                beforeSend: function() {
                    showLoading();
                },
                success: function(data) {
                    $('#id_department').val(id);
                    $('#name_department').val(data.data.name);
                    $('#description_department').val(data.data.description);
                    $('#active_department').val(data.data.active);

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
                    <table id="datatablesSimple" class="table-department">
                        <thead>
                            <tr>
                                <th>Nama Departemen</th>
                                <th>Deskripsi</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Nama Departemen</th>
                                <th>Deskripsi</th>
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
                            <input type="hidden" class="form-control add_dep" id="id_department" name="id">
                            <div class="row gx-3 mb-3">
                                <div class="col-md-12">
                                    <label class="small mb-12" for="name_department">Nama Departemen</label>
                                    <input class="form-control add_dep" id="name_department" name="name" type="text" placeholder="Departemen"/>
                                </div>
                            </div>
                            <div class="row gx-3 mb-3">
                                <div class="col-md-12">
                                    <label class="small mb-12" for="description_department">Deskripsi</label>
                                    <textarea class="form-control add_dep" id="description_department" name="description" type="text" placeholder="Deskripsi Departemen"></textarea>
                                </div>
                            </div>
                            <div class="row gx-3 mb-3">
                                <div class="col-md-12">
                                    <label class="small mb-12" for="active_department">Status</label>
                                    <select class="form-control add_dep" id="active_department" name="active">
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
                    <button class="btn btn-light btn-sm" type="button" onclick="save_department()"><i class="fa-regular fa-floppy-disk"></i> &nbsp; Simpan</button>
                </div>
            </div>
        </div>
    </div>

<?= $this->endSection() ?>