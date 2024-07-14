<?= $this->extend('layout/default') ?>

<?= $this->section('title') ?>
    <title><?= $title ?> &mdash; Empower Biz</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <style>
        .form-check {
            display: flex; 
            justify-content: center; 
            align-items: center;
        }
    </style>
    <script src="<?= base_url()?>/assets/js/simple-datatables.min.js"></script>
    <script type="text/javascript">
        var dataTable1;

        $(function() {
            // dataTables = new simpleDatatables.DataTable("#datatablesSimple");
            get_list_role();
            reset_form();

            $('#reload').click(function(){
                reset_form();
                get_list_role();
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

        function get_list_role() {
            if (dataTable1) {
                dataTable1.destroy();
            }

            $('.table-role tbody').empty();
            
            $.ajax({
                url: '<?= base_url('api/system/listRole') ?>',
                type: 'GET',
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
                                    '<td>'+v.description+'</td>'+
                                    '<td><span class="badge '+badgeStatus+'">'+status+'</span></td>'+
                                    '<td>'+
                                        '<button type="button" class="btn btn-datatable btn-icon btn-transparent-dark me-2" onclick="edit_role('+v.id+')"><i data-feather="edit"></i></button>'+    
                                        '<button type="button" class="btn btn-datatable btn-icon btn-transparent-dark" onclick="delete_role('+v.id+')"><i data-feather="trash-2"></i></button>'+
                                    '</td>'+
                                '</tr>';
                            $('.table-role tbody').append(str);
                        });
                    } else {
                        str = '';
                        $('.table-role tbody').append(str);
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
        
        function save_role() {
            let addForm = $('#add_form').serialize();

            $.ajax({
                type : 'POST',
                url: '<?= base_url("api/system/role") ?>',
                data: addForm,
                cache: false,
                dataType : 'json',
                beforeSend: function() {
                    showLoading();
                },
                success: function(data) {
                    $('#add_modal').modal('hide');
                    get_list_role()

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

        function delete_role(id) {
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
                        url: '<?= base_url("api/system/role") ?>?id='+id,
                        cache: false,
                        dataType : 'json',
                        beforeSend: function() {
                            showLoading();
                        },
                        success: function(data) {
                            $('#add_modal').modal('hide');
                            get_list_role()

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

        function edit_role(id) {
            if(id == '' || id == null) {
                return false;
            }

            $.ajax({
                type : 'GET',
                url: '<?= base_url("api/system/role")?>?id='+id,
                cache: false,
                dataType : 'json',
                beforeSend: function() {
                    showLoading();
                },
                success: function(data) {
                    $('#id_role_edit').val(id);
                    $('#name_role_edit').val(data.data.name);
                    $('#description_role_edit').val(data.data.description);
                    $('#active_role_edit').val(data.data.active);

                    $('.modal-title').html('Detail Data')

                    permission_role(id);

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

        function permission_role(id) {
            if(id == '' || id == null) {
                return false;
            }
            
            $.ajax({
                type: 'GET',
                url: '<?= base_url("api/system/menuPermissionRole")?>?id_role=' + id,
                cache: false,
                dataType: 'json',
                beforeSend: function() {
                    showLoading();
                },
                success: function(data) {
                    $('#table-permission').empty();
                    let str = '';

                    if (data.data.length > 0) {
                        let groupByMenu = data.data.reduce((acc, item) => {
                            if (!acc[item.name_menu]) {
                                acc[item.name_menu] = [];
                            }
                            acc[item.name_menu].push(item);
                            return acc;
                        }, {});
                        
                        $.each(Object.keys(groupByMenu), function(i, menu_name){
                            str += '<tr><td colspan="8" style="background-color: #f2f2f2">' + menu_name + '</td></tr>';
                            $.each(groupByMenu[menu_name], function(k, val) {
                                let formCheck = ''; 
                                ['active', 'add', 'edit', 'delete', 'detail_view', 'import', 'export'].forEach(function(action) {
                                    console.log(val[action]);
                                    let checked = val[action] == 1 ? 'checked' : '';
                                    formCheck += '<td class="text-center">'+
                                                    '<div class="form-check">'+
                                                        '<input class="form-check-input" type="checkbox" ' + checked + ' data-item="' + val.id_item_menu + '" data-role="' + val.id_role + '" data-action="' + action + '">'+
                                                    '</div>'+
                                                '</td>';
                                });

                                str += '<tr data-item="' + val.id_item_menu + '" data-role="'+val.id_role+'">'+
                                            '<td>'+val.name_item_menu+'</td>'+
                                            formCheck+
                                        '</tr>';
                            });
                        });

                        $('#table-permission').append(str);

                    }
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

        function save_edit_role() {
            let editForm = $('#edit_form').serialize();

            $.ajax({
                type : 'POST',
                url: '<?= base_url("api/system/role") ?>',
                data: editForm,
                cache: false,
                dataType : 'json',
                beforeSend: function() {
                    showLoading();
                },
                success: function(data) {
                    save_role_permission_role();
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: "Access Failed",
                        text: "Internal Server Error",
                        icon: "error"
                    });
                }
            });

        }

        function save_role_permission_role() {
            let permissions = [];
            let id = $('#id_role_edit').val();

            $('#table-permission tr').each(function() {
                let row = $(this);
                let id_item_menu = row.data('item');
                let id_role = row.data('role');
                
                if (id_item_menu) {
                    let permission = {
                        id_item_menu: id_item_menu,
                        id_role: id_role,
                        active: row.find('input[data-action="active"]').is(':checked') ? 1 : 0,
                        add: row.find('input[data-action="add"]').is(':checked') ? 1 : 0,
                        edit: row.find('input[data-action="edit"]').is(':checked') ? 1 : 0,
                        delete: row.find('input[data-action="delete"]').is(':checked') ? 1 : 0,
                        detail_view: row.find('input[data-action="detail_view"]').is(':checked') ? 1 : 0,
                        import: row.find('input[data-action="import"]').is(':checked') ? 1 : 0,
                        export: row.find('input[data-action="export"]').is(':checked') ? 1 : 0
                    };

                    permissions.push(permission);
                }
            });

            $.ajax({
                type: 'POST',
                url: '<?= base_url("api/system/savePermissionRole") ?>',
                data: {permission: permissions, id_role: id},
                dataType: 'json',
                success: function(response) {
                    if(response.status == true) {
                        get_list_role();
                        
                        $('#detail_modal').modal('hide');

                        Swal.fire({
                            title: "Berhasil",
                            text: response.message,
                            icon: "success"
                        });
                    } else {
                        Swal.fire({
                            title: "Gagal",
                            text: response.message,
                            icon: "error"
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: "Save Permission Role Failed",
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
                    <table id="datatablesSimple" class="table-role">
                        <thead>
                            <tr>
                                <th>Nama Role</th>
                                <th>Deskripsi</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Nama Role</th>
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
                            <input type="hidden" class="form-control add_dep" id="id_role" name="id">
                            <div class="row gx-3 mb-3">
                                <div class="col-md-12">
                                    <label class="small mb-12" for="name_role">Nama Role</label>
                                    <input class="form-control add_dep" id="name_role" name="name" type="text" placeholder="Role"/>
                                </div>
                            </div>
                            <div class="row gx-3 mb-3">
                                <div class="col-md-12">
                                    <label class="small mb-12" for="description_role">Deskripsi</label>
                                    <textarea class="form-control add_dep" id="description_role" name="description" type="text" placeholder="Deskripsi Role"></textarea>
                                </div>
                            </div>
                            <div class="row gx-3 mb-3">
                                <div class="col-md-12">
                                    <label class="small mb-12" for="active_role">Status</label>
                                    <select class="form-control add_dep" id="active_role" name="active">
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
                    <button class="btn btn-light btn-sm" type="button" onclick="save_role()"><i class="fa-regular fa-floppy-disk"></i> &nbsp; Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="detail_modal">
        <div class="modal-dialog modal-lg" role="document" style="--bs-modal-width: 90%">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Data</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row" style="padding: 0px 20px 0px 20px">
                        <div class="col-xl-12">
                            <div class="card mb-4">
                                <div class="card-header">Role</div>
                                <div class="card-body">
                                    <form id="edit_form">
                                        <input type="hidden" class="form-control add_dep" id="id_role_edit" name="id">
                                        <div class="row gx-3 mb-3">
                                            <div class="col-md-12">
                                                <label class="small mb-12" for="name_role">Nama Role</label>
                                                <input class="form-control add_dep" id="name_role_edit" name="name" type="text" placeholder="Role"/>
                                            </div>
                                        </div>
                                        <div class="row gx-3 mb-3">
                                            <div class="col-md-12">
                                                <label class="small mb-12" for="description_role">Deskripsi</label>
                                                <textarea class="form-control add_dep" id="description_role_edit" name="description" type="text" placeholder="Deskripsi Role"></textarea>
                                            </div>
                                        </div>
                                        <div class="row gx-3 mb-3">
                                            <div class="col-md-12">
                                                <label class="small mb-12" for="active_role">Status</label>
                                                <select class="form-control add_dep" id="active_role_edit" name="active">
                                                    <option value="" disabled selected>Pilih Status...</option>
                                                    <option value="1" >Aktif</option>
                                                    <option value="0" >Non-Aktif</option>
                                                </select>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="padding: 0px 20px 0px 20px">
                        <div class="col-xl-12">
                            <div class="card mb-4">
                                <div class="card-header">Hak Akses Role</div>
                                <div class="card-body">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr style="">
                                                <th width="18%">Menu</th>
                                                <th width="6%" class="text-center">Show Menu</th>
                                                <th width="6%" class="text-center">Create Data</th>
                                                <th width="6%" class="text-center">Edit Data</th>
                                                <th width="6%" class="text-center">Delete Data</th>
                                                <th width="6%" class="text-center">Detail View</th>
                                                <th width="6%" class="text-center">Import Data</th>
                                                <th width="6%" class="text-center">Export Data</th>
                                            </tr>
                                        </thead>
                                        <tbody id="table-permission">
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light btn-sm" type="button" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i></i> &nbsp; Keluar</button>
                    <button class="btn btn-light btn-sm" type="button" onclick="save_edit_role()"><i class="fa-regular fa-floppy-disk"></i> &nbsp; Simpan</button>
                </div>
            </div>
        </div>
    </div>
    
<?= $this->endSection() ?>