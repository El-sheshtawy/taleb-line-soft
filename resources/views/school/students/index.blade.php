<h5 style="display: flex; justify-content: space-between">
    <span class="text-primary">
        الطلاب
        <span class="badge bg-primary text-light">{{ count($students) }}</span>
    </span>
    @if(!in_array(auth()->user()->user_type, ['مراقب', 'مشرف']))
    <a class="badge bg-danger text-white" onclick="return confirm('هل انت متاكد من حذف الطلاب؟')" 
        href="{{ route('school.students.delete', ['grade_id' => request()->grade_id, 'class_id' => request()->class_id]) }}">
        حذف الطلاب
    </a>
    @endif
</h5>
<div class="row g-2 mt-2 mb-3 p-2 bg-light border rounded">
    <div class="col-12 col-md-6 mb-2">
        <label for="search_grade_id" class="form-label fw-bold text-primary mb-1">الصف:</label>
        <select name="grade_id" class="form-select" id="search_grade_id" 
                style="font-size:14px;background:#ffd400;border-radius:5px;" 
                onchange="handleGradeChange(this.value)">
            <option selected value="all">الكل</option>
            @foreach($grades as $grade)
                <option value="{{ $grade->id }}" {{ request('grade_id') == $grade->id ? 'selected' : '' }}>{{ $grade->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-12 col-md-6 mb-2">
        <label for="search_class_id" class="form-label fw-bold text-primary mb-1">الفصل:</label>
        <select name="class_id" class="form-select" id="search_class_id" 
                style="font-size:14px;background:#ffd400;border-radius:5px;" 
                onchange="handleClassChange(this.value)">
            <option selected value="all">الكل</option>
            @if(request('grade_id') && is_numeric(request('grade_id')))
                @foreach($classes as $class)
                    <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                @endforeach
            @endif
        </select>
    </div>
</div>

<div class="table-responsive"> 
    <table class="table myTable table-secondary table-bordered table-striped  text-nowrap">
        <thead>
            <tr class="text-center">
                <th class="p-1 text-center">م</th>
                <th class="p-1 text-center">الاسم</th>
                <th class="p-1 text-center">الصف</th>
                <th class="p-1 text-center">الفصل</th>
                <th class="p-1 text-center">الرقم المدني</th>
                <th class="p-1 text-center">الهاتف</th>
            </tr>
        </thead>
        <tbody id="studentTableBody">
            @foreach($students as $student)
                <tr class="text-center student-row" 
                    data-grade="{{ $student->grade_id }}" 
                    data-class="{{ $student->class_id }}">
                    <td class="p-1">{{$loop->iteration}}</td>
                    <td class="p-1 text-end responsive-cell">
                        @if(in_array(auth()->user()->user_type, ['مراقب', 'مشرف']))
                            <span class="text-{{ $student->gender ? 'primary' : 'danger' }} text-{{ $student->note ? 'danger' : '' }}">
                                {{ $student->name }}
                            </span>
                        @else
                            <a href="#"
                                class="edit-student-btn cell-link
                                text-{{ $student->gender ? 'primary' : 'danger' }} text-{{ $student->note ? 'danger' : '' }}"
                                data-bs-toggle="modal" data-bs-target="#editStudentModal"
                                data-student="{{ json_encode($student) }}"
                                data-edit-action="{{ route('school.students.update', $student->id) }}"
                                data-delete-action="{{ route('school.students.destroy', $student->id) }}">
                                {{ $student->name }}
                            </a>
                        @endif
                    </td>
                    <td class="p-1">{{$student->grade->name ?? '-'}}</td>
                    <td class="p-1">{{$student->classRoom->name ?? '-'}}</td>
                    <td class="p-1">{{$student->passport_id}}</td>
                    <td class="p-1">{{$student->phone_number}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-2 p-2 bg-light border rounded text-center">
        <strong>إجمالي عدد الطلاب: <span class="badge bg-success">{{ $students->count() }}</span></strong>
    </div>
</div>


@if(!in_array(auth()->user()->user_type, ['مراقب', 'مشرف']))
<div class="row g-2 my-1">
    <div class="col-12 col-md-4">
        <button type="button" class="btn btn-primary w-100 text-center" data-bs-toggle="modal" data-bs-target="#createStudentModal">
            إضافة طالب
        </button>
    </div>
    <div class="col-12 col-md-4">
        <button type="button" class="btn btn-primary w-100 text-center" data-bs-toggle="modal" data-bs-target="#importStudentsModal">
            إستيراد طلاب <i class="fas fa-upload"></i>
        </button>
    </div>
    <div class="col-12 col-md-3">
        <button class="btn btn-success w-100 text-center">
            <a href="{{ asset('templates/الطلاب.xlsx') }}" class="text-white w-100 h-100" download>
                تحميل قالب الطلاب <i class="fas fa-download"></i>
            </a>
        </button>
    </div>
    <div class="col-12 col-md-3">
        <button type="button" class="btn btn-danger w-100 text-center" onclick="exportSchoolPDF()">
            طباعة PDF <i class="fas fa-file-pdf"></i>
        </button>
    </div>
</div>
@else
<div class="row g-2 my-1">
    <div class="col-12">
        <button type="button" class="btn btn-danger w-100 text-center" onclick="exportSchoolPDF()">
            طباعة PDF <i class="fas fa-file-pdf"></i>
        </button>
    </div>
</div>
@endif



@include('school.students.create')
@include('school.students.import')
@include('school.students.edit')
@include('school.students.delete')

<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".edit-student-btn").forEach(button => {
        button.addEventListener("click", function () {
            let studentData = JSON.parse(this.getAttribute("data-student"));
            
            document.getElementById("updateStudentForm").action = this.getAttribute("data-edit-action");
            document.getElementById("deleteStudentForm").action = this.getAttribute("data-delete-action");
            
            let studentClassId = studentData.class_id;
            
            document.getElementById("edit_student_name").value = studentData.name;
            document.getElementById("edit_student_gender").value = studentData.gender;
            document.getElementById("edit_student_passport_id").value = studentData.passport_id;
            document.getElementById("edit_student_phone_number").value = studentData.phone_number;
            document.getElementById("edit_student_phone_number_2").value = studentData.phone_number_2;
            document.getElementById("edit_student_grade_id").value = studentData.grade_id;
            document.getElementById("edit_student_nationality_id").value = studentData.nationality_id;
            document.getElementById("edit_student_note").value = studentData.note;
            
            getGradeClasses(studentData.grade_id, 'edit_student_class_id', function() {
                if (studentClassId) {
                    document.getElementById("edit_student_class_id").value = studentClassId;
                }
            });
            
        });
    });
});
</script>


