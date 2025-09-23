$(".openDropdown").on("click", function () {
  $(this).siblings(".dropdown").slideToggle();
});

$(document).on("click", ".checkall", function () {
  let target = $(this).data("target");
  $(target).prop("checked", $(this).prop("checked"));
});

var typeSearch = 1;
$(".searchname").on("change", function () {
  typeSearch = $(".searchname").find(":selected").val();
  mainPage();
});

$(document).on("click", ".checkboxlist", function (e) {
  //btndeletelist
  if ($(".checkboxlist:checkbox:checked").length > 0) {
    $(".btndeletelist").fadeIn();
  } else {
    $(".btndeletelist").fadeOut();
  }
});

// Datatables
function main(gender = "", type = "", bus = "", trip_id = "") {
  var dataTable = $(".myTable").DataTable({
    bAutoWidth: false,
    columnDefs: [
      { orderable: false, targets: 0 },  // Disable sorting for the first column (name)
      { orderable: true, targets: [1, 2, 3] }  // Enable sorting for the other columns
    ],
    order: [[1, "asc"]],
    bDestroy: true,
    responsive: true,
    pageLength: 25,

    initComplete: function (settings, json) {
      $(".getNumber").html("");
      $(".dataTables_info").appendTo(".getNumber");
    },

    language: {
      info: "_TOTAL_",
      sProcessing: "جارٍ التحميل...",
      sLengthMenu: " _MENU_ ",
      sZeroRecords: "لم يعثر على أية سجلات",
      sInfo: "_TOTAL_ ",
      sInfoEmpty: "0",
      sInfoFiltered: "",
      sInfoPostFix: "",
      sSearch: "",
      searchPlaceholder: "ابحث من هنا",
      sUrl: "",
      oPaginate: {
        sFirst: "الأول",
        sPrevious: "السابق",
        sNext: "التالي",
        sLast: "الأخير",
      },
    },
    processing: true,
    serverSide: true,

    ajax: {
      url: "datatables/home.php",
      type: "POST",
      data: {
        gender: gender,
        type: type,
        bus: bus,
        trip_id: trip_id, // Added the new parameter
        typeSearch: typeSearch,
      },
      error: function (xhr, error, code) {
        console.log(xhr, code);
      },
    },
    drawCallback: function (settings) {
      var response = settings.json;
      console.log(response);
      $(".getNumberAttend").html(response.attend);
    },
  });
}
main();

// Handle changes for #typePilgrims
$("#typePilgrims").on("change", function () {
  let typePilgrims = $(this).val();
  let genderPilgrims = $("#genderPilgrims").val();
  let busPilgrims = $("#busPilgrims").val();
  let tripFilter = $("#tripsfilter").val(); // Get the trip_id value
  main(genderPilgrims, typePilgrims, busPilgrims, tripFilter);
});

// Handle changes for #genderPilgrims
$("#genderPilgrims").on("change", function () {
  let typePilgrims = $("#typePilgrims").val();
  let genderPilgrims = $(this).val();
  let busPilgrims = $("#busPilgrims").val();
  let tripFilter = $("#tripsfilter").val(); // Get the trip_id value
  main(genderPilgrims, typePilgrims, busPilgrims, tripFilter);
});

// Handle changes for #busPilgrims
$("#busPilgrims").on("change", function () {
  let typePilgrims = $("#typePilgrims").val();
  let genderPilgrims = $("#genderPilgrims").val();
  let busPilgrims = $(this).val();
  let tripFilter = $("#tripsfilter").val(); // Get the trip_id value
  main(genderPilgrims, typePilgrims, busPilgrims, tripFilter);
});

// Handle changes for #tripsfilter
$("#tripsfilter").on("change", function () {
  let typePilgrims = $("#typePilgrims").val();
  let genderPilgrims = $("#genderPilgrims").val();
  let busPilgrims = $("#busPilgrims").val();
  let tripFilter = $(this).val(); // Get the trip_id value
  main(genderPilgrims, typePilgrims, busPilgrims, tripFilter);
});



