
<!-- School Account Creation Modal -->
<div class="modal fade" id="createSchoolModal" tabindex="-1" aria-labelledby="createSchoolModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- Larger Modal -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createSchoolModalLabel">إضافة حساب مدرسة</h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="schoolAccountForm" action="{{ route('admin.school-accounts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-12 mb-1">
                            <label for="school_name" class="form-label"> اسم المدرسة <i class="text-danger">*</i></label>
                            <input type="text" class="form-control" id="school_name" name="school_name" required>
                        </div>
                        
                        <div class="col-md-6 mb-1">
                            <label for="school_logo_url" class="form-label"> شعار المدرسة <i class="text-gray">png, jpg, jpeg, svg</i></label>
                            <input type="file" class="form-control" id="school_logo_url" name="school_logo_url" accept="image/*">
                        </div>
                        
                        <div class="col-md-6 mb-1">
                            <label for="school_banner_url" class="form-label"> لافتة المدرسة <i class="text-gray">png, jpg, jpeg, svg</i></label>
                            <input type="file" class="form-control" id="school_banner_url" name="school_banner_url" accept="image/*">
                        </div>

                        <div class="col-md-6 mb-1">
                            <label for="username" class="form-label">اسم المستخدم <i class="text-danger">*</i></label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>

                        <div class="col-md-6 mb-1">
                            <label for="password" class="form-label">كلمة المرور <i class="text-danger">*</i></label>
                            <input type="text" class="form-control" id="password" name="password" required>
                        </div>

                        <div class="col-md-6 mb-1">
                            <label for="subscription_state" class="form-label">حالة الاشتراك</label>
                            <select class="form-control" id="subscription_state" name="subscription_state">
                                <option value="active">نشط</option>
                                <option value="inactive">غير نشط</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-1">
                            <label for="edu_region" class="form-label">المنطقة التعليمية</label>
                            <input type="text" class="form-control" id="edu_region" name="edu_region">
                        </div>

<
                        <div class="col-md-6 mb-1">
                            <label for="follow_up_id" class="form-label">نظام المتابعة  <i class="text-danger">*</i></label>
                            <select class="form-control" id="follow_up_id" name="follow_up_id" required>
                                <option value="">اختر</option>
                                @foreach($followUps as $followUp)
                                    <option value="{{ $followUp->id }}">{!! $followUp->getItemsBadgesHtml() !!}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-1">
                            <label for="level_id" class="form-label">المرحلة</label>
                            <select class="form-control" id="level_id" name="level_id">
                                <option value="">المرحلة</option>
                                @foreach($levels as $level)
                                    <option value="{{ $level->id }}">{{ $level->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-1">
                            <label for="teachers_default_password" class="form-label">كلمة مرور المعلمين الافتراضية</label>
                            <input type="text" class="form-control" id="teachers_default_password" name="teachers_default_password">
                        </div>
                        
                        <div class="col-md-6 mb-1">
                            <label for="students_default_password" class="form-label">كلمة مرور الطلاب الافتراضية</label>
                            <input type="text" class="form-control" id="students_default_password" name="students_default_password">
                        </div>
                        
                        <div class="col-md-12 mb-1">
                            <label for="absence_count" class="form-label">نصاب الغياب</label>
                            <input type="number" class="form-control" id="absence_count" name="absence_count">
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">حفظ</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>