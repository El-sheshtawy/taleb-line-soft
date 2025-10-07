<h5><span class="text-primary">المراقبون والمشرفون <span class="badge bg-primary text-light">{{count($specialUsers)}}</span> </span></h5>
<div class="table-responsive">
    <table class="table myTable table-secondary table-bordered table-striped align-middle text-nowrap">
        <thead>
            <tr class="text-center">
                <th class="p-1 text-center">م</th>
                <th class="p-1 text-center">النوع</th>
                <th class="p-1 text-center">المدرسة</th>
                <th class="p-1 text-center">اسم المستخدم</th>
                <th class="p-1 text-center">كلمة المرور</th>
                <th class="p-1 text-center">الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($specialUsers as $user)
                <tr class="text-center">
                    <td class="p-1">{{$loop->iteration}}</td>
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
                    <td class="p-1">
                        @php
                            $school = null;
                            if($user->school_id) {
                                $school = App\Models\SchoolAccount::find($user->school_id);
                            } elseif($user->profile && $user->profile->school_id) {
                                $school = App\Models\SchoolAccount::find($user->profile->school_id);
                            }
                            
                            $password = 'غير محدد';
                            if($school) {
                                if($user->user_type === 'مراقب') {
                                    $password = $school->viewer_password ?? 'غير محدد';
                                } elseif($user->user_type === 'مشرف') {
                                    $password = $school->supervisor_password ?? 'غير محدد';
                                }
                            }
                        @endphp
                        {{$password}}
                    </td>
                    <td class="p-1">
                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteSpecialUserModal" onclick="setDeleteAction('{{ route('admin.special-users.destroy', $user->id) }}')">حذف</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<button type="button" class="block btn btn-primary w-100 my-4 text-center" data-bs-toggle="modal" data-bs-target="#createSpecialUserModal">إضافة مراقب أو مشرف</button>

@include('admin.special_users.create')
@include('admin.special_users.delete')

<script>
function setDeleteAction(action) {
    document.getElementById('deleteSpecialUserForm').action = action;
}
</script>