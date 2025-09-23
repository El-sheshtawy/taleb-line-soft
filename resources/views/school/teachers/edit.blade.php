<div class="modal fade" id="editTeacherModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="" id="updateTeacherForm">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">بيانات المعلم</h5>
                    <button type="button" class="btn-close m-0" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row">
                     <div class="mb-3 col-md-12">
                        <label class="form-label" for="edit_teacher_name">الاسم : <i class="text-danger">*</i></label>
                        <input type="text" name="name" id="edit_teacher_name" class="form-control" required>
                    </div>
                    
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="edit_teacher_passport_id">الرقم المدني  : <i class="text-danger">*</i></label>
                        <input type="text" name="passport_id" id="edit_teacher_passport_id" minlength="12" maxlength="12" class="form-control" required>
                    </div>
                    
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="edit_teacher_phone_number">رقم الهاتف  : <i class="text-danger">*</i></label>
                        <input type="text" name="phone_number" id="edit_teacher_phone_number" class="form-control" minlength="8" maxlength="8">
                    </div>
                    
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="edit_teacher_nationality_id">الجنسية: <i class="text-danger">*</i></label>
                        <select name="nationality_id" id="edit_teacher_nationality_id" class="form-control" required>
                            <option value="">اختر الجنسية</option>
                            @foreach($nationalities as $nationality)
                                <option value="{{ $nationality->id }}">{{ $nationality->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="edit_teacher_subject">المادة: <i class="text-danger">*</i></label>
                        <input type="text" name="subject" id="edit_teacher_subject" class="form-control" required>
                    </div>
                    
                    
                    <div class="mb-3 col-md-6 form-check">
                        <input type="checkbox" name="head_of_department" id="edit_teacher_head_of_department">
                        <label class="form-check-label" for="edit_teacher_head_of_department">
                            رئيس قسم
                        </label>
                    </div>
                
                    <div class="mb-3 col-md-6 form-check">
                        <input type="checkbox" name="supervisor" id="edit_teacher_supervisor">
                        <label class="form-check-label" for="edit_teacher_supervisor">
                            مشرف
                        </label>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ</button>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteTeacherModal">حذف</button>
                </div>
            </div>
        </form>
    </div>
</div>
