<div class="table-responsive mb-1"> 
    <table class="table table-secondary table-bordered align-middle mb-0">
        <style>
            @media (max-width: 768px) {
                .table th:nth-child(1),
                .table td:nth-child(1) {
                    width: 15% !important;
                    font-size: 10px !important;
                }
                .table th:nth-child(2),
                .table td:nth-child(2) {
                    width: 25% !important;
                    font-size: 10px !important;
                    white-space: nowrap;
                }
                .table th:nth-child(n+3):nth-child(-n+9),
                .table td:nth-child(n+3):nth-child(-n+9) {
                    width: 7% !important;
                    font-size: 10px !important;
                }
                .table th:last-child,
                .table td:last-child {
                    width: 7% !important;
                    font-size: 10px !important;
                }
            }
        </style>
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