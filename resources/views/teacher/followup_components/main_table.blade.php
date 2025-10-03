<div class="table-responsive mb-1"> 
    <table class="table table-secondary table-bordered align-middle mb-0 text-nowrap">
        <thead>
            <tr class="text-center align-middle">
                <th class="p-1" style="width: 30px; max-width: 30px;">#</th>
                <th class="p-1 text-end sticky-col" style="width: 200px; max-width: 200px;">الطالب</th>
                @for ($i = 1; $i <= 7; $i++)
                    <?php 
                        $sessionHasData = false;
                        if(auth()->user()->user_type != 'school'){
                            foreach($students as $student) {
                                if($today != $date){
                                    if (auth()->user()->user_type != 'مشرف') {
                                        $sessionHasData = true;
                                    }
                                }else{
                                    $studentSessions = $sessions[$student->id] ?? collect();
                                    if($studentSessions->where('session_number', $i)->isNotEmpty()) {
                                        $sessionHasData = true;
                                        break;
                                    }
                                }
                            }
                        }
                    ?>
                    <th style="min-width:35px; width: 40px; max-width: 40px;">
                        <button type="button" 
                                class="w-100 h-100 bg-primary px-1 py-1 session-btn {{ $sessionHasData ? 'btn-secondary' : 'btn-primary' }}" 
                                data-session="{{ $i }}" 
                                data-bs-toggle="modal" 
                                data-bs-target="#sessionModal"
                                {{ $sessionHasData ? 'disabled' : '' }}>
                            {{ $i }}
                        </button>
                    </th>
                @endfor
                <th class="p-1" style="width: 30px; max-width: 30px;">غ</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
                <tr class="text-center">
                    <td class="p-1" style="background-color: #15336a; color:#ffd400; width: 30px; max-width: 30px;">{{ $loop->iteration }}</td>
                    <td class="p-1 text-end responsive-cell sticky-col" style="width: 200px; max-width: 200px; word-wrap: break-word; white-space: normal;">
                        <a href="#student-details-{{ $student->id }}" 
                           class="student-name-toggle cell-link d-flex align-items-center justify-content-start
                                {{ $student->note ? 'text-danger' : ($student->gender ? '' : 'text-danger') }}" 
                           style="color: {{ $student->note ? '#dc3545' : ($student->gender ? '#000' : '#dc3545') }} !important; text-decoration: none;"
                           data-bs-toggle="collapse"
                           aria-expanded="false"
                           aria-controls="student-details-{{ $student->id }}">
                            {{ $student->name }}
                        </a>
                    </td>
                    @for ($i = 1; $i <= 7; $i++)
                        <?php
                            $studentSessions = $sessions[$student->id] ?? collect();
                            $session = $studentSessions ? $studentSessions->where('session_number', $i)->first() : null;
                            
                            $sessionHasData = false;
                            if(auth()->user()->user_type != 'school'){
                                if($today != $date){
                                    if (auth()->user()->user_type != 'مشرف') {
                                        $sessionHasData = true;
                                    }
                                }else{
                                    foreach($students as $s) {
                                        $sSessions = $sessions[$s->id] ?? collect();
                                        if($sSessions->where('session_number', $i)->isNotEmpty()) {
                                            $sessionHasData = true;
                                            break;
                                        }
                                    }
                                }
                                if (auth()->user()->user_type == 'مشرف') {
                                    $sessionHasData = false;
                                }
                            }
                        ?>
                        <td class="p-1 {{ $sessionHasData ? '' : 'follow-up-cell' }}" 
                            data-student-id="{{ $student->id }}" 
                            data-session-number="{{ $i }}"
                            style="@if($session) background-color: {{ $session->followUpItem->background_color ?? '' }}; color: {{ $session->followUpItem->text_color ?? 'transparent' }}; @endif
                                   @if($sessionHasData) cursor: not-allowed; opacity: 0.8; @else cursor: pointer; @endif"
                            data-follow-up-id="{{ $session ? $session->follow_up_item_id ?? null : '' }}"
                            @if($sessionHasData) onclick="return false;" @endif>
                            @if($session)
                                {{ $session->followUpItem->letter ?? '' }}
                            @endif
                        </td>
                    @endfor
                    <td class="px-1" style="width: 30px; max-width: 30px;">
                        <?php $day =  $days->where('student_id', $student->id)->first() ?>
                        @if($day && $day->is_absent)
                            <span class="text-danger">غ</span>
                        @endif
                    </td>
                </tr>
                <tr class="collapse student-details-row" id="student-details-{{ $student->id }}" data-bs-parent=".table">
                    <td colspan="10" class="p-0 border-0">
                        <div class="collapse-inner bg-light p-2">
                            <div class="row">
                                <div class="col-md-12">
                                    <p><strong>الاسم الكامل:</strong> 
                                        <a href="#" class="text-{{$student->gender ? 'danger' : 'primary'}} view-student-btn" 
                                           data-bs-toggle="modal" data-bs-target="#studentDetailsModal"
                                           data-student-name="{{ $student->name }}"
                                           data-student-passport="{{ $student->passport_id }}"
                                           data-student-phone="{{ $student->phone_number }}"
                                           data-student-gender="{{ $student->gender ? 'ذكر' : 'أنثى' }}"
                                           data-student-grade="{{ $student->grade->name ?? '' }}"
                                           data-student-class="{{ $student->classRoom->name ?? '' }}"
                                           data-student-note="{{ $student->note ?? 'لا توجد ملاحظات' }}">
                                            {{ $student->name }}
                                        </a>
                                    </p>
                                    <p><strong>الرقم المدني :</strong> {{ $student->passport_id }}</p>
                                    <p><strong>الملاحظة :</strong> <span class="text-secondary">{{ $student->note}}</span></p>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <form action="{{route('teacher.student-sessions.quick-update')}}" class="quick-update-form" method="POST">
                                        @csrf
                                        <input type="hidden" name="student_id" value="{{$student->id}}">
                                        <input type="hidden" name="date" value="{{$date}}">
                                        <div class="table-responsive">
                                            <table class="table table-bordered align-middle mb-0 inner-table" style="min-width: 500px;">
                                                <thead>
                                                    <tr>
                                                        <th class="p-1 text-center session-col-header">الحصة</th>
                                                        <th class="p-1 text-center teacher-col-header">المعلم</th>
                                                        <th class="p-1 text-center subject-col-header">المادة</th>
                                                        <th class="p-1 text-center status-col-header">الحالة</th>
                                                        <th class="p-1 text-center notes-col-header">0</th>
                                                    </tr>
                                                </thead>
                                            <tbody>
                                                @for ($i = 1; $i <= 7; $i++)
                                                    <?php
                                                        $session = ($sessions[$student->id] ?? collect())->where('session_number', $i)->first();
                                                    ?>
                                                    <tr class="text-center">
                                                        <th class="p-1 session-col-data">{{$i}}</th>
                                                        <td class="teacher-col-data">{{$session && $session->teacher ? \Illuminate\Support\Str::limit($session->teacher->name, 8) : '-'}}</td>
                                                        <td class="subject-col-data">{{$session && $session->teacher ? $session->teacher->subject : '-'}}</td>
                                                        <td class="status-col-data" style="@if($session) background-color: {{ $session->followUpItem->background_color ?? '' }}; color: {{ $session->followUpItem->text_color ?? 'transparent' }}; @endif">
                                                            @if($session && $session->followUpItem)
                                                                {{ $session->followUpItem->letter ?? '' }}
                                                            @endif
                                                        </td>
                                                        <td class="notes-col-data"><input type="text" class="form-control notes-input" name="notes[{{$i}}]" value="{{ $session->teacher_note ?? '' }}"></td>
                                                    </tr>
                                                @endfor
                                                </tbody>
                                            </table>
                                        </div>
                                        @if(!in_array(auth()->user()->user_type, ['مراقب']))
                                        <button type="submit" class="btn btn-success mt-2 mb-4">حفظ</button>
                                        @endif
                                        <button type="button" class="btn btn-primary mt-2 mb-4 show-followup-btn" data-student-id="{{$student->id}}">عرض سجل المتابعة</button>
                                        <div class="followup-table-container mt-2" id="followup-table-{{$student->id}}" style="display: none;"></div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
         <tfoot>
	        <tr>
    	        <td colspan="10" class="p-3 bg-light">
    	            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
    	                <div class="teacher-selection-section w-100">
    	                   <div class="row g-2 align-items-end">
            				    @if(in_array(auth()->user()->user_type, ['school', 'مشرف']))
                				    <div class="col-md-4 d-flex flex-row flex-sm-column align-items-center align-items-sm-start gap-1">
            				            <label class="form-label fw-bold text-muted">اسم المعلم</label>
                                        <select id="teacher-select" class="form-control d-inline-block d-sm-block" style="font-size:16px;background:#ffd400;border-radius:5px;position: unset;" name="teacher_id_select" onchange="changeTeacherSubjectSelection('teacher-select', 'subject_id_show');">
            				                <option value="" selected disabled>اختر المعلم من القائمة</option>
            				                @foreach($teachers as $t)
            				                    <option value="{{ $t->id }}" 
            				                        @if(isset($teacher) && $teacher->id == $t->id) selected @endif
            				                        data-subject="{{ $t->subject ?? 'غير محدد' }}">
            				                        {{ $t->name }} 
            				                    </option>
            				                @endforeach
            				            </select>
                				    </div>
                				    
                				    <div class="col-6 col-md-4 col-sm-6 d-flex flex-row flex-sm-column align-items-center align-items-sm-start gap-1">
            				            <label class="form-label fw-bold text-muted">المادة الدراسية</label>
            				            <div id="subject_id_show" class="subject-display form-control d-inline-block d-sm-block p-1 bg-light rounded border">
            				                <span class="text-muted">غير محدد</span>
            				            </div>
                				    </div>
                				@else
                				    <input type="hidden" value="{{$teacher->id ?? ''}}" name="teacher_id" id="teacher-select">
                				@endif
            				    @if(!in_array(auth()->user()->user_type, ['مراقب']))
            				    <div class="col-6 col-md-4 col-sm-6">
            				         <button type="button" id="saveSessionBtn" class="btn btn-primary" disabled>حفظ التغييرات</button>
            				    </div>
            				    @endif
            				</div>
    	                </div>
    	            </div>
    	        </td>
	        </tr>
        </tfoot>
    </table>
