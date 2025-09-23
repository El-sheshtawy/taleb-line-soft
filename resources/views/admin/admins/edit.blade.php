<div class="modal fade" id="updateAdminModal" tabindex="-1" aria-labelledby="updateAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateAdminModalLabel">حساب المشرف</h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateAdminForm" action="" method="POST">
                    @csrf
                    @method('PUT')

                    <input type="hidden" id="edit_admin_id" name="admin_id">

                    <div class="row">
                        <div class="col-md-12 mb-1">
                            <label for="edit_admin_name" class="form-label">اسم المشرف <i class="text-danger">*</i></label>
                            <input type="text" class="form-control" id="edit_admin_name" name="admin_name" required>
                        </div>

                        <div class="col-md-12 mb-1">
                            <label for="edit_admin_username" class="form-label">اسم المستخدم <i class="text-danger">*</i></label>
                            <input type="text" class="form-control" id="edit_admin_username" name="username" required>
                        </div>

                        <div class="col-md-12 mb-1">
                            <label for="edit_admin_password" class="form-label">كلمة المرور</label>
                            <input type="password" class="form-control" id="edit_admin_password" name="password" placeholder="اتركه فارغًا إذا لم ترغب في تغييره">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">حفظ</button>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAdminModal">حذف</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
