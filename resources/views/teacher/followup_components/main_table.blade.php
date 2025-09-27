<div class="table-responsive mb-1"> 
    <table class="table table-secondary table-bordered align-middle mb-0 text-nowrap">
        <thead>
            <tr class="text-center align-middle">
                <th class="p-1" style="width: 30px; max-width: 30px;">#</th>
                <th class="p-1 text-end" style="width: 120px; max-width: 120px;">الطالب</th>
                @for ($i = 1; $i <= 7; $i++)
                    <?php 
                        $sessionHasData = false;
                        if(auth()->user()->user_type != 'school'){
                            foreach($students as $student) {
                                if($today != $date){
                                    $sessionHasData = true;
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
                    <td class="p-1 text-end responsive-cell" style="width: 120px; max-width: 120px; word-wrap: break-word; white-space: normal;">
                        <a href="#student-details-{{ $student->id }}" 
                           class="student-name-toggle cell-link d-flex align-items-center justify-content-start
                                text-{{$student->gender ? 'primary' : 'danger'}} text-{{ $student->note ? 'danger' : '' }}" 
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
                                    $sessionHasData = true;
                                }else{
                                    foreach($students as $s) {
                                        $sSessions = $sessions[$s->id] ?? collect();
                                        if($sSessions->where('session_number', $i)->isNotEmpty()) {
                                            $sessionHasData = true;
                                            break;
                                        }
                                    }
                                }
                                if ($teacher && $teacher->supervisor == 1) {
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
                                        <table class="table table-bordered align-middle mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="p-1 text-center">الحصة</th>
                                                    <th class="p-1 text-center" style="min-width:60px">المعلم</th>
                                                    <th class="p-1 text-center">المادة</th>
                                                    <th class="p-1 text-center">الحالة</th>
                                                    <th class="p-1 text-center">الملاحظات</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @for ($i = 1; $i <= 7; $i++)
                                                    <?php
                                                        $session = ($sessions[$student->id] ?? collect())->where('session_number', $i)->first();
                                                    ?>
                                                    <tr class="text-center">
                                                        <th class="p-1">{{$i}}</td>
                                                        <td>{{$session && $session->teacher ? \Illuminate\Support\Str::limit($session->teacher->name, 12) : '-'}}</td>
                                                        <td>{{$session && $session->teacher ? $session->teacher->subject : '-'}}</td>
                                                        <td style="@if($session) background-color: {{ $session->followUpItem->background_color ?? '' }}; color: {{ $session->followUpItem->text_color ?? 'transparent' }}; @endif">
                                                            @if($session && $session->followUpItem)
                                                                {{ $session->followUpItem->letter ?? '' }}
                                                            @endif
                                                        </td>
                                                        <td><input type="text" class="form-control" name="notes[{{$i}}]" value="{{ $session->teacher_note ?? '' }}"></td>
                                                    </tr>
                                                @endfor
                                            </tbody>
                                        </table>
                                        @if(!in_array(auth()->user()->user_type, ['مراقب']))
                                        <button type="submit" class="btn btn-success mt-2 mb-4">حفظ</button>
                                        @endif
                                        <button type="button" class="btn btn-primary mt-2 mb-4 show-followup-btn" data-student-id="{{$student->id}}">عرض سجل المتابعة</button>
                                        <div class="followup-table-container mt-2" id="followup-table-{{$student->id}}" style="display: none;"><!-- The table will be inserted here --></div>
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
            				    @if(auth()->user()->user_type == 'school')
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
            				         <button type="button" id="saveSessionBtn" class="btn btn-primary" disabled>
            		                            حفظ التغييرات</button>
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
    /* Enhanced collapse animation */
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
    
    /* Hover effect */
    .student-name-toggle:hover {
        color: #0d6efd;
        text-decoration: none;
    }

.table-responsive {
    overflow-x: visible;
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

@media (max-width: 768px) {
    .table-responsive {
        overflow-x: auto !important;
        -webkit-overflow-scrolling: touch !important;
        scroll-behavior: smooth !important;
    }
    .table-responsive table {
        min-width: 400px !important;
    }
    .table-responsive::-webkit-scrollbar {
        height: 6px;
    }
    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }
    .table-responsive::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 3px;
    }
    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
}
</style>

@include('teacher.followup_components.student_details_modal')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // toggle student info
        document.querySelectorAll('.student-name-toggle').forEach(toggle => {
            toggle.addEventListener('click', function() {
                const targetId = this.getAttribute('href');
                const isExpanding = !this.classList.contains('collapsed');
                
                if (isExpanding) {
                    // Close any other open collapses
                    document.querySelectorAll('.student-name-toggle').forEach(otherToggle => {
                        if (otherToggle !== this && !otherToggle.classList.contains('collapsed')) {
                            const otherTarget = otherToggle.getAttribute('href');
                            bootstrap.Collapse.getInstance(otherTarget).hide();
                        }
                    });
                }
            });
        });
        
        // quick update student notes
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
        
        // Handle follow-up record button click
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
        
        // Handle student details modal
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

