<h5><span class="text-primary">المراحل الدراسية  <span class="badge bg-primary text-light">{{count($levels)}}</span> </span></h5>

<div class="table-responsive">
    <table class="table myTable table-secondary table-bordered table-striped align-middle text-nowrap">
        <thead>
            <tr>
                <th class="p-1 text-center">
                    <span class="badge text-warning text-center" style="width:100%;text-align:left;font-weight:bold;">م</span>
                </th>
                <th class="p-1 text-center">المرحلة الدراسي</th>
            </tr>
        </thead>
        <tbody>
            @foreach($levels as $level)
                <tr>
                    <td class="p-1 text-center">{{$loop->iteration}}</td>
                    <td class="p-1 text-center">{{$level->name}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>