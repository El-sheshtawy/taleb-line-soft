<form action="{{ route('teacher.index') }}" method="GET" id="dateForm">
    <div class="row g-2 align-items-center justify-content-center w-100">
        <div class="col-md-4 d-flex align-items-center gap-2">
            <label for="date" class="fw-bold text-primary">التاريخ:</label>
            <input type="date" class="form-control" name="date" id="date" 
                   style="font-size:16px;background:#ffd400;border-radius:5px;position: unset;"
                   style="background-color: yellow;" 
                   value="{{ request('date', $date) }}" onchange="this.form.submit()">
        </div>
    
        <div class="col-6 col-md-4 col-sm-6 d-flex align-items-center gap-2">
            <label for="search_grade_id" class="fw-bold text-primary">الصف:</label>
            <select name="grade_id" class="form-control" id="search_grade_id" 
                    style="font-size:16px;background:#ffd400;border-radius:5px;position: unset;" 
                    onchange="getGradeClasses(this.value)">
                <option selected disabled>اختر الصف...</option>
                @foreach($grades as $grade)
                    <option value="{{ $grade->id }}" {{ request('grade_id') == $grade->id ? 'selected' : '' }}>
                        {{ $grade->name }}
                    </option>
                @endforeach
            </select>
        </div>
    
        <div class="col-6 col-md-4 col-sm-6 d-flex align-items-center gap-2">
            <label for="search_class_id" class="fw-bold text-primary">الفصل:</label>
            <select name="class_id" class="form-control" id="search_class_id" 
                    style="font-size:16px;background:#ffd400;border-radius:5px;position: unset;"
                    onchange="this.form.submit()">
                <option selected disabled>اختر الفصل...</option>
                @if(request('grade_id'))
                    @foreach($classes as $class)
                        @if($class->grade_id == request('grade_id'))
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endif
                    @endforeach
                @endif
            </select>
        </div>
    </div>
</form>
    
<script>
    function getGradeClasses(gradeId) {
        const classSelect = document.getElementById('search_class_id');
        if (!gradeId || gradeId === "اختر الصف...") {
            classSelect.innerHTML = '<option selected disabled>اختر الفصل...</option>';
            return;
        }
        
        let schoolId = {{$school->id}}
    
        classSelect.innerHTML = '<option value="">جاري التحميل...</option>';
        classSelect.disabled = true;
        fetch(`{{ url('/public/schools/${schoolId}/grades/${gradeId}/classes') }}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                let options = '<option selected disabled>اختر الفصل...</option>';
                data.forEach(cls => {
                    options += `<option value="${cls.id}">${cls.name}</option>`;
                });
                
                classSelect.innerHTML = options;
                classSelect.disabled = false;
                
                if (data.length === 1) {
                    classSelect.value = data[0].id;
                    document.getElementById('dateForm').submit();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                classSelect.innerHTML = '<option value="">حدث خطأ أثناء التحميل</option>';
            });
    }
</script>