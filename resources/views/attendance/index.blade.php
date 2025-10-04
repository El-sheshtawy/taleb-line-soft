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
        .myTable th:nth-child(2),
        .myTable td:nth-child(2) {
            width: 250px;
            min-width: 250px;
            max-width: 250px;
        }
        
        .myTable th:nth-child(4),
        .myTable td:nth-child(4) {
            width: 300px;
            min-width: 300px;
            max-width: 300px;
            overflow-x: auto;
            white-space: nowrap;
        }
        
        .myTable td:nth-child(4)::-webkit-scrollbar {
            height: 4px;
        }
        
        .myTable td:nth-child(4)::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        .myTable td:nth-child(4)::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 2px;
        }
        
        .myTable td:nth-child(4)::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        
        @media (max-width: 768px) {
            .table-responsive {
                overflow-x: hidden !important;
            }
            
            .myTable {
                width: 100% !important;
                table-layout: fixed;
            }
            
            .myTable th:nth-child(1),
            .myTable td:nth-child(1) {
                width: 8% !important;
                font-size: 12px;
                padding: 2px !important;
            }
            
            .myTable th:nth-child(2),
            .myTable td:nth-child(2) {
                width: 42% !important;
                font-size: 12px;
            }
            
            .myTable th:nth-child(3),
            .myTable td:nth-child(3) {
                width: 10% !important;
                font-size: 12px;
                padding: 2px !important;
            }
            
            .myTable th:nth-child(4),
            .myTable td:nth-child(4) {
                width: 40% !important;
                font-size: 12px;
            }
        }
    </style>

</head>

<body>
    <input type="hidden" class="expire_date" value="2028-02-25">

    <div class="app">
        @include('layouts.nav')

        <div class="appOne" style="background: #fff;">
            <div class="guarantor">
                <div class="title"
                    style="color:#fff;background:#555;display:flex; justify-content:center;align-items:center">
                    <h5> <span style="color:#75f98c;"> <i class="ri-shield-user-fill"></i>تقرير الغياب</span></h5>
                </div>
                <p> هذه الصفحة تمكنكم من متابعة حضور الطلاب <br /> ولكم كل الشكر والتقدير على دعمكم لنا 🌹 </p>
            </div>

            <div class="listOfName">

                <form method="get">
                    <div class="d-flex align-items-center justify-content-center gap-2  p-2 text-center"
                        style="background:#0E2550">
                        @if (!in_array(auth()->user()->user_type, ['school', 'teacher', 'مراقب', 'مشرف']))
                            <div class="form-group">
                                <label for="school_id" class="text-white">المدرسة</label>
                                <select name="school_id" id="school_id"
                                    style="font-size:16px;background:#ffd400;border-radius:5px;position: unset;"
                                    onchange="this.form.submit()">
                                    <option value="">اختر المدرسة</option>
                                    @foreach ($schools as $school)
                                        <option value="{{ $school->id }}"
                                            {{ request('school_id') == $school->id ? 'selected' : '' }}>
                                            {{ $school->school_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="grade_id" class="text-white">الصف</label>
                            <select name="grade_id" id="grade_id"
                                style="font-size:16px;background:#ffd400;border-radius:5px;position: unset;"
                                onchange="this.form.submit()" {{ !$school ? 'disabled' : '' }}>
                                <option value="">اختر الصف</option>
                                @foreach ($grades as $grade)
                                    <option value="{{ $grade->id }}"
                                        {{ request('grade_id') == $grade->id ? 'selected' : '' }}>
                                        {{ $grade->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="class_id" class="text-white">الفصل</label>
                            <select name="class_id" id="class_id"
                                style="font-size:16px;background:#ffd400;border-radius:5px;position: unset;"
                                onchange="this.form.submit()" {{ !request('grade_id') ? 'disabled' : '' }}>
                                <option value="">اختر الفصل</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}"
                                        {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>

                @if (request('grade_id') && request('class_id'))
                    <div class="table-responsive">
                        @if ($students->isEmpty())
                            <div class="alert alert-info text-center">
                                لا يوجد طلاب مسجلين في هذا الفصل
                            </div>
                        @else
                            <table class="table myTable table-secondary table-bordered table-striped  text-nowrap">
                                <thead>
                                    <tr>
                                        <th class="p-1 text-center">م</th>
                                        <th class="p-1 text-center">الطالب</th>
                                        <th class="p-1 text-center">الغياب</th>
                                        <th class="p-1 text-center">ملاحظات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($students as $index => $student)
                                        <tr>
                                            <td class="p-1 text-center">{{ $loop->iteration }}</td>
                                            <td class="p-1 responsive-cell"><a
                                                    class="text-{{ $student->gender ? 'primary' : 'danger' }} text-{{ $student->note ? 'danger' : '' }} cell-link">{{ $student->name }}</a>
                                            </td>
                                            <td
                                                class="p-1 text-center {{ $student->absences_count > 0 ? 'text-danger fw-bold' : '' }}">
                                                {{ $student->absences_count }}
                                            </td>
                                            <td class="p-1">
                                                {{-- {{ \Illuminate\Support\Str::limit($student->note, 17, '') }} --}}
                                                {{ $student->note }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                @else
                    <div class="alert alert-warning text-center mt-4">
                        <i class="fas fa-info-circle"></i> الرجاء اختيار الصف والفصل لعرض تقرير الغياب
                    </div>
                @endif
            </div>
        </div>


        <footer class="footer">
            <img width="150" src="{{ asset('storage/school_logos/banner1.jpg') }}" alt="School Logo">
            <img width="150" src="dashboard/uploads/footer/banner1.jpg" alt="">
        </footer>


        @include('layouts.footer')


    </div>
    </div>



    <!-- JS Files -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('js/buttons.html5.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="{{ asset('js/jszip.min.js') }}"></script>
    <script src="{{ asset('js/datatables.js') }}"></script>
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

</body>

</html>
