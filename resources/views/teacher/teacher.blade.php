<!DOCTYPE html>

<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
        .btn-secondary.session-btn {
            cursor: not-allowed;
            opacity: 0.8;
        }

        td[data-session-number] {
            pointer-events: none;
            cursor: not-allowed;
            opacity: 0.8;
        }

        td.follow-up-cell {
            opacity: 1;
            pointer-events: auto;
        }

        .active-session {
            box-shadow: 0 0 0 2px #0d6efd;
        }

        .session-btn.active {
            background-color: #0d6efd;
            color: white;
            font-weight: bold;
        }

        .follow-up-modal-cell {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .follow-up-modal-cell:hover {
            opacity: 0.9;
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
                    @if(auth()->user()->user_type === 'مراقب')
                        <h5> <span style="color:#75f98c;"> <i class="ri-eye-fill"></i>مراقب</span>
                            {{ $teacher ? $teacher->name : '' }} </h5>
                    @elseif(auth()->user()->user_type === 'مشرف')
                        <h5> <span style="color:#75f98c;"> <i class="ri-user-settings-fill"></i>مشرف</span>
                            {{ $teacher ? $teacher->name : '' }} </h5>
                    @else
                        <h5> <span style="color:#75f98c;"> <i class="ri-shield-user-fill"></i>المعلم</span>
                            {{ $teacher ? $teacher->name : '' }} </h5>
                    @endif
                </div>
                @if(auth()->user()->user_type === 'مراقب')
                    <p> هذه الصفحة تمكنكم من مراقبة حضور الطلاب <br /> ولكم كل الشكر والتقدير على دعمكم لنا 🌹 </p>
                @else
                    <p> هذه الصفحة تمكنكم من متابعة حضور الطلاب <br /> ولكم كل الشكر والتقدير على دعمكم لنا 🌹 </p>
                @endif
            </div>

            <div class="listOfName">

                <!--<h5 class="text-primary text-bold my-1">كشف حضور الطلاب <span class="badge bg-primary text-light">{{ count($students) }}</span></h5>-->
                <div class="d-flex justify-content-between align-items-center gap-2 my-1 p-2 text-center"
                    style="background:#0E2550">
                    <h5 class="text-primary text-bold m-0">
                        كشف متابعة الطلاب
                        <span class="badge bg-primary text-light">{{ count($students) }}</span>
                    </h5>

                    <select id="settingsSelector"
                        style="font-size:16px;background:#ffd400;border-radius:5px;position: unset;">
                        <option disabled selected>الاعدادات</option>
                        <option value="monitoring">عرض المتابعة</option>
                        <option value="export-pdf">تصدير بي دي اف</option>
                        <option value="meeting-room">غرفة ميتينج</option>
                        <x-action-button>
                            @if (auth()->user()->user_type == 'school')
                                <option value="clear-data">مسح متابعة اليوم</option>
                            @endif
                        </x-action-button>
                    </select>
                </div>

                <div class="p-2 bg-light shadow-sm rounded border border-primary mb-1">
                    @include('teacher.followup_components.search_form')
                </div>

                @include('teacher.followup_components.main_table')
                @include('teacher.followup_components.session_modal')


                <h5 class="text-primary text-bold my-1">إحصائيات الحصص</h5>
                @include('teacher.followup_components.stats_table')
            </div>
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
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="{{ asset('js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('js/buttons.html5.min.js') }}"></script>
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

    <script>
        function changeTeacherSubjectSelection(teacherSelectId, subjectDisplayId) {
            const teacherSelect = document.getElementById(teacherSelectId);
            const subjectDisplay = document.getElementById(subjectDisplayId);
            const selectedOption = teacherSelect.options[teacherSelect.selectedIndex];
            const subjectName = selectedOption.getAttribute('data-subject') || 'غير محدد';
            subjectDisplay.innerHTML = `<span>${subjectName}</span>`;

            document.getElementById('saveSessionBtn').disabled = teacherSelect.value === "";
        }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // ========== GLOBAL VARIABLES AND CONFIGURATION ==========
            const followUpItems = JSON.parse('{!! json_encode(
                $followUp->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'letter' => $item->letter,
                        'bgColor' => $item->background_color,
                        'textColor' => $item->text_color,
                    ];
                }),
            ) !!}');

            const sessionChanges = {};
            const dateInput = document.getElementById('date');
            let activeSession = null; // Track currently active session

            // ========== UTILITY FUNCTIONS ==========

            function sessionHasAnyData(sessionNumber) {
                isSchool = {{ auth()->user()->user_type == 'school' ? 'true' : 'false' }};
                if (isSchool) return false;
                const cells = document.querySelectorAll(`[data-session-number="${sessionNumber}"]`);
                for (const cell of cells) {
                    if (cell.getAttribute('data-follow-up-id')) {
                        return true;
                    }
                }
                return false;
            }

            function showLoading() {
                Swal.fire({
                    title: 'جاري الحفظ',
                    html: 'الرجاء الانتظار...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            }

            function showSuccess(message) {
                Swal.fire({
                    icon: 'success',
                    title: 'تم بنجاح',
                    text: message,
                    confirmButtonText: 'حسناً'
                });
            }

            function showError(message) {
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: message,
                    confirmButtonText: 'حسناً'
                });
            }

            function cycleFollowUp(currentId) {
                if (!followUpItems.length) return null;

                if (!currentId) {
                    return followUpItems[0];
                }

                const currentIndex = followUpItems.findIndex(item => item.id == currentId);

                if (currentIndex === -1 || currentIndex === followUpItems.length - 1) {
                    return null;
                }
                return followUpItems[currentIndex + 1];
            }

            function updateSessionVisualState() {
                // Remove all active classes
                document.querySelectorAll('.session-btn').forEach(btn => {
                    btn.classList.remove('active');
                });
                document.querySelectorAll('th, td').forEach(el => {
                    el.classList.remove('active-session');
                });

                // Add active class to current session
                if (activeSession) {
                    document.querySelectorAll(`.session-btn[data-session="${activeSession}"]`).forEach(btn => {
                        btn.classList.add('active');
                    });
                    document.querySelectorAll(
                            `th[data-session="${activeSession}"], td[data-session-number="${activeSession}"]`)
                        .forEach(el => {
                            el.classList.add('active-session');
                        });
                }
            }

            // ========== EVENT HANDLERS ==========

            // Handle click on main table cells
            function handleCellClick() {
                const sessionNumber = this.getAttribute('data-session-number');

                // Check if another session is active
                if (activeSession && activeSession !== sessionNumber) {
                    showError('يجب حفظ التغييرات في الحصة الحالية قبل تعديل حصة أخرى');
                    return;
                }

                // Set this as the active session if none is active
                if (!activeSession) {
                    activeSession = sessionNumber;
                    updateSessionVisualState();
                }

                const studentId = this.getAttribute('data-student-id');
                const currentId = this.getAttribute('data-follow-up-id');

                const nextItem = cycleFollowUp(currentId);

                if (nextItem) {
                    this.setAttribute('data-follow-up-id', nextItem.id);
                    this.style.backgroundColor = nextItem.bgColor;
                    this.style.color = nextItem.textColor;
                    this.textContent = nextItem.letter;
                } else {
                    this.setAttribute('data-follow-up-id', '');
                    this.removeAttribute('style');
                    this.textContent = '';
                }

                if (!sessionChanges[sessionNumber]) {
                    sessionChanges[sessionNumber] = {};
                }
                sessionChanges[sessionNumber][studentId] = nextItem ? nextItem.id : null;

                updateSaveButtonState();
            }

            function updateSaveButtonState() {
                const saveBtn = document.getElementById('saveSessionBtn');
                const hasChanges = Object.keys(sessionChanges).length > 0;
                saveBtn.disabled = !hasChanges;
            }

            // Save button for main table
            async function handleSaveClick() {
                @if(auth()->user()->user_type === 'مراقب')
                    showError('غير مسموح لك بتنفيذ هذا الإجراء');
                    return;
                @endif
                
                const saveBtn = document.getElementById('saveSessionBtn');
                if (saveBtn) saveBtn.disabled = true;

                const date = dateInput.value || "{{ request('date', $date) }}";

                if (Object.keys(sessionChanges).length === 0) {
                    showError('لا توجد تغييرات لحفظها');
                    return;
                }

                // showLoading();

                try {
                    const savePromises = [];
                    const teacherId = document.getElementById('teacher-select').value;
                    const userType = '{{ auth()->user()->user_type }}';
                    if (!teacherId && userType !== 'مشرف') {
                        showError('الرجاء اختيار المعلم قبل الحفظ');
                        return;
                    }

                    for (const [sessionNumber, studentsData] of Object.entries(sessionChanges)) {
                        const formData = new FormData();
                        formData.append('date', date);
                        formData.append('session_number', sessionNumber);
                        formData.append('teacher_id', teacherId);

                        for (const [studentId, followUpItemId] of Object.entries(studentsData)) {
                            formData.append(`students[${studentId}][follow_up_item_id]`, followUpItemId || '');
                            formData.append(`students[${studentId}][teacher_note]`, '');
                        }

                        savePromises.push(
                            fetch("{{ route('teacher.student-sessions.store') }}", {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                    'Accept': 'application/json',
                                },
                                body: formData
                            }).then(async response => {
                                if (!response.ok) {
                                    throw new Error(await response.text());
                                }
                                return response.json();
                            })
                        );
                    }

                    const results = await Promise.all(savePromises);
                    const allSuccess = results.every(result => result && result.success);

                    if (allSuccess) {
                        Object.keys(sessionChanges).forEach(key => delete sessionChanges[key]);
                        activeSession = null; // Clear active session
                        updateSessionVisualState();
                        await showSuccess('تم حفظ جميع التغييرات بنجاح');
                        location.reload();
                    } else {
                        const errorMessages = results
                            .filter(result => !result.success)
                            .map(result => result.message)
                            .join('\n');
                        showError('حدث خطأ أثناء الحفظ:\n' + errorMessages);
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showError(error.message.includes('<!DOCTYPE') ?
                        'حدث خطأ في الخادم. الرجاء المحاولة مرة أخرى' :
                        'حدث خطأ أثناء الحفظ: ' + error.message);
                }
            }

            // Initialize the button state
            updateSaveButtonState();

            // Handle click on modal cells
            function handleModalCellClick(e) {
                const modalCell = e.target.closest('.follow-up-modal-cell');
                if (!modalCell) return;

                const currentId = modalCell.getAttribute('data-follow-up-id');
                const hiddenInput = modalCell.parentElement.querySelector('input[type="hidden"]');
                const nextItem = cycleFollowUp(currentId);

                if (nextItem) {
                    // Update visual appearance
                    modalCell.setAttribute('data-follow-up-id', nextItem.id);
                    modalCell.style.backgroundColor = nextItem.bgColor;
                    modalCell.style.color = nextItem.textColor;
                    modalCell.textContent = nextItem.letter;

                    // Update hidden input
                    hiddenInput.value = nextItem.id;
                } else {
                    // Reset visual appearance
                    modalCell.setAttribute('data-follow-up-id', '');
                    modalCell.style.backgroundColor = '';
                    modalCell.style.color = '';
                    modalCell.textContent = '';

                    // Reset hidden input
                    hiddenInput.value = '';
                }
            }

            // Modal session handling
            async function handleSessionButtonClick(e) {
                const sessionNumber = this.getAttribute("data-session");

                // Check if session has data
                if (sessionHasAnyData(sessionNumber)) {
                    e.preventDefault();
                    e.stopPropagation();
                    showError('لا يمكن تعديل هذه الحصة لأنها تحتوي على بيانات بالفعل');
                    return;
                }

                // Set this as the active session
                activeSession = sessionNumber;
                updateSessionVisualState();

                document.getElementById("modalSessionNumber").value = sessionNumber;
                document.getElementById("modalSessionTitle").textContent = sessionNumber;

                const selectedDate = dateInput.value || "{{ request('date', $date) }}";

                try {
                    const response = await fetch(
                        `{{ url('/teacher/student-sessions') }}/${sessionNumber}?date=${selectedDate}`, {
                            headers: {
                                'Accept': 'application/json'
                            }
                        });

                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }

                    const data = await response.json();

                    // Reset all modal cells first
                    document.querySelectorAll('.follow-up-modal-cell').forEach(cell => {
                        cell.setAttribute('data-follow-up-id', '');
                        cell.style.backgroundColor = '';
                        cell.style.color = '';
                        cell.textContent = '';

                        const hiddenInput = cell.parentElement.querySelector('input[type="hidden"]');
                        hiddenInput.value = '';

                        const noteInput = cell.parentElement.parentElement.querySelector(
                            'input[name^="students["][name$="[teacher_note]"]');
                        noteInput.value = '';
                    });

                    // Populate with current session data
                    data.forEach(session => {
                        const cell = document.querySelector(
                            `.follow-up-modal-cell[data-student-id="${session.student_id}"]`);
                        if (!cell) return;

                        const hiddenInput = cell.parentElement.querySelector('input[type="hidden"]');
                        const noteInput = cell.parentElement.parentElement.querySelector(
                            'input[name^="students["][name$="[teacher_note]"]');

                        if (session.follow_up_item_id) {
                            const item = followUpItems.find(i => i.id == session.follow_up_item_id);
                            if (item) {
                                cell.setAttribute('data-follow-up-id', item.id);
                                cell.style.backgroundColor = item.bgColor;
                                cell.style.color = item.textColor;
                                cell.textContent = item.letter;
                                hiddenInput.value = item.id;
                            }
                        }

                        if (noteInput && session.teacher_note) {
                            noteInput.value = session.teacher_note;
                        }
                    });
                } catch (error) {
                    console.error('Error loading session data:', error);
                    showError('حدث خطأ أثناء تحميل بيانات الحصة: ' + error.message);
                }
            }

            // Handle modal form submission
            async function handleFormSubmit(e) {
                @if(auth()->user()->user_type === 'مراقب')
                    e.preventDefault();
                    showError('غير مسموح لك بتنفيذ هذا الإجراء');
                    return;
                @endif
                
                e.preventDefault();
                // showLoading();

                try {
                    const formData = new FormData(this);
                    const teacherId = document.getElementById('teacher-select').value;
                    const userType = '{{ auth()->user()->user_type }}';
                    if (!teacherId && userType !== 'مشرف') {
                        showError('الرجاء اختيار المعلم قبل الحفظ');
                        return;
                    }
                    if (teacherId) formData.append('teacher_id', teacherId);

                    const response = await fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'Accept': 'application/json',
                        }
                    });

                    const data = await response.json();

                    if (data && data.success) {
                        activeSession = null; // Clear active session
                        updateSessionVisualState();
                        await showSuccess('تم حفظ بيانات الحصة بنجاح');
                        location.reload();
                    } else {
                        showError(data?.message || 'حدث خطأ أثناء الحفظ');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showError(error.message.includes('<!DOCTYPE') ?
                        'حدث خطأ في الخادم. الرجاء المحاولة مرة أخرى' :
                        'حدث خطأ أثناء الحفظ: ' + error.message);
                }
            }

            // ========== EVENT LISTENERS ==========
            document.querySelectorAll('.follow-up-cell').forEach(cell => {
                cell.addEventListener('click', handleCellClick);
            });

            const saveBtn = document.getElementById('saveSessionBtn');
            if (saveBtn) {
                saveBtn.addEventListener('click', handleSaveClick);
            }
            document.getElementById('sessionModal').addEventListener('click', handleModalCellClick);
            document.querySelectorAll(".session-btn").forEach(button => {
                button.addEventListener("click", handleSessionButtonClick);
            });
            document.getElementById('sessionForm').addEventListener('submit', handleFormSubmit);
        });
    </script>
    @php
        $classId = request('class_id');
        $meetingRoomLink = null;

        if ($classId) {
            $class = \App\Models\ClassRoom::find($classId);
            $meetingRoomLink = $class ? $class->meeting_room_link : null;
        }
    @endphp
    <script>
        function checkMonitoringParameter() {
            const urlParams = new URLSearchParams(window.location.search);
            const settingsSelector = document.getElementById('settingsSelector');

            if (urlParams.has('monitoring')) {
                if (!document.querySelector('#settingsSelector option[value="show-all"]')) {
                    const showAllOption = document.createElement('option');
                    showAllOption.value = "show-all";
                    showAllOption.textContent = "عرض الكل";

                    const firstOption = settingsSelector.options[0];
                    settingsSelector.insertBefore(showAllOption, firstOption.nextSibling);

                    settingsSelector.value = "monitoring";
                }
            }
        }
        checkMonitoringParameter()

        async function exportToPDF() {
            // Get table data
            const table = document.querySelector('.table-responsive table');
            if (!table) {
                showError('لم يتم العثور على الجدول');
                return;
            }

            const title = 'كشف متابعة الطلاب';
            const tableHTML = table.outerHTML;
            
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/admin/export-pdf';
            form.target = '_blank';
            
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            
            const titleInput = document.createElement('input');
            titleInput.type = 'hidden';
            titleInput.name = 'title';
            titleInput.value = title;
            
            const tableInput = document.createElement('input');
            tableInput.type = 'hidden';
            tableInput.name = 'tableData';
            tableInput.value = tableHTML;
            
            form.appendChild(csrfInput);
            form.appendChild(titleInput);
            form.appendChild(tableInput);
            
            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
            
            printWindow.document.write(printContent);
            printWindow.document.close();
            
            // Wait for content to load then print
            printWindow.onload = function() {
                printWindow.print();
                printWindow.close();
            };
        }

        document.getElementById('settingsSelector').addEventListener('change', function() {
            let selectedOption = this.value;
            const urlParams = new URLSearchParams(window.location.search);

            if (selectedOption === "monitoring") {
                urlParams.set('monitoring', 'true');
                window.location.search = urlParams.toString();
            } else if (selectedOption === "show-all") {
                urlParams.delete('monitoring');
                window.location.search = urlParams.toString();
            } else if (selectedOption === "export-pdf") {
                exportToPDF(); // Call the function to generate the PDF
            } else if (selectedOption === "meeting-room") {
                let meetingRoomLink = "{{ $meetingRoomLink }}";
                if (meetingRoomLink) {
                    window.open(meetingRoomLink, "_blank");
                } else {
                    showError("لا يوجد رابط اجتماع لهذا الفصل.");
                }
            } else if (selectedOption === "clear-data") {
                @if (auth()->user()->user_type != 'school')
                    showError('ليس لديك صلاحيات لهذا ')
                @endif

                const selectedDate = "{{ request('date') }}";
                const selectedGradeId = "{{ request('grade_id') }}";
                const selectedClassId = "{{ request('class_id') }}";

                if (selectedDate == '' || selectedGradeId == '' || selectedClassId == '') {
                    showError('لا بد من اختيار صف, فصل, ويوم أولاً');
                } else {
                    let confirmDelete = confirm("هل أنت متأكد أنك تريد مسح بيانات هذا الصف في هذا اليوم؟");
                    if (confirmDelete) {
                        deleteAllDayAttendance(selectedDate, selectedGradeId, selectedClassId);
                    }
                }
            }
        });

        @if (auth()->user()->user_type == 'school')
            async function deleteAllDayAttendance(selectedDate, selectedGradeId, selectedClassId) {
                try {
                    const response = await fetch(
                        `{{ url('school/attendance/delete-day') }}?date=${selectedDate}&grade_id=${selectedGradeId}&class_id=${selectedClassId}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                'Accept': 'application/json',
                            }
                        });

                    const data = await response.json();

                    if (data && data.success) {
                        await showSuccess('تم حذف بيانات اليوم  بنجاح');
                        location.reload();
                    } else {
                        showError(data?.message || 'حدث خطأ أثناء الحذف');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showError(error.message.includes('<!DOCTYPE') ?
                        'حدث خطأ في الخادم. الرجاء المحاولة مرة أخرى' :
                        'حدث خطأ أثناء الحذف: ' + error.message);
                }
            }
        @endif
    </script>
</body>

</html>
