<h5><span class="text-primary">إعدادات المدرسة </span></h5>

<form id="schoolAccountForm" action="{{ route('school.update', $school) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-md-6 mb-1">
            <label for="school_name" class="form-label"> اسم المدرسة <i class="text-danger">*</i></label>
            <input type="text" class="form-control" id="school_name" value="{{ $school->school_name }}"
                name="school_name" {{ in_array(auth()->user()->user_type, ['مراقب', 'مشرف']) ? 'readonly' : '' }} required>
        </div>

        <div class="col-md-6 mb-1">
            <label for="edu_region" class="form-label">المنطقة التعليمية</label>
            <input type="text" class="form-control" id="edu_region" value="{{ $school->edu_region }}"
                name="edu_region" {{ in_array(auth()->user()->user_type, ['مراقب', 'مشرف']) ? 'readonly' : '' }}>
        </div>

        @if(!in_array(auth()->user()->user_type, ['مراقب', 'مشرف']))
        <div class="col-md-6 mb-1">
            <label for="school_logo_url" class="form-label"> شعار المدرسة <i class="text-gray">png, jpg, jpeg,
                    svg</i></label>
            <input type="file" class="form-control" id="school_logo_url" name="school_logo_url" accept="image/*">

            @if ($school->school_logo_url)
                <div class="mt-2 d-flex align-items-center gap-1">
                    <img src="{{ $school->school_logo_url }}" alt="School Logo"
                        style="max-width: 100px; max-height: 100px;" class="img-thumbnail me-2">
                    <button type="button" class="btn btn-sm btn-danger" onclick="deleteImage('logo')">حذف
                        الشعار</button>
                </div>
            @endif
        </div>

        <div class="col-md-6 mb-1">
            <label for="school_banner_url" class="form-label"> لافتة المدرسة <i class="text-gray">png, jpg, jpeg,
                    svg</i></label>
            <input type="file" class="form-control" id="school_banner_url" name="school_banner_url" accept="image/*">

            @if ($school->school_banner_url)
                <div class="mt-2 d-flex align-items-center gap-1">
                    <img src="{{ $school->school_banner_url }}" alt="School Banner"
                        style="max-width: 200px; max-height: 100px;" class="img-thumbnail me-2">
                    <button type="button" class="btn btn-sm btn-danger" onclick="deleteImage('banner')">حذف
                        اللافتة</button>
                </div>
            @endif
        </div>
        @else
        <div class="col-md-6 mb-1">
            <label class="form-label">شعار المدرسة</label>
            @if ($school->school_logo_url)
                <div class="mt-2">
                    <img src="{{ $school->school_logo_url }}" alt="School Logo"
                        style="max-width: 100px; max-height: 100px;" class="img-thumbnail">
                </div>
            @else
                <p class="text-muted">لا يوجد شعار</p>
            @endif
        </div>

        <div class="col-md-6 mb-1">
            <label class="form-label">لافتة المدرسة</label>
            @if ($school->school_banner_url)
                <div class="mt-2">
                    <img src="{{ $school->school_banner_url }}" alt="School Banner"
                        style="max-width: 200px; max-height: 100px;" class="img-thumbnail">
                </div>
            @else
                <p class="text-muted">لا توجد لافتة</p>
            @endif
        </div>
        @endif

        <!-- Rest of your form fields -->
        <!--
        <div class="col-md-6 mb-1">
            <label for="username" class="form-label">اسم المستخدم <i class="text-danger">*</i></label>
            <input type="text" class="form-control" id="username"  value="{{ $school->username }}" name="username" required>
        </div>

        <div class="col-md-6 mb-1">
            <label for="password" class="form-label">كلمة المرور <i class="text-danger">*</i></label>
            <input type="text" class="form-control" id="password" name="password" value="{{ $school->password }}">
        </div>


        <div class="col-md-6 mb-1">
            <label for="follow_up_id" class="form-label">نظام المتابعة  <i class="text-danger">*</i></label>
            <select class="form-control" id="follow_up_id" name="follow_up_id" required>
                <option value="">اختر</option>
                @foreach ($followUps as $followUp)
