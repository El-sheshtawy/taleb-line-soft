<div class="table-responsive mb-1"> 
  <table class="table">
  <thead>
      <tr class="text-center align-middle">
        <th class="p-1">#</th>
        <th class="p-1 text-end">تواريخ المتابعة</th>
        <th>1</th>
        <th>2</th>
        <th>3</th>
        <th>4</th>
        <th>5</th>
        <th>6</th>
        <th>7</th>
        <th class="p-1">غائب</th>
      </tr>
    </thead>

    <tbody id="attendance-body">
    
    </tbody>

</table>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // let studentId = window.location.pathname.split('/')[2]; // Extract student ID from URL
    // console.log(studentId);
    // console.log("Full Path:", window.location.pathname);
    let pathParts = window.location.pathname.split('/');
    console.log("Path Parts:", pathParts); // Debugging log

    // Find the first numeric value (assuming the student ID is always a number)
    let studentId = pathParts.find(part => /^\d+$/.test(part));
    console.log("Extracted Student ID:", studentId);

    if (!studentId) {
        console.error("Student ID not found in URL");
        return;
    }
    fetch(`https://taleb.sitevip.org/taleb_development/public/students/${studentId}/attendance`)
        .then(response => response.json())
        .then(data => {
           

            if (!data.sessions || data.sessions.length === 0) {
                alert("لا توجد بيانات حضور لهذا الطالب.");
                return;
            }

            let groupedSessions = {};

            // Group sessions by date
            data.sessions.forEach(session => {
                let date = session.created_at.split("T")[0]; // Extract date part
                if (!groupedSessions[date]) {
                    groupedSessions[date] = { 1: "", 2: "", 3: "", 4: "", 5: "", 6: "", 7: "" };
                }
                groupedSessions[date][session.session_number] = session.follow_up_item ? session.follow_up_item.letter : "";
            });

            let tbody = document.getElementById("attendance-body");
            tbody.innerHTML = ""; // Clear old data

            let rowIndex = 1;
            for (let date in groupedSessions) {
                let row = `<tr class="text-center align-middle">
                    <td>${rowIndex++}</td>
                    <td class="p-1 text-end">${date}</td>`;
                
                for (let i = 1; i <= 7; i++) {
                    row += `<td>${groupedSessions[date][i] || ""}</td>`;
                }

                row += `<td>${Object.values(groupedSessions[date]).includes("غ") ? "غائب" : ""}</td>`;
                row += `</tr>`;

                tbody.innerHTML += row;
            }
        })
        .catch(error => console.error("Error fetching data:", error));
});
</script>

</div>