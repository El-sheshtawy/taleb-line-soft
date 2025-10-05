<style>
    table th {
        padding: 5px !important;
    }
</style>
<div class="content my-4">
    <div class="table-responsive">
        @if ($student && $attendanceRecords->isNotEmpty())
            <h5 style="cursor: pointer"
                class="mb-3 title text-center fw-bold {{ $student->note ? 'text-danger' : 'text-primary' }}"
                data-bs-toggle="modal" data-bs-target="#viewStudentModal">
                متابعة الطالب
                : <span> {{ $student->name }} </span>
            </h5>
            <table class="table table-bordered align-middle mb-0 general-table">
                <thead>
                    <tr class="text-center">
                        <th>اليوم</th>
                        <th>التاريخ</th>
                        @foreach (range(1, 7) as $session)
                            <th>{{ $session }}</th>
                        @endforeach
                        <th>غياب</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalAbsentDays = 0;
                        $sessionAbsentCounts = array_fill(1, 7, 0);
                    @endphp

                    @foreach ($attendanceRecords as $date => $dayRecord)
                        @php
                            $isDayAbsent = $dayRecord->is_absent ?? false;
                            $hasSessions = $dayRecord->sessions->isNotEmpty();

                            // Count absent days
                            if ($isDayAbsent) {
                                $totalAbsentDays++;
                            }

                            // Group sessions by number for easy lookup
                            $sessionsByNumber = $hasSessions
                                ? $dayRecord->sessions->keyBy('session_number')
                                : collect();
                        @endphp
                        <tr class="text-center">
                            <td class="p-1">
                                {{ \Carbon\Carbon::parse($date)->locale('ar')->translatedFormat('l') }}
                            </td>
                            <td class="p-1" style="width: 150px">
                                {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                            </td>

                            @foreach (range(1, 7) as $sessionNumber)
                                @php
                                    $session = $sessionsByNumber[$sessionNumber] ?? null;
                                    $status = $session ? $session->followUpItem : null;

                                    // Count absent sessions
                                    if ($status && $status->is_absent) {
                                        $sessionAbsentCounts[$sessionNumber]++;
                                    }
                                @endphp
                                <td class="p-1"
                                    style="@if ($status) background-color: {{ $status->background_color ?? '' }}; color: {{ $status->text_color ?? 'transparent' }}; @endif">
                                    @if ($status)
                                        {{ $status->letter ?? '' }}
                                    @endif
                                </td>
                            @endforeach

                            <td>
                                @if ($isDayAbsent)
                                    <span class="badge bg-danger p-1">غائب</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="text-center bg-light">
                        <th class="p-1" colspan="2">المجموع</th>
                        @foreach (range(1, 7) as $sessionNumber)
                            <th class="p-1">{{ $sessionAbsentCounts[$sessionNumber] }}</th>
                        @endforeach
                        <th class="p-1">{{ $totalAbsentDays }}</th>
                    </tr>
                </tfoot>
            </table>
        @elseif($student)
            <div class="alert alert-info">لا توجد سجلات حضور للطالب في الفترة المحددة</div>
        @endif
    </div>
</div>


{{-- preview data of student --}}
@if ($student)
    <div class="modal fade" id="viewStudentModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">تفاصيل الطالب</h5>
                    <button type="button" class="btn-close btn-close-white m-0" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row p-4">
                    <div class="mb-3 col-md-6">
                        <label class="form-label fw-bold text-primary">الاسم:</label>
                        <div class="form-control bg-light border-0 shadow-sm">{{ $student->name }}</div>
                    </div>

                    @if(!in_array(auth()->user()->user_type, ['مراقب']))
                    <div class="mb-3 col-md-6">
                        <label class="form-label fw-bold text-primary">الرقم المدني:</label>
                        <div class="form-control bg-light border-0 shadow-sm">{{ $student->passport_id }}</div>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label class="form-label fw-bold text-primary">رقم الهاتف:</label>
                        <div class="form-control bg-light border-0 shadow-sm">{{ $student->phone_number }}</div>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label class="form-label fw-bold text-primary">رقم هاتف إضافي:</label>
                        <div class="form-control bg-light border-0 shadow-sm">{{ $student->phone_number_2 }}</div>
                    </div>
                    @endif

                    <div class="mb-3 col-md-6">
                        <label class="form-label fw-bold text-primary">الصف:</label>
                        <div class="form-control bg-light border-0 shadow-sm">{{ $student->grade->name }}</div>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label class="form-label fw-bold text-primary">الفصل:</label>
                        <div class="form-control bg-light border-0 shadow-sm">{{ $student->classRoom->name }}</div>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label class="form-label fw-bold text-primary">الجنس:</label>
                        <div class="form-control bg-light border-0 shadow-sm">{{ $student->gender ? 'ذكر' : 'أنثى' }}</div>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label class="form-label fw-bold text-primary">الجنسية:</label>
                        <div class="form-control bg-light border-0 shadow-sm">{{ $student->nationality?->name }}</div>
                    </div>

                    <div class="mb-3 col-md-12">
                        <label class="form-label fw-bold text-primary">الملاحظات:</label>
                        <div class="form-control bg-light border-0 shadow-sm" style="min-height: 100px">
                            {{ $student->note ?? 'لا توجد ملاحظات' }}</div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-primary px-4" data-bs-dismiss="modal">إغلاق</button>
                </div>
            </div>
        </div>
    </div>
@endif
