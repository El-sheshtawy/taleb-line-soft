<div class="modal fade" id="editSpecialUserModal" tabindex="-1" aria-labelledby="editSpecialUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSpecialUserModalLabel">تعديل مراقب أو مشرف</h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateSpecialUserForm" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_user_type" class="form-label">نوع المستخدم</label>
                            <select class="form-control" id="edit_user_type" name="user_type" required>
                                <option value="مراقب">مراقب</option>
                                <option value="مشرف">مشرف</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit_school_id" class="form-label">المدرسة</label>
                            <select class="form-control" id="edit_school_id" name="school_id" required>
                                @foreach($schoolAccounts as $school)
                                    <option value="{{ $school->id }}">{{ $school->school_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="edit_name" class="form-label">الاسم</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit_username" class="form-label">اسم المستخدم</label>
                            <input type="text" class="form-control" id="edit_username" name="username" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit_password" class="form-label">كلمة المرور</label>
                            <input type="text" class="form-control" id="edit_password" name="password">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" onclick="confirmDelete()">حذف</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">حفظ</button>
                    </div>

                </form>
                
                <form id="deleteSpecialUserForm" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>
</div>