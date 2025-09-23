<!DOCTYPE html>

<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">

    <!--<link rel="icon" type="image/x-icon" href="resources/images/icon.jpg">-->
    <title>منصة طالب</title>

  <!-- CSS Files -->
    <link href="{{ asset('css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <!--<link href="{{ asset('css/remixicon.css') }}" rel="stylesheet">-->
    <link href="{{ asset('css/sweetalert2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/responsive_min.css') }}" rel="stylesheet">
</head>

<body>
    <input type="hidden" class="expire_date" value="2028-02-25">

    <!-- تغير الالوان -->

    <div class="app">

        <div class="header" style="background:#004aad !important;background:#f80">
            <div class="appHeader ">
                <a href="#">
                    <!--<img width="150" src="{{ asset('dashboard/uploads/header/image.png') }}" alt="">-->
                </a>

                <div class="info text-center">

                    <h5 style="color:1 ? #fff : #8eff8e;">منصة طالب</h5>
                    <p>العام 2025م- 2026م</p>
                </div>
            </div>
        </div>

        <div class="appOne" style="background: #fff;">
            <div class="guarantor">
                <div class="title" style="color:#fff;background:#555;display:flex; justify-content:center;align-items:center">
                    <h5> <span style="color:#75f98c;"> <i class="ri-shield-user-fill"></i>المعلم</span>اسم المعلم</h5>
                </div>
                <p>    هذه الصفحة تمكنكم من متابعة حضور الطلاب   <br /> ولكم كل الشكر والتقدير على دعمكم لنا 🌹 </p>
            </div>

            <div class="listOfName">
                <h5 class="text-primary text-bold my-1">
                    كشف حضور الطالب 
                    <span class="badge bg-primary text-light student-name" data-student-id="{{ $student->id }}">
                        {{ $student->name }}
                    </span>
                </h5>
                <div class="table-responsive mb-1"> 
                    <table class="table table-secondary table-bordered align-middle mb-0">
                    <thead>
                         <tr>
                            <th class="p-1 text-center">التاريخ</th>
                            <th class="p-1 text-center">1</th>
                            <th class="p-1 text-center">2</th>
                            <th class="p-1 text-center">3</th>
                            <th class="p-1 text-center">4</th>
                            <th class="p-1 text-center">5</th>
                            <th class="p-1 text-center">6</th>
                            <th class="p-1 text-center">7</th>
                            <th class="p-1 text-center">غائب</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($days as $day)
                            <tr class="text-center">
                                <td>{{ $day->date}}</td>
                                @for ($i = 1; $i <= 7; $i++)
                                    <?php
                                        $session = $day->sessions()->where('session_number', $i)->first() ?? null;
                                    ?>
                                    <td style="@if($session) background-color: {{ $session->followUpItem->background_color ?? '' }}; color: {{ $session->followUpItem->text_color ?? 'transparent' }}; @endif">
                                        @if($session && $session->followUpItem)
                                            {{ $session->followUpItem->letter ?? '' }}
                                        @endif
                                    </td>
                                @endfor
                                <td>
                                    @if($day && $day->is_absent)
                                        <span class="text-danger">غ</span>
                                    @endif    
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
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


        <div class="navbar-bottom">
            <div>
            @if(auth()->user()->user_type == 'school')
                <a href="{{route('school.index')}}" > <i class="ri-building-fill" style="color: #0d6efd;"></i> الادارة </a>
            @endif
            <a href="#" style="background:red;" > <i class="ri-user-star-fill" style="color: #0d6efd;"></i> المعلم </a>
            <a href="student" > <i class="ri-graduation-cap-fill" style="color: #0d6efd;"></i> الطلاب </a>
            <a href="father" > <i class="ri-parent-fill" style="color: #0d6efd;"></i> ولي الامر </a>
            <a href="report"> <i class="ri-file-chart-fill" style="color: #0d6efd;"></i> التقارير </a>
            <a href="tasks" class=""> <i class="ri-list-check-3" style="color: #0d6efd;"></i> المهام </a>    
            <a href="{{route('files.index')}}" > <i class="ri-folder-fill" style="color: #0d6efd;"></i> الملفات </a>
            <a href="{{route('reminders.index')}}" > <i class="ri-notification-3-fill" style="color: #0d6efd;"></i> تنبيهات </a
        </div>
    </div>
    </div>



   <!-- JS Files -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.min.js') }}"></script>
    <!--<script src="{{ asset('js/html2pdf.bundle.js') }}"></script>-->
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <!--<script src="{{ asset('js/select2.min.js') }}"></script>-->
    <!--<script src="{{ asset('js/main/ajax.js') }}"></script>-->
    <script src="{{ asset('js/script2.js') }}"></script>
    <!--<script src="../cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>-->
</body>
</html>