<script>
function handleGradeChange(gradeId) {
    const url = new URL(window.location.href);
    
    if (gradeId === 'all') {
        url.searchParams.delete('grade_id');
        url.searchParams.delete('class_id');
    } else {
        url.searchParams.set('grade_id', gradeId);
        url.searchParams.delete('class_id'); // Reset class when grade changes
    }
    
    window.location.href = url.toString();
}

function handleClassChange(classId) {
    const url = new URL(window.location.href);
    const gradeId = document.getElementById('search_grade_id').value;
    
    if (classId === 'all') {
        url.searchParams.delete('class_id');
    } else {
        url.searchParams.set('class_id', classId);
        // Make sure grade_id is set if it's not already
        if (gradeId && gradeId !== 'all') {
            url.searchParams.set('grade_id', gradeId);
        }
    }
    
    window.location.href = url.toString();
}

// Initialize the class dropdown based on the selected grade
document.addEventListener("DOMContentLoaded", function() {
    const gradeId = "{{ request('grade_id') }}";
    const classId = "{{ request('class_id') }}";
    
    if (gradeId && gradeId !== 'all') {
        getGradeClasses(gradeId, 'search_class_id', function() {
            if (classId) {
                document.getElementById('search_class_id').value = classId;
            }
        });
    }
});

function getGradeClasses(gradeId, classId, callback = null) {
    const classSelect = document.getElementById(classId);
    
    if (!gradeId || gradeId === "all") {
        classSelect.innerHTML = '<option selected value="all">الكل</option>';
        if (callback) callback();
        return;
    }
    
    let schoolId = {{$school->id}}
    
    classSelect.innerHTML = '<option value="">جاري التحميل...</option>';
    classSelect.disabled = true;
    
    fetch(`{{ url('/public/public/schools/${schoolId}/grades/${gradeId}/classes') }}`)
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            let options = '<option selected value="all">الكل</option>';
            data.forEach(cls => {
                options += `<option value="${cls.id}">${cls.name}</option>`;
            });
            
            classSelect.innerHTML = options;
            classSelect.disabled = false;
            
            if (callback) callback();
        })
        .catch(error => {
            console.error('Error:', error);
            classSelect.innerHTML = '<option value="">حدث خطأ أثناء التحميل</option>';
            if (callback) callback();
        });
}

function exportSchoolPDF() {
    const activeTable = document.querySelector('.myTable');
    if (!activeTable) {
        alert('لم يتم العثور على جدول لتصديره');
        return;
    }

    const title = 'قائمة الطلاب';
    const tableHTML = activeTable.outerHTML;
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/school/export-pdf';
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
}
</script>