// Ajax

$("#createroom").on("submit", function (event) {
  event.preventDefault();
  $.ajax({
    url: "ajax.php",
    method: "POST",
    data: new FormData(this),
    dataType: "json",
    contentType: false,
    cache: false,
    processData: false,

    success: function (data) {
      if (data.status) {
        Swal.fire("تم انشاء الغرفة بنجاح");
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "رقم الغرفة موجود من قبل",
        });
      }
      $("#createroom")[0].reset();
    },
    error: function (err) {
      console.log(err);
    },
  });
});

// $(".openRoom").on("click", function (e) {
//   let id = $(this).data("roomnumber");
//   openRoom(id);
// });

// function openRoom(id) {
//   let idGET = id;
//   $.ajax({
//     url: "ajax.php",
//     method: "POST",
//     data: {
//       action: "openRoom",
//       roomnumber: idGET,
//     },
//     success: function (data) {
//       if (data == "empty") {
//         $(".fetchResponse").html(`
//             <div class="alert alert-warning">لا يوجد حجاج</div>
//         `);
//       } else {
//         $(".fetchResponse").html(data);
//       }
//     },
//   });
// }
$(".openRoom").on("click", function (e) {
  let id = $(this).data("roomnumber");
  let clickedRow = $(this).closest("tr");
  let existingDetailsRow = clickedRow.next(".details-room");

  // If the details for this row are already open, toggle them
  if (existingDetailsRow.length) {
    if (existingDetailsRow.is(":visible")) {
      existingDetailsRow.fadeOut(300, function () {
        $(this).remove();
      });
    } else {
      existingDetailsRow.fadeIn(300);
    }
    return;
  }

  // Remove other open details rows
  $(".details-room").fadeOut(300, function () {
    $(this).remove();
  });

  // Open new room details
  openRoom(id, clickedRow);
});

function openRoom(id, clickedRow) {
  let idGET = id;
  $.ajax({
    url: "ajax.php",
    method: "POST",
    data: {
      action: "openRoom",
      roomnumber: idGET,
    },
    success: function (data) {
      let newRow = $('<tr class="details-room"></tr>');

      let newCell = $('<td colspan="4" style="padding: 10px !important"></td>');

      if (data == "empty") {
        newCell.html('<div class="alert alert-warning">لا يوجد حجاج</div>');
      } else {
        newCell.html(data);
      }

      newRow.append(newCell).hide(); // Initially hide the row
      clickedRow.after(newRow);
      newRow.fadeIn(300); // Fade-in animation
    },
  });
}



// Insert People To Room
$(".insertBtnMain").on("click", function () {
  var room = $("#valueList").find(":selected").val();
  if (room == "") {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "من فضلك حدد الغرفة اولاً",
    });
  } else {
    var idUsers = new Array();
    if ($(".getVote:checkbox:checked").length > 0) {
      $(".getVote:checkbox:checked").each(function (i) {
        idUsers.push($(this).data("id"));
        $.ajax({
          url: "ajax.php",
          method: "POST",
          data: {
            action: "insertVoteMulti",
            idUsers: idUsers,
            room: room,
          },
          beforeSend: function () {
            $(".loading").css("display", "flex");
          },
          success: function (data) {
            if (data == "fullroom") {
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "الغرفة ممتلئة",
              });
            } else {
              Swal.fire({
                icon: "success",
                title: "نجاح",
                text: "تم اضافتة بنجاح",
              });
              var table = $(".myTable").DataTable();
              table.ajax.reload();
            }

            $(".loading").css("display", "none");
          },
        });
      });
    } else {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "من فضلك حدد الاشخاص اولاً",
      });
    }
  }
});

//التفاصيل
$(document).on("click", ".details", function () {
  let id = $(this).data("id");
  getDetails(id);
});

