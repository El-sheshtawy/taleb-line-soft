<!DOCTYPE html>

<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <title>منصة طالب</title>

    <!-- CSS Files -->
    <link href="{{ asset('css/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sweetalert2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/buttons.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/datatables.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/responsive_min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/all.min.css') }}" rel="stylesheet">

    <style>
        .collapsible-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s ease;
        }

        .collapsible-content.show {
            max-height: fit-content;
        }

        .tab-pane {
            display: none;
        }

        .tab-pane.active {
            display: block !important;
        }
    </style>

    <style>
        .teacher-tooltip {
            cursor: pointer;
            color: #0d6efd;
        }

        .teacher-tooltip:hover {
            color: #0b5ed7;
        }

        .teacher-tooltip {
            cursor: pointer;
            color: #0d6efd;
        }

        .teacher-tooltip:hover {
            color: #0b5ed7;
        }

        tr.odd {
            background-color: #eee;
            /* Light gray for odd rows */
        }

        tr.even {
            background-color: #ddd;
            /* White for even rows */
        }

        /* Ensure text remains readable on colored rows */
        tr.odd td,
        tr.even td {
            color: #000;
        }

        .print-general-table {
            background: #ffd400;
            color: #000;
            width: fit-content;
            text-align: center;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .openTableClasses {
            cursor: pointer;
            color: #203d04;
        }

        .green-color {
            color: #203d04 !important;
            font-weight: bold !important;
            font-size: 20px;
        }

        @media (max-width: 500px) {
            .green-color {
                font-size: 16px !important;
            }
        }

        .teacher-name {
            display: none;
        }
    </style>
</head>

<body>
    <input type="hidden" class="expire_date" value="2028-02-25">

    <div class="app">
        @include('layouts.nav')

        <div class="appOne" style="background: #fff;">
            <div class="guarantor">
                <div class="title pt-1"
                    style="color:#fff;background:#555;display:flex; justify-content:center;align-items:center">
                    <h5> <span style="color:#75f98c;"> <i class="ri-shield-user-fill"></i>مدرسة</span>
                        {{ $school->school_name }}</h5>
                </div>
                <p> هذه الصفحة تمكنكم من متابعة حضور الطلاب <br /> ولكم كل الشكر والتقدير على دعمكم لنا 🌹 </p>
            </div>

            <div class="listOfName">
                <div class="d-flex align-items-center justify-content-center gap-2 p-2 text-center position-relative"
                    style="background:#0E2550">
                    {{-- <div class="fw-bold print-general-table">
                        <i class="fa fa-print"></i>
                    </div> --}}

                    <select id="settingsSelector" class="tabs-selector"
                        style="font-size:16px;background:#ffd400;border-radius:5px;position: unset;">
                        <option value="general-tab" url="{{ route('school.statistics.index') }}">الحصص
                        </option>
                        <option value="students-tab" url="{{ route('school.statistics.students') }}">الطلاب</option>
                        <option value="classes-tab" url="{{ route('school.statistics.classes') }}">الفصول</option>
                        <option value="school-tab" url="{{ route('school.statistics.school') }}">المدرسة</option>
                        <option selected value="table-general" url="{{ route('school.statistics.table') }}">
                            الجدول
                            العام
                        </option>
                    </select>

                    {{-- <form action="{{ url()->current() }}" class="">
                        <input type="date" class="p-1" name="date" id="date"
                            style="font-size:16px;background:#ffd400;border-radius:5px;position: unset;"
                            value="{{ request('date', $date) }}" onchange="this.form.submit()">
                    </form> --}}
                </div>

                <!-- school.statistics.table.index.blade.php -->

                <div class="content mt-4">
                    <div class="pdf-window" style="height: 80vh;">
                        @if ($table)
                            <iframe src="https://docs.google.com/gview?url={{ urlencode($table) }}&embedded=true"
                                style="width:100%; height:100%;" frameborder="0">
                            </iframe>
                        @else
                            <p>No PDF file available.</p>
                        @endif
                    </div>
                </div>


            </div>
        </div>


        <footer class="footer">
            <!--<img width="150" src="dashboard/uploads/footer/banner1.jpg" alt="">-->
        </footer>
        <div id="timer" class="down-footer d-flex flex-row">
            <small class="text-white d-textitme">باقي على الوقت</small>
            <div>
                <span class="day"></span>
                <span class="hour"></span>
                <span class="minute"></span>
                <span class="second"></span>
            </div>
        </div>


        @include('layouts.footer')

    </div>



    <!-- JS Files -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="{{ asset('js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('js/jszip.min.js') }}"></script>
    <script src="{{ asset('js/datatables.js') }}"></script>

    <script>
        document.getElementById('toggleButton').addEventListener('click', function(e) {
            e.preventDefault();
            const content = document.getElementById('collapseExample');
            content.classList.toggle('show');
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ في الإدخال',
                    html: '{!! implode('<br>', $errors->all()) !!}',
                });
            @endif

            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'نجاح!',
                    text: '{{ session('success') }}',
                    confirmButtonText: 'حسناً'
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ!',
                    text: '{{ session('error') }}',
                    confirmButtonText: 'حسناً'
                });
            @endif

            @if (session('warning'))
                Swal.fire({
                    icon: 'warning',
                    title: 'تحذير!',
                    text: '{{ session('warning') }}',
                    confirmButtonText: 'حسناً'
                });
            @endif
        });
    </script>

    {{-- navigation in select --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const settingsSelector = document.getElementById("settingsSelector");

            settingsSelector.addEventListener("change", function() {
                const selectedOption = this.options[this.selectedIndex];
                const url = selectedOption.getAttribute('url');
                if (url) {
                    window.location.href = url;
                }
            });
        });
    </script>

    {{-- teahcer name --}}
    <script>
        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('.teacher-tooltip'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>

    {{-- script to print the table --}}
    <script>
        document.querySelector('.print-general-table').addEventListener('click', function() {
            const table = document.querySelector('.general-table');
            if (!table) return;

            const schoolBannerUrl = '{{ $school->school_banner_url }}';

            // Get date from URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            const dateParam = urlParams.get('date');
            const formattedDate = dateParam ? new Date(dateParam).toLocaleDateString('ar-EG') : 'تاريخ غير محدد';

            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
            <html>
                <head>
                    <title>طباعة الجدول</title>
                    <style>
                        body {
                            direction: rtl;
                            font-family: Arial, sans-serif;
                            padding: 20px;
                        }
                        h1 {
                            text-align: center;
                            margin-bottom: 20px;
                            color: #333;
                        }
                        table {
                            width: 100%;
                            border-collapse: collapse;
                            page-break-inside: auto;
                        }
                        thead { display: table-header-group; }
                        tfoot { display: table-footer-group; }
                        .banner-header img {
                            max-width: 100%;
                            height: auto;
                            margin-bottom: 10px;
                        }
                        .footer-row {
                            background-color: #f8f9fa;
                            font-weight: bold;
                        }

                        th, td {
                            border: 1px solid #000;
                            padding: 8px;
                            text-align: center;
                            page-break-inside: avoid;
                        }

                        @media print {
                            .banner-header {
                                display: table-header-group;
                            }
                            tfoot {
                                display: table-footer-group;
                            }
                        }

                        a.openTableClasses {
                            color: #203d04;
                            text-decoration: none;
                        }
                        .text-danger { color: #dc3545 !important; }
                        .green-color { color: #28a745 !important; }
                    </style>
                </head>
                <body>
                    <table>
                        <thead class="banner-header">
                            <tr>
                                <th colspan="100%">
                                    <img src="${schoolBannerUrl}" alt="School Banner">
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="100%" style="text-align: center;">
                                    <h1>احصائيات الفصول بتاريخ ${formattedDate}</h1>
                                </td>
                            </tr>
                            ${table.tBodies[0].innerHTML}
                        </tbody>
                        <tfoot>
                            ${table.tFoot ? table.tFoot.innerHTML : ''}
                        </tfoot>
                    </table>

                    <script>
                        window.onload = function () {
                            window.print();
                            window.onafterprint = function () {
                                window.close();
                            };
                        };
                    <\/script>
                </body>
            </html>
        `);
            printWindow.document.close();
        });
    </script>
</body>

</html>
