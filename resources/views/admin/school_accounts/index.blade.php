<h5><span class="text-primary">الحسابات المدرسية <span class="badge bg-primary text-light">{{count($schoolAccounts)}}</span> </span></h5>
<div class="table-responsive">
    <table class="table myTable table-secondary table-bordered table-striped align-middle text-nowrap">
        <thead id="tableHeaders">
            <tr class="text-center">
                <th class="p-1 text-center"><span class="badge text-warning text-center" style="width:100%;text-align:left;font-weight:bold;">م</span></th>
                <th class="p-1 text-center">المدرسة</th>
                <th class="p-1 text-center">المرحلة</th>
                <th class="p-1 text-center">المنطقة </th>
                <th class="p-1 text-center">الطلاب</th>
                <th class="p-1 text-center">الحالة</th>
            </tr>
        </thead>
        <tbody>
            @foreach($schoolAccounts as $schoolAccount)
                <tr class="text-center">
                    <td class="p-1 text-center">{{$loop->iteration}}</td>
                    <td class="p-1">
                        <a href="#" class="m-1 edit-school-btn pe-auto" 
                            data-bs-toggle="modal" 
                            data-bs-target="#updateSchoolModal" 
                            data-school="{{ json_encode($schoolAccount) }}"
                            data-edit-action="{{ route('admin.school-accounts.update', $schoolAccount->id) }}"
                            data-delete-action="{{ route('admin.school-accounts.destroy', $schoolAccount->id) }}">
                            {{$schoolAccount->school_name}}
                        </a>
                    </td>
                    <td class="p-1">{{$schoolAccount->level->name ?? ''}}</td>
                    <td class="p-1">{{$schoolAccount->edu_region}}</td>
                    <td class="p-1 text-center">
                        <span class="badge bg-info">{{$schoolAccount->students_count}}</span>
                    </td>	
                    <td class="p-1 text-center">
                        <b class="text-{{$schoolAccount->subscription_state == 'active' ? 'success' : 'danger'}}">{{$schoolAccount->subscription_state == 'active' ? 'نشط' : 'غير نشط'}}</b>
                    </td>	
                </tr>
                
            @endforeach
        </tbody>
    </table>
</div>

<button  type="button" class="block btn btn-primary w-100 my-4 text-center" data-bs-toggle="modal" data-bs-target="#createSchoolModal">إنشاء حساب مدرسة</button>


@include('admin.school_accounts.create')
@include('admin.school_accounts.edit')
@include('admin.school_accounts.delete')

<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".edit-school-btn").forEach(button => {
        button.addEventListener("click", function () {
            let schoolData = JSON.parse(this.getAttribute("data-school"));

            document.getElementById("updateSchoolAccountForm").action = this.getAttribute("data-edit-action");
            document.getElementById("deleteSchoolAccountForm").action = this.getAttribute("data-delete-action");

            document.getElementById("edit_school_id").value = schoolData.id;
            document.getElementById("edit_school_name").value = schoolData.school_name;
            document.getElementById("edit_username").value = schoolData.username;
            document.getElementById("edit_edu_region").value = schoolData.edu_region;
            document.getElementById("edit_school_password").value = schoolData.password;
            document.getElementById("edit_absence_count").value = schoolData.absence_count;
            document.getElementById("edit_teachers_default_password").value = schoolData.teachers_default_password;
            document.getElementById("edit_students_default_password").value = schoolData.students_default_password;

            document.getElementById("edit_subscription_state").value = schoolData.subscription_state;
            document.getElementById("edit_follow_up_id").value = schoolData.follow_up_id || "";
            document.getElementById("edit_level_id").value = schoolData.level_id || "";

            document.getElementById("edit_logo_preview").src = schoolData.school_logo_url ?? "";
            document.getElementById("edit_banner_preview").src = schoolData.school_banner_url ?? "";
            
            document.getElementById("edit_viewer_name").value = schoolData.viewer_name || "";
            document.getElementById("edit_viewer_password").value = schoolData.viewer_password || "";
            document.getElementById("edit_supervisor_name").value = schoolData.supervisor_name || "";
            document.getElementById("edit_supervisor_password").value = schoolData.supervisor_password || "";
        });
    });
});
</script>

