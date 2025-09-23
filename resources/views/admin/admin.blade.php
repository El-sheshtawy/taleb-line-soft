<html lang="ar">

<head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <title>منصة طالب</title>

    <link href="{{ asset('css/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sweetalert2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/buttons.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/datatables.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/responsive_min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/all.min.css') }}" rel="stylesheet">

</head>



<body>
    <input type="hidden" class="expire_date" value="2028-02-25">
    <!--<div id="block">-->
    <!--    <h3>موقوف مؤقتًا</h3>-->
    <!--</div>-->

    <!-- تغير الالوان -->

    <div class="app">
        @include('layouts.nav')

        <div class="appOne" style="background: #fff;">
            <div class="guarantor">
                <div class="title"
                    style="color:#fff;background:#555;display:flex; justify-content:center;align-items:center">
                    <h5> <span style="color:#75f98c;"> <i class="ri-shield-user-fill"></i>صفحة الادمن
                        </span>{{ auth()->user()->profile->name }}</h5>
                </div>
                <p>
                    هذه الصفحة تمكنكم من متابعة حضور الطلاب
                    <br />
                    ولكم كل الشكر والتقدير على دعمكم لنا 🌹
                </p>
            </div>
            <div class="listOfName">
                <div class="d-flex align-items-center justify-content-center gap-2  p-2 text-center"
                    style="background:#0E2550">
                    <select id="settingsSelector" class="tabs-selector"
                        style="font-size:16px;background:#ffd400;border-radius:5px;position: unset;">
                        <option disabled selected>الاعدادات</option>
                        <option value="academic-years">الاعوام</optio>
                        <option value="classes">الصفوف</option>
                        <option value="stages">المراحل </option>
                        <option value="page_data">بيانات الصفحة</option>
                        <option value="images">الصور</option>
                        <option value="banners">شريط الاخبار</option>
                    </select>

                    <select id="accountsSelector" class="tabs-selector"
                        style="font-size:16px;background:#ffd400;border-radius:5px;position: unset;">
                        <option disabled selected>الحسابات</option>
                        <option value="school-accounts">المدارس</option>
                        <option value="admins">المشرفون</option>
                        <option value="school-subscriptions">الاشتراكات</option>
                        <option value="follow-up-systems">نظم المتابعة</option>
                    </select>

                    <select id="busPilgrims" class="tabs-selector"
                        style="font-size:16px;background:#ffd400;border-radius:5px;position: unset;">
                        <option selected disabled>الصلاحيات</option>
                        <option value="1">الادمن</option>
                        <option value="2">المدرسة</option>
                        <option value="3">المعلم</option>
                        <option value="4">ولي الامر</option>

                    </select>
                </div>

                <div class="tab-content mt-1">
                    <div class="tab-pane fade" id="academic-years"> @include('admin.academic_years.index') </div>
                    <div class="tab-pane fade" id="stages"> @include('admin.stages.index') </div>
                    <div class="tab-pane fade" id="classes"> @include('admin.classes.index') </div>
                    <div class="tab-pane fade" id="admins"> @include('admin.admins.index') </div>
                    <div class="tab-pane fade" id="school-accounts"> @include('admin.school_accounts.index') </div>
                    <div class="tab-pane fade" id="school-subscriptions"> @include('admin.school_subscriptions.index') </div>
                    <div class="tab-pane fade" id="follow-up-systems"> @include('admin.followup_systems.index') </div>
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
    <script src="{{ asset('js/dataTables.buttons.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="{{ asset('js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('js/jszip.min.js') }}"></script>
    <script src="{{ asset('js/datatables.js') }}"></script>

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
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const activeTab = localStorage.getItem('adminActiveTab');
            if (activeTab) {
                document.querySelectorAll(".tab-pane").forEach(tab => {
                    tab.classList.remove("show", "active");
                });
                document.getElementById(activeTab)?.classList.add("show", "active");

                document.querySelectorAll('select[id$="Selector"]').forEach(select => {
                    if (select.querySelector(`.tabs-selector option[value="${activeTab}"]`)) {
                        select.value = activeTab;
                    }
                });
            }

            function handleTabChange(selectorId) {
                const tabSelector = document.getElementById(selectorId);
                if (!tabSelector) return;

                tabSelector.addEventListener("change", function() {
                    const selectedTab = this.value;
                    localStorage.setItem('adminActiveTab', selectedTab);

                    document.querySelectorAll(".tab-pane").forEach(tab => {
                        tab.classList.remove("show", "active");
                    });

                    document.getElementById(selectedTab)?.classList.add("show", "active");
                });
            }

            handleTabChange("settingsSelector");
            handleTabChange("accountsSelector");
        });
    </script>


</body>

</html>
