<!-- School Account Update Modal -->
<div class="modal fade" id="updateSchoolModal" tabindex="-1" aria-labelledby="updateSchoolModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateSchoolModalLabel"> بيانات المدرسة</h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateSchoolAccountForm" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <input type="hidden" id="edit_school_id" name="school_id">
                    <input type="hidden" id="delete_image_type" name="delete_image_type" value="">

                    <div class="row">
                        <div class="col-md-12 mb-1">
                            <label for="edit_school_name" class="form-label">اسم المدرسة <i class="text-danger">*</i></label>
                            <input type="text" class="form-control" id="edit_school_name" name="school_name" required>
                        </div>

                        <div class="col-md-6 mb-1">
                            <label for="edit_school_logo_url" class="form-label">رابط شعار المدرسة <i class="text-gray">png, jpg, jpeg, svg</i></label>
                            <input type="file" class="form-control" id="edit_school_logo_url" name="school_logo_url" accept="image/*">
                            <img id="edit_logo_preview" class="mt-2" style="max-height:200px; max-width: 100%">
                            <button type="button" class="btn btn-sm btn-danger" onclick="deleteImage('logo')">حذف الشعار</button>
                        </div>
                        
                        <div class="col-md-6 mb-1">
                            <label for="edit_school_banner_url" class="form-label">رابط شعار المدرسة <i class="text-gray">png, jpg, jpeg, svg</i></label>
                            <input type="file" class="form-control" id="edit_school_banner_url" name="school_banner_url" accept="image/*">
                            <img id="edit_banner_preview" class="mt-2" style="max-height:200px; max-width: 100%">
                            <button type="button" class="btn btn-sm btn-danger" onclick="deleteImage('banner')">حذف اللافتة</button>
                        </div>

                        <div class="col-md-6 mb-1">
                            <label for="edit_username" class="form-label">اسم الادمن <i class="text-danger">*</i></label>
                            <input type="text" class="form-control" id="edit_username" name="username" required>
                        </div>

                        <div class="col-md-6 mb-1">
                            <label for="edit_school_password" class="form-label">كلمة المرور</label>
                            <input type="text" class="form-control" id="edit_school_password" name="password">
                        </div>

                        <div class="col-md-6 mb-1">
                            <label for="edit_subscription_state" class="form-label">حالة الاشتراك</label>
                            <select class="form-control" id="edit_subscription_state" name="subscription_state">
                                <option value="active">نشط</option>
                                <option value="inactive">غير نشط</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-1">
                            <label for="edit_edu_region" class="form-label">المنطقة التعليمية</label>
                            <input type="text" class="form-control" id="edit_edu_region" name="edu_region">
                        </div>

                        <div class="col-md-6 mb-1">
                            <label for="edit_follow_up_id" class="form-label">نظام المتابعة <i class="text-danger">*</i></label>
                            <select class="form-control" id="edit_follow_up_id" name="follow_up_id" required>
                                <option value="">اختر</option>
                                @foreach($followUps as $followUp)
                                    <option value="{{ $followUp->id }}">
                                         {!! $followUp->getItemsBadgesHtml() !!}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-1">
                            <label for="edit_level_id" class="form-label">المرحلة</label>
                            <select class="form-control" id="edit_level_id" name="level_id">
                                <option value="">اختر المرحلة الدراسية</option>
                                @foreach($levels as $level)
                                    <option value="{{ $level->id }}">{{ $level->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    
                        <div class="col-md-6 mb-1">
                            <label for="edit_teachers_default_password" class="form-label">كلمة مرور المعلمين الافتراضية</label>
                            <input type="text" class="form-control" id="edit_teachers_default_password" name="teachers_default_password" placeholder="اتركه فارغًا إذا لم ترغب">
                        </div>
                        
                        <div class="col-md-6 mb-1">
                            <label for="edit_students_default_password" class="form-label">كلمة مرور الطلاب الافتراضية</label>
                            <input type="text" class="form-control" id="edit_students_default_password" name="students_default_password" placeholder="اتركه فارغًا إذا لم ترغب">
                        </div>
                        
                        <div class="col-md-6 mb-1">
                            <label for="edit_viewer_name" class="form-label">اسم المراقب</label>
                            <input type="text" class="form-control" id="edit_viewer_name" name="viewer_name">
                        </div>
                        
                        <div class="col-md-6 mb-1">
                            <label for="edit_viewer_password" class="form-label">كلمة مرور المراقب</label>
                            <input type="text" class="form-control" id="edit_viewer_password" name="viewer_password">
                        </div>
                        
                        <div class="col-md-6 mb-1">
                            <label for="edit_supervisor_name" class="form-label">اسم المشرف</label>
                            <input type="text" class="form-control" id="edit_supervisor_name" name="supervisor_name">
                        </div>
                        
                        <div class="col-md-6 mb-1">
                            <label for="edit_supervisor_password" class="form-label">كلمة مرور المشرف</label>
                            <input type="text" class="form-control" id="edit_supervisor_password" name="supervisor_password">
                        </div>
                        
                        <div class="col-md-12 mb-1">
                            <label for="edit_absence_count" class="form-label">نصاب الغياب</label>
                            <input type="number" class="form-control" id="edit_absence_count" name="absence_count">
                        </div>
                        
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">حفظ</button>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteSchoolModal" >حذف</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
<script>
function deleteImage(imageType) {
    if(confirm('هل أنت متأكد من حذف هذه الصورة؟')) {
        console.log("dfnsbfbsdfbsmdbfm");
        
        const form = document.getElementById('updateSchoolAccountForm');
        const schoolId = document.getElementById('edit_school_id').value;
        const deleteImageType = document.getElementById('delete_image_type');
        deleteImageType.value = imageType;
        console.log(schoolId);
        
        
        fetch(`{{ route("admin.delete-image-byid", ":schoolId") }}`.replace(':schoolId', schoolId), {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
       'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    body: JSON.stringify({
        delete_image_type: imageType
    })
}).then(response => response.json())
        .then(data => {
            if (data.success) {
                if(imageType === 'logo') {
                    document.getElementById('edit_logo_preview').src = '';
                    document.getElementById('edit_school_logo_url').value = '';
                } else if(imageType === 'banner') {
                    document.getElementById('edit_banner_preview').src = '';
                    document.getElementById('edit_school_banner_url').value = '';
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
        })
    }
}

</script>