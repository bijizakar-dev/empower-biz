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

        var dataTable1;

        $(function() {

            // CHECK ROLE PERMISSIONS USER 
            if (permission_add == true) {
                $('#add').show();
            }

            get_list_employee();
            reset_form();

            $('#reload').click(function(){
                reset_form();
                get_list_employee();
            });

            $('#add').click(function() {
                reset_form();
                $('#add_modal').modal('show');
                $('.modal-title').html('Tambah Data')
            });
        });

        function reset_form() {
            $('.add_emp').val('');
            $('#img_emp').removeAttr('src')
            $('#img_emp').attr('src', '<?= base_url()?>template/assets/img/demo/user-placeholder.svg'); 
        }

        function get_list_employee() {
            if (dataTable1) {
                dataTable1.destroy();
            }
            $('.table-employee tbody').empty();
            
            $.ajax({
                url: '<?= base_url('api/masterdata/listEmployee') ?>',
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
                            // Permission 
                            let btn_edit    = '-';
                            let btn_delete  = '-';

                            if (permission_edit == true) {
                                btn_edit = '<button type="button" class="btn btn-datatable btn-icon btn-transparent-dark me-2" onclick="edit_employee('+v.id+')"><i data-feather="edit"></i></button> ';
                            }

                            if (permission_delete == true) {
                                btn_delete =  '<button type="button" class="btn btn-datatable btn-icon btn-transparent-dark" onclick="delete_employee('+v.id+')"><i data-feather="trash-2"></i></button> ';
                            }
                            // Permission 

                            badgeStatus = v.active == 1 ? 'bg-green-soft text-green' : 'bg-red-soft text-red';
                            status = v.active == 1 ? 'Aktif' : 'Non-Aktif'

                            photoProf = v.gender == 'F' ? 'profile-1.png' : 'profile-2.png';

                            str = '<tr>'+
                                    '<td>'+
                                        '<div class="d-flex align-items-center">'+
                                            '<div class="avatar me-2"><img class="avatar-img img-fluid" src="<?= base_url()?>template/assets/img/illustrations/profiles/'+photoProf+'" /></div>'+
                                            '<div>'+v.name+' <br> <span style="font-size: 13px"><small> NIP. '+v.nip+'</small></span></div>'+
                                        '</div>'+
                                    '</td>'+
                                    '<td>'+v.department_name+'</td>'+
                                    '<td>'+v.address+'</td>'+
                                    '<td class="text-center"><span class="badge '+badgeStatus+'">'+status+'</span></td>'+
                                    '<td>'+
                                        btn_edit+  
                                        btn_delete+  
                                    '</td>'+
                                '</tr>';

                            $('.table-employee tbody').append(str);
                        });
                    } else {
                        str = '';
                        $('.table-employee tbody').append(str);
                    }

                    feather.replace();
                    dataTable1 = new simpleDatatables.DataTable("#datatablesSimple");

                },
                error: function(xhr, status, error) {
                    hideLoading();
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
        
        function save_employee() {
            let addForm = $('#add_form').serialize();

            $.ajax({
                type : 'POST',
                url: '<?= base_url("api/masterdata/employee") ?>',
                data: addForm,
                cache: false,
                dataType : 'json',
                beforeSend: function(){
                    showLoading();
                },
                success: function(data) {
                    $('#add_modal').modal('hide');
                    get_list_employee()

                    Swal.fire({
                        title: "Berhasil",
                        text: "Data Berhasil Simpan",
                        icon: "success"
                    });

                },
                error: function(e){
                    Swal.fire({
                        title: "Access Failed",
                        text: "Internal Server Error",
                        icon: "error"
                    });
                },
                complete: function(){
                    hideLoading();
                    reset_form();
                }
            });
        }

        function delete_employee(id) {
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
                        url: '<?= base_url("api/masterdata/employee") ?>?id='+id,
                        cache: false,
                        dataType : 'json',
                        success: function(data) {
                            $('#add_modal').modal('hide');
                            get_list_employee()

                            Swal.fire("Berhasil", "Data Berhasil Hapus", "success");
                        },
                        error: function(e){
                            Swal.fire({
                                title: "Access Failed",
                                text: "Internal Server Error",
                                icon: "error"
                            });
                        }
                    });
                }
            });

            
        }

        function edit_employee(id) {
            if(id == '' || id == null) {
                return false;
            }

            $.ajax({
                type : 'GET',
                url: '<?= base_url("api/masterdata/employee")?>?id='+id,
                cache: false,
                dataType : 'json',
                beforeSend: function(){
                    showLoading();
                    reset_form();
                },
                success: function(data) {
                    if(data.data.gender == 'M') {
                        $('#img_emp').removeAttr('src')
                        $('#img_emp').attr('src', '<?= base_url()?>template/assets/img/illustrations/profiles/profile-2.png');
                    } else {
                        $('#img_emp').removeAttr('src')
                        $('#img_emp').attr('src', '<?= base_url()?>template/assets/img/illustrations/profiles/profile-1.png');
                    }

                    $('#id_emp').val(id);
                    $('#nip_emp').val(data.data.nip);
                    $('#name_emp').val(data.data.name);
                    $('#birth_date_emp').val(data.data.birth_date);
                    $('#address_emp').val(data.data.address);
                    $('#gender_emp').val(data.data.gender);
                    $('#phone_number_emp').val(data.data.phone_number);
                    $('#education_emp').val(data.data.education);

                    $('#id_department_emp').val(data.data.id_department);

                    $('#total_cuti_emp').val(0);
                    $('#active_emp').val(data.data.active);

                    $('.modal-title').html('Edit Data Pegawai')
                    $('#add_modal').modal('show');
                },
                error: function(e){
                    Swal.fire({
                        title: "Access Failed",
                        text: "Internal Server Error",
                        icon: "error"
                    });
                },
                complete: function(){
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
                    <table id="datatablesSimple" class="table-employee">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Departemen</th>
                                <th>Alamat</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <div class="modal fade" id="add_modal">
        <div class="modal-dialog" role="document" style="--bs-modal-width: 90%">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Data Pegawai</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row" style="padding: 0px 20px 0px 20px">
                        <div class="col-xl-4">
                            <div class="card mb-4 mb-xl-0">
                                <div class="card-header">Foto Pegawai</div>
                                <div class="card-body text-center">
                                    <!-- Profile picture image-->
                                    <img class="img-account-profile rounded-circle mb-2" src="<?= base_url()?>/template/assets/img/demo/user-placeholder.svg" alt="" id="img_emp"/>
                                    <!-- Profile picture help block-->
                                    <div class="small font-italic text-muted mb-4">Foto Belum Dapat diganti.</div>
                                    <!-- Profile picture upload button-->
                                    <button class="btn btn-primary" type="button">Upload Foto</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-8">
                            <div class="card mb-4">
                                <div class="card-header">Detail Pegawai</div>
                                <div class="card-body">
                                    <form id="add_form">
                                        <!-- input Hidden -->
                                        <input type="hidden" class="form-control add_emp" id="id_emp" name="id">
                                        <!-- input Hidden -->

                                        <div class="mb-3">
                                            <label class="small mb-1" for="nip_emp">NIP</label>
                                            <input class="form-control form-control-solid add_emp" id="nip_emp" type="text" name="nip" placeholder="NIP Generate Auto" readonly/>
                                        </div>
                                        <hr>

                                        <!-- IDENTITAS DIRI -->
                                        <div class="mb-3">
                                            <label class="small mb-1" for="name_emp">Nama Lengkap</label>
                                            <input class="form-control add_emp" id="name_emp" type="text" name="name" placeholder="Nama Lengkap" />
                                        </div>
                                        <div class="row gx-3 mb-3">
                                            <div class="col-md-4">
                                                <label class="small mb-1" for="birth_date_emp">Tanggal lahir</label>
                                                <input class="form-control add_emp" id="birth_date_emp" type="date" name="birth_date" placeholder="Tanggal Lahir" />
                                            </div>
                                            <div class="col-md-3">
                                                <label class="small mb-1" for="gender_emp">Jenis Kelamin</label>
                                                <select class="form-select add_emp" id="gender_emp" name="gender" aria-label="Default select example">
                                                    <option value="" selected disabled>Pilih Jenis Kelamin...</option>
                                                    <option value="M">Laki-laki</option>
                                                    <option value="F">Perempuan</option>
                                                </select>
                                            </div>
                                            <div class="col-md-5">
                                                <label class="small mb-1" for="phone_number_emp">No. Telpon</label>
                                                <input class="form-control add_emp" id="phone_number_emp" type="text" name="phone_number" placeholder="No. Telpon" />
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="small mb-1" for="address_emp">Alamat Lengkap</label>
                                            <textarea class="form-control add_emp" id="address_emp"  name="address" placeholder="Alamat Lengkap"></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="small mb-1" for="education_emp">Pendidikan Terakhir</label>
                                            <input class="form-control add_emp" id="education_emp"  name="education" placeholder="Pendidikan Terakhir" />
                                        </div>
                                        <!-- IDENTITAS DIRI -->

                                        <hr>
                                        <!-- KEPEGAWAIAN -->
                                        <div class="row gx-3 mb-3">
                                            <div class="col-md-6">
                                                <label class="small mb-1" for="id_department_emp">Departemen</label>
                                                <select class="form-select add_emp" id="id_department_emp" name="id_department" aria-label="Default select example">
                                                    <option value="" selected disabled>Pilih Departemen...</option>
                                                    <?php foreach ($department as $key => $value): ?>
                                                        <option value="<?= esc($key) ?>"><?= esc($value) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="small mb-1" for="active_emp">Status</label>
                                                <select class="form-select add_emp" id="active_emp" name="active" aria-label="Default select example">
                                                    <option value="" selected disabled>Pilih Status...</option>
                                                    <option value="1">Aktif</option>
                                                    <option value="0">Non-Aktif</option>
                                                </select>
                                            </div>
                                        </div>
                                        <!-- KEPEGAWAIAN -->
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light btn-sm" type="button" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i></i> &nbsp; Keluar</button>
                    <button class="btn btn-light btn-sm" type="button" onclick="save_employee()"><i class="fa-regular fa-floppy-disk"></i> &nbsp; Simpan</button>
                </div>
            </div>
        </div>
    </div>

<?= $this->endSection() ?>