<h5><span class="text-primary">الفصول<span class="badge bg-primary text-light">{{count($classes)}}</span> </span></h5>

<div class="row g-3 mt-1 mb-1">
    <div class="col-md-4 d-flex align-items-center gap-2  mt-1">
        <label for="gradeFilter" class="fw-bold text-primary">الصف:</label>
        <select id="gradeFilter" class="form-control" style="font-size:16px;background:#ffd400;border-radius:5px;position: unset;">
            <option value="all" selected>عرض الكل</option>
            @foreach($classes->unique('grade_id') as $class)
                @if($class->grade)
                    <option value="{{ $class->grade_id }}">{{ $class->grade->name }}</option>
                @endif
            @endforeach
        </select>
    </div>
</div>


<table class="table myTable table-secondary table-bordered table-striped">
    <thead>
        <tr class="text-center">
            <th class="p-1 text-center">م</th>
            <th class="p-1 text-center">الصف</th>
            <th class="p-1 text-center">الفصل</th>
            <th class="p-1 text-center">الطلاب</th>
            <th class="p-1 text-center">رابط الغرفة</th>
        </tr>
    </thead>
    <tbody id="tableBody">
        @foreach($classes as $class)
            <tr class="text-center" data-grade-id="{{ $class->grade_id }}">
                <td class="p-1 text-center">{{$loop->iteration}}</td>
                <td class="p-1 text-center">{{$class->grade->name}}</td>
                <td class="p-1 text-center">
                    @if(in_array(auth()->user()->user_type, ['مراقب', 'مشرف']))
                        {{$class->name}}
                    @else
                        <a href="#" class="edit-class-btn" 
                            data-bs-toggle="modal" 
                            data-bs-target="#editClassModal" 
                            data-class="{{ json_encode($class) }}"
                            data-edit-action="{{ route('school.classes.update', $class->id) }}"
                            data-delete-action="{{ route('school.classes.destroy', $class->id) }}">
                            {{$class->name}}
                        </a>
                    @endif
                </td>
                <td class="p-1 text-center">
                    <span class="badge bg-info">{{ $class->students_count ?? 0 }}</span>
                </td>
                <td class="p-1 text-center">
                    @if(!empty($class->meeting_room_link))
                        <a href="{{ $class->meeting_room_link }}" target="_blank" class="btn btn-primary btn-sm">فتح</a>
                    @else
                        <span class="text-muted">لا يوجد</span>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@if(!in_array(auth()->user()->user_type, ['مراقب', 'مشرف']))
<button  type="button" class="block btn btn-primary w-100 my-4 text-center" data-bs-toggle="modal" data-bs-target="#createClassModal">إنشاء فصل </button>
@endif


@include('school.classes.create')
@include('school.classes.edit')
@include('school.classes.delete')

<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".edit-class-btn").forEach(button => {
        button.addEventListener("click", function () {
            let classData = JSON.parse(this.getAttribute("data-class"));
            document.getElementById("updateClassForm").action = this.getAttribute("data-edit-action");
            document.getElementById("deleteClassForm").action = this.getAttribute("data-delete-action");

            document.getElementById("edit_class_name").value = classData.name;
            document.getElementById("edit_class_grade_id").value = classData.grade_id;
            document.getElementById("edit_class_meeting_room_link").value = classData.meeting_room_link;
        });
    });
    
    // Filter Grades
    const gradeFilter = document.getElementById("gradeFilter");
    const tableRows = document.querySelectorAll("#tableBody tr");

    gradeFilter.addEventListener("change", function () {
        const selectedGrade = this.value;

        tableRows.forEach(row => {
            const rowGrade = row.getAttribute("data-grade-id");
            if (selectedGrade === "all" || selectedGrade === rowGrade) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });
});


</script>