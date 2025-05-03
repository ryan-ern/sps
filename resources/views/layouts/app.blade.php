<?php
$segments = request()->segments(); ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    @if (config('app.is_demo'))
        <title itemprop="name">
            Perpustakaan Digital SMP Negeri 1 Sidomulyo
        </title>
        <meta name="description" content="Perpustakaan Digital SMP Negeri 1 Sidomulyo">
        <meta name="keywords"
            content="perpus smp n 1 sidmulyo, smp 1 sidomulyo, smp n 1 sidomulyo, smpn 1 sidumulyo, perpustakaan, perpustakaan online, sistem perpustakaan smp, sistem perpustakaan sekolah, sistem perpus, perpus online, sistem perpustakaan smp n 1 sidomulyo">
    @endif
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/img/logo.png">
    <title>
        {{ ucwords(str_replace('-', ' ', end($segments) ?? 'Dashboard')) }} | Perpustakaan Digital SMP Negeri 1 Sidomulyo)
    </title>
    <!--     Fonts and icons     -->
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700|Noto+Sans:300,400,500,600,700,800|PT+Mono:300,400,500,600,700"
        rel="stylesheet" />
    <link href="https://cdn.datatables.net/datetime/1.5.5/css/dataTables.dateTime.min.css" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
    <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/4c9b7484fe.js" crossorigin="anonymous"></script>
    <!-- CSS Files -->
    <link id="pagestyle" href="../assets/css/corporate-ui-dashboard.css?v=1.0.0" rel="stylesheet" />

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    {{-- date range --}}
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <style>
        .truncate {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100px;
        }

        .truncate:hover {
            white-space: normal;
            max-width: none;
            overflow: visible;
        }

        .active>.page-link {
            background-color: #1e293b !important;
            color: #fff !important;
        }

        .nav-tabs .nav-link.active {
            color: #fff !important;
            background-color: #2157d0 !important;
        }

        .nav-tabs .nav-link {
            color: #fff !important;
            background-color: #1e293b !important;
        }

        .card {
            border: 1px solid #0f172a !important;
        }
    </style>
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>

