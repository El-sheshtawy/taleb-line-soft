<div class="modal fade" id="importStudentsModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('school.students.import') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">رفع ملف الطلاب</h5>
                    <button type="button" class="btn-close m-0" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row">
                    <div class="mb-3 col-md-12">
                        <label for="student_file" class="form-label">ارفق الملف : <i class="text-danger">*</i> <i class="text-gray">xlsx,xls,csv,ods</i></label>
                        <input type="file" name="file" id="student_file" class="form-control"  accept=".xlsx, .xls, .csv" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">إستيراد</button>
                </div>
            </div>
        </form>
    </div>
</div>
