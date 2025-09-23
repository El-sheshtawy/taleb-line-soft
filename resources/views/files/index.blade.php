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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        .file-list {
            max-width: 1200px;
            margin: 0 auto;
        }

        .file-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f0f0f0;
            border-radius: 50%;
        }

        .file-icon i,
        .file-icon svg {
            line-height: 0;
        }

        .excel-icon {
            color: #217346;
        }

        .folder-icon {
            color: #768192;
        }

        .image-icon {
            color: #d83b01;
        }

        .add-button {
            position: fixed;
            bottom: 30px;
            left: 30px;
            width: 60px;
            height: 60px;
            background-color: #00c3ff;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            border: none;
        }

        .divider {
            height: 1px;
            background-color: #e9ecef;
            margin: 0;
        }

        .table th {
            font-weight: 500;
            color: #555;
            padding-top: 16px;
            padding-bottom: 16px;
        }

        .file-row {
            vertical-align: middle;
        }

        .file-row td {
            padding-top: 15px;
            padding-bottom: 15px;
        }

        @media (max-width: 576px) {
            .file-icon {
                width: 30px;
                height: 30px;
            }

            .file-icon i {
                font-size: 1rem !important;
            }
        }

        .tr-with-note,
        .tr-with-note td {
            border-bottom: none !important;
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
                    <h5> <span style="color:#75f98c;"> <i class="ri-shield-user-fill"></i>ملفات مدرسة </span>
                        {{ $school->school_name }}</h5>
                </div>
                <p> هذه الصفحة تمكنكم من متابعة حضور الطلاب <br /> ولكم كل الشكر والتقدير على دعمكم لنا 🌹 </p>
            </div>

            <div class="listOfName pb-2">
                <h5><span class="text-primary mt-2">الملفات المدرسية <span
                            class="badge bg-primary text-light">{{ count($files) }}</span> </span></h5>
                <div class="table-responsive file-list py-3">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th scope="col" class="p-1 text-center">م</th>
                                <th scope="col" class="p-1">العنوان</th>
                                <th scope="col" class="p-1 text-center">أخر تحديث</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($files as $file)
                                @php
                                    $icon = match (strtolower($file->type)) {
                                        'pdf' => 'bi-file-earmark-pdf-fill text-danger',
                                        'doc', 'docx' => 'bi-file-earmark-word-fill text-primary',
                                        'xls', 'xlsx' => 'bi-file-earmark-excel-fill text-success',
                                        'jpg', 'jpeg', 'png', 'gif' => 'bi-file-earmark-image-fill text-warning',
                                        'zip', 'rar' => 'bi-file-earmark-zip-fill text-secondary',
                                        default => 'bi-file-earmark-fill text-muted',
                                    };
                                @endphp
                                <tr class="{{ $file->note && $file->note != '' ? 'tr-with-note' : '' }}">
                                    <td class="text-center p-1">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td class="p-1">
                                        <div class="d-flex align-items-center gap-2">
                                            @if (auth()->user()->user_type == 'school')
                                                <a href="#" class="file-icon edit-file-btn" data-bs-toggle="modal"
                                                    data-bs-target="#updateFileModal"
                                                    data-file="{{ json_encode($file) }}"
                                                    data-icon="{{ $icon }}"
                                                    data-edit-action="{{ route('files.update', $file->id) }}"
                                                    data-delete-action="{{ route('files.destroy', $file->id) }}">
                                                    <i class="bi {{ $icon }}" style="font-size: 1.5rem;"></i>
                                                </a>
                                            @else
                                                <a href="{{ route('files.download', $file) }}" class="file-icon"> <i
                                                        class="bi {{ $icon }}" style="font-size: 1.5rem;"></i>
                                                </a>
                                            @endif
                                            <a href="{{ route('files.view', $file->id) }}"
                                                target="_blank">{{ $file->name }}.{{ $file->type }}</a>
                                        </div>
                                    </td>
                                    <td class="text-center p-1">
                                        {{ $file->updated_at->locale('ar')->isoFormat('MMM D, dddd') }}</td>
                                </tr>

                                @if ($file->note && $file->note != '')
                                    <tr class="file-note-row" style="border-top: none;">
                                        <td colspan="4" class="p-1">
                                            <p> <b>ملاحظات:</b> <span class="text-secondary">{{ $file->note }}</span>
                                            </p>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if (auth()->user()->user_type == 'school')
                    <button type="button" class="block btn btn-primary w-100 my-4 text-center" data-bs-toggle="modal"
                        data-bs-target="#addFileModal">إضافة ملف</button>
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

    @if (auth()->user()->user_type == 'school')
        @include('files.create')
        @include('files.edit')
        @include('files.delete')
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

    @if (auth()->user()->user_type == 'school')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                document.querySelectorAll(".edit-file-btn").forEach(button => {
                    button.addEventListener("click", function() {
                        let fileData = JSON.parse(this.getAttribute("data-file"));
                        let icon = this.getAttribute("data-icon");

                        document.getElementById("updateFileForm").action = this.getAttribute(
                            "data-edit-action");
                        document.getElementById("deleteFileForm").action = this.getAttribute(
                            "data-delete-action");

                        document.getElementById("edit_file_name").value = fileData.name;
                        document.getElementById("edit_file_note").value = fileData.note;

                        const previewSpan = document.getElementById("file_preview_name").innerHTML =
                            `<i class="bi ${icon}"></i> ${fileData.name}.${fileData.type}`;
                    });
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
