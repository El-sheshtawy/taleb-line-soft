<!-- School Account Update Modal -->
<div class="modal fade" id="updateFileModal" tabindex="-1" aria-labelledby="updateFileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateFileModalLabel"> ملف المدرسة</h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateFileForm" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <input type="hidden" id="edit_school_id" name="school_id">

                    <div class="row">
                        <div class="mb-1">
                            <label for="file" class="form-label">الملف (<span id="file_preview_name"></span>)</label>
                            <input type="file" name="file" id="file" class="form-control">
                        </div>
                        
                        <div class="mb-1">
                            <label for="edit_file_name" class="form-label">عنوان الملف</label>
                            <input type="text" name="name" id="edit_file_name" class="form-control" placeholder="اسم الملف الظاهري">
                        </div>
    
                        <div class="mb-1">
                            <label for="edit_file_note" class="form-label">ملاحظات</label>
                            <textarea name="note" id="edit_file_note" class="form-control" rows="2"></textarea>
                        </div>
                        
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">حفظ</button>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteFileModal" >حذف</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
