<h5><span class="text-primary">الصفوف الدراسية  <span class="badge bg-primary text-light">{{count($grades)}}</span> </span></h5>

<div class="table-responsive">
    <table class="table myTable table-secondary table-bordered table-striped align-middle text-nowrap">
        <thead>
            <tr class="text-end">
                <th class="p-1 text-center">
                    <span class="badge text-warning text-center" style="width:100%;text-align:left;font-weight:bold;">م</span>
                </th>
                <th class="p-1 text-center">الصف الدراسي</th>
                  <th class="p-1 text-center">المرحلة</th>
            </tr>
        </thead>
        <tbody>
            @foreach($grades as $grade)
                <tr>
                    <td class="p-1 text-center">{{$loop->iteration}}</td>
                    <td class="p-1 text-center">{{$grade->name}}</td>
                    <td class="p-1 text-center">{{$grade->level->name ?? 'N/A'}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>