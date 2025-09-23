<div class="modal fade" id="createClassModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('school.classes.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">إضافة فصل</h5>
                    <button type="button" class="btn-close m-0" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    <label for="class_grade_id">الصف : <i class="text-danger">*</i></label>
                    <select name="grade_id"  id="class_grade_id" class="form-control mb-1">
                        <option selected disabled>اختر الصف</option>
                         @foreach($grades as $grade)
                            <option value="{{$grade->id}}">{{$grade->name}}</option>
                         @endforeach
                    </select>
                    
                    <label for="class_name">الفصل : <i class="text-danger">*</i></label>
                    <input type="text" name="name" id="class_name" class="form-control mb-1" required>
                    
                    <label for="meeting_room_link">رابط غرفة الاجتماع:</label>
                    <input type="text" name="meeting_room_link" id="meeting_room_link" 
                           class="form-control mb-1" placeholder="أدخل رابط الاجتماع">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ</button>
                </div>
            </div>
        </form>
    </div>
</div>
