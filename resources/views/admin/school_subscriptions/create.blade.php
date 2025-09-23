<div class="modal fade" id="createSchoolSubscriptionModal" tabindex="-1" aria-labelledby="createSubscriptionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createSubscriptionModalLabel">انشاء عام دراسي جديد</h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <form action="{{ route('admin.school-subscriptions.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-1">
                        <label for="school_id" class="form-label">المدرسة</label>
                        <select name="school_id" class="form-control" required>
                            <option  disabled selected >إختر المدرسة</option>
                            @foreach($schoolAccounts as $school)
                                <option value="{{ $school->id }}">{{ $school->school_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-1">
                        <label for="start_date" class="form-label">تاريخ البداية</label>
                        <input type="date" name="start_date" class="form-control" required>
                    </div>
                    <div class="mb-1">
                        <label for="end_date" class="form-label">تاريخ النهاية</label>
                        <input type="date" name="end_date" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">حفظ</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                </div>
            </form>
        </div>
    </div>
</div>
