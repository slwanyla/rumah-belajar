<?php
session_start();
require_once '../admin/koneksi.php';

if (!isset($_SESSION['id_pengguna']) || $_SESSION['peran'] != 'siswa') {
    header("Location: ../login.php"); // Use consistent path
    exit();
}

function tanggalindo($tgl)
{
    $tanggal = substr($tgl, 8, 2);
    $bulan = getBulan(substr($tgl, 5, 2));
    $tahun = substr($tgl, 0, 4);
    return $tanggal . ' ' . $bulan . ' ' . $tahun;
}
function getBulan($bln)
{
    switch ($bln) {
        case 1:
            return "Januari";
            break;
        case 2:
            return "Februari";
            break;
        case 3:
            return "Maret";
            break;
        case 4:
            return "April";
            break;
        case 5:
            return "Mei";
            break;
        case 6:
            return "Juni";
            break;
        case 7:
            return "Juli";
            break;
        case 8:
            return "Agustus";
            break;
        case 9:
            return "September";
            break;
        case 10:
            return "Oktober";
            break;
        case 11:
            return "November";
            break;
        case 12:
            return "Desember";
            break;
    }
}

?>
    <!DOCTYPE html>

    <html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default" data-assets-path="../admin/assets/" data-template="vertical-menu-template-free">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

        <title>Dashboard Siswa</title>

        <meta name="description" content="" />

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="../img/logo1.png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

        <link rel="stylesheet" href="../admin/assets/admin/admin/assets/vendor/fonts/boxicons.css" />

        <!-- Core CSS -->
        <link rel="stylesheet" href="../admin/assets/admin/admin/assets/vendor/css/core.css" class="template-customizer-core-css" />
        <link rel="stylesheet" href="../admin/assets/admin/admin/assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
        <link rel="stylesheet" href="../admin/assets/admin/admin/assets/css/demo.css" />

        <!-- Vendors CSS -->
        <link rel="stylesheet" href="../admin/assets/admin/admin/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
        <link rel="stylesheet" href="../admin/assets/admin/admin/assets/vendor/libs/apex-charts/apex-charts.css" />

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!-- Helpers -->
        <script src="../admin/assets/admin/admin/assets/vendor/js/helpers.js"></script>

        <script src="../admin/assets/admin/admin/assets/js/config.js"></script>
        <link href="../admin/css/plugins/morris.css" rel="stylesheet">

    </head>

    <body>
        <style>
            /* Mengatur warna latar belakang sidebar */
            #layout-menu {
                background-color: #002B5B !important;
                /* Biru Gelap */
                color: white;
                /* Teks putih */
            }

            /* Warna teks default di sidebar */
            #layout-menu .menu-link {
                color: white;
            }

            /* Hover efek untuk menu item */
            #layout-menu .menu-link:hover {
                background-color: #00509E;
                /* Biru lebih terang untuk efek hover */
                color: white;
            }

            /* Warna teks aktif */
            #layout-menu .menu-item.active>.menu-link {
                background-color: #004080;
                /* Biru tua untuk menu aktif */
                color: white;
            }

            /* Submenu warna */
            #layout-menu .menu-sub {
                background-color: #003366;
                /* Biru gelap untuk submenu */
            }

            #layout-menu .menu-sub .menu-link {
                color: white;
            }

            #layout-menu .menu-sub .menu-link:hover {
                background-color: #00509E;
                color: white;
            }

            /* Ikon warna */
            #layout-menu .menu-icon {
                color: white;
            }
        </style>
        <!-- Layout wrapper -->
        <div class="layout-wrapper layout-content-navbar">
            <div class="layout-container">
                <!-- Menu -->

                <?php
                // Function to check if a specific page is active
                function isActive($page)
                {
                    return isset($_GET['page']) && $_GET['page'] === $page ? ' active' : '';
                }
                ?>

                <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                    <div class="app-brand demo">
                        <a href="../index.php" class="app-brand-link text-center">
                            <span class="demo menu-text fw-bold ms-2" style="color: white;">Rumah Belajar</span>
                        </a>
                        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                            <i class="bx bx-chevron-left bx-sm align-middle"></i>
                        </a>
                    </div>
                    <div class="menu-inner-shadow"></div>
                    <ul class="menu-inner py-1">
                        <!-- Dashboard -->
                        <li class="menu-item<?= !isset($_GET['page']) ? ' active' : '' ?>">
                            <a href="./index.php" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                                <div data-i18n="Email">Dashboard</div>
                            </a>
                        </li>


                        <!-- Pertemuan Siswa -->
                        <li class="menu-item<?= isActive('pertemuan') ?>">
                            <a href="?page=pertemuan" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-calendar-check"></i> <!-- Ikon Kalender Cek untuk Rekap Kehadiran siswa -->
                                <div data-i18n="Calendar">Pilih Pertemuan</div>
                            </a>
                        </li>
                    </ul>
                    <a href="https://wa.me/+6288213125939" 
                        target="_blank" 
                        style="display: inline-block; width: 50px; height: 50px; background-color: green; border-radius: 50%; text-align: center; line-height: 50px; color: white; font-size: 24px;">
                        <i class="fab fa-whatsapp"></i>
                    </a>


                </aside>

                <!-- / Menu -->

                <!-- Layout container -->
                <div class="layout-page">
                    <!-- Navbar -->

                    <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
                        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                                <i class="bx bx-menu bx-sm"></i>
                            </a>
                        </div>

                        <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                            <!-- Search -->
                            <div class="navbar-nav align-items-center">
                                <li>
                                    <a href="" style="color: dark;">Hari ini :
                                        <?php
                                        function tanggal($format, $nilai = "now")
                                        {
                                            $en = array(
                                                "Sun",
                                                "Mon",
                                                "Tue",
                                                "Wed",
                                                "Thu",
                                                "Fri",
                                                "Sat",
                                                "Jan",
                                                "Feb",
                                                "Mar",
                                                "Apr",
                                                "May",
                                                "Jun",
                                                "Jul",
                                                "Aug",
                                                "Sep",
                                                "Oct",
                                                "Nov",
                                                "Dec"
                                            );
                                            $id = array(
                                                "Minggu",
                                                "Senin",
                                                "Selasa",
                                                "Rabu",
                                                "Kamis",
                                                "Jumat",
                                                "Sabtu",
                                                "Jan",
                                                "Feb",
                                                "Maret",
                                                "April",
                                                "Mei",
                                                "Juni",
                                                "Juli",
                                                "Agustus",
                                                "September",
                                                "Oktober",
                                                "November",
                                                "Desember"
                                            );
                                            return str_replace($en, $id, date($format, strtotime($nilai)));
                                        }

                                        date_default_timezone_set('Asia/Jakarta');
                                        echo tanggal("D, j M Y");
                                        ?>
                                    </a>
                                </li>
                            </div>
                            <!-- /Search -->

                            <ul class="navbar-nav flex-row align-items-center ms-auto">

                                <li>
                                    <a style="color: darkblue;">
                                        Pukul <span id="jam"></span>
                                    </a>
                                </li>

                                <!-- User -->
                                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                                        <div class="avatar avatar-online">
                                            <img src="../admin/img/adm.png" alt class="w-px-40 h-auto rounded-circle" />
                                        </div>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">

                                        <li>
                                            <a class="dropdown-item" href="../logout.php">
                                                <i class="bx bx-power-off me-2"></i>
                                                <span class="align-middle">Log Out</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <!--/ User -->
                            </ul>
                        </div>
                    </nav>

                    <div id="page-wrapper">

                        <div class="container-fluid">

                            <?php
                            if (@$_GET['page'] == '') {
                                include ("Course.php");
                            } else if (@$_GET['page'] == 'materimatematika1') {
                                include "materi/materi-matematika-1.php";
                            } else if (@$_GET['page'] == 'materimatematika2') {
                                include "materi/materi-matematika-2.php";
                            } else if (@$_GET['page'] == 'materimatematika3') {
                                include "materi/materi-matematika-3.php";
                            } else if (@$_GET['page'] == 'materimatematika4') {
                                include "materi/materi-matematika-4.php";
                            } else if (@$_GET['page'] == 'materimatematika5') {
                                include "materi/materi-matematika-5.php";
                            } else if (@$_GET['page'] == 'materimatematika6') {
                                include "materi/materi-matematika-6.php";
                            } else if (@$_GET['page'] == 'materimatematikatk') {
                                include "materi/materi-matematika-tk.php";
                            } else if (@$_GET['page'] == 'materismp1') {
                                include "materi/materi-smp-1.php";
                            } else if (@$_GET['page'] == 'materismp2') {
                                include "materi/materi-smp-2.php";
                            } else if (@$_GET['page'] == 'materismp3') {
                                include "materi/materi-smp-3.php";
                            } else if (@$_GET['page'] == 'materitajwid') {
                                include "materi/materi-tajwid.php";
                            } else if (@$_GET['page'] == 'aksesmateri') {
                                include "akses_materi.php";
                            } else if (@$_GET['page'] == 'presensimasuk') {
                                include "presensimasuk.php";
                            } else if (@$_GET['page'] == 'presensimasukdetail') {
                                include "presensimasukdetail.php";
                            } else if (@$_GET['page'] == 'presensi') {
                                include "presensi2.php";
                            } else if (@$_GET['page'] == 'riwayatpembayaran') {
                                include "riwayatpembayaran.php";
                            } else if (@$_GET['page'] == 'detailpembayaran') {
                                include "detailpembayaran.php";
                            } else if (@$_GET['page'] == 'editpembayaran') {
                                include "editpembayaran.php";
                            } else if (@$_GET['page'] == 'hapuspembayaran') {
                                include "hapuspembayaran.php";
                            } elseif (@$_GET['page'] == 'pertemuan') {
                                include "pertemuan.php";
                            }elseif (@$_GET['page'] == 'course') {
                                include "Course.php";
                            }
                            ?>

                        </div>


                    </div>

                </div>

                <script src="../admin/assets/admin/admin/assets/vendor/libs/jquery/jquery.js"></script>
                <script src="../admin/assets/admin/admin/assets/vendor/libs/popper/popper.js"></script>
                <script src="../admin/assets/admin/admin/assets/vendor/js/bootstrap.js"></script>
                <script src="../admin/assets/admin/admin/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
                <script src="../admin/assets/admin/admin/assets/vendor/js/menu.js"></script>

                <!-- endbuild -->

            
                <!-- Main JS -->
                <script src="../admin/assets/admin/admin/assets/js/main.js"></script>

                <!-- Place this tag in your head or just before your close body tag. -->
                <script async defer src="https://buttons.github.io/buttons.js"></script>
                <script src="../admin/js/plugins/morris/raphael.min.js"></script>
                <script src="../admin/js/plugins/morris/morris.min.js"></script>
                <script src="../admin/js/plugins/morris/morris-data.js"></script>
                <script src="../admin/js/responsive-tabs.js"></script>
                <script src="../admin/js/jfunc.js"></script>

                <script type="text/javascript">
                    $('ul.nav.nav-tabs  a').click(function(e) {
                        e.preventDefault();
                        $(this).tab('show');
                    });

                    (function($) {
                        // Test for making sure event are maintained
                        $('.js-alert-test').click(function() {
                            alert('Button Clicked: Event was maintained');
                        });
                        fakewaffle.responsiveTabs(['xs', 'sm']);
                    })(jQuery);
                </script>
    </body>

</html>


