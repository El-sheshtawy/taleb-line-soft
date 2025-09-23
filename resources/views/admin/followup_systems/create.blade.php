<div class="modal fade" id="createFollowupSystemModal" tabindex="-1" aria-labelledby="createFollowupSystemLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createFollowupSystemLabel">إضافة نظام متابعة جديد</h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <form action="{{ route('admin.follow-ups.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    
                    <div class="mb-1">
                        <label for="followup_name" class="form-label">الاسم</label>
                        <input type="text" name="name" id="followup_name" class="form-control" placeholder="ملاحظات">
                    </div>
                    <div class="mb-1">
                        <label for="followup_notes" class="form-label">الملاحظات</label>
                        <textarea name="notes" id="followup_notes" class="form-control" placeholder="ملاحظات"></textarea>
                    </div>
                    
                    <!-- Follow-Up Items Table -->
                    <h6 class="form-label">عناصر المتابعة</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered text-center align-middle">
                            <thead>
                                <tr class="text-nowrap text-center">
                                    <th class="p-1">الحرف</th>
                                    <th class="p-1">المعني </th>
                                    <th class="p-1">النص</th>
                                    <th class="p-1">الخلفية</th>
                                    <th class="p-1">غياب؟</th>
                                    <th class="p-1">إجراء</th>
                                </tr>
                            </thead>
                            <tbody id="followUpItemsContainer" class=" text-center ">
                                <!-- Items will be added dynamically here -->
                            </tbody>
                        </table>
                    </div>
                    <button type="button" class="btn btn-sm btn-success my-2" id="addFollowUpItem">إضافة عنصر جديد</button>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                    <button type="submit" class="btn btn-primary">إضافة</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const container = document.getElementById("followUpItemsContainer");
    const addButton = document.getElementById("addFollowUpItem");

    addButton.addEventListener("click", function() {
        const index = container.children.length;

        const itemHTML = `
            <tr>
                <td calss="p-1"><input type="text" name="follow_up_items[${index}][letter]" class="form-control" placeholder="حرف الحالة"></td>
                <td calss="p-1"><input type="text" name="follow_up_items[${index}][meaning]" class="form-control" placeholder="معنى الحالة"></td>
                <td calss="p-1"><input type="color" name="follow_up_items[${index}][text_color]" class="form-control form-control-color"></td>
                <td calss="p-1"><input type="color" name="follow_up_items[${index}][background_color]" class="form-control form-control-color"></td>
                <td calss="p-1"><input type="checkbox" name="follow_up_items[${index}][is_absent]" class="form-check-input"></td>
                <td calss="p-1"><button type="button" class="btn btn-sm btn-danger removeItem">حذف</button></td>
            </tr>
        `;

        const row = document.createElement("tr");
        row.innerHTML = itemHTML;
        container.appendChild(row);

        // Add event listener to remove button
        row.querySelector(".removeItem").addEventListener("click", function() {
            row.remove();
        });
    });
});
</script>
