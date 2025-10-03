@if (auth()->user()->user_type == 'admin')
    <div class="navbar-bottom">
        <div>
            <a href="{{ route('admin.index') }}">
                <i class="ri-shield-user-fill" style="color: #0d6efd;"></i>
                الادمن
            </a>
            <a href="{{ route('attendance.index') }}">
                <i class="ri-graduation-cap-fill" style="color: #0d6efd;"></i>
                كشوفات
            </a>
        </div>
    </div>
@endif

@if (auth()->user()->user_type == 'school')
    <div class="navbar-bottom">
        <div>
            <a href="{{ route('school.index') }}"><i class="ri-shield-user-fill" style="color: #0d6efd;"></i> الادارة</a>
            <a href="{{ route('teacher.index') }}"> <i class="ri-user-star-fill" style="color: #0d6efd;"></i> المعلم
            </a>
            <a href="{{ route('attendance.index') }}"> <i class="ri-graduation-cap-fill" style="color: #0d6efd;"></i>
                كشوفات </a>
            <a href="{{ route('school.statistics.index') }}">
                <i class="ri-parent-fill" style="color: #0d6efd;"></i>
                احصائيات
            </a>
            <a href="#!"> <i class="ri-file-chart-fill" style="color: #0d6efd;"></i> تقارير </a>
            <a href="{{ route('files.index') }}"> <i class="ri-folder-fill" style="color: #0d6efd;"></i> الملفات </a>
            <a href="{{ route('reminders.index') }}"> <i class="ri-notification-3-fill" style="color: #0d6efd;"></i>
                رسائل </a>
            <a href="#!"> <i class="ri-parent-fill" style="color: #0d6efd;"></i> ولى امر </a>
        </div>
    </div>
@endif

@if (in_array(auth()->user()->user_type, ['teacher', 'مراقب', 'مشرف']))
    <div class="navbar-bottom">
        <div>
            @if (in_array(auth()->user()->user_type, ['مراقب', 'مشرف']))
                <a href="{{ route('school.index') }}"> <i class="ri-shield-user-fill" style="color: #0d6efd;"></i> الادارة </a>
            @endif
            <a href="{{ route('teacher.index') }}"> <i class="ri-user-star-fill" style="color: #0d6efd;"></i> المعلم
            </a>
            <a href="{{ route('attendance.index') }}"> <i class="ri-graduation-cap-fill" style="color: #0d6efd;"></i>
                كشوفات </a>
            <a href="{{ route('school.statistics.index') }}">
                <i class="ri-bar-chart-fill" style="color: #0d6efd;"></i>
                احصائيات
            </a>
            <a href="#!"> <i class="ri-file-chart-fill" style="color: #0d6efd;"></i> تقارير </a>
            <a href="{{ route('files.index') }}"> <i class="ri-folder-fill" style="color: #0d6efd;"></i> الملفات </a>
            <a href="{{ route('reminders.index') }}"> <i class="ri-notification-3-fill" style="color: #0d6efd;"></i>
                رسائل </a>
            @if (in_array(auth()->user()->user_type, ['مراقب', 'مشرف']))
                <a href="#!"> <i class="ri-parent-fill" style="color: #0d6efd;"></i> ولى امر </a>
            @endif
        </div>
    </div>
@endif

@if (auth()->user()->user_type == 'student')
    <div class="navbar-bottom">
        <div>
            <a href="#!"> <i class="ri-parent-fill" style="color: #0d6efd;"></i> ولى امر </a>
            <a href="#!"> <i class="ri-file-chart-fill" style="color: #0d6efd;"></i> تقارير </a>
            <a href="#!"> <i class="ri-file-chart-fill" style="color: #0d6efd;"></i> اجتماع اونلاين </a>
        </div>
    </div>
@endif


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const currentPath = window.location.pathname;
        const links = document.querySelectorAll('.navbar-bottom a');

        // Remove active state from all links first
        links.forEach(link => {
            const icon = link.querySelector('i');
            link.classList.remove('bg-danger', 'text-white');
            if (icon) {
                icon.style.color = '#0d6efd';
            }
        });

        // Find the best matching link based on base path
        let bestMatch = null;
        let bestMatchLength = 0;

        links.forEach(link => {
            const linkPath = new URL(link.href).pathname;
            
            // Check if current path starts with link path (for nested routes)
            if (currentPath.startsWith(linkPath) && linkPath.length > bestMatchLength) {
                bestMatch = link;
                bestMatchLength = linkPath.length;
            }
        });

        // Apply active state to the best match only
        if (bestMatch) {
            const icon = bestMatch.querySelector('i');
            bestMatch.classList.add('bg-danger', 'text-white');
            if (icon) {
                icon.style.color = '#fff';
            }
        }
    });
</script>
