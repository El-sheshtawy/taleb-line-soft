<div class="modal fade" id="editClassModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="" id="updateClassForm">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تعديل الفصل</h5>
                    <button type="button" class="btn-close m-0" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    <label for="edit_class_grade_id">الصف : <i class="text-danger">*</i></label>
                    <select name="grade_id"  id="edit_class_grade_id" required class="form-control mb-1">
                        <option selected disabled>اختر الصف</option>
                         @foreach($grades as $grade)
                            <option value="{{$grade->id}}">{{$grade->name}}</option>
                         @endforeach
                    </select>
                    
                     <label for="edit_class_name">الفصل : <i class="text-danger">*</i></label>
                    <input type="text" name="name" id="edit_class_name" required class="form-control mb-1" required>
                    
                    
                    <label for="edit_class_meeting_room_link">رابط غرفة الاجتماع:</label>
                    <input type="text" name="meeting_room_link" id="edit_class_meeting_room_link" 
                           class="form-control mb-1" placeholder="أدخل رابط الاجتماع">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ</button>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteClassModal">حذف</button>
                </div>
            </div>
        </form>
    </div>
</div>
