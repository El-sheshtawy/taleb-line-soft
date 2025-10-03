<!-- Student Details Modal -->
<div class="modal fade" id="studentDetailsModal" tabindex="-1" aria-labelledby="studentDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="studentDetailsModalLabel">تفاصيل الطالب</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold">الاسم الكامل</label>
                        <p id="modal_student_name" class="form-control-plaintext border p-2 bg-light"></p>
                    </div>
                    @if(auth()->user()->user_type == 'school' || !$school->hide_passport_id)
                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold">الرقم المدني</label>
                            <p id="modal_student_passport" class="form-control-plaintext border p-2 bg-light"></p>
                        </div>
                    @endif
                    @if(auth()->user()->user_type == 'school' || !$school->hide_phone1)
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">رقم الهاتف</label>
                            <p id="modal_student_phone" class="form-control-plaintext border p-2 bg-light"></p>
                        </div>
                    @endif
                    <div class="col-6 mb-3">
                        <label class="form-label fw-bold">الجنس</label>
                        <p id="modal_student_gender" class="form-control-plaintext border p-2 bg-light"></p>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label fw-bold">الصف</label>
                        <p id="modal_student_grade" class="form-control-plaintext border p-2 bg-light"></p>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label fw-bold">الفصل</label>
                        <p id="modal_student_class" class="form-control-plaintext border p-2 bg-light"></p>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold">الملاحظات</label>
                        <p id="modal_student_note" class="form-control-plaintext border p-2 bg-light"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>