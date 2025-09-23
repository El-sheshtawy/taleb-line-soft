<div class="general-table">
    @foreach ($studentsData as $data)
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="card-title mb-0 table-title">
                    <p>
                        متابعة طلاب صف
                    </p>
                    <p>
                        {{ $grade->name }}
                        -
                        {{ $class->name }}
                    </p>
                    <p>
                        {{ $data['date'] }}
                    </p>
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive mb-1">
                    <table class="table table-secondary table-bordered align-middle mb-0 text-nowrap">
                        <thead>
                            <tr class="text-center align-middle">
                                <th class="p-1">#</th>
                                <th class="p-1 text-end">الطالب</th>
                                @for ($i = 1; $i <= 7; $i++)
                                    <?php
                                    $sessionHasData = false;
                                    if (auth()->user()->user_type != 'school') {
                                        foreach ($data['students'] as $student) {
                                            if ($today != $data['date']) {
                                                $sessionHasData = true;
                                            } else {
                                                $studentSessions = $data['sessions'][$student->id] ?? collect();
                                                if ($studentSessions->where('session_number', $i)->isNotEmpty()) {
                                                    $sessionHasData = true;
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                    ?>
                                    <th style="min-width:20px">
                                        <button type="button"
                                            class="w-100 h-100 bg-primary px-2 py-1 session-btn {{ $sessionHasData ? 'btn-secondary' : 'btn-primary' }}"
                                            data-session="{{ $i }}" data-bs-toggle="modal"
                                            data-bs-target="#sessionModal" data-date="{{ $data['date'] }}"
                                            {{ $sessionHasData ? 'disabled' : '' }}>
                                            {{ $i }}
                                        </button>
                                    </th>
                                @endfor
                                <th class="p-1">غائب</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['students'] as $student)
                                <tr class="text-center">
                                    <td class="p-1">{{ $loop->iteration }}</td>
                                    <td class="p-1 text-end responsive-cell">
                                        <a href="#student-details-{{ $student->id }}"
                                            class="student-name-toggle cell-link d-flex align-items-center justify-content-start
                                        text-{{ $student->gender ? 'primary' : 'danger' }} text-{{ $student->note ? 'danger' : '' }}"
                                            data-bs-toggle="collapse" aria-expanded="false"
                                            aria-controls="student-details-{{ $student->id }}">
                                            {{ $student->name }}
                                        </a>
                                    </td>
                                    @for ($i = 1; $i <= 7; $i++)
                                        <?php
                                        $studentSessions = $data['sessions'][$student->id] ?? collect();
                                        $session = $studentSessions ? $studentSessions->where('session_number', $i)->first() : null;

                                        $sessionHasData = false;
                                        if (auth()->user()->user_type != 'school') {
                                            if ($today != $data['date']) {
                                                $sessionHasData = true;
                                            } else {
                                                foreach ($data['students'] as $s) {
                                                    $sSessions = $data['sessions'][$s->id] ?? collect();
                                                    if ($sSessions->where('session_number', $i)->isNotEmpty()) {
                                                        $sessionHasData = true;
                                                        break;
                                                    }
                                                }
                                            }
                                            if ($teacher && $teacher->supervisor == 1) {
                                                $sessionHasData = false;
                                            }
                                        }

                                        ?>
                                        <td class="p-1 {{ $sessionHasData ? '' : 'follow-up-cell' }}"
                                            data-student-id="{{ $student->id }}"
                                            data-session-number="{{ $i }}" data-date="{{ $data['date'] }}"
                                            style="@if ($session) background-color: {{ $session->followUpItem->background_color ?? '' }}; color: {{ $session->followUpItem->text_color ?? 'transparent' }}; @endif
                                              @if ($sessionHasData) cursor: not-allowed; opacity: 0.8; @else cursor: pointer; @endif"
                                            data-follow-up-id="{{ $session ? $session->follow_up_item_id ?? null : '' }}"
                                            @if ($sessionHasData) onclick="return false;" @endif>
                                            @if ($session)
                                                {{ $session->followUpItem->letter ?? '' }}
                                                {{-- @if ($session->teacher)
                                                <br><small class="text-muted">{{ $session->teacher->name }}</small>
                                            @endif --}}
                                            @endif
                                        </td>
                                    @endfor
                                    <td class="px-1">
                                        <?php $day = $data['days']->where('student_id', $student->id)->first(); ?>
                                        @if ($day && $day->is_absent)
                                            <span class="text-danger">غ</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" class="p-1 fw-bold text-light bg-info">المعلم</td>
                                @for ($i = 1; $i <= 7; $i++)
                                    @php
                                        $sessionForTeacher = null;
                                        foreach ($data['students'] as $student) {
                                            $studentSessions = $data['sessions'][$student->id] ?? collect();
                                            $session = $studentSessions
                                                ? $studentSessions->where('session_number', $i)->first()
                                                : null;
                                            if ($session) {
                                                $sessionForTeacher = $session;
                                                break;
                                            }
                                        }
                                    @endphp
                                    <td class="p-1 text-center text-light bg-info">
                                        @if ($sessionForTeacher && $sessionForTeacher->teacher)
                                            {{ explode(' ', $sessionForTeacher->teacher->name)[0] }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                @endfor
                                <td class="p-1 text-center text-light bg-info">
                                    <span class="">-</span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="p-1 fw-bold text-light bg-danger">الغياب</td>
                                @for ($i = 1; $i <= 7; $i++)
                                    <td class="p-1 text-center text-light bg-danger">
                                        <?php
                                        $presentCount = $data['students']
                                            ->filter(function ($student) use ($data, $i) {
                                                return isset($data['sessions'][$student->id]) && $data['sessions'][$student->id]->where('session_number', $i)->isNotEmpty();
                                            })
                                            ->count();
                                        $abs[$i] = $presentCount;
                                        ?>
                                        <span class="">{{ $presentCount }}</span>
                                    </td>
                                @endfor
                                <td class="p-1 text-center text-light bg-danger">
                                    <?php
                                    $absentCount = $data['days']->where('is_absent', true)->count();
                                    ?>
                                    <span class="">{{ $absentCount }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="p-1 fw-bold  text-light bg-primary">الحضور</td>
                                @for ($i = 1; $i <= 7; $i++)
                                    <td class="p-1 text-center text-light bg-primary">
                                        <?php
                                        $totalCount = $data['students']->count() - $abs[$i];
                                        ?>
                                        <span class="">{{ $totalCount }}</span>
                                    </td>
                                @endfor
                                <td class="p-1 text-center text-light bg-primary">
                                    <?php
                                    $absentCount = $data['students']->count() - $data['days']->where('is_absent', true)->count();
                                    ?>
                                    <span class="">{{ $absentCount }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="p-1 fw-bold text-light bg-success">المجموع</td>
                                @for ($i = 1; $i <= 7; $i++)
                                    <td class="p-1 text-center text-light bg-success">
                                        <?php
                                        $totalCount = $data['students']->count();
                                        ?>
                                        <span class="">{{ $totalCount }}</span>
                                    </td>
                                @endfor
                                <td class="p-1 text-center text-light bg-success">
                                    <span class="">{{ $absentCount }}</span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    @endforeach

    <style>
        .table-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 5px;
        }

        .table-title p {
            color: yellow;
            font-weight: bold;
        }

        @media print {
            .table-title {
                border-top: 2px solid #000;
            }

            .table-title p {
                color: #000;
            }
        }
    </style>
</div>
