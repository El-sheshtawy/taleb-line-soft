<style>
    .header form button {
        padding: 2px 15px;
        font-size: 15px;
        border-radius: 12px;
        margin: 10px 0;
    }
</style>
@if (auth()->user()->user_type == 'admin')
    <div class="header" style="background:#004aad !important;background:#f80">
        <div class="appHeader ">
            <a href="{{ route('admin.index') }}">
                <img width="150" src="{{ asset('storage/school_logos/taleblogo.png') }}" alt="School Logo">
            </a>
            <div class="info text-center">
                <h5 style="color:1 ? #fff : #8eff8e;">منصة طالب</h5>
                <p>
                    @php
                        $activeAcademicYear = \App\Models\AcademicYear::where('status', 1)->first();
                    @endphp
                    @if ($activeAcademicYear)
                        {{ $activeAcademicYear->name }}
                    @else
                        لا يوجد عام دراسي نشط
                    @endif
                </p>
                <form action="{{ route('logout') }}" method="post">
                    @csrf
                    <button type="submit" class="btn btn-danger">تسجيل الخروج</button>
                </form>
                <div style="background: rgba(255,255,255,0.2); padding: 5px 10px; border-radius: 15px; margin-top: 8px; font-size: 12px; color: #fff; font-weight: bold;">{{ auth()->user()->username }}</div>
            </div>
        </div>
    </div>
@endif
@if (auth()->user()->user_type == 'school')
    <div class="header" class="header"
        style="background: #004aad; width: 100%; min-height: 70px; padding-bottom:0.3rem;">
        <div class="appHeader ">
            <a href="{{ route('school.index') }}">
                @if ($school && $school->school_logo_url)
                    <img width="150" src="{{ $school->school_logo_url }}" alt="School Logo">
                @else
                    <img width="150" src="{{ asset('dashboard/uploads/header/image.png') }}" alt="Default Logo">
                @endif
            </a>

            <div class="info text-center">

                <h5 style="color:1 ? #fff : #8eff8e;">منصة طالب</h5>
                <p>
                    @php
                        $activeAcademicYear = \App\Models\AcademicYear::where('status', 1)->first();
                    @endphp
                    @if ($activeAcademicYear)
                        {{ $activeAcademicYear->name }}
                    @else
                        لا يوجد عام دراسي نشط
                    @endif
                </p>
                <form action="{{ route('logout') }}" method="post">
                    @csrf
                    <button type="submit" class="btn btn-danger">تسجيل الخروج</button>
                </form>
                <div style="background: rgba(255,255,255,0.2); padding: 5px 10px; border-radius: 15px; margin-top: 8px; font-size: 12px; color: #fff; font-weight: bold;">{{ auth()->user()->username }}</div>
            </div>
        </div>
    </div>
@endif
@if (in_array(auth()->user()->user_type, ['teacher', 'مراقب', 'مشرف']))
    <div class="header" class="header"
        style="background: #004aad; width: 100%; min-height: 70px; padding-bottom:0.3rem;">
        <div class="appHeader w-100 h-100">
            <a href="{{ route('teacher.index') }}">
                @if ($school && $school->school_logo_url)
                    <img width="150" src="{{ $school->school_logo_url }}" alt="School Logo">
                @else
                    <img width="150" src="{{ asset('dashboard/uploads/header/image.png') }}" alt="Default Logo">
                @endif
            </a>

            <div class="info text-center">

                <h5 style="color:1 ? #fff : #8eff8e;">منصة طالب</h5>
                <p>
                    @php
                        $activeAcademicYear = \App\Models\AcademicYear::where('status', 1)->first();
                    @endphp
                    @if ($activeAcademicYear)
                        {{ $activeAcademicYear->name }}
                    @else
                        لا يوجد عام دراسي نشط
                    @endif
                </p>
                <form action="{{ route('logout') }}" method="post">
                    @csrf
                    <button type="submit" class="btn btn-danger">تسجيل الخروج</button>
                </form>
                <div style="background: rgba(255,255,255,0.2); padding: 5px 10px; border-radius: 15px; margin-top: 8px; font-size: 12px; color: #fff; font-weight: bold;">{{ auth()->user()->username }}</div>
            </div>
        </div>
    </div>
@endif
@if (auth()->user()->user_type == 'student')
@endif
