<div class="modal fade" id="createAdminModal" tabindex="-1" aria-labelledby="createAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- Larger Modal -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createAdminModalLabel">إضافة حساب مشرف</h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="adminForm" action="{{ route('admin.admins.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-12 mb-1">
                            <label for="admin_name" class="form-label"> اسم المشرف <i class="text-danger">*</i></label>
                            <input type="text" class="form-control" id="admin_name" name="name" required>
                        </div>

                        <div class="col-md-12 mb-1">
                            <label for="admin_username" class="form-label">اسم المستخدم <i class="text-danger">*</i></label>
                            <input type="text" class="form-control" id="admin_username" name="username" required>
                        </div>

                        <div class="col-md-12 mb-1">
                            <label for="admin_password" class="form-label">كلمة المرور <i class="text-danger">*</i></label>
                            <input type="password" class="form-control" id="admin_password" name="password" required>
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