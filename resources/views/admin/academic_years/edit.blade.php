<div class="modal fade" id="editAcademicYearModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="" id="updateAcademicYearForm">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">العام الدراسي</h5>
                    <button type="button" class="btn-close m-0" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label for="edit_academic_year_name">الاسم : <i class="text-danger">*</i></label>
                    <input type="text" name="name" id="edit_academic_year_name" class="form-control mb-1" required>

                    <label for="edit_academic_year_start_date">تاريخ البداية : <i class="text-danger">*</i></label>
                    <input type="date" name="start_date" id="edit_academic_year_start_date" class="form-control mb-1" required>

                    <label for="edit_academic_year_end_date">تاريخ النهاية : <i class="text-danger">*</i></label>
                    <input type="date" name="end_date"  id="edit_academic_year_end_date" class="form-control mb-1" required>

                    <label for="edit_academic_year_status">الحالة :</label>
                    <select name="status"  id="edit_academic_year_status" class="form-control mb-1">
                        <option value="active">نشط</option>
                        <option value="inactive">غير نشط</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ</button>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAcademicYearModal">حذف</button>
                </div>
            </div>
        </form>
    </div>
</div>
