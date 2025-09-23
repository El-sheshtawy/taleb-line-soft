<form action="{{ url()->current() }}" method="get" id="dateForm">
    <div class="row">
        {{-- Grade select --}}
        <div class="col-6 col-md-4 d-flex align-items-center gap-2 mb-2">
            <label for="search_grade_id" class="fw-bold text-primary">الصف:</label>
            <select name="grade_id" class="form-control" id="search_grade_id" required
                style="font-size:16px;background:#ffd400;border-radius:5px;" onchange="getGradeClasses(this.value)">
                <option selected disabled value="">اختر الصف...</option>
                @foreach ($grades as $grade)
                    <option value="{{ $grade->id }}" {{ request('grade_id') == $grade->id ? 'selected' : '' }}>
                        {{ $grade->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Class select --}}
        <div class="col-6 col-md-4 d-flex align-items-center gap-2 mb-2">
            <label for="search_class_id" class="fw-bold text-primary">الفصل:</label>
            <select name="class_id" class="form-control" id="search_class_id" required
                style="font-size:16px;background:#ffd400;border-radius:5px;" onchange="getClassStudents(this.value)">
                <option selected disabled value="">اختر الفصل...</option>
                @if (request('grade_id'))
                    @foreach ($classes->where('grade_id', request('grade_id')) as $class)
                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>

        {{-- Student select --}}
        <div class="col-12 col-md-4 d-flex align-items-center gap-2 mb-2">
            <label for="student_id" class="fw-bold text-primary">الطالب:</label>
            <select name="student_id" class="form-control" id="student_select" required
                style="font-size:16px;background:#ffd400;border-radius:5px;">
                <option selected disabled>اختر الطالب...</option>
                @if (request('class_id'))
                    @php
                        $students = \App\Models\Student::where('class_id', request('class_id'))
                            ->where('school_id', $school->id)
                            ->get();
                    @endphp
                    @foreach ($students as $student)
                        <option value="{{ $student->id }}"
                            {{ request('student_id') == $student->id ? 'selected' : '' }}>
                            {{ $student->name }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>

        {{-- Date from --}}
        <div class="col-6 col-md-4 d-flex align-items-center gap-2 mb-2">
            <label class="fw-bold text-primary">من:</label>
            <input type="date" name="from" class="form-control" value="{{ request('from') }}">
        </div>

        {{-- Date to --}}
        <div class="col-6 col-md-4 d-flex align-items-center gap-2 mb-2">
            <label class="fw-bold text-primary">إلى:</label>
            <input type="date" name="to" class="form-control" value="{{ request('to') }}">
        </div>

        {{-- submit --}}
        <div class="col-12 col-md-4">
            <button class="btn btn-primary w-100">بحث</button>
        </div>
    </div>
</form>


<script>
    function getGradeClasses(gradeId) {
        const classSelect = document.getElementById('search_class_id');
        const studentSelect = document.getElementById('student_select');
        const schoolId = {{ $school->id }};

        classSelect.innerHTML = '<option selected value="" disabled>جاري التحميل...</option>';
        classSelect.disabled = true;

        studentSelect.innerHTML = '<option selected value="" disabled>اختر الطالب...</option>';
        studentSelect.disabled = true;

        fetch(`/public/public/schools/${schoolId}/grades/${gradeId}/classes`)
            .then(response => response.json())
            .then(data => {
                let options = '<option selected value="" disabled>اختر الفصل...</option>';
                data.forEach(cls => {
                    options += `<option value="${cls.id}">${cls.name}</option>`;
                });

                classSelect.innerHTML = options;
                classSelect.disabled = false;

                // If only one class, auto-load its students
                if (data.length === 1) {
                    classSelect.value = data[0].id;
                    getClassStudents(data[0].id);
                }
            })
            .catch(error => {
                console.error('Error loading classes:', error);
                classSelect.innerHTML = '<option value="">حدث خطأ أثناء التحميل</option>';
            });
    }

    function getClassStudents(classId) {
        const studentSelect = document.getElementById('student_select');
        const schoolId = {{ $school->id }};

        studentSelect.innerHTML = '<option selected value="" disabled>جاري التحميل...</option>';
        studentSelect.disabled = true;

        fetch(`/public/public/schools/${schoolId}/classes/${classId}/students`)
            .then(response => response.json())
            .then(data => {
                let options = '<option selected value="" disabled>اختر الطالب...</option>';
                data.forEach(student => {
                    const style = student.note ? 'style="background-color: red; color: white;"' : '';
                    options += `<option value="${student.id}" ${style}>${student.name}</option>`;
                });

                studentSelect.innerHTML = options;
                studentSelect.disabled = false;

                if (data.length === 1) {
                    studentSelect.value = data[0].id;
                }
            })
            .catch(error => {
                console.error('Error loading students:', error);
                studentSelect.innerHTML = '<option value="">حدث خطأ أثناء التحميل</option>';
            });
    }
</script>
