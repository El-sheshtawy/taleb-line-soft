<!DOCTYPE html>

<html lang="ar" dir="rtl">

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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        .inbox {
            background-color: #fff;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .email-item {
            display: flex;
            padding: 15px 20px;
            border-bottom: 1px solid #f0f0f0;
            position: relative;
            cursor: pointer;
            text-align: right;
        }

        .email-item:last-child {
            border-bottom: none;
        }

        .email-content {
            flex-grow: 1;
            min-width: 0;
        }

        .email-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .sender-name {
            font-weight: 500;
            font-size: 16px;
        }

        .time {
            color: #888;
            font-size: 14px;
        }

        .email-preview {
            color: #666;
            font-size: 14px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .delete-btn,
        .edit-btn {
            position: absolute;
            color: white;
            width: 36px;
            height: 36px;
            border: none;
            right: auto;
            font-size: 12px;
            border-radius: 4px;
            cursor: pointer;
            display: none;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .email-item:hover .delete-btn,
        .email-item.active .delete-btn,
        .email-item:hover .edit-btn,
        .email-item.active .edit-btn {
            display: flex;
        }

        .delete-btn {
            background-color: #e53935;
            left: 20px;
            top: 15px;
        }

        .edit-btn {
            background-color: #007bff;
            left: 65px;
            top: 15px;
        }

        .delete-btn:hover {
            background-color: #d32f2f;
        }

        .edit-btn:hover {
            background-color: #0056b3;
        }

        .email-item.active {
            background-color: #f5f5f5;
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
                    <h5> <span style="color:#75f98c;"> <i class="ri-shield-user-fill"></i>تنبيهات مدرسة </span>
                        {{ $school->school_name }}</h5>
                </div>
                <p> هذه الصفحة تمكنكم من متابعة حضور الطلاب <br /> ولكم كل الشكر والتقدير على دعمكم لنا 🌹 </p>
            </div>

            <div class="listOfName pt-2">
                <h5><span class="text-primary mt-2">التنبيهات المدرسية <span
                            class="badge bg-primary text-light">{{ count($reminders) }}</span> </span></h5>
                <div class="inbox">
                    @foreach ($reminders as $reminder)
                        {{-- <div class="email-item" id="email-1"> --}}
                        <div class="email-item show-reminder-btn" data-bs-toggle="modal"
                            data-bs-target="#showReminderModal" data-title="{{ $reminder->title }}"
                            data-content="{{ $reminder->content }}">

                            @if (auth()->user()->user_type == 'school')
                                <button type="button" class="delete-btn delete-reminder-btn" data-bs-toggle="modal"
                                    data-bs-target="#deleteReminderModal"
                                    data-delete-action="{{ route('reminders.destroy', $reminder->id) }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            @endif
                            @if (auth()->user()->user_type == 'school')
                                <button type="button" class="edit-btn update-reminder-btn" data-bs-toggle="modal"
                                    data-bs-target="#updateReminderModal" data-title="{{ $reminder->title }}"
                                    data-content="{{ $reminder->content }}"
                                    data-update-action="{{ route('reminders.update', $reminder->id) }}">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                            @endif
                            <div class="email-content">
                                <div class="email-header">
                                    <div class="sender-name">{{ $reminder->title }}</div>
                                    <div class="time">
                                        {{ $reminder->created_at->locale('ar')->isoFormat('MMM D, dddd, h:mm A') }}
                                    </div>
                                </div>
                                <div class="email-preview">{{ $reminder->content }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @if (auth()->user()->user_type == 'school')
                    <button type="button" class="block btn btn-primary w-100 my-4 text-center" data-bs-toggle="modal"
                        data-bs-target="#addReminderModal">إضافة تنبيه</button>
                @endif
            </div>
        </div>


        <footer class="footer">
            <!--<img width="150" src="dashboard/uploads/footer/banner1.jpg" alt="">-->
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
    @include('reminders.show')
    @if (auth()->user()->user_type == 'school')
        @include('reminders.create')
        @include('reminders.edit')
        @include('reminders.delete')
    @endif

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
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".show-reminder-btn").forEach(button => {
                button.addEventListener("click", function() {
                    document.querySelector("#showReminderModal #edit_reminder_title").value = this
                        .getAttribute("data-title");
                    document.querySelector("#showReminderModal #edit_reminder_content").value = this
                        .getAttribute("data-content");
                });
            });
        });
    </script>
    
    @if (auth()->user()->user_type == 'school')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                document.querySelectorAll(".delete-reminder-btn").forEach(button => {
                    button.addEventListener("click", function() {
                        document.getElementById("deleteReminderForm").action = this.getAttribute(
                            "data-delete-action");
                    });
                });
            });

            document.querySelectorAll(".update-reminder-btn").forEach(button => {
                button.addEventListener("click", function() {
                    document.getElementById("edit_reminder_title").value = this.getAttribute("data-title");
                    document.getElementById("edit_reminder_content").value = this.getAttribute("data-content");
                    document.getElementById("updateReminderForm").action = this.getAttribute(
                        "data-update-action");
                });
            });
        </script>
    @endif

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
</body>

</html>
