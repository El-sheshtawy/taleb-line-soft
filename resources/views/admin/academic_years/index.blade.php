<h5><span class="text-primary"> الاعوام الدراسية<span class="badge bg-primary text-light">{{count($academicYears)}}</span> </span></h5>
<div class="table-responsive">
    <table class="table myTable table-secondary table-bordered table-striped align-middle text-nowrap">
        <thead>
            <tr class="text-center">
    	        <th class="p-1 text-center"><span class="badge text-warning text-center" style="width:100%; text-align:left; font-weight:bold;">م</span></th>
    	        <th class="p-1 text-center">الاسم</th>
    	        <th class="p-1 text-center">البداية</th>
    	        <th class="p-1 text-center">النهاية</th>
    	        <th class="p-1 text-center">الحالة</th>
            </tr>
        </thead>
        <tbody>
            @foreach($academicYears as $academicYear)
                <tr>
                    <td class="p-1 text-center">{{$loop->iteration}}</td>
                    <td class="p-1 text-center">
                        <a href="#" class="edit-academicYear-btn" 
                            data-bs-toggle="modal" 
                            data-bs-target="#editAcademicYearModal" 
                            data-academicYear="{{ json_encode($academicYear) }}"
                            data-edit-action="{{ route('admin.academic-years.update', $academicYear->id) }}"
                            data-delete-action="{{ route('admin.academic-years.destroy', $academicYear->id) }}">
                            {{ $academicYear->name }}
                        </a>
                    </td>
                    <td class="p-1 text-center">{{ $academicYear->start_date }}</td>
                    <td class="p-1 text-center">{{ $academicYear->end_date }}</td>
                    <td class="p-1 text-center">
                        <b class="text-{{ $academicYear->status == 'inactive' ? 'danger' : 'success' }}">{{ $academicYear->status == 'inactive' ? 'غير نشط' : 'نشط' }}</b>
                    </td>
                </tr>
                
            @endforeach
        </tbody>
    </table>
</div>


<button  type="button" class="block btn btn-primary w-100 mt-4 text-center" data-bs-toggle="modal" data-bs-target="#createAcademicYearModal">إنشاء عام</button>


@include('admin.academic_years.create')
@include('admin.academic_years.edit')
@include('admin.academic_years.delete')

<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".edit-academicYear-btn").forEach(button => {
        button.addEventListener("click", function () {
            let academicYearData = JSON.parse(this.getAttribute("data-academicYear"));
            
            document.getElementById("updateAcademicYearForm").action = this.getAttribute("data-edit-action");
            document.getElementById("deleteAcademicYearForm").action = this.getAttribute("data-delete-action");

            document.getElementById("edit_academic_year_name").value = academicYearData.name;
            document.getElementById("edit_academic_year_start_date").value = academicYearData.start_date;
            document.getElementById("edit_academic_year_end_date").value = academicYearData.end_date;
            document.getElementById("edit_academic_year_status").value = academicYearData.status;
        });
    });
});
</script>