</div>

<style>
.student-details-row .collapse-inner {
    overflow: hidden;
    transition: all 0.3s ease;
}

.student-details-row.collapsing .collapse-inner,
.student-details-row.show .collapse-inner {
    opacity: 1;
    transform: translateY(0);
}

.student-details-row:not(.show) .collapse-inner {
    opacity: 0;
    transform: translateY(-10px);
}

.student-name-toggle:hover {
    color: #0d6efd;
    text-decoration: none;
}

.table-responsive {
    overflow-x: auto;
    position: relative;
}

.sticky-col {
    position: sticky;
    right: 0;
    background-color: white;
    z-index: 10;
    border-right: 2px solid #dee2e6;
}

.table th:not(:nth-child(2)), 
.table td:not(:nth-child(2)) {
    white-space: nowrap;
    text-overflow: ellipsis;
    overflow: hidden;
}

.table th:nth-child(2), 
.table td:nth-child(2) {
    font-size: 12px;
    line-height: 1.2;
}

/* Desktop styles for inner table */
.session-col-header, .session-col-data {
    width: 60px;
    font-size: 10px;
}

.teacher-col-header, .teacher-col-data {
    width: 80px;
    font-size: 10px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.subject-col-header, .subject-col-data {
    width: 100px;
    font-size: 10px;
}

.status-col-header, .status-col-data {
    width: 80px;
    font-size: 10px;
}

.notes-col-header, .notes-col-data {
    width: 180px;
    font-size: 10px;
}

@media (max-width: 768px) {
    .table-responsive {
        overflow-x: hidden !important;
    }
    
    .table-responsive table {
        width: 100% !important;
        min-width: unset !important;
        table-layout: fixed;
    }
    
    .sticky-col {
        width: 35% !important;
        min-width: unset !important;
        max-width: unset !important;
        font-size: 10px !important;
        padding: 2px !important;
    }
    
    .table th:first-child,
    .table td:first-child {
        width: 8% !important;
        font-size: 10px !important;
        padding: 2px !important;
    }
    
    .table th:nth-child(n+3):nth-child(-n+9),
    .table td:nth-child(n+3):nth-child(-n+9) {
        width: 8% !important;
        font-size: 10px !important;
        padding: 1px !important;
    }
    
    .table th:last-child,
    .table td:last-child {
        width: 5% !important;
        font-size: 10px !important;
        padding: 2px !important;
    }
    
    .session-btn {
        font-size: 10px !important;
        padding: 2px !important;
    }
    
    /* MOBILE OPTIMIZED INNER TABLE */
    .inner-table {
        min-width: 100% !important;
        width: 100% !important;
        max-width: 100% !important;
        table-layout: fixed !important;
        display: table !important;
    }
    
    .inner-table thead,
    .inner-table tbody,
    .inner-table tr {
        width: 100% !important;
        display: table !important;
        table-layout: fixed !important;
    }
    
    /* Session column - VERY SMALL */
    .session-col-header,
    .session-col-data,
    .inner-table th:nth-child(1),
    .inner-table td:nth-child(1) {
        width: 5% !important;
        min-width: 5% !important;
        max-width: 5% !important;
        font-size: 7px !important;
        padding: 1px 0px !important;
        box-sizing: border-box !important;
    }
    
    /* Teacher column - SMALL */
    .teacher-col-header,
    .teacher-col-data,
    .inner-table th:nth-child(2),
    .inner-table td:nth-child(2) {
        width: 10% !important;
        max-width: 10% !important;
        min-width: 10% !important;
        font-size: 6px !important;
        padding: 1px !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        white-space: nowrap !important;
        box-sizing: border-box !important;
    }
    
    /* Subject column */
    .subject-col-header,
    .subject-col-data,
    .inner-table th:nth-child(3),
    .inner-table td:nth-child(3) {
        width: 15% !important;
        max-width: 15% !important;
        min-width: 15% !important;
        font-size: 7px !important;
        padding: 1px !important;
        box-sizing: border-box !important;
    }
    
    /* Status column - VERY SMALL */
    .status-col-header,
    .status-col-data,
    .inner-table th:nth-child(4),
    .inner-table td:nth-child(4) {
        width: 5% !important;
        min-width: 5% !important;
        max-width: 5% !important;
        font-size: 7px !important;
        padding: 1px 0px !important;
        box-sizing: border-box !important;
    }
    
    /* Notes column - VERY LARGE */
    .notes-col-header,
    .notes-col-data,
    .inner-table th:nth-child(5),
    .inner-table td:nth-child(5) {
        width: 65% !important;
        max-width: 65% !important;
        min-width: 65% !important;
        font-size: 10px !important;
        padding: 2px !important;
        box-sizing: border-box !important;
    }
    
    .notes-input,
    .inner-table input[type="text"],
    .inner-table .form-control {
        font-size: 10px !important;
        padding: 4px 6px !important;
        height: 28px !important;
        width: 100% !important;
        box-sizing: border-box !important;
    }
}

/* Extra small mobile devices */
@media (max-width: 480px) {
    .session-col-header,
    .session-col-data,
    .inner-table th:nth-child(1),
    .inner-table td:nth-child(1) {
        width: 4% !important;
        min-width: 4% !important;
        max-width: 4% !important;
    }
    
    .teacher-col-header,
    .teacher-col-data,
    .inner-table th:nth-child(2),
    .inner-table td:nth-child(2) {
        width: 9% !important;
        max-width: 9% !important;
        min-width: 9% !important;
    }
    
    .subject-col-header,
    .subject-col-data,
    .inner-table th:nth-child(3),
    .inner-table td:nth-child(3) {
        width: 13% !important;
        max-width: 13% !important;
        min-width: 13% !important;
    }
    
    .status-col-header,
    .status-col-data,
    .inner-table th:nth-child(4),
    .inner-table td:nth-child(4) {
        width: 4% !important;
        min-width: 4% !important;
        max-width: 4% !important;
    }
    
    .notes-col-header,
    .notes-col-data,
    .inner-table th:nth-child(5),
    .inner-table td:nth-child(5) {
        width: 70% !important;
        min-width: 70% !important;
        max-width: 70% !important;
    }
}
</style>

@include('teacher.followup_components.student_details_modal')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.student-name-toggle').forEach(toggle => {
            toggle.addEventListener('click', function() {
                const targetId = this.getAttribute('href');
                const isExpanding = !this.classList.contains('collapsed');
                
                if (isExpanding) {
                    document.querySelectorAll('.student-name-toggle').forEach(otherToggle => {
                        if (otherToggle !== this && !otherToggle.classList.contains('collapsed')) {
                            const otherTarget = otherToggle.getAttribute('href');
                            bootstrap.Collapse.getInstance(otherTarget).hide();
                        }
                    });
                }
            });
        });
        
        document.querySelectorAll('.quick-update-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const submitButton = form.querySelector('button[type="submit"]');
                const originalButtonText = submitButton.innerHTML;
                
                submitButton.disabled = true;
                submitButton.innerHTML = `
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    جاري الحفظ...
                `;
                
                const formData = new FormData(form);
                
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Accept': 'application/json',
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw err; });
                    }
                    return response.json();
                })
                .then(data => {
                    showSuccess('تم حفظ بيانات الطالب بنجاح');
                    if (data.updated_sessions) {
                        updateSessionUI(data.updated_sessions);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    const errorMessage = error.message || 'حدث خطأ غير متوقع أثناء محاولة حفظ البيانات';
                    showError(errorMessage);
                })
                .finally(() => {
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalButtonText;
                });
            });
        });
        
        document.querySelectorAll('.show-followup-btn').forEach(button => {
            button.addEventListener('click', function() {
                const studentId = this.getAttribute('data-student-id');
                const container = document.getElementById(`followup-table-${studentId}`);
                
                if (container.style.display === 'none') {
                    if (!container.hasAttribute('data-loaded')) {
                        fetchFollowupRecord(studentId, container);
                    } else {
                        container.style.display = 'block';
                    }
                } else {
                    container.style.display = 'none';
                }
            });
        });
        
        document.querySelectorAll('.view-student-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                document.getElementById('modal_student_name').textContent = this.getAttribute('data-student-name');
                document.getElementById('modal_student_passport').textContent = this.getAttribute('data-student-passport');
                document.getElementById('modal_student_phone').textContent = this.getAttribute('data-student-phone');
                document.getElementById('modal_student_gender').textContent = this.getAttribute('data-student-gender');
                document.getElementById('modal_student_grade').textContent = this.getAttribute('data-student-grade');
                document.getElementById('modal_student_class').textContent = this.getAttribute('data-student-class');
                document.getElementById('modal_student_note').textContent = this.getAttribute('data-student-note');
            });
        });
    });
    
    function updateSessionUI(sessions) {
        sessions.forEach(session => {
            const cell = document.querySelector(`td[data-student-id="${session.student_id}"][data-session-number="${session.session_number}"]`);
            if (cell) {
                cell.style.backgroundColor = session.background_color || '';
                cell.style.color = session.text_color || '';
                cell.textContent = session.letter || '';
                cell.setAttribute('data-follow-up-id', session.follow_up_item_id || '');
            }
        });
    }
    
    function fetchFollowupRecord(studentId, container) {
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
                container.innerHTML = data.html;
                container.style.display = 'block';
                container.setAttribute('data-loaded', 'true');
            }
        })
        .catch(error => {
            console.error('Error loading follow-up record:', error);
            container.innerHTML = '<div class="alert alert-danger">حدث خطأ أثناء تحميل سجل المتابعة</div>';
            container.style.display = 'block';
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
</script>