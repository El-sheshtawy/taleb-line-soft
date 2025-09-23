<div class="modal fade" id="editFollowupSystemModal" tabindex="-1" aria-labelledby="editFollowupSystemLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editFollowupSystemLabel">نظام المتابعة</h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <form id="editFollowupForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    
                    <div class="mb-1">
                        <label for="edit_followup_name" class="form-label">الاسم</label>
                        <input type="text" name="name" id="edit_followup_name" class="form-control" placeholder="ملاحظات">
                    </div>
                    <div class="mb-1">
                        <label class="form-label" for="edit_followup_notes">الملاحظات</label>
                        <textarea id="edit_followup_notes" name="notes" class="form-control" placeholder="ملاحظات"></textarea>
                    </div>
                    
                    <!-- Follow-Up Items Table -->
                    <h6 class="form-label">عناصر المتابعة</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered text-center align-middle">
                            <thead>
                                <tr class="text-nowrap">
                                    <th class="px-1">الحرف</th>
                                    <th class="px-1">المعني</th>
                                    <th class="px-1">النص</th>
                                    <th class="px-1">الخلفية</th>
                                    <th class="px-1">غياب؟</th>
                                    <th class="px-1">إجراء</th>
                                </tr>
                            </thead>
                            <tbody id="editFollowUpItemsContainer">
                                <!-- Items will be added dynamically here -->
                            </tbody>
                        </table>
                    </div>
                    <button type="button" class="btn btn-sm btn-success mt-2 mb-1" id="addEditFollowUpItem">إضافة عنصر جديد</button>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                    <button type="submit" class="btn btn-primary">حفظ</button>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteFollowupSystemModal">حذف</button>
                </div>
            </form>
        </div>
    </div>
</div>