// function getDetails(id) {
//   let getID = id;
//   $.ajax({
//     url: "ajax.php",
//     method: "POST",
//     data: {
//       action: "details",
//       id: getID,
//     },
//     success: function (data) {
//       data = $.trim(data);
//       $(".load_deta_details").html(data);
//     },
//   });
// }
function getDetails(id) {
  let getID = id;
  $.ajax({
    url: "ajax.php",
    method: "POST",
    data: {
      action: "details",
      id: getID,
    },
    success: function (data) {
      data = $.trim(data);

      // Find the clicked row
      const clickedRow = $(`a.details[data-id="${getID}"]`).closest("tr");

      // Check if the details row already exists
      if (clickedRow.next().hasClass("details-row")) {
        // If details row exists, remove it (close the details)
        clickedRow.next().fadeOut(300, function () {
          $(this).remove();
        });
      } else {
        // If no details row exists, remove any existing details row and append the new one
        $("tr.details-row").fadeOut(300, function () {
          $(this).remove();
        });

        const detailsRow = `
          <tr class="details-row" style="display: none;">
            <td colspan="5" style="background: #f9f9f9;">
              <div class="load_deta_details" style="padding: 10px; border: 1px solid #ddd; position: relative;">
                <!-- Close button at the top-left -->
                <button class="close-details" style="position: absolute; top: 10px; left: 10px; font-size: 16px; background: #c51334; color: white; border: none; padding: 3px 10px; cursor: pointer; z-index: 1;">
                  x
                </button>
                ${data}
              </div>
            </td>
          </tr>
        `;

        // Append the new details row and fade it in
        clickedRow.after(detailsRow);
        clickedRow.next().fadeIn(300);  // Fade in the newly added details row
      }
    },
  });
}



$(document).on("click", ".close-details", function () {
  $(this).closest("tr.details-row").remove();
});






// عرض الاشخاص داخل الغرفة
$(document).on("click", ".search_degree", function () {
  let id = $(this).data("id");
  $.ajax({
    url: "ajax.php",
    method: "POST",
    data: {
      action: "search_persons",
      id: id,
    },
    success: function (data) {
      data = $.trim(data);
      $(".load_data_persons").html(data);
    },
  });
});

// تعديل بيانات الشخص


$(document).on("click", ".changeDataDetails", function () {
  const $button = $(this);
  const id = $button.data("id");

  // Function to get value from input or default from button's data attribute
  const getValue = (selector, dataKey) => {
    const $input = $(selector);
    return $input.val() || $button.data(dataKey);
  };

  // Capture values from inputs or default from button's data attributes
  const phone = getValue(".newValuePhone", "phone");
  const phone2 = getValue(".newValuePhone2", "phone2");
  const comment = getValue("#comment", "comment");
  const superior = getValue(".superior", "superior");
  const price = getValue(".newValuePrice", "price");
  const paid = getValue(".newValuePaid", "paid");
  const bus = getValue("#bus-select", "bus");
  const trip = getValue("#trip-select", "trip");

  // Validate that price is greater than or equal to paid
  if (superior == 1 && parseFloat(paid) > parseFloat(price)) {
    Swal.fire({
      icon: "error",
      title: "ERROR",
      text: "المبلغ المدفوع يجب أن يكون أقل من أو يساوي السعر.",
    });
    return; // Stop execution if validation fails
  }

  // Send AJAX request
  $.ajax({
    url: "ajax.php",
    method: "POST",
    data: {
      action: "change_data_person",
      id: id,
      phone: phone,
      phone2: phone2,
      comment: comment,
      superior: superior,
      price: price,
      paid: paid,
      bus: bus,
      trip: trip
    },
    success: function (data) {
      getDetails(id);
      Swal.fire({
        icon: "success",
        title: "SUCCESS",
        text: "تم التعديل بنجاح",
      });
    },
    error: function (xhr, status, error) {
      Swal.fire({
        icon: "error",
        title: "ERROR",
        text: "حدث خطأ أثناء التعديل. يرجى المحاولة مرة أخرى.",
      });
    }
  });
});


