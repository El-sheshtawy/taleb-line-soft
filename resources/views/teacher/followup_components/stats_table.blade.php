<div class="table-responsive mb-1"> 
    <table class="table table-secondary table-bordered align-middle mb-0 text-nowrap stats-table">
    <tbody>
        {{-- Session Numbers Row --}}
        <tr>
            <th class="p-1 text-center">الحصة</th>
            @for ($i = 1; $i <= 7; $i++)
                <th class="p-1 text-center">{{ $i }}</th>
            @endfor
        </tr>
        
        {{-- Teacher Names Row --}}
        <tr>
            <th class="p-1 text-center">المعلم</th>
            @for ($i = 1; $i <= 7; $i++)
                @php
                    $sessionForTeacher = null;
                    foreach($students as $student) {
                        $studentSessions = $sessions[$student->id] ?? collect();
                        $session = $studentSessions ? $studentSessions->where('session_number', $i)->first() : null;
                        if($session) {
                            $sessionForTeacher = $session;
                            break;
                        }
                    }
                @endphp
                <td class="p-1 text-center">
                    @if($sessionForTeacher && $sessionForTeacher->teacher)
                        {{explode(' ', $sessionForTeacher->teacher->name)[0]}}
                    @else
                        -
                    @endif
                </td>
            @endfor
        </tr>
        
        {{-- Subjects Row --}}
        <tr>
            <th class="p-1 text-center">المادة</th>
            @for ($i = 1; $i <= 7; $i++)
                @php
                    $sessionForSubject = null;
                    foreach($students as $student) {
                        $studentSessions = $sessions[$student->id] ?? collect();
                        $session = $studentSessions ? $studentSessions->where('session_number', $i)->first() : null;
                        if($session) {
                            $sessionForSubject = $session;
                            break;
                        }
                    }
                @endphp
                <td class="p-1 text-secondary text-center">
                    @if($sessionForSubject && $sessionForSubject->teacher)
                        {{ $sessionForSubject->teacher->subject }}
                    @else
                        -
                    @endif
                </td>
            @endfor
        </tr>
        
        {{-- Absent Count Row --}}
        <tr>
            <th class="p-1 text-center">غائب</th>
            @for ($i = 1; $i <= 7; $i++)
                @php
                    $absentCount = 0;
                    foreach($students as $student) {
                        $studentDay = $days->where('student_id', $student->id)->first();
                        $studentSessions = $sessions[$student->id] ?? collect();
                        $session = $studentSessions ? $studentSessions->where('session_number', $i)->first() : null;
                        if($session && $session->followUpItem && $session->followUpItem->is_absent) {
                            $absentCount++;
                        }
                    }
                @endphp
                <td class="p-1 text-center text-danger text-bold">{{ $absentCount }}</td>
            @endfor
        </tr>
        
        {{-- Present Count Row --}}
        <tr>
            <th class="p-1 text-center">حاضر</th>
            @for ($i = 1; $i <= 7; $i++)
                @php
                    $presentCount = 0;
                    foreach($students as $student) {
                        $studentDay = $days->where('student_id', $student->id)->first();
                        $studentSessions = $sessions[$student->id] ?? collect();
                        $session = $studentSessions ? $studentSessions->where('session_number', $i)->first() : null;
                        if(!$session || !$session->followUpItem || !$session->followUpItem->is_absent) {
                            $presentCount++;
                        }
                    }
                @endphp
                <td class="p-1 text-center text-success text-bold">{{ $presentCount }}</td>
            @endfor
        </tr>
        
        {{-- Total Count Row --}}
        <tr>
            <th class="p-1 text-center">مجموع</th>
            @for ($i = 1; $i <= 7; $i++)
                <td class="p-1 text-center text-primary text-bold">{{ count($students) }}</td>
            @endfor
        </tr>
    </tbody>
</table>
</div>

<style>
.stats-table th,
.stats-table td {
    font-size: inherit !important;
}

@media (max-width: 768px) {
    .stats-table {
        width: 100% !important;
        table-layout: fixed;
    }
    .stats-table th:first-child,
    .stats-table td:first-child {
        width: 20% !important;
        font-size: 10px !important;
        padding: 2px !important;
    }
    .stats-table th:not(:first-child),
    .stats-table td:not(:first-child) {
        width: 11.4% !important;
        font-size: 10px !important;
        padding: 1px !important;
    }
}
</style>