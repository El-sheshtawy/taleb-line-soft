<div class="modal fade" id="createSpecialUserModal" tabindex="-1" aria-labelledby="createSpecialUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createSpecialUserModalLabel">إضافة مراقب أو مشرف</h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="specialUserForm" action="{{ route('admin.special-users.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-1">
                            <label for="user_type" class="form-label">نوع المستخدم <i class="text-danger">*</i></label>
                            <select class="form-control" id="user_type" name="user_type" required>
                                <option value="">اختر النوع</option>
                                <option value="مراقب">مراقب (عرض فقط)</option>
                                <option value="مشرف">مشرف (عرض + إجراءات المعلمين)</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-1">
                            <label for="school_id" class="form-label">المدرسة <i class="text-danger">*</i></label>
                            <select class="form-control" id="school_id" name="school_id" required>
                                <option value="">اختر المدرسة</option>
                                @foreach($schoolAccounts as $school)
                                    <option value="{{ $school->id }}">{{ $school->school_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12 mb-1">
                            <label for="name" class="form-label">الاسم <i class="text-danger">*</i></label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>

                        <div class="col-md-6 mb-1">
                            <label for="passport_id" class="form-label">الرقم المدني <i class="text-danger">*</i></label>
                            <input type="text" class="form-control" id="passport_id" name="passport_id" required maxlength="12">
                        </div>

                        <div class="col-md-6 mb-1">
                            <label for="phone_number" class="form-label">رقم الهاتف</label>
                            <input type="text" class="form-control" id="phone_number" name="phone_number" maxlength="8">
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
                            <label for="subject" class="form-label">المادة</label>
                            <input type="text" class="form-control" id="subject" name="subject">
                        </div>

                        <div class="col-md-6 mb-1">
                            <label for="nationality_id" class="form-label">الجنسية <i class="text-danger">*</i></label>
                            <select class="form-control" id="nationality_id" name="nationality_id" required>
                                <option value="">اختر الجنسية</option>
                                @foreach($nationalities as $nationality)
                                    <option value="{{ $nationality->id }}">{{ $nationality->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">حفظ</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>