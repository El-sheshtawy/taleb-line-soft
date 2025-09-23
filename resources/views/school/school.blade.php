<!DOCTYPE html>

<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <title>منصة طالب</title>

    <!-- CSS Files -->
    <link href="{{ asset('css/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sweetalert2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/buttons.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/datatables.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/responsive_min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/all.min.css') }}" rel="stylesheet">

    <style>
        .collapsible-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s ease;
        }

        .collapsible-content.show {
            max-height: fit-content;
        }

        .tab-pane {
            display: none;
        }

        .tab-pane.active {
            display: block !important;
        }
    </style>
</head>

<body>
    <input type="hidden" class="expire_date" value="2028-02-25">

    <div class="app">
        @include('layouts.nav')

        <div class="appOne" style="background: #fff;">
            <div class="guarantor">
                <div class="title pt-1"
                    style="color:#fff;background:#555;display:flex; justify-content:center;align-items:center">
                    <h5> <span style="color:#75f98c;"> <i class="ri-shield-user-fill"></i>مدرسة</span>
                        {{ $school->school_name }}</h5>
                </div>
                <p> هذه الصفحة تمكنكم من متابعة حضور الطلاب <br /> ولكم كل الشكر والتقدير على دعمكم لنا 🌹 </p>
            </div>

            <div class="d-flex flex-wrap gap-3 justify-content-center my-2">

                <div>الفصول :
                    <span
                        style="font-size:12px;background:#198754;padding:0 5px;color:#fff">{{ count($classes) }}</span>
                </div>

                <div>الطلاب :
                    <span
                        style="font-size:12px;background:#198754;padding:0 5px;color:#fff">{{ count($students) }}</span>
                </div>

                <div>المعلمون :
                    <span
                        style="font-size:12px;background:#198754;padding:0 5px;color:#fff">{{ count($teachers) }}</span>
                </div>
            </div>

            <div class="listOfName">
                <div class="d-flex align-items-center justify-content-center gap-2 p-2 text-center position-relative"
                    style="background:#0E2550">
                    <a href="#" id="toggleButton" style="width:100px"
                        class="text-white position-absolute end-0 text-end pe-2">فتح</a>
                    <select id="settingsSelector" class="tabs-selector"
                        style="font-size:16px;background:#ffd400;border-radius:5px;position: unset;">
                        <option selected value="settings-tab">الاعدادات</option>
                        <option value="classes-tab">الفصول</optio>
                        <option value="students-tab">الطلاب</optio>
                        <option value="teachers-tab">المعلمون</option>
                    </select>
                </div>

                <div class="collapse show collapsible-content" id="collapseExample">
                    <div class="tab-content mt-1" data-active-tab="{{ old('active_tab', 'settings-tab') }}">
                        <div id="settings-tab" class="tab-pane fade">@include('school.settings.index') </div>
                        <div id="classes-tab" class="tab-pane fade"> @include('school.classes.index') </div>
                        <div id="students-tab" class="tab-pane fade"> @include('school.students.index') </div>
                        <div id="teachers-tab" class="tab-pane fade">@include('school.teachers.index') </div>
                    </div>
                </div>

            </div>
        </div>


        <footer class="footer">
             <img width="150" src="{{ asset('storage/school_logos/banner1.jpg') }}" alt="School Logo">
        </footer>
        <div id="timer" class="down-footer d-flex flex-row">
            <small class="text-white d-textitme">باقي على الوقت</small>
            <div>
                <span class="day"></span>
                <span class="hour"></span>
                <span class="minute"></span>
                <span class="second"></span>
            </div>
        </div>


        @include('layouts.footer')

    </div>



    <!-- JS Files -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="{{ asset('js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('js/jszip.min.js') }}"></script>
    <script src="{{ asset('js/datatables.js') }}"></script>

    <script>
        document.getElementById('toggleButton').addEventListener('click', function(e) {
            e.preventDefault();
            const content = document.getElementById('collapseExample');
            content.classList.toggle('show');
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ في الإدخال',
                    html: '{!! implode('<br>', $errors->all()) !!}',
                });
            @endif

            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'نجاح!',
                    text: '{{ session('success') }}',
                    confirmButtonText: 'حسناً'
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ!',
                    text: '{{ session('error') }}',
                    confirmButtonText: 'حسناً'
                });
            @endif

            @if (session('warning'))
                Swal.fire({
                    icon: 'warning',
                    title: 'تحذير!',
                    text: '{{ session('warning') }}',
                    confirmButtonText: 'حسناً'
                });
            @endif
        });



        document.addEventListener("DOMContentLoaded", function() {
            const activeTab = localStorage.getItem('schoolActiveTab') || 'settings-tab';
            document.querySelectorAll(".tab-pane").forEach(tab => {
                tab.classList.remove("active", "show");
            });
            const targetTab = document.getElementById(activeTab);
            if (targetTab) {
                targetTab.classList.add("active", "show");
            }
            document.getElementById("settingsSelector").value = activeTab;
            document.getElementById("settingsSelector").addEventListener("change", function() {
                const selectedTab = this.value;
                localStorage.setItem('schoolActiveTab', selectedTab);
                document.querySelectorAll(".tab-pane").forEach(tab => {
                    tab.classList.remove("active", "show");
                });
                document.getElementById(selectedTab).classList.add("active", "show");
            });
        });
    </script>
</body>

</html>
