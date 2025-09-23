<div class="modal fade" id="updateReminderModal" tabindex="-1" aria-labelledby="updateReminderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" id="updateReminderForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="updateReminderModalLabel">تنبيه</h5>
                    <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-1">
                        <label for="edit_reminder_title" class="form-label">العنوان <i class="text-danger">*</i></label>
                        <input type="text" name="title" id="edit_reminder_title" class="form-control">
                    </div>

                    <div class="mb-1">
                        <label for="edit_reminder_content" class="form-label">الوصف</label>
                        <textarea name="content" id="edit_reminder_content" class="form-control" rows="2"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">حفظ</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                </div>
            </form>
        </div>
    </div>
</div>