// Remove List
$(document).on("click", ".btndeletelist", function (e) {
  e.stopPropagation();
  $(".checkboxlist:checked").each(function () {
    let idList = $(this).data("id");
    $.ajax({
      url: "ajax.php",
      method: "POST",
      data: {
        action: "deleteRooms",
        idList: idList,
      },
      success: function (data) {
        location.reload();
      },
    });
  });
});

$(document).on("click", ".deletePersonFromRoom", function (e) {
  e.preventDefault(); // Prevent default action (if any)

  // Get the person ID and room number
  let id = $(this).data("id");
  let roomnumber = $(this).data("roomnumber");

  // Show the confirmation dialog
  if (confirm("هل أنت متأكد أنك تريد إزالة هذه الشخص من الغرفة؟")) {
    // If user clicks "OK", proceed with the AJAX request
    $.ajax({
      url: "ajax.php",
      method: "POST",
      data: {
        action: "deletePersonFromRoom",
        id: id,
      },
      success: function (data) {
        // Call function to open the room after deletion
        // openRoom(roomnumber);
        location.reload();
      },
      error: function (xhr, status, error) {
        // Handle any errors that occur during the request
        alert("حدث خطأ أثناء العملية.");
      }
    });
  } else {
    // If user clicks "Cancel", do nothing
    return false;
  }
});


// Tasks

var id = $(".next_id_task").data("id");
var id_show_task = $("#id_show_task").val();

// show next id task
$(".next_id_task").on("click", function () {
  let hrefAdd = $("#title_task").find("a").attr("href");
  let type = $(this).data("type");
  console.log(id);
  $.ajax({
    url: "ajax.php",
    type: "post",
    dataType: "json",
    data: { action: "show_next_task", id: id, type: type },
    success: function (response) {
      console.log(response);
      if (type == "next") {
        if (response.next) {
          id_show_task++;
        } else {
          id_show_task = 1;
        }
      } else {
        if (response.prev) {
          id_show_task--;
        } else {
          id_show_task = response.count;
        }
      }

      $("#form_tasks")[0].reset();
      $("#id_show_task").val(id_show_task);
      $("#title_task").html(
        `${response.tasks.title} <a class="btn btn-success" href='${hrefAdd}'>+<a/>`
      );
      $("#id_dask").val(response.tasks.id);
      $("#title_task_page").val(response.tasks.title);
      $("#date_task").val(response.tasks.date);
      $("#desc_task").val(response.tasks.descrption);
      $("#importance_task").val(response.tasks.importance);

      if (response.tasks.status == 1) {
        $("#status_task").attr("checked", true);
      } else {
        $("#status_task").attr("checked", false);
      }
      id = response.tasks.id;
    },
    error: function (xhr, status, error) {
      console.error(error);
    },
  });
});

// Expire Date

var countDownDate = new Date($(".expire_date").val()).getTime();
var x = setInterval(function () {
  $("#timer").css("display", "flex");
  // Get today's date and time
  var now = new Date().getTime();

  // Find the distance between now and the count down date
  var distance = countDownDate - now;

  // Time calculations for days, hours, minutes and seconds
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);

  $("#timer .day").html(days + "يوم");
  $("#timer .hour").html(hours + "ساعة");
  $("#timer .minute").html(minutes + "دقيقة");
  $("#timer .second").html(seconds + "ثانية");

  // If the count down is finished, write some text
  if (distance < 0) {
    clearInterval(x);
    $("#block").css("display", "flex");
    $(".d-textitme").hide();
    $("#timer .day").html("صفر");
    $("#timer .hour").html("صفر");
    $("#timer .minute").html("صفر");
    $("#timer .second").html("صفر");
  }
}, 1000);
