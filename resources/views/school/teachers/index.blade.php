<h5><span class="text-primary">المعلمون<span class="badge bg-primary text-light">{{count($teachers)}}</span> </span></h5>
<div class="row g-3 mb-1 mt-1">
    <div class="col-md-4 d-flex align-items-center gap-2  mt-1mt-1">
        <label for="search_subject" class="fw-bold text-primary">المادة:</label>
        <select id="search_subject" class="form-control" name="subject"
                style="font-size:16px;background:#ffd400;border-radius:5px;position: unset;"
                onchange="submitFilterForm(this.value)">
            <option value="all" {{ request('subject') == 'all' || !request()->has('subject') ? 'selected' : '' }}>عرض الكل</option>
            <option value="head_of_department" {{ request('subject') == 'head_of_department' ? 'selected' : '' }}>رؤساء الأقسام</option>
            <option value="supervisor" {{ request('subject') == 'supervisor' ? 'selected' : '' }}>المشرفون</option>
            @foreach($teachers->unique('subject') as $teacher)
                @if($teacher->subject)
                    <option value="{{ $teacher->subject }}" {{ request('subject') == $teacher->subject ? 'selected' : '' }}>{{ $teacher->subject }}</option>
                @endif
            @endforeach
        </select>
    </div>
</div>
<div class="table-responsive">
    <table class="table myTable table-secondary table-bordered table-striped  text-nowrap">
        <thead>
            <tr class ="text-center">
                <th class="p-1 text-center">م</th>
                <th class="p-1 text-center">الاسم </th>
                <th class="p-1 text-center">المادة </th>
                <th class="p-1 text-center">الرقم المدني</th>
                <th class="p-1 text-center">الهاتف</th>
    
            </tr>
        </thead>
        <tbody>
        @foreach($teachers as $teacher)
            <tr class="text-center teacher-row" data-subject="{{ $teacher->subject }}" data-head="{{ $teacher->head_of_department ? '1' : '0' }}" data-supervisor="{{ $teacher->supervisor ? '1' : '0' }}">
                <td class="p-1">{{$loop->iteration}}</td>
                <td class="p-1 text-end responsive-cell">
                    @if(in_array(auth()->user()->user_type, ['مراقب', 'مشرف']))
                        <span class="text-{{$teacher->head_of_department ? 'success' : 'primary'}}">
                            {{$teacher->name}}
                        </span>
                    @else
                        <a href="#" class="edit-teacher-btn cell-link text-{{$teacher->head_of_department ? 'success' : 'primary'}}" 
                            data-bs-toggle="modal" 
                            data-bs-target="#editTeacherModal" 
                            data-teacher="{{ json_encode($teacher) }}"
                            data-edit-action="{{ route('school.teachers.update', $teacher->id) }}"
                            data-delete-action="{{ route('school.teachers.destroy', $teacher->id) }}">
                             {{$teacher->name}}
                        </a>
                    @endif
                </td>
                <td class="p-1">{{$teacher->subject}}</td>
                <td class="p-1">{{$teacher->passport_id }}</td>
                <td class="p-1">{{$teacher->phone_number }}</td>
            </tr>
        @endforeach
    </tbody>
    </table>
</div>
@if(!in_array(auth()->user()->user_type, ['مراقب', 'مشرف']))
<x-action-button>
    <div class="d-flex gap-2 mb-1">
        <button  type="button" class="block btn btn-primary w-50 mt-4 text-center" data-bs-toggle="modal" data-bs-target="#createTeacherModal">إضافة معلم  </button>
        <button  type="button" class="block btn btn-primary w-50 mt-4 text-center" data-bs-toggle="modal" data-bs-target="#importTeachersModal">إستيراد معلمين <i class="fas fa-upload"></i></button>
        <button  type="button" class="block btn btn-success w-50 mt-4 text-center"><a href="{{asset('templates/المعلمين.xlsx')}}" class="w-100 h-100 text-white" download> تحميل قالب المعلمين <i class="fa fa-download"></i></a></button>
    </div>
</x-action-button>
@endif

@include('school.teachers.create')
@include('school.teachers.import')
@include('school.teachers.edit')
@include('school.teachers.delete')

<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".edit-teacher-btn").forEach(button => {
        button.addEventListener("click", function () {
            let teacherData = JSON.parse(this.getAttribute("data-teacher"));
            
            document.getElementById("updateTeacherForm").action = this.getAttribute("data-edit-action");
            document.getElementById("deleteTeacherForm").action = this.getAttribute("data-delete-action");
            
            document.getElementById("edit_teacher_name").value = teacherData.name;
            document.getElementById("edit_teacher_passport_id").value = teacherData.passport_id;
            document.getElementById("edit_teacher_phone_number").value = teacherData.phone_number;
            document.getElementById("edit_teacher_subject").value = teacherData.subject;
            document.getElementById("edit_teacher_nationality_id").value = teacherData.nationality_id;
            
            document.getElementById("edit_teacher_head_of_department").checked = teacherData.head_of_department;
            document.getElementById("edit_teacher_supervisor").checked = teacherData.supervisor;
        });
    });
});
function submitFilterForm(value) {
    const url = new URL(window.location.href);
    if (value === 'all') {
        url.searchParams.delete('subject');
    } else {
        url.searchParams.set('subject', value);
    }
    window.location.href = url.toString();
}




</script>

