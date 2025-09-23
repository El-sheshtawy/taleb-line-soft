<div class="modal fade" id="createAcademicYearModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('admin.academic-years.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">إنشاء اشتراك جديد</h5>
                    <button type="button" class="btn-close m-0" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label for="academic_year_name">الاسم : <i class="text-danger">*</i></label>
                    <input type="text" name="name" id="academic_year_name" class="form-control mb-1" required>

                    <label for="academic_year_start_date">البداية : <i class="text-danger">*</i></label>
                    <input type="date" name="start_date" id="academic_year_start_date" class="form-control mb-1" required>

                    <label for="academic_year_end_date">النهاية : <i class="text-danger">*</i></label>
                    <input type="date" name="end_date"  id="academic_year_end_date" class="form-control mb-1" required>

                    <label for="academic_year_status">الحالة :</label>
                    <select name="status"  id="academic_year_status" class="form-control mb-1">
                        <option value="active">نشط</option>
                        <option value="inactive">غير نشط</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">إضافة</button>
                </div>
            </div>
        </form>
    </div>
</div>
