<div class="modal fade" id="deleteAcademicYearModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="deleteAcademicYearForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تأكيد الحذف</h5>
                    <button type="button" class="btn-close m-0" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>هل أنت متأكد أنك تريد حذف هذا العام الدراسي؟ لا يمكن التراجع عن هذا الإجراء.</p><hr>
                    <p>من الممكن ان يكون هذا العام الدراسي مرتبط ببعض الفصول, الطلاب, أو المعلمين, هل تريد حذفهم أيضا؟</p>
                    <div class="">
                        <input type="checkbox" class="form-check-input" id="deleteRelated" name="delete_related">
                        <label class="form-check-label text-danger" for="deleteRelated">نعم، أريد حذف جميع البيانات المرتبطة</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-danger">حذف</button>
                </div>
            </div>
        </form>
    </div>
</div>
