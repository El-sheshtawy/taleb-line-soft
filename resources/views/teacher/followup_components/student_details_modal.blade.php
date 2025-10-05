<!-- Student Details Modal -->
<div class="modal fade" id="studentDetailsModal" tabindex="-1" aria-labelledby="studentDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="studentDetailsModalLabel">تفاصيل الطالب</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row">
                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold text-primary">الاسم الكامل</label>
                        <p id="modal_student_name" class="form-control border-0 shadow-sm bg-light"></p>
                    </div>
                    @if(!in_array(auth()->user()->user_type, ['مراقب']))
                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold text-primary">الرقم المدني</label>
                        <p id="modal_student_passport" class="form-control border-0 shadow-sm bg-light"></p>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label fw-bold text-primary">رقم الهاتف</label>
                        <p id="modal_student_phone" class="form-control border-0 shadow-sm bg-light"></p>
                    </div>
                    @endif
                    <div class="col-6 mb-3">
                        <label class="form-label fw-bold text-primary">الجنس</label>
                        <p id="modal_student_gender" class="form-control border-0 shadow-sm bg-light"></p>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label fw-bold text-primary">الصف</label>
                        <p id="modal_student_grade" class="form-control border-0 shadow-sm bg-light"></p>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label fw-bold text-primary">الفصل</label>
                        <p id="modal_student_class" class="form-control border-0 shadow-sm bg-light"></p>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold text-primary">الملاحظات</label>
                        <p id="modal_student_note" class="form-control border-0 shadow-sm bg-light"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-primary px-4" data-bs-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>