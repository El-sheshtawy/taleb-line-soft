<h5><span class="text-primary"> المشرفون <span class="badge bg-primary text-light">{{count($admins)}}</span> </span></h5>
<div class="table-responsive">
    <table class="table myTable table-secondary table-bordered table-striped align-middle text-nowrap">
        <thead id="tableHeaders">
            <tr class="text-center">
                <th class="p-1 text-center"><span class="badge text-warning text-center" style="width:100%;text-align:left;font-weight:bold;">م</span></th>
                <th class="p-1 text-center">الاسم</th>
                <th class="p-1 text-center">اسم المستخدم</th>
            </tr>
        </thead>
        <tbody>
            @foreach($admins as $admin)
                <tr class="text-center">
                    <td class="p-1 text-center">{{$loop->iteration}}</td>
                    <td class="p-1 text-center">
                        @if(auth()->user()->profile->id <= $admin->id)
                            <a href="#" class="m-1 edit-admin-btn pe-auto" 
                                data-bs-toggle="modal" 
                                data-bs-target="#updateAdminModal" 
                                data-admin="{{ json_encode($admin) }}"
                                data-admin-name = "{{$admin->profile->name}}"
                                data-edit-action="{{ route('admin.admins.update', $admin->profile) }}"
                                data-delete-action="{{ route('admin.admins.destroy', $admin->profile) }}">
                                {{$admin->profile->name}}
                            </a>
                        @else
                            {{$admin->profile->name}}
                        @endif
                    </td>
                    <td class="p-1 text-center">{{$admin->username}}</td>
                </tr>
                
            @endforeach
        </tbody>
    </table>
</div>

<button  type="button" class="block btn btn-primary w-100 my-4 text-center" data-bs-toggle="modal" data-bs-target="#createAdminModal">إضافة مشرف</button>


@include('admin.admins.create')
@include('admin.admins.edit')
@include('admin.admins.delete')

<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".edit-admin-btn").forEach(button => {
        button.addEventListener("click", function () {
            let adminData = JSON.parse(this.getAttribute("data-admin"));
            let adminNameData = this.getAttribute("data-admin-name");

            document.getElementById("updateAdminForm").action = this.getAttribute("data-edit-action");
            document.getElementById("deleteAdminForm").action = this.getAttribute("data-delete-action");

            document.getElementById("edit_admin_name").value = adminNameData;
            document.getElementById("edit_admin_username").value = adminData.username;
        });
    });
});
</script>

