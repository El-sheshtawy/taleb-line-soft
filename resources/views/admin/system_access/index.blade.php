<h5><span class="text-primary">إعدادات الوصول للنظام</span></h5>

<div class="card">
    <div class="card-header">
        <h6 class="mb-0">أيام الأسبوع المسموح بها للدخول</h6>
        <small class="text-muted">يطبق على المعلمين والمشرفين والمراقبين فقط</small>
    </div>
    <div class="card-body">
        <form id="systemAccessForm" action="{{ route('admin.system-access.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="sunday" name="sunday" value="1" {{ $settings->sunday ?? true ? 'checked' : '' }}>
                        <label class="form-check-label" for="sunday">الأحد</label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="monday" name="monday" value="1" {{ $settings->monday ?? true ? 'checked' : '' }}>
                        <label class="form-check-label" for="monday">الاثنين</label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="tuesday" name="tuesday" value="1" {{ $settings->tuesday ?? true ? 'checked' : '' }}>
                        <label class="form-check-label" for="tuesday">الثلاثاء</label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="wednesday" name="wednesday" value="1" {{ $settings->wednesday ?? true ? 'checked' : '' }}>
                        <label class="form-check-label" for="wednesday">الأربعاء</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="thursday" name="thursday" value="1" {{ $settings->thursday ?? true ? 'checked' : '' }}>
                        <label class="form-check-label" for="thursday">الخميس</label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="friday" name="friday" value="1" {{ $settings->friday ?? true ? 'checked' : '' }}>
                        <label class="form-check-label" for="friday">الجمعة</label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="saturday" name="saturday" value="1" {{ $settings->saturday ?? true ? 'checked' : '' }}>
                        <label class="form-check-label" for="saturday">السبت</label>
                    </div>
                </div>
            </div>
            
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">حفظ الإعدادات</button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('systemAccessForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'تم الحفظ!',
                text: data.message,
                confirmButtonText: 'حسناً'
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'خطأ!',
            text: 'حدث خطأ أثناء حفظ الإعدادات',
            confirmButtonText: 'حسناً'
        });
    });
});
</script>