<option value="{{ $followUp->id }}" {{ $school->follow_up_id == $followUp->id ? 'selected' : '' }}>{!! $followUp->getItemsBadgesHtml() !!}</option>
@endforeach
            </select>
        </div>
        -->

        @if(!in_array(auth()->user()->user_type, ['مراقب', 'مشرف']))
        <div class="col-md-6 mb-1 mt-4">
            <label for="table_general" class="form-label"> <i class="text-gray"> الجدول العام PDF</i></label>
            <br>
            <label for="table_general" class="form-label">
                {{ basename($school->table_general) }}
            </label>
            <input type="file" class="form-control" id="table_general" name="table_general"
                accept=".pdf,application/pdf">

            @if ($school->table_general)
                <div class="mt-2 d-flex align-items-center gap-1">
                    <a href="{{ $school->table_general }}" target="_blank" class="btn btn-sm btn-primary">
                        عرض الجدول العام
                    </a>
                </div>
            @endif
        </div>
        <div class="col-md-6 mb-1">
            <label for="table_classes" class="form-label"> <i class="text-gray"> جدول الحصص PDF</i></label>
            <br>
            <label for="table_general" class="form-label">
                {{ basename($school->table_classes) }}
            </label>
            <input type="file" class="form-control" id="table_classes" name="table_classes"
                accept=".pdf,application/pdf">

            @if ($school->table_classes)
                <div class="mt-2 d-flex align-items-center gap-1">
                    <a href="{{ $school->table_classes }}" target="_blank" class="btn btn-sm btn-primary">
                        عرض جدول الحصص
                    </a>
                </div>
            @endif
        </div>
        @else
        <div class="col-md-6 mb-1 mt-4">
            <label class="form-label">الجدول العام</label>
            @if ($school->table_general)
                <div class="mt-2">
                    <a href="{{ $school->table_general }}" target="_blank" class="btn btn-sm btn-primary">
                        عرض الجدول العام
                    </a>
                </div>
            @else
                <p class="text-muted">لا يوجد جدول</p>
            @endif
        </div>
        <div class="col-md-6 mb-1">
            <label class="form-label">جدول الحصص</label>
            @if ($school->table_classes)
                <div class="mt-2">
                    <a href="{{ $school->table_classes }}" target="_blank" class="btn btn-sm btn-primary">
                        عرض جدول الحصص
                    </a>
                </div>
            @else
                <p class="text-muted">لا يوجد جدول</p>
            @endif
        </div>
        @endif

        <div class="col-md-6 mb-1">
            <label for="absence_count" class="form-label">نصاب الغياب </label>
            <input type="number" class="form-control" id="absence_count" value="{{ $school->absence_count }}"
                name="absence_count" {{ in_array(auth()->user()->user_type, ['مراقب', 'مشرف']) ? 'readonly' : '' }}>
        </div>

        <div class="col-md-6 mb-1">
            <label for="teachers_default_password" class="form-label">كلمة مرور المعلمين الافتراضية</label>
            <input type="text" class="form-control" id="teachers_default_password"
                value="{{ $school->teachers_default_password }}" name="teachers_default_password" {{ in_array(auth()->user()->user_type, ['مراقب', 'مشرف']) ? 'readonly' : '' }}>
        </div>

        <div class="col-md-6 mb-1">
            <label for="students_default_password" class="form-label">كلمة مرور الطلاب الافتراضية</label>
            <input type="text" class="form-control" id="students_default_password"
                value="{{ $school->students_default_password }}" name="students_default_password" {{ in_array(auth()->user()->user_type, ['مراقب', 'مشرف']) ? 'readonly' : '' }}>
        </div>
        
        <div class="col-md-6 mb-1">
            <div class="row">
                <div class="col-6">
                    <label for="viewer_username" class="form-label">اسم المراقب</label>
                    <input type="text" class="form-control" id="viewer_username" 
                        name="viewer_username" value="{{ $school->viewer_name }}" {{ in_array(auth()->user()->user_type, ['مراقب', 'مشرف']) ? 'readonly' : '' }}>
                </div>
                <div class="col-6">
                    <label for="viewer_password" class="form-label">كلمة مرور المراقب</label>
                    <input type="text" class="form-control" id="viewer_password" placeholder="12345678"
                        name="viewer_password" value="{{ $school->viewer_password }}" {{ in_array(auth()->user()->user_type, ['مراقب', 'مشرف']) ? 'readonly' : '' }}>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-1">
            <div class="row">
                <div class="col-6">
                    <label for="supervisor_username" class="form-label">اسم المشرف</label>
                    <input type="text" class="form-control" id="supervisor_username" placeholder="s123"
                        name="supervisor_username" value="{{ $school->supervisor_name }}" {{ in_array(auth()->user()->user_type, ['مراقب', 'مشرف']) ? 'readonly' : '' }}>
                </div>
                <div class="col-6">
                    <label for="supervisor_password" class="form-label">كلمة مرور المشرف</label>
                    <input type="text" class="form-control" id="supervisor_password" placeholder=""
                        name="supervisor_password" value="{{ $school->supervisor_password }}" {{ in_array(auth()->user()->user_type, ['مراقب', 'مشرف']) ? 'readonly' : '' }}>
                </div>
            </div>
        </div>
    </div>

    @if(!in_array(auth()->user()->user_type, ['مراقب', 'مشرف']))
    <button type="submit" class="btn btn-primary px-3 mb-4">حفظ</button>
    @endif
</form>

<script>
    function deleteImage(imageType) {
        if (confirm('هل أنت متأكد من حذف هذه الصورة؟')) {
            fetch('{{ route('school.delete-image', $school) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        image_type: imageType
                    })
                })
                .then(response => {
                    if (response.ok) {
                        window.location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف الصورة');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('حدث خطأ أثناء حذف الصورة');
                });
        }
    }
</script>
