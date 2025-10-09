<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <title>تقارير الغياب - منصة طالب</title>

    <!-- CSS Files -->
    <link href="{{ asset('css/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sweetalert2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/responsive_min.css') }}" rel="stylesheet">
    
    <style>
        @media (max-width: 768px) {
            .table th:nth-child(1),
            .table td:nth-child(1) {
                width: 8% !important;
                font-size: 11px !important;
            }
            
            .table th:nth-child(2),
            .table td:nth-child(2) {
                width: 5% !important;
                max-width: 5% !important;
                font-size: 10px !important;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                padding: 1px !important;
            }
            
            .table th:nth-child(3),
            .table td:nth-child(3),
            .table th:nth-child(4),
            .table td:nth-child(4) {
                width: 15% !important;
                font-size: 10px !important;
            }
            
            .table th:nth-child(5),
            .table td:nth-child(5) {
                width: 57% !important;
                font-size: 10px !important;
            }
            
            .modal-dialog {
                margin: 10px !important;
                max-width: calc(100% - 20px) !important;
            }
            
            .modal-content {
                max-height: 90vh !important;
                overflow-y: auto !important;
            }
            
            .modal-body {
                padding: 10px !important;
            }
            
            .modal-body table th:first-child,
            .modal-body table td:first-child {
                width: 25% !important;
            }
            
            .modal-body table th:nth-child(n+2):nth-child(-n+8),
            .modal-body table td:nth-child(n+2):nth-child(-n+8) {
                width: 9% !important;
            }
            
            .modal-body table th:last-child,
            .modal-body table td:last-child {
                width: 12% !important;
            }
        }
    </style>
</head>

<body>
    <div class="app">
        @include('layouts.nav')

        <div class="appOne" style="background: #fff;">
            <div class="guarantor">
                <div class="title" style="color:#fff;background:#555;display:flex; justify-content:center;align-items:center">
                    <h5><span style="color:#75f98c;"><i class="ri-shield-user-fill"></i>تقرير الغياب اليومي</span></h5>
                </div>
                <p>هذه الصفحة تمكنكم من متابعة حضور الطلاب</p>
            </div>
            
            <!-- Date Filter -->
            <div class="d-flex align-items-center justify-content-center gap-2 p-2 text-center" style="background:#0E2550">
                <form method="GET" class="d-flex align-items-center gap-2">
                    <label for="date" class="text-white">{{ $dayName }}:</label>
                    <input type="date" name="date" id="date" value="{{ $selectedDate }}" 
                           style="font-size:16px;background:#ffd400;border-radius:5px;border:none;padding:5px;" 
                           onchange="this.form.submit()">
                </form>
            </div>
            
            <!-- Students Table -->
            @if($students->count() > 0)
            <div class="table-responsive mt-3">
                <table class="table table-secondary table-bordered table-striped text-nowrap">
                    <thead>
                        <tr class="text-center">
                            <th class="p-1">م</th>
                            <th class="p-1">الاسم</th>
                            <th class="p-1">صف</th>
                            <th class="p-1">فصل</th>
                            <th class="p-1">غياب</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                        <tr class="text-center">
                            <td class="p-1">{{ $loop->iteration }}</td>
                            <td class="p-1">{{ $student->name }}</td>
                            <td class="p-1">{{ $student->grade->name ?? '-' }}</td>
                            <td class="p-1">{{ $student->classRoom->name ?? '-' }}</td>
                            <td class="p-1 {{ $student->total_absences > 0 ? 'text-danger fw-bold' : 'text-success' }}" 
                                style="cursor: pointer;" onclick="showStudentRecord({{ $student->id }})">
                                {{ $student->total_absences }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-2 p-2 bg-light border rounded text-center">
                    <strong>عدد الغائبين اليوم: <span class="badge bg-danger">{{ $absentTodayCount }}</span></strong>
                </div>
            </div>
            @else
            <div class="alert alert-info text-center mt-4">
                <i class="fas fa-info-circle"></i> لا يوجد طلاب في هذه المدرسة
            </div>
            @endif
        </div>

        <footer class="footer">
            <img width="150" src="{{ asset('storage/school_logos/banner1.jpg') }}" alt="School Logo">
        </footer>

        @include('layouts.footer')
    </div>

    <!-- Student Record Modal -->
    <div class="modal fade" id="studentRecordModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">سجل متابعة الطالب</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="studentRecordContent">
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">جاري التحميل...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JS Files -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.min.js') }}"></script>
    
    <script>
        function showStudentRecord(studentId) {
            const modal = new bootstrap.Modal(document.getElementById('studentRecordModal'));
            const content = document.getElementById('studentRecordContent');
            
            // Show loading
            content.innerHTML = '<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">جاري التحميل...</span></div></div>';
            modal.show();
            
            // Fetch student record using the same route as teacher page
            const url = `{{ route('student.followup.record', ['student' => ':studentId']) }}`.replace(':studentId', studentId);
            
            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.html) {
                    content.innerHTML = data.html;
                } else {
                    content.innerHTML = '<div class="alert alert-warning">لا توجد بيانات متابعة لهذا الطالب</div>';
                }
            })
            .catch(error => {
                console.error('Error loading follow-up record:', error);
                content.innerHTML = '<div class="alert alert-danger">حدث خطأ أثناء تحميل سجل المتابعة</div>';
            });
        }
    </script>
</body>
</html>