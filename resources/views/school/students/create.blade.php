<div class="modal fade" id="createStudentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('school.students.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">إضافة طالب </h5>
                    <button type="button" class="btn-close m-0" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row">
                    <div class="mb-3 col-md-6">
                        <label for="student_name" class="form-label">الاسم : <i class="text-danger">*</i></label>
                        <input type="text" name="name" id="student_name" class="form-control" required>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="student_passport_id">الرقم المدني : <i class="text-danger">*</i></label>
                        <input type="text" name="passport_id" id="student_passport_id" minlength="12" maxlength="12" class="form-control" required>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="student_phone_number">رقم الهاتف  : <i class="text-danger">*</i></label>
                        <input type="text" name="phone_number" id="student_phone_number" minlength="8" maxlength="8"  class="form-control">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="student_phone_number_2">رقم هاتف إضافي</label>
                        <input type="text" name="phone_number_2" id="student_phone_number_2" minlength="8" maxlength="8"  class="form-control">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="student_grade_id"> الصف  : <i class="text-danger">*</i></label>
                        <select name="grade_id"  id="student_grade_id" class="form-control" onchange="getGradeClasses(this.value, 'student_class_id')">
                            <option selected disabled>اختر الصف...</option>
                             @foreach($grades as $grade)
                                <option value="{{$grade->id}}">{{$grade->name}}</option>
                             @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="student_class_id">الفصل  : <i class="text-danger">*</i></label>
                         <select name="class_id"  id="student_class_id" class="form-control">
                            <option selected disabled>اختر الفصل</option>
                        </select>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="student_gender">الجنس  : <i class="text-danger">*</i></label>
                        <select name="gender" id="student_gender" class="form-control" required>
                            <option value="1">ذكر</option>
                            <option value="0">أنثى</option>
                        </select>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="student_nationality_id">الجنسية  : <i class="text-danger">*</i></label>
                        <select name="nationality_id" id="student_nationality_id" class="form-control">
                            <option value="">اختر الجنسية</option>
                            @foreach($nationalities as $nationality)
                                <option value="{{ $nationality->id }}">{{ $nationality->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3 col-md-12">
                        <label class="form-label" for="student_note">ملاحظة</label>
                        <textarea name="note" id="student_note" class="form-control"></textarea>
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