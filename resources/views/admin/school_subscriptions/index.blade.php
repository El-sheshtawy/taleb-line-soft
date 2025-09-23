<h5><span class="text-primary">الاشتراكات المدرسية <span class="badge bg-primary text-light">{{count($schoolAccountSubscriptions)}}</span> </span></h5>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<div class="table-responsive">
    <table class="table myTable table-secondary table-bordered table-striped align-middle text-nowrap">
        <thead id="tableHeaders">
            <tr class="text-center">
                <th class="p-1 text-center">م</th>
                    <th class="p-1 text-center">المدرسة</th>
                    <th class="p-1 text-center">تاريخ البداية</th>
                    <th class="p-1 text-center">تاريخ النهاية</th>
            </tr>
        </thead>
        <tbody>
            @foreach($schoolAccountSubscriptions as $schoolAccountSubscription)
                <tr class="edit-subscription-btn text-center" 
                            data-bs-toggle="modal" 
                            data-bs-target="#editSchoolSubscriptionModal" 
                            data-schoolSubscription="{{ json_encode($schoolAccountSubscription) }}"
                            data-edit-action="{{ route('admin.school-subscriptions.update', $schoolAccountSubscription->id) }}"
                            data-delete-action="{{ route('admin.school-subscriptions.destroy', $schoolAccountSubscription->id) }}">
                    <td class="p-1 text-center">{{$loop->iteration}}</td>
                    <td class="p-1 text-center">{{$schoolAccountSubscription->schoolAccount->school_name}}</td>	
                    <td class="p-1 text-center">{{$schoolAccountSubscription->start_date}}</td>
                    <td class="p-1 text-center">{{$schoolAccountSubscription->end_date}}</td>	
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<button  type="button" class="block btn btn-primary w-100 my-4 text-center" data-bs-toggle="modal" data-bs-target="#createSchoolSubscriptionModal">انشاء اشتراك</button>


@include('admin.school_subscriptions.create')
@include('admin.school_subscriptions.edit')
@include('admin.school_subscriptions.delete')

<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".edit-subscription-btn").forEach(button => {
        button.addEventListener("click", function () {
            let schoolSubscriptionData = JSON.parse(this.getAttribute("data-schoolSubscription"));

            document.getElementById("editSchoolSubscriptionForm").action = this.getAttribute("data-edit-action");
            document.getElementById("deleteSchoolSubscriptionForm").action = this.getAttribute("data-delete-action");
            
            document.getElementById("edit_subscription_school_id").value = schoolSubscriptionData.school_id;
            document.getElementById("edit_subscription_start_date").value = schoolSubscriptionData.start_date;
            document.getElementById("edit_subscription_end_date").value = schoolSubscriptionData.end_date;
        });
    });
});
</script>

