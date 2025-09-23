<h5><span class="text-primary"> نظم المتابعة <span class="badge bg-primary text-light">{{ count($followUps) }}</span> </span></h5>
<div class="table-responsive">
    <table class="table myTable table-secondary table-bordered table-striped align-middle text-nowrap">
        <thead>
            <tr class="text-center">
                <th class="p-1 text-center"><span class="badge text-warning text-center" style="width:100%; text-align:left; font-weight:bold;">م</span></th>
                <th class="p-1 text-center">الاسم</th>
                <th class="p-1 text-center">الملاحظات</th>
                <th class="p-1 text-center">تفاصيل الحضور</th>
                <th class="p-1 text-center">الغياب</th>
            </tr>
        </thead>
        <tbody>
            @foreach($followUps as $followUp)
                <tr class="edit-followup-btn" data-bs-toggle="modal" 
                            data-bs-target="#editFollowupSystemModal" 
                            data-followup="{{ json_encode($followUp) }}"
                            data-edit-action="{{ route('admin.follow-ups.update', $followUp->id) }}"
                            data-delete-action="{{ route('admin.follow-ups.destroy', $followUp->id) }}">
                    <td class="p-1 text-center">{{ $loop->iteration }}</td>
        
                    <td class="p-1 text-center">{{ $followUp->name }}</td>
                    <td class="p-1 text-center">{{ $followUp->notes }}</td>
                    <td class="p-1 text-center"> {!! $followUp->getItemsBadgesHtml() !!} </td>
                    <td class="p-1 text-center"> {!! $followUp->getAbsentItemBadgeHtml() !!}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<button type="button" class="block btn btn-primary w-100 my-4 text-center" data-bs-toggle="modal" data-bs-target="#createFollowupSystemModal">إنشاء نظام متابعة</button>

@include('admin.followup_systems.create')
@include('admin.followup_systems.edit')
@include('admin.followup_systems.delete')

<script>
document.addEventListener("DOMContentLoaded", function () {
    const editContainer = document.getElementById("editFollowUpItemsContainer");
    const addEditButton = document.getElementById("addEditFollowUpItem");

    function populateExistingItems(followupData) {
        editContainer.innerHTML = '';

        if (followupData.items && followupData.items.length > 0) {
            followupData.items.forEach((item, index) => {
                addItemToEditTable(index, item);
            });
        }
    }

    function addItemToEditTable(index, itemData = {}) {
        const itemHTML = `
            <tr>
                <td><input type="text" name="follow_up_items[${index}][letter]" class="form-control" placeholder="حرف الحالة" value="${itemData.letter || ''}"></td>
                <td><input type="text" name="follow_up_items[${index}][meaning]" class="form-control" placeholder="معنى الحالة" value="${itemData.meaning || ''}"></td>
                <td><input type="color" name="follow_up_items[${index}][text_color]" class="form-control form-control-color" value="${itemData.text_color || '#000000'}"></td>
                <td><input type="color" name="follow_up_items[${index}][background_color]" class="form-control form-control-color" value="${itemData.background_color || '#ffffff'}"></td>
                <td><input type="checkbox" name="follow_up_items[${index}][is_absent]" class="form-check-input" ${itemData.is_absent == 1 ? 'checked' : ''}></td>
                <td><button type="button" class="btn btn-sm btn-danger removeItem">حذف</button></td>
            </tr>
        `;

        const row = document.createElement("tr");
        row.innerHTML = itemHTML;
        editContainer.appendChild(row);

        row.querySelector(".removeItem").addEventListener("click", function() {
            row.remove();
        });
    }

    document.querySelectorAll(".edit-followup-btn").forEach(button => {
        button.addEventListener("click", function () {
            let followupData = JSON.parse(this.getAttribute("data-followup"));
            document.getElementById("editFollowupForm").action = this.getAttribute("data-edit-action");
            document.getElementById("deleteFollowupForm").action = this.getAttribute("data-delete-action");
            document.getElementById("edit_followup_name").value = followupData.name;
            document.getElementById("edit_followup_notes").value = followupData.notes;

            populateExistingItems(followupData);
        });
    });

    addEditButton.addEventListener("click", function() {
        const index = editContainer.children.length;
        addItemToEditTable(index);
    });
});
</script>
