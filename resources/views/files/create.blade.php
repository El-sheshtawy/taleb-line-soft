<div class="modal fade" id="addFileModal" tabindex="-1" aria-labelledby="addFileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('files.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addFileModalLabel">إضافة ملف</h5>
                    <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-1">
                        <label for="file" class="form-label">الملف <i class="text-danger">*</i></label>
                        <input type="file" name="file" id="file" class="form-control" required>
                    </div>
                    
                    <div class="mb-1">
                        <label for="name" class="form-label">عنوان الملف</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="اسم الملف الظاهري">
                    </div>

                    <div class="mb-1">
                        <label for="note" class="form-label">ملاحظات</label>
                        <textarea name="note" id="note" class="form-control" rows="2"></textarea>
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