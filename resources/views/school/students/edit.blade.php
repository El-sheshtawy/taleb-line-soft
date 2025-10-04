<div class="modal fade" id="editStudentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="" id="updateStudentForm">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">بيانات الطالب</h5>
                    <button type="button" class="btn-close m-0" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row">
                     <div class="mb-3 col-md-6">
                        <label class="form-label" for="edit_student_name">الاسم : <i class="text-danger">*</i></label>
                        <input type="text" name="name" id="edit_student_name" class="form-control" required>
                    </div>

                    @if(auth()->user()->user_type == 'school' || !$school->hide_passport_id)
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="edit_student_passport_id">الرقم المدني : <i class="text-danger">*</i></label>
                        <input type="text" name="passport_id" id="edit_student_passport_id" minlength="12" maxlength="12" class="form-control" required>
                    </div>
                    @endif
                    
                    @if(auth()->user()->user_type == 'school' || !$school->hide_phone1)
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="edit_student_phone_number">رقم الهاتف  : <i class="text-danger">*</i></label>
                        <input type="text" name="phone_number" id="edit_student_phone_number" minlength="8" maxlength="8"  class="form-control">
                    </div>
                    
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="edit_student_phone_number_2">رقم هاتف إضافي</label>
                        <input type="text" name="phone_number_2" id="edit_student_phone_number_2" minlength="8" maxlength="8"  class="form-control">
                    </div>
                    @endif
                    
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="edit_student_grade_id"> الصف  : <i class="text-danger">*</i></label>
                        <select name="grade_id"  id="edit_student_grade_id" class="form-control" onchange="getGradeClasses(this.value, 'edit_student_class_id')">
                            <option selected disabled>اختر الصف</option>
                             @foreach($grades as $grade)
                                <option value="{{$grade->id}}">{{$grade->name}}</option>
                             @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="edit_student_class_id">الفصل  : <i class="text-danger">*</i></label>
                         <select name="class_id"  id="edit_student_class_id" class="form-control">
                        </select>
                    </div>
                    
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="edit_student_gender">الجنس  : <i class="text-danger">*</i></label>
                        <select name="gender" id="edit_student_gender" class="form-control" required>
                            <option value="1">ذكر</option>
                            <option value="0">أنثى</option>
                        </select>
                    </div>
                    
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="edit_student_nationality_id">الجنسية: <i class="text-danger">*</i></label>
                        <select name="nationality_id" id="edit_student_nationality_id" class="form-control">
                            <option value="">اختر الجنسية</option>
                            @foreach($nationalities as $nationality)
                                <option value="{{ $nationality->id }}">{{ $nationality->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3 col-md-12">
                        <label class="form-label" for="edit_student_note">ملاحظة</label>
                        <textarea name="note" id="edit_student_note" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ</button>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteStudentModal">حذف</button>
                </div>
            </div>
        </form>
    </div>
</div>