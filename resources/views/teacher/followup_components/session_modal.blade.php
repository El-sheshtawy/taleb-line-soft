<div class="modal fade" id="sessionModal" tabindex="-1" aria-labelledby="sessionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title">متابعة طلاب ({{ request('date', $date) }})</h5>
                    <h5 class="modal-title" id="sessionModalLabel">
                        الصف: {{ request('grade_id') ? $grades->find(request('grade_id'))->name : '' }} -
                        الفصل: {{ request('class_id') ? $classes->find(request('class_id'))->name : '' }} -
                        الحصة: <span id="modalSessionTitle"></span>
                    </h5>
                </div>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('teacher.student-sessions.store') }}" id="sessionForm" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="session_number" id="modalSessionNumber">
                    <input type="hidden" name="date" value="{{ request('date', $date) }}">

                    <table class="table table-secondary table-bordered">
                        <thead>
                            <tr class="text-center">
                                <th class="p-1">م</th>
                                <th class="p-1 w-50">الطالب</th>
                                <th class="p-1">المتابعة</th>
                                <th class="p-1">ملاحظة المعلم</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $student)
                                <tr>
                                    <td class="">{{ $loop->iteration }}</td>
                                    <td class="pt-1 w-50 text-{{ $student->gender ? 'primary' : 'danger' }}">
                                        {{ $student->name }}</td>
                                    <td>
                                        <div class="follow-up-modal-cell" data-student-id="{{ $student->id }}"
                                            style="cursor: pointer; min-height: 25px; display: flex; align-items: center; justify-content: center;"
                                            data-follow-up-id="">
                                        </div>
                                        <input type="hidden" name="students[{{ $student->id }}][follow_up_item_id]"
                                            value="">
                                    </td>
                                    <td class="">
                                        <input type="text" class="form-control px-1 py-0" value=""
                                            name="students[{{ $student->id }}][teacher_note]">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="p-2  text-end">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="teacher-info text-start">
                                            <div><strong>المعلم:</strong> {{ $teacher->name ?? 'غير محدد' }}</div>
                                            <div><strong>المادة:</strong> {{ $teacher->subject ?? 'غير محدد' }}</div>
                                        </div>
                                        <div class="d-flex gap-1">
                                            <button type="submit" class="btn btn-primary"> حفظ التغييرات</button>
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">إغلاق</button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    @media (max-width: 580px) {

        input,
        select,
        button:not(.showInfoSearch) {
            font-size: 15px !important;
        }
    }
</style>