<body class="g-sidenav-show  bg-gray-100">

    <x-app.sidebar />

    {{ $slot }}

    <!--   Core JS Files   -->
    <script src="../assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>
    <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script src="../assets/js/plugins/chartjs.min.js"></script>
    <script src="../assets/js/plugins/swiper-bundle.min.js" type="text/javascript"></script>

    {{-- Datatable --}}
    <script type="text/javascript" src="https://buttons.github.io/buttons.js" async defer crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>

    <script type="text/javascript" src="https://cdn.datatables.net/2.1.8/js/dataTables.js" defer
        referrerpolicy="no-referrer" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js" defer
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/3.2.0/js/dataTables.buttons.js" defer
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.bootstrap5.js" defer
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js" defer></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.html5.min.js" defer
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.print.min.js" defer
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" defer></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('.dataTable').each(function() {
                let isTanpaTambah = $(this).hasClass('tanpa-tambah');
                let isTanpaFilter = $(this).hasClass('tanpa-filter');
                let isTanpaAksi = $(this).hasClass('tanpa-aksi');

                let buttons = [];

                if (!isTanpaTambah) {
                    buttons.push({
                        text: 'Tambah Data',
                        className: 'btn btn-dark me-3',
                        action: function() {
                            $('#dynamicModal').modal('show');
                        },
                        attr: {
                            'id': 'tambahButton',
                            'data-bs-toggle': 'modal',
                            'data-bs-target': '#dynamicModal',
                            'data-modal-type': 'tambah'
                        }
                    });
                }

                if (!isTanpaFilter) {
                    buttons.push({
                        text: `<i class="fa-solid fa-filter me-2"></i>Filter <span id="filterText">{{ request('per_page') }}</span> Data`,
                        extend: 'collection',
                        className: 'btn btn-dark me-3',
                        attr: {
                            id: 'filterButton'
                        },
                        buttons: [{
                                text: '5 Data',
                                className: 'dropdown-item',
                                action: () => updatePerPage(5)
                            },
                            {
                                text: '10 Data',
                                className: 'dropdown-item',
                                action: () => updatePerPage(10)
                            },
                            {
                                text: '25 Data',
                                className: 'dropdown-item',
                                action: () => updatePerPage(25)
                            },
                            {
                                text: '50 Data',
                                className: 'dropdown-item',
                                action: () => updatePerPage(50)
                            },
                            {
                                text: '100 Data',
                                className: 'dropdown-item',
                                action: () => updatePerPage(100)
                            },
                            {
                                text: '500 Data',
                                className: 'dropdown-item',
                                action: () => updatePerPage(500)
                            },
                            {
                                text: 'Semua Data',
                                className: 'dropdown-item',
                                action: () => updatePerPage('Semua')
                            }
                        ]
                    });
                }

                if (!isTanpaAksi) {
                    buttons.push({
                        text: '<i class="fa-solid fa-download me-2"></i>Aksi Data',
                        extend: 'collection',
                        className: 'btn btn-dark dropdown-toggle me-2',
                        buttons: [{
                                extend: 'print',
                                text: '<i class="fa-solid fa-print me-2"></i>Print',
                                className: 'dropdown-item',
                                exportOptions: {
                                    columns: ':not(:last-child)',
                                    stripHtml: false
                                }
                            },
                            {
                                extend: 'excel',
                                text: '<i class="fa-solid fa-file-excel me-2"></i>Excel',
                                className: 'dropdown-item',
                                exportOptions: {
                                    columns: ':not(:last-child)',
                                    stripHtml: true
                                }
                            },
                            {
                                extend: 'pdf',
                                text: '<i class="fa-solid fa-file-pdf me-2"></i>Pdf',
                                className: 'dropdown-item',
                                exportOptions: {
                                    columns: ':not(:last-child)',
                                    stripHtml: true
                                }
                            }
                        ]
                    });
                }
                let table = new DataTable(this, {
                    paging: false,
                    searching: false,
                    info: false,
                    ordering: true,
                    language: {
                        search: '',
                        searchPlaceholder: 'Cari Data',
                        emptyTable: 'Tidak Ada Data Yang Tersedia',
                        lengthMenu: 'Tampilan _MENU_ data',
                        loadingRecords: "Sedang Memuat...",
                        zeroRecords: "Data Tidak Ditemukan",
                    },
                    layout: {
                        topStart: {
                            buttons: buttons
                        }
                    }
                });
                // Fungsi untuk mendapatkan nilai per_page dari URL
                function getPerPageFromURL() {
                    let params = new URLSearchParams(window.location.search);
                    return params.get('per_page') || 5; // Default 10 jika tidak ada parameter
                }

                // Fungsi untuk memperbarui jumlah data per halaman
                function updatePerPage(perPage) {
                    let url = new URL(window.location.href);
                    url.searchParams.set('per_page', perPage);
                    window.location.href = url.toString();
                }

                // Update tampilan jumlah data saat halaman dimuat
                document.getElementById('filterText').textContent = getPerPageFromURL();
            });
        });
    </script>
    {{-- end Datatable --}}

    {{-- date range --}}
    <script>
        $(document).ready(function() {
            function getParameterByName(name) {
                let url = new URL(window.location.href);
                return url.searchParams.get(name);
            }
            let dateRange = getParameterByName("dates") || moment().format("MM/DD/YYYY") + " - " + moment().format(
                "MM/DD/YYYY");
            let dates = dateRange.split(" - ");
            $('.dates').each(function() {
                $(this).daterangepicker({
                    "showDropdowns": true,
                    ranges: {
                        'Hari Ini': [moment(), moment()],
                        'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        '7 Hari Terakhir': [moment().subtract(6, 'days'), moment()],
                        'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
                        'Bulan Kemarin': [moment().subtract(1, 'month').startOf('month'), moment()
                            .subtract(1,
                                'month').endOf('month')
                        ],
                        'Semua Data': ['01/01/0001', '01/01/0001']
                    },
                    "locale": {
                        "format": "MM/DD/YYYY",
                        "separator": " - ",
                        "applyLabel": "Terapkan",
                        "cancelLabel": "Batal",
                        "fromLabel": "Dari",
                        "toLabel": "Sampai",
                        "customRangeLabel": "Pilih Rentang",
                        "weekLabel": "W",
                        "daysOfWeek": ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"],
                        "monthNames": [
                            "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                            "Juli", "Agustus", "September", "Oktober", "November", "Desember"
                        ],
                        "firstDay": 1
                    },
                    "startDate": moment(dates[0], "MM/DD/YYYY"),
                    "endDate": moment(dates[1], "MM/DD/YYYY"),
                    "maxDate": moment()
                });
            });
        });
    </script>

    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Corporate UI Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="../assets/js/corporate-ui-dashboard.min.js?v=1.0.0"></script>
</body>

</html>
