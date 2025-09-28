<div class="modal fade" id="createTeacherModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('school.teachers.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">إضافة معلم </h5>
                    <button type="button" class="btn-close m-0" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row">
                    <div class="mb-3 col-md-12">
                        <label for="teacher_name" class="form-label">الاسم : <i class="text-danger">*</i></label>
                        <input type="text" name="name" id="teacher_name" class="form-control" required>
                    </div>
                    
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="teacher_passport_id">الرقم المدني  : <i class="text-danger">*</i></label>
                        <input type="text" name="passport_id" id="teacher_passport_id" minlength="12" maxlength="12" class="form-control" required>
                    </div>
                    
                    
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="teacher_phone_number">رقم الهاتف  : <i class="text-danger">*</i></label>
                        <input type="text" name="phone_number" id="teacher_phone_number" minlength="8" maxlength="8" class="form-control" required>
                    </div>
                    
                    <div class="mb-3 col-md-6">
                        <label for="teacher_nationality_id" class="form-label">الجنسية  : <i class="text-danger">*</i></label>
                        <select name="nationality_id" id="teacher_nationality_id" class="form-control" required>
                            <option value="">اختر الجنسية</option>
                            @foreach($nationalities as $nationality)
                                <option value="{{ $nationality->id }}">{{ $nationality->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3 col-md-6">
                        <label for="teacher_subject" class="form-label">المادة  : <i class="text-danger">*</i></label>
                        <select name="subject" id="teacher_subject" class="form-control" required>
                            <option value="">اختر المادة</option>
                            <option value="اللغة العربية">اللغة العربية</option>
                            <option value="اللغة الإنجليزية">اللغة الإنجليزية</option>
                            <option value="الرياضيات">الرياضيات</option>
                            <option value="العلوم">العلوم</option>
                            <option value="التربية الإسلامية">التربية الإسلامية</option>
                            <option value="التربية البدنية">التربية البدنية</option>
                            <option value="التربية الفنية">التربية الفنية</option>
                            <option value="التربية الموسيقية">التربية الموسيقية</option>
                            <option value="التاريخ">التاريخ</option>
                            <option value="الجغرافيا">الجغرافيا</option>
                            <option value="الحاسوب">الحاسوب</option>
                            <option value="الفيزياء">الفيزياء</option>
                            <option value="الكيمياء">الكيمياء</option>
                            <option value="الأحياء">الأحياء</option>
                        </select>
                    </div>
                    
                    <div class="mb-3 col-md-6 form-check">
                        <input type="checkbox" name="head_of_department" id="teacher_head_of_department">
                        <label class="form-check-label" for="teacher_head_of_department">
                            رئيس قسم
                        </label>
                    </div>
                
                    <div class="mb-3 col-md-6 form-check">
                        <input type="checkbox" name="supervisor" id="teacher_supervisor">
                        <label class="form-check-label" for="teacher_supervisor">
                            مشرف
                        </label>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ</button>
                </div>
            </div>
        </form>
    </div>
</div>
