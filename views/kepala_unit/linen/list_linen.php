<?php
session_start();

if (isset($_SESSION['login']) && $_SESSION['login'] == 'punten') {
    if (isset($_SESSION['role']) && $_SESSION['role'] == '1') {

        include_once 'views/templates/head.php';
        require 'controller/config/connection.php';
        $role = $_SESSION['role'];
        $nama = $_SESSION['nama_user'];

        ?>

        <body class="theme-blue">
            <!-- side bar -->
            <?php
                    include_once 'views/templates/navbar/top_bar.php';
                    include_once 'views/templates/navbar/left_side_bar.php';
                    ?>
            <!-- end side bar -->

            <section class="content">
                <div class="container-fluid">
                    <div class="block-header">
                        <h2>LINEN</h2>
                        <ol class="breadcrumb align-right">
                            <li><a href="javascript:void(0);">Dashboard</a></li>
                            <li><a href="javascript:void(0);">Kepala Unit</a></li>
                            <li><a href="javascript:void(0);">Linen</a></li>
                            <li class="active">Daftar Linen</li>
                        </ol>
                        <?php if (isset($_GET['message_success'])) { ?>
                            <!-- alert success -->
                            <div class="alert alert-success alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <?php echo $_GET['message_success']?>
                            </div>
                            <!-- end alert success -->
                        <?php } elseif (isset($_GET['message_failed'])) { ?>
                            <!-- alert failed -->
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <?php echo $_GET['message_failed'] ?>
                            </div>
                            <!-- end alert failed -->
                        <?php } ?>
                    </div>
                    <!-- Basic Examples -->
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="card">
                                <div class="header">
                                    <a href="javascript:void(0)" class="btn btn-primary waves-effect pull-right" data-toggle="modal" data-target="#modalAdd">Cari Linen</a>
                                    <h2>
                                        DAFTAR LINEN
                                    </h2>
                                </div>
                                <div class="body">
                                    <div class="table-responsive">
                                        <table id="table_user_list" class="table table-striped table-hover dataTable js-exportable" style="width: 100%">
                                            <thead>
                                                <tr>
                                                    <th style="width: 5%;" class="text-nowrap">No</th>
                                                    <th style="width: 30%;" class="text-nowrap">Nama Linen</th>
                                                    <th style="width: 20%;" class="text-nowrap">Kategori</th>
                                                    <th style="width: 30%;" class="text-nowrap">Ruang - Kelas</th>
                                                    <th style="width: 10%;" class="text-nowrap">jumlah</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $no = 1;

                                                if (isset($_GET['ruang']) && !empty($_GET['ruang'])) {
                                                    $ruang = $_GET['ruang'];
                                                    $where = "ruang.nama_ruang = '$ruang' ORDER BY nama_linen ASC";
                                                }else{
                                                    $where = "1 ORDER BY nama_linen ASC";
                                                }
                                                    $getLinen = mysqli_query($conn, "SELECT id_linen, nama_linen, linen.id_ruang, kelas.nama_kelas, ruang.nama_ruang, kategori.nama_kategori, linen.jml_linen FROM `linen` JOIN kelas ON kelas.id_kelas=linen.id_kelas JOIN ruang ON ruang.id_ruang=linen.id_ruang JOIN kategori ON kategori.id_kategori=linen.id_kategori WHERE ".$where);
                                                    while ($data_linen = mysqli_fetch_assoc($getLinen)) {
                                                ?>
                                                    <tr>
                                                        <td><?= $no++ ?></td>
                                                        <td><?= ucwords($data_linen['nama_linen']) ?></td>
                                                        <td><?= ucwords($data_linen['nama_kategori']) ?></td>
                                                        <td><?= ucwords($data_linen['nama_ruang']) ?> - <?=ucwords($data_linen['nama_kelas'])?></td>
                                                        <td><?= $data_linen['jml_linen']?></td>
                                                    </tr>
                                                <?php
                                                    }
                                                    
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- #END# Basic Examples -->

                    <!-- Default Size -->
                    <div class="modal fade" id="modalAdd" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="defaultModalLabel">CARI LINEN</h4>
                                </div>
                                <div class="modal-body">
                                    <!-- Basic Validation -->
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <form id="form_validation" action="" method="GET">
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <select class="form-control show-tick m-t-20" name="ruang" id="ruang_linen" required>
                                                            <option>Pilih Ruang</option>
                                                            <?php 
                                                            $sqlKelas = mysqli_query($conn, "SELECT * FROM ruang WHERE 1 ORDER BY id_ruang ASC");
                                                            while ($dataKelas = mysqli_fetch_assoc($sqlKelas)) {
                                                             ?>
                                                            <option value="<?=$dataKelas['nama_ruang']?>"><?=$dataKelas['nama_ruang']?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <label for="ruang_linen" class="form-label">Pilih Ruang</label>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary waves-effect">CARI</button>
                                            </form>
                                    <button type="button" class="btn btn-link waves-effect waves-red" data-dismiss="modal" style="color:red">TUTUP</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end modal -->
                </div>
            </section>

            <!-- Jquery Core Js -->
            <script src="<?= $base_url ?>vendors/plugins/jquery/jquery.min.js"></script>

            <!-- Bootstrap Core Js -->
            <script src="<?= $base_url ?>vendors/plugins/bootstrap/js/bootstrap.js"></script>

            <!-- Select Plugin Js -->
            <script src="<?= $base_url ?>vendors/plugins/bootstrap-select/js/bootstrap-select.js"></script>

            <!-- Jquery Validation Plugin Css -->
            <script src="<?= $base_url ?>vendors/plugins/jquery-validation/jquery.validate.js"></script>

            <!-- JQuery Steps Plugin Js -->
            <script src="<?= $base_url ?>vendors/plugins/jquery-steps/jquery.steps.js"></script>

            <!-- Sweet Alert Plugin Js -->
            <script src="<?= $base_url ?>vendors/plugins/sweetalert/sweetalert.min.js"></script>

            <!-- Slimscroll Plugin Js -->
            <script src="<?= $base_url ?>vendors/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

            <script src="<?= $base_url ?>vendors/plugins/jquery-datatable/jquery.dataTables.js"></script>

            <!-- Waves Effect Plugin Js -->
            <script src="<?= $base_url ?>vendors/plugins/node-waves/waves.js"></script>
            <!-- Jquery DataTable Plugin Js -->
            <script src="<?= $base_url ?>vendors/DataTables/datatables.min.js"></script>

            <!-- Jquery DataTable Plugin Js -->
            <script src="<?= $base_url ?>vendors/plugins/jquery-datatable/jquery.dataTables.js"></script>
            <script src="<?= $base_url ?>vendors/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
            <script src="<?= $base_url ?>vendors/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js"></script>
            <script src="<?= $base_url ?>vendors/plugins/jquery-datatable/extensions/export/buttons.flash.min.js"></script>
            <script src="<?= $base_url ?>vendors/plugins/jquery-datatable/extensions/export/jszip.min.js"></script>
            <script src="<?= $base_url ?>vendors/plugins/jquery-datatable/extensions/export/pdfmake.min.js"></script>
            <script src="<?= $base_url ?>vendors/plugins/jquery-datatable/extensions/export/vfs_fonts.js"></script>
            <script src="<?= $base_url ?>vendors/plugins/jquery-datatable/extensions/export/buttons.html5.min.js"></script>
            <script src="<?= $base_url ?>vendors/plugins/jquery-datatable/extensions/export/buttons.print.min.js"></script>

            <!-- Custom Js -->
            <script src="<?= $base_url ?>vendors/js/admin.js"></script>

            <!-- Demo Js -->
            <script src="<?= $base_url ?>vendors/js/demo.js"></script>

            <!-- Jquery DataTable Plugin Js -->
    

            <script>
                /* tabel */
                $(document).ready(function() {
                    $('#table_user_list').DataTable({
                        dom: 'Bfrtip',
                        responsive: true,
                        buttons: [{
                              extend: 'pdfHtml5',
                              title: 'Data Linen' 
                            },{
                                extend: 'excel',
                                title: 'Data Linen'
                            },
                            {
                                extend: 'print',
                                title: 'Data Linen'
                            }
                        ],
                        'order': [
                            [0, 'asc']
                        ],
                        'columnDefs': [{
                            'targets': 'no-sort',
                            'orderable': false
                        }],
                        'searching': true,
                        'info': false,
                        'paging': true
                    });
                });
            </script>

        </body>

        </html>

<?php
    } else {
        header('location:' . $base_url . 'logout/?a=tidak sah');
    }
} else {
    header('location:' . $base_url . 'logout/?a=belum login');
}
?>