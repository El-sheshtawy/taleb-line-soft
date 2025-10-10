<div class="mb-3">
    <h6 class="text-danger">سجل متابعة الطالب: {{ $student->name }}</h6>
    <p class="mb-1"><strong>الفصل:</strong> {{ $student->classRoom->name ?? '-' }} | <strong>الصف:</strong> {{ $student->grade->name ?? '-' }}</p>
    <p class="mb-1 text-danger"><strong>عدد أيام الغياب:</strong> {{ $days->where('is_absent', true)->count() }}</p>
</div>

<div class="table-responsive mb-1"> 
    <table class="table table-secondary table-bordered align-middle mb-0">
        <thead>
            <tr>
                <th class="p-1 text-center">اليوم</th>
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
                    <td>{{ \Carbon\Carbon::parse($day->date)->locale('ar')->dayName }}</td>
                    <td>{{ $day->date }}</td>
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