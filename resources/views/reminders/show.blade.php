<div class="modal fade" id="showReminderModal" tabindex="-1" aria-labelledby="showReminderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" id="showReminderForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="showReminderModalLabel">تنبيه</h5>
                    <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-1">
                        <label for="edit_reminder_title" class="form-label">العنوان <i class="text-danger">*</i></label>
                        <input type="text" name="title" id="edit_reminder_title" class="form-control" disabled>
                    </div>

                    <div class="mb-1">
                        <label for="edit_reminder_content" class="form-label">الوصف</label>
                        <textarea name="content" id="edit_reminder_content" class="form-control" disabled rows="2"></textarea>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
