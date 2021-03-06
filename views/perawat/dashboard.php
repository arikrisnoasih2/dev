<?php
session_start();

if (isset($_SESSION['login']) && $_SESSION['login'] == 'punten') {
    if (isset($_SESSION['role']) && $_SESSION['role'] == '4') {

        include_once 'views/templates/head.php';
        require 'controller/config/connection.php';
        $role = $_SESSION['role'];
        $nama = $_SESSION['nama_user'];

        if (isset($_GET['tanggal']) && !empty($_GET['tanggal'])) {
            $date_now = $_GET['tanggal'];
        }else{
            $date_now = date('Y-m-d');
        }

        if (isset($_GET['ruang']) && !empty($_GET['ruang'])) {
            $ruang = $_GET['ruang'];
            $where = " AND ruang.nama_ruang='$ruang'";
        }else{
            $where = "";
        }

        $sqlLinenKotor = mysqli_query($conn, "SELECT linen.nama_linen, kategori.nama_kategori, ruang.nama_ruang, kelas.nama_kelas, linen_kotor.status, jenis_linen_kotor.jumlah FROM `linen_kotor` INNER JOIN linen ON linen.id_linen=linen_kotor.id_linen INNER JOIN ruang ON ruang.id_ruang=linen.id_ruang INNER JOIN kelas ON kelas.id_kelas=linen.id_kelas INNER JOIN kategori ON kategori.id_kategori=linen.id_kategori INNER JOIN jenis_linen_kotor ON jenis_linen_kotor.id_linen_kotor=linen_kotor.id_linen_kotor WHERE DATE(linen_kotor.tgl_pengambilan) = '$date_now'".$where);
        if (mysqli_num_rows($sqlLinenKotor) > 0) {
            while ($dataKotor = mysqli_fetch_assoc($sqlLinenKotor)) {
                $kotor[] = $dataKotor;
            }
        }

        $sqlLinenCuci = mysqli_query($conn, "SELECT linen.nama_linen, kategori.nama_kategori, ruang.nama_ruang, kelas.nama_kelas, pencucian.status, jenis_linen_kotor.jumlah FROM `pencucian` INNER JOIN linen_kotor ON linen_kotor.id_linen_kotor=pencucian.id_linen_kotor INNER JOIN linen ON linen.id_linen=linen_kotor.id_linen INNER JOIN ruang ON ruang.id_ruang=linen.id_ruang INNER JOIN kelas ON kelas.id_kelas=linen.id_kelas INNER JOIN kategori ON kategori.id_kategori=linen.id_kategori INNER JOIN jenis_linen_kotor ON jenis_linen_kotor.id_jenis_linen_kotor=pencucian.id_jenis_linen_kotor WHERE pencucian.status = 'cuci' AND DATE(pencucian.tgl_cuci) = '$date_now'".$where);
        if (mysqli_num_rows($sqlLinenCuci) > 0) {
            while ($dataCuci = mysqli_fetch_assoc($sqlLinenCuci)) {
                $cuci[] = $dataCuci;
            }
        }

        $sqlLinenBersih = mysqli_query($conn, "SELECT linen.nama_linen, kategori.nama_kategori, ruang.nama_ruang, kelas.nama_kelas, linen_bersih.status, linen_bersih.jumlah FROM `linen_bersih` INNER JOIN pencucian ON pencucian.id_pencucian=linen_bersih.id_pencucian INNER JOIN linen_kotor ON linen_kotor.id_linen_kotor=pencucian.id_linen_kotor INNER JOIN linen ON linen.id_linen=linen_kotor.id_linen INNER JOIN ruang ON ruang.id_ruang=linen.id_ruang INNER JOIN kelas ON kelas.id_kelas=linen.id_kelas INNER JOIN kategori ON kategori.id_kategori=linen.id_kategori WHERE DATE(linen_bersih.tgl) = '$date_now'".$where);
        if (mysqli_num_rows($sqlLinenBersih) > 0) {
            while ($dataBersih = mysqli_fetch_assoc($sqlLinenBersih)) {
                $bersih[] = $dataBersih;
            }
        }

        if (!empty($bersih) && !empty($kotor) && !empty($cuci)) {
            $data = array_merge($kotor, $cuci, $bersih);
        }elseif (!empty($bersih) && !empty($kotor)) {
            $data = array_merge($kotor, $bersih);
        }elseif (!empty($cuci) && !empty($bersih)) {
            $data = array_merge($cuci, $bersih);
        }elseif (!empty($kotor) && !empty($cuci)) {
            $data = array_merge($kotor, $cuci);
        }
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
                <h2>PERAWAT</h2>
                <ol class="breadcrumb align-right">
                    <li class="active">Dashboard</li>
                </ol>
                <?php if (isset($_GET['message_success'])) { ?>
                    <!-- alert success -->
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        Selamat, Data Pengguna berhasil ditambahkan!
                    </div>
                    <!-- end alert success -->
                <?php } elseif (isset($_GET['message_failed'])) { ?>
                    <!-- alert failed -->
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        Maaf, Data Pengguna gagal ditambahkan!, harap periksa lagi informasi yang diinputkan!.
                    </div>
                    <!-- end alert failed -->
                <?php } ?>
            </div>
            <!-- Basic Validation -->
            <?php 
            //get permintaan linen
            $sqlPermintaan = mysqli_query($conn, "SELECT `id_permintaan_linen_baru` FROM `permintaan_linen_baru` WHERE 1");
            $totalPermintaan = mysqli_num_rows($sqlPermintaan);

            //get penerimaan linen
            $sqlPenerimaan = mysqli_query($conn, "SELECT `id_penerimaan_linen_baru` FROM `penerimaan_linen_baru` WHERE 1");
            $totalPenerimaan = mysqli_num_rows($sqlPenerimaan);

            //get linen bersih
            $sqlBersih = mysqli_query($conn,"SELECT `id_linen_bersih` FROM `linen_bersih` WHERE DATE(`tgl`) = '$date_now'");
            $totalLinenBersih = mysqli_num_rows($sqlBersih);

            $sqlLinenHilang = mysqli_query($conn, "SELECT `id_linen_hilang` FROM `linen_hilang` WHERE 1");
            $totalLinenHilang = mysqli_num_rows($sqlLinenHilang);

             ?>


            <div class="row clearfix">
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="info-box-3 bg-orange hover-zoom-effect">
                        <div class="icon">
                            <i class="material-icons">pan_tool</i>
                        </div>
                        <div class="content">
                            <div class="text">PERMINTAAN LINEN</div>
                            <div class="number"><?=$totalPermintaan?></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="info-box-3 bg-blue hover-zoom-effect">
                        <div class="icon">
                            <i class="material-icons">cached</i>
                        </div>
                        <div class="content">
                            <div class="text">PENERIMAAN LINEN</div>
                            <div class="number"><?=$totalPenerimaan?></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="info-box-3 bg-gray hover-zoom-effect">
                        <div class="icon">
                            <i class="material-icons">watch_later</i>
                        </div>
                        <div class="content">
                            <div class="text">LINEN HILANG & RUSAK</div>
                            <div class="number"><?=$totalLinenHilang?></div>
                        </div>
                    </div>
                </div>
                
            </div>
            <!-- #END# Basic Validation -->

            <!-- table linen kotor -->
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="card">
                                <div class="header">
                                    <a href="javascript:void(0)" class="btn btn-primary waves-effect pull-right" data-toggle="modal" data-target="#modalAdd">Cari Linen</a>
                                    <h2>
                                        PROSES LINEN - <?=date('d F Y')?>
                                    </h2>
                                </div>
                                <div class="body">
                                    <div class="table-responsive">
                                        <table id="table_user_list" class="table table-striped table-hover" style="width: 100%">
                                            <thead>
                                                <tr>
                                                    <th style="width: 5%;" class="text-nowrap">No</th>
                                                    <th style="width: 45%;" class="text-nowrap">Nama Linen - Kategori</th>
                                                    <th style="width: 40%;" class="text-nowrap">Ruang - Kelas</th>
                                                    <th style="width: 10%;" class="text-nowrap">jumlah</th>
                                                    <th style="width: 15%;" class="text-nowrap">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (!empty($data)) {
                                                
                                                $no = 1; 
                                                foreach ($data as $d) :
                                                    switch ($d['status']) {
                                                        case 'kotor':
                                                            $label = 'label-warning';
                                                            break;

                                                        case 'cuci':
                                                            $label = 'label-primary';
                                                            break;

                                                        case 'bersih':
                                                            $label = 'label-success';
                                                            break;
                                                    }
                                                ?>
                                                <tr>
                                                    <td><?=$no++?></td>
                                                    <td><?=ucwords($d['nama_linen'])?> - <?=ucwords($d['nama_kategori'])?></td>
                                                    <td><?=ucwords($d['nama_ruang'])?> - <?=ucwords($d['nama_kelas'])?></td>
                                                    <td><?=$d['jumlah']?></td>
                                                    <td><label class="label <?=$label?>"><?=ucwords($d['status'])?></label></td>
                                                </tr>
                                                <?php 
                                                endforeach;
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
                                    <h4 class="modal-title" id="defaultModalLabel">CARI LINEN KOTOR</h4>
                                </div>
                                <div class="modal-body">
                                    <!-- Basic Validation -->
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <form id="form_validation" action="" method="GET">
                                               
                                                <div class="form-group">
                                                    <label>Tanggal</label>
                                                    <div class="form-line">
                                                        <input type="date" name="tanggal" class="datepicker form-control" placeholder="Pilih tanggal">
                                                    </div>
                                                </div>
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <select class="form-control show-tick m-t-20" name="ruang" id="ruang_linen" required>
                                                            
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

            <!-- Waves Effect Plugin Js -->
            <script src="<?= $base_url ?>vendors/plugins/node-waves/waves.js"></script>
            <!-- Jquery DataTable Plugin Js -->
            <script src="<?= $base_url ?>vendors/DataTables/datatables.min.js"></script>

            <!-- Custom Js -->
            <script src="<?= $base_url ?>vendors/js/admin.js"></script>

            <!-- Demo Js -->
            <script src="<?= $base_url ?>vendors/js/demo.js"></script>

    <script>
        /* tabel */
                $(document).ready(function() {
                    $('#table_user_list').DataTable({
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

    <?php
    } else {
        header('location:' . $base_url . 'logout/?a=tidak sah');
    }
} else {
    header('location:' . $base_url . 'logout/?a=belum login');
}
?>