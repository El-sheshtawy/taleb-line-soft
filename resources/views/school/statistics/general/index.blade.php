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
            background-color: #15336a !important;
            color: #ffd400 !important;
            font-weight: bold !important;
            font-size: 20px;
            width: 50px;
        }

        .stat-type {
            width: 100px;
        }

        .primary-style {
            background-color: #004aad !important;
            color: #fff !important;
        }

        tfoot tr {
            background-color: #ffd400 !important;
            color: #000;
        }

        @media (max-width: 500px) {
            .green-color {
                width: 37px;
                font-size: 16px !important;
            }

            .stat-type {
                width: 70px;
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
                <div class="d-flex align-items-center justify-content-between gap-2 p-2 text-center position-relative"
                    style="background:#0E2550">
                    <div class="fw-bold print-general-table">
                        <i class="fa fa-print"></i>
                    </div>

                    <select id="settingsSelector" class="tabs-selector"
                        style="font-size:16px;background:#ffd400;border-radius:5px;position: unset;">
                        <option selected value="general-tab" url="{{ route('school.statistics.index') }}">الحصص
                        </option>
                        <option value="students-tab" url="{{ route('school.statistics.students') }}">الطلاب</option>
                        <option value="classes-tab" url="{{ route('school.statistics.classes') }}">الفصول</option>
                        <option value="school-tab" url="{{ route('school.statistics.school') }}">المدرسة</option>
                        <option value="table-general" url="https://docs.google.com/viewerng/viewer?url={{$school->table_general}}">الجدول العام
                        </option>
                        <!--<option value="table-general" url="{{ route('school.statistics.table') }}">الجدول العام-->
                        <!--</option>-->
                    </select>

                    <form action="{{ url()->current() }}" class="">
                        <input type="date" class="p-1" name="date" id="date"
                            style="font-size:16px;background:#ffd400;border-radius:5px;position: unset;"
                            value="{{ request('date', $date) }}" onchange="this.form.submit()">
                    </form>
                </div>

                <div class="content mt-4">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0 general-table">
                            <thead>
                                <tr class="text-center align-middle">
                                    <th class="p-1">صف</th>
                                    <th class="p-1">فصل</th>
                                    <th class="p-1 stat-type">إحصائيات</th>
                                    @for ($i = 1; $i <= $sessionCount; $i++)
                                        <th class="p-1">{{ $i }}</th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($statistics as $index => $classStats)
                                    @php
                                        $rowClass = $index % 2 === 0 ? 'even' : 'odd';
                                    @endphp

                                    <!-- Absentee Row with bottom border -->
                                    <tr class="{{ $rowClass }}">
                                        <td rowspan="3" class="p-1 text-center green-color">
                                            {{ $classStats['class']->grade->name ?? '-' }}</td>
                                        <td rowspan="3" class="p-1 text-center green-color">
                                            {{ $classStats['class']->name }}</td>
                                        <td class="p-1 primary-style">غائب</td>
                                        @for ($i = 1; $i <= $sessionCount; $i++)
                                            <td
                                                class="p-1 text-center {{ $classStats['sessions'][$i]['total_absent'] > 0 ? 'text-danger' : '' }}">
                                                {{ $classStats['sessions'][$i]['total_absent'] }}
                                            </td>
                                        @endfor
                                    </tr>

                                    <!-- Teacher Row -->
                                    <tr class="{{ $rowClass }}">
                                        <td class="p-1 primary-style">المعلم</td>
                                        @for ($i = 1; $i <= $sessionCount; $i++)
                                            <td class="p-1 text-center">
                                                @if ($classStats['sessions'][$i]['teachers']->isNotEmpty())
                                                    @php
                                                        $teacher =
                                                            $classStats['sessions'][$i]['teachers']->first()[
                                                                'teacher'
                                                            ] ?? null;
                                                        $teacherName = $teacher->name ?? '-';
                                                    @endphp
                                                    <span class="teacher-tooltip" data-bs-toggle="tooltip"
                                                        title="{{ $teacherName }}">
                                                        <i class="fas fa-eye"></i>
                                                        <span class="teacher-name">{{ $teacherName }}</span>
                                                    </span>
                                                @else
                                                    {{-- <a href="{{ $school->table_classes }}"
                                                        class="openTableClasses">-</a> --}}
                                                    <!--<a href="#" class="openTableClasses"-->
                                                    <!--    data-pdf-url="{{ $school->table_classes }}">-</a>-->
                                                    <a href="https://docs.google.com/viewerng/viewer?url={{ $school->table_classes }}" class="">-</a>
                                                    
                                                @endif
                                            </td>
                                        @endfor
                                    </tr>

                                    <!-- Subject Row -->
                                    <tr class="{{ $rowClass }}" style="border-bottom: 2px solid #fff;">
                                        <td class="p-1 primary-style">المادة</td>
                                        @for ($i = 1; $i <= $sessionCount; $i++)
                                            <td class="p-1 text-center">
                                                @if ($classStats['sessions'][$i]['teachers']->isNotEmpty())
                                                    {{ $classStats['sessions'][$i]['teachers']->first()['teacher']->subject ?? '-' }}
                                                @else
                                                    {{-- <a href="{{ $school->table_classes }}"
                                                        class="openTableClasses">-</a> --}}
                                                    <a href="https://docs.google.com/viewerng/viewer?url={{ $school->table_classes }}" class="">-</a>
                                                @endif
                                            </td>
                                        @endfor
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr style="background-color: #f8f9fa; font-weight: bold;">
                                    <td colspan="2" class="p-1 text-center">المجموع</td>
                                    <td class="p-1">إجمالي الغياب</td>
                                    @for ($i = 1; $i <= $sessionCount; $i++)
                                        @php
                                            $totalAbsent = 0;
                                            foreach ($statistics as $classStats) {
                                                $totalAbsent += $classStats['sessions'][$i]['total_absent'] ?? 0;
                                            }
                                        @endphp
                                        <td class="p-1 text-center {{ $totalAbsent > 0 ? 'text-danger' : '' }}">
                                            {{ $totalAbsent }}
                                        </td>
                                    @endfor
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

            </div>
        </div>

        <!-- Modal Structure -->
        <div class="modal fade" id="pdfModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">PDF Viewer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="pdf-container"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- show pdf --}}
        <script>
            // Wait for DOM to be fully loaded
            document.addEventListener('DOMContentLoaded', function() {
                // Attach click event to all PDF links
                document.querySelectorAll('.openTableClasses').forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const pdfUrl = this.getAttribute('data-pdf-url');
                        showPdfInModal(pdfUrl);
                    });
                });
            });

            function showPdfInModal(pdfUrl) {
                const modal = new bootstrap.Modal(document.getElementById('pdfModal'));
                const container = document.getElementById('pdf-container');

                // Clear previous content and show loading state
                container.innerHTML =
                    '<div class="d-flex justify-content-center align-items-center" style="height: 80vh;"><div class="spinner-border text-primary" role="status"></div></div>';

                // Check if mobile device
                const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);

                if (isMobile) {
                    // Use Google Docs viewer for mobile
                    container.innerHTML = `
                        <iframe
                            src="https://docs.google.com/gview?url=${encodeURIComponent(pdfUrl)}&embedded=true"
                            width="100%"
                            height="100%"
                            style="border: none; min-height: 80vh;"
                            frameborder="0">
                        </iframe>
                    `;
                } else {
                    // Use regular iframe for desktop
                    container.innerHTML = `
                        <iframe
                            src="${pdfUrl}"
                            width="100%"
                            height="100%"
                            style="border: none; min-height: 80vh;"
                            frameborder="0">
                        </iframe>
                    `;
                }

                // Show the modal
                modal.show();

                // Focus the iframe for better accessibility
                setTimeout(() => {
                    const iframe = container.querySelector('iframe');
                    if (iframe) iframe.focus();
                }, 500);
            }
        </script>


        <footer class="footer">
            <img width="150" src="{{ asset('storage/school_logos/banner1.jpg') }}" alt="School Logo">
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
            const dateParam = urlParams.get('date') || new Date().toISOString().split('T')[0];


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
                        table tbody td {
                            font-size: 14px;
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
