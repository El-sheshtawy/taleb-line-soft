<h5><span class="text-primary">المراقبون والمشرفون <span class="badge bg-primary text-light">{{count($specialUsers)}}</span> </span></h5>
<div class="table-responsive">
    <table class="table myTable table-secondary table-bordered table-striped align-middle text-nowrap">
        <thead>
            <tr class="text-center">
                <th class="p-1 text-center">م</th>
                <th class="p-1 text-center">الاسم</th>
                <th class="p-1 text-center">النوع</th>
                <th class="p-1 text-center">المدرسة</th>
                <th class="p-1 text-center">اسم المستخدم</th>
                <th class="p-1 text-center">كلمة المرور</th>
            </tr>
        </thead>
        <tbody>
            @foreach($specialUsers as $user)
                <tr class="text-center">
                    <td class="p-1">{{$loop->iteration}}</td>
                    <td class="p-1">
                        <a href="#" class="edit-special-user-btn" 
                            data-bs-toggle="modal" 
                            data-bs-target="#editSpecialUserModal" 
                            data-user="{{ json_encode($user) }}"
                            data-edit-action="{{ route('admin.special-users.update', $user->id) }}"
                            data-delete-action="{{ route('admin.special-users.destroy', $user->id) }}">
                            {{$user->profile ? $user->profile->name : $user->username}}
                        </a>
                    </td>
                    <td class="p-1">
                        <span class="badge bg-{{$user->user_type === 'مراقب' ? 'info' : 'warning'}}">
                            {{$user->user_type}}
                        </span>
                    </td>
                    <td class="p-1">
                    @if($user->school_id)
                        {{App\Models\SchoolAccount::find($user->school_id)->school_name ?? 'غير محدد'}}
                    @elseif($user->profile && $user->profile->school_id)
                        {{App\Models\SchoolAccount::find($user->profile->school_id)->school_name ?? 'غير محدد'}}
                    @else
                        غير محدد
                    @endif
                </td>
                    <td class="p-1">{{$user->username}}</td>
                    <td class="p-1">{{$user->plain_password ?? 'غير محدد'}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<button type="button" class="block btn btn-primary w-100 my-4 text-center" data-bs-toggle="modal" data-bs-target="#createSpecialUserModal">إضافة مراقب أو مشرف</button>

@include('admin.special_users.create')
@include('admin.special_users.edit')
@include('admin.special_users.delete')

<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".edit-special-user-btn").forEach(button => {
        button.addEventListener("click", function () {
            let userData = JSON.parse(this.getAttribute("data-user"));
            
            document.getElementById("updateSpecialUserForm").action = this.getAttribute("data-edit-action");
            document.getElementById("deleteSpecialUserForm").action = this.getAttribute("data-delete-action");
            
            document.getElementById("edit_name").value = userData.profile.name;
            document.getElementById("edit_username").value = userData.username;
            document.getElementById("edit_user_type").value = userData.user_type;
            document.getElementById("edit_school_id").value = userData.profile.school_id;
            document.getElementById("edit_passport_id").value = userData.profile.passport_id;
            document.getElementById("edit_phone_number").value = userData.profile.phone_number || '';
            document.getElementById("edit_subject").value = userData.profile.subject || '';
            document.getElementById("edit_nationality_id").value = userData.profile.nationality_id;
        });
    });
});

// Add delete confirmation
function confirmDelete() {
    if (confirm('هل أنت متأكد من حذف هذا المستخدم؟')) {
        const form = document.getElementById('deleteSpecialUserForm');
        
        // Add event listener to reload page after form submission
        form.addEventListener('submit', function() {
            setTimeout(function() {
                window.location.reload();
            }, 1000);
        });
        
        form.submit();
    }
}
</script>