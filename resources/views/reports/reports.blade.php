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
            .table td:nth-child(2),
            .table th:nth-child(3),
            .table td:nth-child(3) {
                width: 18% !important;
                font-size: 10px !important;
            }
            
            .table th:nth-child(4),
            .table td:nth-child(4) {
                width: 30% !important;
                font-size: 10px !important;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                padding: 1px !important;
            }
            
            .table th:nth-child(5),
            .table td:nth-child(5) {
                width: 16% !important;
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
            <div class="d-flex align-items-center justify-content-center gap-2 p-2 text-center position-relative" style="background:#0E2550">
                <div class="position-absolute" style="left: 15px; top: 50%; transform: translateY(-50%);">
                    <div id="dateCountSection" class="text-white" style="{{ request('show_all_absences') ? 'display: none;' : '' }}">
                        <span class="badge bg-danger">{{ $absentTodayCount }}</span>
                    </div>
                    <div id="allAbsencesCountSection" class="text-white" style="{{ !request('show_all_absences') ? 'display: none;' : '' }}">
                        <span class="badge bg-danger">{{ $students->count() }}</span>
                    </div>
                </div>
                <form method="GET" class="d-flex align-items-center gap-2" id="filterForm">
                    <div id="dateSection" class="d-flex align-items-center gap-2" style="{{ request('show_all_absences') ? 'display: none !important;' : '' }}">
                        <label for="date" class="text-white">{{ $dayName }}:</label>
                        <input type="date" name="date" id="date" value="{{ $selectedDate }}" 
                               style="font-size:16px;background:#ffd400;border-radius:5px;border:none;padding:5px;" 
                               onchange="this.form.submit()">
                    </div>
                </form>
                <input type="checkbox" name="show_all_absences" id="showAllAbsences" value="1" 
                       {{ request('show_all_absences') ? 'checked' : '' }}
                       onchange="toggleDateFilter()" class="form-check-input position-absolute" 
                       style="right: 15px; top: 50%; transform: translateY(-50%);" form="filterForm">
            </div>
            
            <!-- Search Box and Print Button -->
            <div class="row mt-3 mb-2">
                <div class="col-md-8">
                    <input type="text" id="searchInput" class="form-control" placeholder="بحث في الطلاب..." 
                           style="font-size:14px;border-radius:5px;">
                </div>
                <div class="col-md-4">
                    <button type="button" class="btn btn-danger w-100" onclick="window.print()">
                        طباعة PDF <i class="fas fa-print"></i>
                    </button>
                </div>
            </div>
            
            @if($students->count() > 0)
            
            <!-- Students Table -->
            <div class="table-responsive mt-3">
                <table class="table table-secondary table-bordered table-striped text-nowrap">
                    <thead>
                        <tr class="text-center">
                            <th class="p-1">م</th>
                            <th class="p-1">صف</th>
                            <th class="p-1">فصل</th>
                            <th class="p-1">الاسم</th>
                            <th class="p-1" style="cursor: pointer;" onclick="sortByAbsence()">غياب <span id="sortIcon">↓</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                        <tr class="text-center student-row">
                            <td class="p-1">{{ $loop->iteration }}</td>
                            <td class="p-1">{{ $student->grade->name ?? '-' }}</td>
                            <td class="p-1">{{ $student->classRoom->name ?? '-' }}</td>
                            <td class="p-1 text-end student-name">{{ $student->name }}</td>
                            <td class="p-1 text-danger fw-bold" style="cursor: pointer;" onclick="toggleStudentRecord({{ $student->id }})">
                                {{ $student->total_absences }}
                            </td>
                        </tr>
                        <tr id="studentRecord{{ $student->id }}" class="student-record" style="display: none;">
                            <td colspan="5" class="p-2">
                                <div class="loading-content" id="loadingContent{{ $student->id }}">
                                    <div class="text-center">
                                        <div class="spinner-border spinner-border-sm" role="status">
                                            <span class="visually-hidden">جاري التحميل...</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="record-content" id="recordContent{{ $student->id }}" style="display: none;"></div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
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



    <!-- JS Files -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.min.js') }}"></script>
    
    <script>
        // Sort table by absence count
        let sortState = 'default'; // default -> desc -> asc -> default
        let originalOrder = [];
        
        // Store original order on page load
        document.addEventListener('DOMContentLoaded', function() {
            const rows = Array.from(document.querySelectorAll('.student-row'));
            originalOrder = rows.map(row => row.cloneNode(true));
        });
        
        function sortByAbsence() {
            const tbody = document.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('.student-row'));
            const sortIcon = document.getElementById('sortIcon');
            
            if (sortState === 'default') {
                // Sort descending (high to low)
                rows.sort((a, b) => {
                    const aCount = parseInt(a.cells[4].textContent.trim());
                    const bCount = parseInt(b.cells[4].textContent.trim());
                    return bCount - aCount;
                });
                sortState = 'desc';
                sortIcon.textContent = '↓';
            } else if (sortState === 'desc') {
                // Sort ascending (low to high)
                rows.sort((a, b) => {
                    const aCount = parseInt(a.cells[4].textContent.trim());
                    const bCount = parseInt(b.cells[4].textContent.trim());
                    return aCount - bCount;
                });
                sortState = 'asc';
                sortIcon.textContent = '↑';
            } else {
                // Reset to default order
                tbody.innerHTML = '';
                originalOrder.forEach((row, index) => {
                    const newRow = row.cloneNode(true);
                    newRow.cells[0].textContent = index + 1;
                    tbody.appendChild(newRow);
                    
                    // Re-append the corresponding record row if it exists
                    const studentId = newRow.cells[4].getAttribute('onclick').match(/\d+/)[0];
                    const recordRow = document.getElementById('studentRecord' + studentId);
                    if (recordRow) {
                        tbody.appendChild(recordRow);
                    }
                });
                sortState = 'default';
                sortIcon.textContent = '';
                return;
            }
            
            // Clear tbody and re-append sorted rows with their record rows
            tbody.innerHTML = '';
            rows.forEach((row, index) => {
                // Update row number
                row.cells[0].textContent = index + 1;
                tbody.appendChild(row);
                
                // Re-append the corresponding record row if it exists
                const studentId = row.cells[4].getAttribute('onclick').match(/\d+/)[0];
                const recordRow = document.getElementById('studentRecord' + studentId);
                if (recordRow) {
                    tbody.appendChild(recordRow);
                }
            });
        }
        
        // Toggle date filter based on checkbox
        function toggleDateFilter() {
            const checkbox = document.getElementById('showAllAbsences');
            const dateSection = document.getElementById('dateSection');
            const dateCountSection = document.getElementById('dateCountSection');
            const allAbsencesCountSection = document.getElementById('allAbsencesCountSection');
            const form = document.getElementById('filterForm');
            
            if (checkbox.checked) {
                dateSection.style.display = 'none';
                dateCountSection.style.display = 'none';
                allAbsencesCountSection.style.display = 'block';
            } else {
                dateSection.style.display = 'flex';
                dateCountSection.style.display = 'block';
                allAbsencesCountSection.style.display = 'none';
            }
            
            // Submit form when checkbox changes
            form.submit();
        }
        
        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('.student-row');
            
            rows.forEach(row => {
                const studentName = row.querySelector('.student-name').textContent.toLowerCase();
                if (studentName.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
        
        function toggleStudentRecord(studentId) {
            const recordRow = document.getElementById('studentRecord' + studentId);
            const loadingContent = document.getElementById('loadingContent' + studentId);
            const recordContent = document.getElementById('recordContent' + studentId);
            
            if (recordRow.style.display === 'none') {
                recordRow.style.display = 'table-row';
                
                // If content is already loaded, just show it
                if (recordContent.innerHTML.trim() !== '') {
                    loadingContent.style.display = 'none';
                    recordContent.style.display = 'block';
                    return;
                }
                
                // Show loading and fetch data
                loadingContent.style.display = 'block';
                recordContent.style.display = 'none';
                
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
                    loadingContent.style.display = 'none';
                    if (data.html) {
                        recordContent.innerHTML = data.html;
                        recordContent.style.display = 'block';
                    } else {
                        recordContent.innerHTML = '<div class="alert alert-warning">لا توجد بيانات متابعة لهذا الطالب</div>';
                        recordContent.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Error loading follow-up record:', error);
                    loadingContent.style.display = 'none';
                    recordContent.innerHTML = '<div class="alert alert-danger">حدث خطأ أثناء تحميل سجل المتابعة</div>';
                    recordContent.style.display = 'block';
                });
            } else {
                recordRow.style.display = 'none';
            }
        }
    </script>
</body>
</html>