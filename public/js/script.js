// disabled any click
let level = $(".level").val();
if (level == 6) {
  $(".voteButtonMain").prop("disabled", true);
  $("#createList .click").prop("disabled", true);
  // $("#insetListContent").hide();
}

function exportTableToCSV() {
  var csvContent = "\uFEFF"; // BOM (Byte Order Mark) to support UTF-8
  var table = document.querySelector(".styleTable");

  // Get table headers
  var headers = [];
  for (var i = 0; i < table.rows[0].cells.length; i++) {
    headers.push('"' + table.rows[0].cells[i].innerText + '"');
  }
  csvContent += headers.join(",") + "\n";

  // Get table data
  for (var i = 1; i < table.rows.length; i++) {
    var row = [];
    for (var j = 0; j < table.rows[i].cells.length; j++) {
      row.push('"' + table.rows[i].cells[j].innerText + '"');
    }
    csvContent += row.join(",") + "\n";
  }

  // Create a Blob
  var blob = new Blob([csvContent], { type: "text/csv;charset=utf-8;" });

  // Create a download link
  var link = document.createElement("a");
  link.href = URL.createObjectURL(blob);
  link.download = "exported_data.csv";

  // Append the link to the document and trigger a click event
  document.body.appendChild(link);
  link.click();

  // Remove the link from the document
  document.body.removeChild(link);
}

function hideInPrintPdf() {
  const td3 = document.querySelectorAll("table tr td:nth-child(1)");
  const td4 = document.querySelectorAll("table tr td:nth-child(4)");
  const td1 = document.querySelectorAll("table tr td:nth-child(3)");
  td1.forEach((e) => {
    e.style.visibility = "hidden";
  });
  td3.forEach((e) => {
    e.style.visibility = "hidden";
  });
  td4.forEach((e) => {
    e.style.visibility = "hidden";
  });
  const hidePdf = document.querySelectorAll(".hidePdf");

  hidePdf.forEach((e) => {
    e.style.visibility = "hidden";
  });

  if (document.querySelector("#changeChose")) {
    document.querySelector("#changeChose").style.display = "none";
  }
  document.querySelector("#table_search_paginate").style.display = "none";
  document.querySelector("#table_search_filter").style.display = "none";
  document.querySelector("#table_search_length").style.display = "none";
  document.querySelector(".appBtnPrint").style.display = "none";
  document.querySelector("#table_search_info").style.display = "none";
  document.querySelector(".insertMulti").style.visibility = "hidden";
}

function showAfterPrintPdf() {
  const td1 = document.querySelectorAll("table tr td:nth-child(1)");
  const td3 = document.querySelectorAll("table tr td:nth-child(3)");
  const td4 = document.querySelectorAll("table tr td:nth-child(4)");
  const hidePdf = document.querySelectorAll(".hidePdf");

  hidePdf.forEach((e) => {
    e.style.visibility = "visible";
  });

  td1.forEach((e) => {
    e.style.visibility = "visible";
  });

  td4.forEach((e) => {
    e.style.visibility = "visible";
  });

  td3.forEach((e) => {
    e.style.visibility = "visible";
  });
  if (document.querySelector("#changeChose")) {
    document.querySelector("#changeChose").style.display = "inline-block";
  }
  document.querySelector("#table_search_paginate").style.display =
    "inline-block";
  document.querySelector("#table_search_filter").style.display = "inline-block";
  document.querySelector("#table_search_length").style.display = "inline-block";
  document.querySelector(".appBtnPrint").style.display = "inline-block";
  document.querySelector("#table_search_info").style.display = "inline-block";
  document.querySelector(".insertMulti").style.visibility = "visible";
}
function generatePDF() {
  var element = document.querySelector(".parents");
  hideInPrintPdf();
  var pdfPromise = html2pdf(element, {
    margin: 0,
    filename: "table.pdf",
    image: { type: "jpeg", quality: 0.98 },
    html2canvas: { scale: 2 },
    jsPDF: { unit: "mm", format: "a4", orientation: "portrait" },
  });

  pdfPromise
    .then(function (pdf) {
      showAfterPrintPdf();
    })
    .catch(function (error) {
      console.error("Error generating PDF:", error);
    });
}

function printTable(namePage) {
  // Create a new window for printing
  var printWindow = window.open("", "_blank");

  // Get the table content by id
  var tableContent = document.querySelector(".styleTable").outerHTML;

  // Apply additional styles for the printout
  var printStyles = `
     <style>
     @import url('https://fonts.googleapis.com/css2?family=El+Messiri&amp;display=swap');

     body { 
        font-family: 'El Messiri', sans-serif;

        margin: 20px;
        direction:rtl;
    }
      table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
       th, td { border: 1px solid #dddddd; text-align: right; font-size:9px }
        th { background-color: #f2f2f2; }
        table input[type='checkbox']{
            display:none;
        }
        a{
            text-decoration:none;
        }
        table tr td:nth-child(1),
        table tr th:nth-child(1),
        table tr td:nth-child(3),
        table tr th:nth-child(3),
        table tr td:nth-child(4),
        table tr th:nth-child(4)
        {
            display:none;
        }
     </style>`;
  var title = "<title>Printable Table</title>";
  var combinedContent = `<html> ${title}<head>${printStyles}</head><body>
     صفحة ${namePage}
     ${tableContent}
     </body></html>`;

  // Set the content of the print window
  printWindow.document.body.innerHTML = combinedContent;

  // Print the contents of the new window
  printWindow.print();

  // Check if the user clicked "Cancel" in the print dialog
  if (!window.matchMedia("print").matches) {
    // Code to execute if the user clicked "Cancel"
    // For example, you can close the page
    printWindow.close();
  }
}

$(function () {
  function TableMadmen(gender = "", committee = "") {
    var dataTable = $(".getTableMadmen").DataTable({
      bAutoWidth: false,
      // "bInfo" : false,

      columnDefs: [{ orderable: false, targets: 0 }],
      order: [[1, "asc"]],
      bDestroy: true,
      responsive: true,
      pageLength: 100,

      initComplete: function (settings, json) {
        $(".getNumber").html("");
        $(".dataTables_info").appendTo(".getNumber");
        $(".getNumberMadmen2").html("");

        $(".getNumberMadmen2").html($(".getNumber").text());
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
        url: "load_data_madmen.php",
        type: "POST",
        data: {
          nameEvent: $(".nameEvent").val(),
          idEVENT: $(".idEvent").val(),
          idParent: $(".idParent").val(),
          idSupervisor: $(".idSupervisor").val(),
          idUserNow: $(".idUser").val(),
          rank: $(".level").val(),
          gender: gender,
          committee: committee,
        },
      },
      drawCallback: function (settings) {
        // Here the response
        var response = settings.json;
        console.log(response);
        $(".getNumberAttend").html(response.attend);
      },
    });
  }

  function getNumberAttend(type, id) {
    let idSupervisor = $(".idSupervisor").val();
    let idParent = $(".idParent").val();
    let rank = $(".level").val();
    $.ajax({
      url: "_supervisor/ajax_frontend.php",
      method: "POST",
      data: {
        action: "getNumberMadmen",
        type: type,
        idSupervisor: idSupervisor,
        idParent: idParent,
        rank: rank,
      },
      success: function (data) {
        data = $.trim(data);
        $(id).html(data);
      },
    });
  }

  $(document).on("change", "#GenderHome", function () {
    let GenderHome = $("#GenderHome").find(":selected").val();
    let committee = $("#committee").find(":selected").val();

    TableMadmen(GenderHome, committee);
  });
  $(document).on("change", "#committee", function () {
    let GenderHome = $("#GenderHome").find(":selected").val();
    let committee = $("#committee").find(":selected").val();
    TableMadmen(GenderHome, committee);
  });

  $(".getMadmen").on("click", function () {
    $(".home").removeClass("active");
    $(".showMadmenButtonNavbar").addClass("active");

    $(".namePage").html("صفحة  المضامين");
    $(".mainMaleOrFemaleHomePage").hide();
    TableMadmen();

    $("#showMamen").removeClass("d-none");
    $("#showVoters").addClass("d-none");
    $(this).addClass("d-none");
    $(".getVoters").removeClass("d-none");
    $(".areaName").addClass("d-none");
    $(".committees").addClass("d-none");
    $(".headquarters").addClass("d-none");
  });

  $(".getVoters").on("click", function () {
    $(".home").addClass("active");
    $(".showMadmenButtonNavbar").removeClass("active");

    $(".areaName").removeClass("d-none");
    $(".areaName").removeClass("d-none");
    $(".committees").removeClass("d-none");
    $(".headquarters").removeClass("d-none");

    $(".namePage").html("الصفحة الرئيسية");

    $(".mainMaleOrFemaleHomePage").show();
    mainPage();
    $("#showMamen").addClass("d-none");
    $("#showVoters").removeClass("d-none");
    $(this).addClass("d-none");
    $(".getMadmen").removeClass("d-none");
  });

  $(document).on("show.bs.modal", ".modal", function () {
    const zIndex = 1040 + 10 * $(".modal:visible").length;
    $(this).css("z-index", zIndex);
    setTimeout(() =>
      $(".modal-backdrop")
        .not(".modal-stack")
        .css("z-index", zIndex - 1)
        .addClass("modal-stack")
    );
  });

  $(document).on("click", ".btn-print-csv", () => {
    exportTableToCSV();
  });
  $(document).on("click", ".btn-print-pdf", () => {
    generatePDF();
  });
  $(document).on("click", ".print", () => {
    let pageName = $(".print").data("pagename");
    printTable(pageName);
  });

  $(".family_value_search ").select2();

  $(".openDropdown").on("click", function () {
    $(this).siblings(".dropdown").slideToggle();
  });

  var typeSearch = 1;
  $(".searchname").on("change", function () {
    typeSearch = $(".searchname").find(":selected").val();
    mainPage();
  });

  function mainPage(
    mainMaleOrFemale = "all",
    areaName = "all",
    committees = "all",
    headquarters = "all"
  ) {
    var dataTable = $(".myTable").DataTable({
      bAutoWidth: false,
      // "bInfo" : false,
      columnDefs: [{ orderable: false, targets: 0 }],
      order: [[1, "asc"]],
      bDestroy: true,
      responsive: true,
      pageLength: 100,

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
        url: "load_data.php",
        type: "POST",
        data: {
          nameEvent: $(".nameEvent").val(),
          idEVENT: $(".idEvent").val(),
          idParent: $(".idParent").val(),
          idSupervisor: $(".idSupervisor").val(),
          rank: $(".level").val(),
          idUserNow: $(".idUser").val(),
          mainMaleOrFemale: mainMaleOrFemale,
          areaName: areaName,
          typeSearch: typeSearch,
          committees: committees,
          headquarters: headquarters,
        },
      },
      drawCallback: function (settings) {
        // Here the response
        var response = settings.json;
        console.log(response);
        $(".getNumberAttend").html(response.attend);
      },
    });
  }

  mainPage();

  $(document).on("change", "#mainMaleOrFemale", function () {
    let changeChose = $("#mainMaleOrFemale").find(":selected").val();
    let areaName = $(".areaName").find(":selected").val();
    let committees = $(".committees").find(":selected").val();
    let headquarters = $(".headquarters").find(":selected").val();
    mainPage(changeChose, areaName, committees, headquarters);
  });

  $(document).on("change", ".areaName", function () {
    let changeChose = $("#mainMaleOrFemale").find(":selected").val();
    let areaName = $(".areaName").find(":selected").val();
    let committees = $(".committees").find(":selected").val();
    let headquarters = $(".headquarters").find(":selected").val();
    mainPage(changeChose, areaName, committees, headquarters);
  });

  $(document).on("change", ".committees", function () {
    let changeChose = $("#mainMaleOrFemale").find(":selected").val();
    let areaName = $(".areaName").find(":selected").val();
    let committees = $(".committees").find(":selected").val();
    let headquarters = $(".headquarters").find(":selected").val();
    mainPage(changeChose, areaName, committees, headquarters);
  });

  $(document).on("change", ".headquarters", function () {
    let changeChose = $("#mainMaleOrFemale").find(":selected").val();
    let areaName = $(".areaName").find(":selected").val();
    let committees = $(".committees").find(":selected").val();
    let headquarters = $(".headquarters").find(":selected").val();
    mainPage(changeChose, areaName, committees, headquarters);
  });

  // TIMER

  var countDownDate = new Date($(".expireDate").val()).getTime();

  var x = setInterval(function () {
    $("#timer").css("display", "flex");
    // Get today's date and time
    var now = new Date().getTime();

    // Find the distance between now and the count down date
    var distance = countDownDate - now;

    // Time calculations for days, hours, minutes and seconds
    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
    var hours = Math.floor(
      (distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)
    );
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

    $("#timer .day").html(days + "يوم");
    $("#timer .hour").html(hours + "ساعة");
    $("#timer .minute").html(minutes + "دقيقة");
    $("#timer .second").html(seconds + "ثانية");

    // If the count down is finished, write some text
    if (distance < 0) {
      clearInterval(x);
      $(".d-textitme").hide();
      $("#timer .day").html("صفر");
      $("#timer .hour").html("صفر");
      $("#timer .minute").html("صفر");
      $("#timer .second").html("صفر");
    }
  }, 1000);

  $(document).on("click", ".details_name_of_item", function () {
    $(".btn-close").click();
  });

  $(document).on("click", ".btn-search-qarib", function () {
    $(".btn-close-searchuser").click();
  });

  /* 
اضافة كلاس الاكتف وحذفة فى  جميع الحالات
*/

  $(".family_value_search").on("change", function () {
    if ($(this).find(":selected").val() != "none") {
      $(".button_search").removeClass("disabled");
    } else {
      $(".button_search").addClass("disabled");
    }
  });
  $(".input_value_search").on("keyup", function () {
    if ($(this).val().length != 0) {
      $(".button_search").removeClass("disabled");
    } else {
      if ($(".family_value_search").find(":selected").val() == "none") {
        $(".button_search").addClass("disabled");
      }
    }
  });

  // DataTable For Search
  $(document).on("click", ".btn_load_data", function () {
    $("#itemList").hide();
    $("#itemList").find(".btn-close").click();
  });

  $(document).on("click", ".checkboxlist", function (e) {
    //btndeletelist
    if ($(".checkboxlist:checkbox:checked").length > 0) {
      $(".btndeletelist").fadeIn();
    } else {
      $(".btndeletelist").fadeOut();
    }
  });

  $(document).on("click", ".checkall", function () {
    let target = $(this).data("target");
    $(target).prop("checked", $(this).prop("checked"));
  });

  function get_all_counts() {
    let idUser = $(".idUser").val();
    let idSupervisor = $(".idSupervisor").val();

    $.ajax({
      url: "_supervisor/ajax_frontend.php",
      method: "POST",
      data: {
        action: "get_all_counts",
        idUser: idUser,
        idSupervisor: idSupervisor,
      },
      success: function (data) {
        $(".get_counts").html(data);
      },
    });
  }
  get_all_counts();

  $(".get_counts").on("click", function () {
    get_all_counts();
  });

  $(".tableSearchDatatables").DataTable({
    bLengthMenu: false, //thought this line could hide the LengthMenu
    bInfo: false,
    bLengthChange: false, //thought this line could hide the LengthMenu

    language: {
      url: "https://cdn.datatables.net/plug-ins/2.0.0/i18n/ar.json",
    },

    processing: true,
    serverSide: true,
    serverMethod: "post",
    processing: true,
    bDestroy: true,
    bJQueryUI: true,
    ajax: {
      url: "ajaxDatatables.php",
      data: {
        action: "ajaxSearch",

        idEvent: $(".idEvent").val(),
        idUser: $(".idUser").val(),
      },
      error: function (xhr, error, code) {
        console.log(xhr, code);
      },
    },
    drawCallback: function (settings) {
      // Here the response
      var response = settings.json;
      console.log(response);
      // $("#showCountSearchTable").html(response.iTotalDisplayRecords)
    },
    order: [[0, "asc"]],

    pageLength: 10,
    columns: [
      {
        data: "name",
      },
    ],
  });

  // ضماناتي
  $(".tableDamanatiDatatables").DataTable({
    bLengthMenu: false, //thought this line could hide the LengthMenu
    bInfo: false,
    bLengthChange: false, //thought this line could hide the LengthMenu

    language: {
      url: "https://cdn.datatables.net/plug-ins/2.0.0/i18n/ar.json",
      sSearch: "ضماناتي",
      searchPlaceholder: "ابحث",
    },

    processing: true,
    serverSide: true,
    serverMethod: "post",
    processing: true,
    bDestroy: true,
    bJQueryUI: true,
    ajax: {
      url: "ajaxDatatables.php",
      data: {
        action: "ajaxDamanati",

        idEvent: $(".idEvent").val(),
        idUser: $(".idUser").val(),
        idSuperVisor: $(".idSupervisor").val(),
      },
    },
    drawCallback: function (settings) {
      // Here the response
      // var response = settings.json;
      // $("#showCountSearchTable").html(response.iTotalDisplayRecords)
    },
    order: [[0, "asc"]],

    pageLength: 10,
    columns: [
      {
        data: "name",
      },
    ],
  });

  $("#select_option").on("change", function () {
    let val = $(this).val();
    ajaxTasks(val);
  });
  ajaxTasks();
  function ajaxTasks(val_selected = 0) {
    $(".tableTasks").DataTable({
      bLengthMenu: false, //thought this line could hide the LengthMenu
      bInfo: false,
      bLengthChange: false, //thought this line could hide the LengthMenu

      language: {
        url: "https://cdn.datatables.net/plug-ins/2.0.0/i18n/ar.json",
      },

      processing: true,
      serverSide: true,
      serverMethod: "post",
      processing: true,
      bDestroy: true,
      bJQueryUI: true,
      ajax: {
        url: "ajaxDatatables.php",
        data: {
          action: "ajaxTasks",
          idEvent: $(".idEvent").val(),
          idUser: $(".idUser").val(),
          username: $(".username").val(),
          idFrontend: $(".idFrontend").val(),
          val_selected: val_selected,
        },
      },
      drawCallback: function (settings) {
        // Here the response
        var response = settings.json;
        $(".getNumberSearchPageTasks").html(response.iTotalDisplayRecords);
      },

      pageLength: 10,
      columns: [
        {
          data: "counter",
        },
        {
          data: "title",
        },

        {
          data: "importance",
        },
      ],
    });
  }

  $("#listorderto").on("change", function () {
    let listorderto = $(this).val();
    let listmandoub = $("#listmandoub").val();
    tableTransAction(listorderto, listmandoub);
  });
  $("#listmandoub").on("change", function () {
    let listorderto = $("#listorderto").val();
    let listmandoub = $(this).val();
    tableTransAction(listorderto, listmandoub);
  });
  $("#doneTrans").on("click", function () {
    let listorderto = $("#listorderto").val();
    let listmandoub = $("#listmandoub").val();
    $(this).hide();
    $("#dontdoneTrans").removeClass("d-none");

    tableTransAction(listorderto, listmandoub, "1");
  });
  $("#dontdoneTrans").on("click", function () {
    $("#dontdoneTrans").addClass("d-none");
    $("#doneTrans").show();
    tableTransAction();
  });
  tableTransAction();

  function tableTransAction(
    listorderto = "",
    listmandoub = "",
    donetrans = ""
  ) {
    $(".tableTransactions").DataTable({
      bLengthMenu: false, //thought this line could hide the LengthMenu
      bInfo: false,
      bLengthChange: false, //thought this line could hide the LengthMenu

      language: {
        url: "https://cdn.datatables.net/plug-ins/2.0.0/i18n/ar.json",
      },

      processing: true,
      serverSide: true,
      serverMethod: "post",
      processing: true,
      bDestroy: true,
      bJQueryUI: true,
      ajax: {
        url: "ajaxDatatables.php",
        data: {
          action: "ajaxTransactions",
          idEvent: $(".idEvent").val(),
          idUser: $(".idUser").val(),
          username: $(".username").val(),
          idFrontend: $(".idFrontend").val(),
          listorderto: listorderto,
          listmandoub: listmandoub,
          donetrans: donetrans,
        },
      },
      drawCallback: function (settings) {
        // Here the response
        var response = settings.json;
        console.log(response);
        $(".getNumberSearchPage").html(response.iTotalDisplayRecords);
      },

      pageLength: 10,
      columns: [
        {
          data: "counter",
        },
        {
          data: "title",
        },
        {
          data: "order_to",
        },
        {
          data: "mandoub",
        },
        {
          data: "importance",
        },
      ],
    });
  }

  // $(document).on("click",".checkbox_ajax",function(){
  //     let check = null;
  //     let id = $(this).data("idvoter");
  //     let username = $(this).data("username");
  //     if($(this).is(':checked')){
  //          check = "checked";
  //     } else {
  //          check = "unchecked"
  //     }

  //     $.ajax({
  //         url:"_supervisor/ajax_frontend.php",
  //         method:"POST",
  //         data : {
  //             action : "checkSearchVoters",
  //             check : check,
  //             idUser : $(".idUser").val(),
  //             id : id,
  //             username : username,
  //             idFrontend : $(".idFrontend").val(),
  //             idSupervisor : $(".idSupervisor").val()
  //         },
  //         success : function(data)
  //         {
  //         }
  //     })

  // })

  // Update Tasks
  $(document).on("click", ".editTask", function () {
    $("#formUpdateTask")[0].reset(); // Reset form elements

    $("#id").val($(this).data("id"));
    $("#title_task").val($(this).data("title"));
    $("#date_task").val($(this).data("date"));
    $("#descrption_task").val($(this).data("descrption"));
    $("#importance_task").val($(this).data("importance"));
    $("#id_task").val($(this).data("id"));
    if ($(this).data("status") == 1) {
      $("#status_task").attr("checked", true);
    } else {
      $("#status_task").attr("checked", false);
    }
  });

  $("#formUpdateTask").submit(function (e) {
    e.preventDefault();
    var formData = $(this).serialize();
    $.ajax({
      url: "_supervisor/ajax_frontend.php",
      type: "post",
      data: formData,
      success: function (response) {
        response = $.trim(response);
        var table = $(".tableTasks").DataTable();
        table.ajax.reload();
      },
      error: function (xhr, status, error) {
        console.error(xhr.responseText);
      },
    });
  });

  // delete task
  $(document).on("click", "#btnDeleteTask", function () {
    let con = confirm("سوف تقوم بعملية الحذف ?");
    let id = $(this).data("id");
    if (con) {
      $.ajax({
        url: "_supervisor/ajax_frontend.php",
        type: "post",
        data: { action: "delete_task", id: id },
        success: function (response) {
          response = $.trim(response);
          if (response == "done") {
            var table = $(".tableTasks").DataTable();
            table.ajax.reload();
          }
        },
        error: function (xhr, status, error) {
          console.error(xhr.responseText);
        },
      });
    }
  });

  var id = $(".next_id_task").data("id");
  var id_show_task = $("#id_show_task").val();

  // show next id task
  $(".next_id_task").on("click", function () {
    let hrefAdd = $("#title_task").find("a").attr("href");
    let id_user = $(".idSupervisor").val();

    let type = $(this).data("type");
    $.ajax({
      url: "_supervisor/ajax_frontend.php",
      type: "post",
      dataType: "json",
      data: { action: "show_next_task", id: id, id_user: id_user, type: type },
      success: function (response) {
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

  var id_trans = $(".next_id_trans").data("id");
  var i = $("#id_show_trans").val();
  // show next id task
  $(".next_id_trans").on("click", function () {
    let hrefAdd = $("#title_trans").find("a").attr("href");
    let id_user = $(".idSupervisor").val();
    let type = $(this).data("type");
    $.ajax({
      url: "_supervisor/ajax_frontend.php",
      type: "post",
      dataType: "json",
      data: {
        action: "show_next_trans",
        id: id_trans,
        id_user: id_user,
        type: type,
      },
      success: function (response) {
        $("#form_trans")[0].reset();
        // Images
        if (response.images != null) {
          $(".showImages").empty();
          $(response.images).each(function (key) {
            $(".showImages").append(`
                    <a href="/_supervisor/uploads/users/${
                      response.images[key].filename
                    }" class="img-trans btn btn-dark btn-sm" target="_blank" >${key + 1} <i class="ri-folder-image-line"></i></a>
                    <a href="#" data-id="${
                      response.images[key].id
                    }" data-filename="${response.images[key].filename}"
                    class="btn btn-danger btn-sm delete_trans_image"><i class="ri-delete-bin-6-fill">
                    </i></a>
                    `);
          });
        } else {
          $(".showImages").empty();
        }

        if (type == "next") {
          if (response.next) {
            i++;
          } else {
            i = 1;
          }
        } else {
          if (response.prev) {
            i--;
          } else {
            i = response.count;
          }
        }

        $("#id_show_trans").val(i);
        $("#id_show_trans_hidden").val(`${response.trans.id}`);

        $("#title_trans").html(
          `${response.trans.applicant} <a class="btn btn-success" href='${hrefAdd}'>+<a/>`
        );
        $("#date").val(response.trans.date_trans);
        $("#applicant").val(response.trans.applicant);
        $("#phone_applicant").val(response.trans.phone_applicant);
        $("#order_by").val(response.trans.order_by);
        $("#phone_order_by").val(response.trans.phone_order_by);
        $("#almueamala").val(response.trans.almueamala);
        $("#details").val(response.trans.details);
        $(".direct_to").each(function (key, value) {
          $(this).attr("selected", false);
          if ($(this).val() == response.trans.direct_to) {
            $(this).attr("selected", true);
          }
        });

        $(".almandub").each(function (key, value) {
          $(this).attr("selected", false);
          if ($(this).val() == response.trans.almandub) {
            $(this).attr("selected", true);
          }
        });

        $(".importance").each(function (key, value) {
          $(this).attr("selected", false);
          if ($(this).val() == response.trans.importance) {
            $(this).attr("selected", true);
          }
        });

        // $(".status_order").attr("checked",false);
        // if(response.status_order)
        // {
        //     $(".status_order").attr("checked",true)
        // }

        $(".option_order").each(function (key, value) {
          $(this).attr("checked", false);
          if ($(this).val() == response.trans.status_order) {
            $(this).attr("checked", true);
          }
        });

        $(".comment").val(response.trans.comment);
        id_trans = response.trans.id;
      },
      error: function (xhr, status, error) {
        console.error(error);
      },
    });
  });

  // اضافة مندوب جديد للمرشح بواسطة المرشح فى صفحة المعاملات
  $("#add_from_mandoub").on("submit", function (e) {
    e.preventDefault();
    let id_user = $(".idSupervisor").val();

    let name = $(this).find("#name").val();
    $.ajax({
      url: "_supervisor/ajax_frontend.php",
      type: "post",
      dataType: "json",
      data: { action: "add_new_mandoub", name: name, id_user: id_user },
      success: function (response) {
        if (response.status == 1) {
          $("#add_from_mandoub")[0].reset();
          $("#add_new_mandoub_modal").modal("hide");
          $("#almandub").empty();

          $.each(response.data, function (index, val) {
            $("#almandub").append(`
                    <option value='${val["id"]}'>${val["name"]}</option>
                    `);
          });
        }
      },
    });
  });

  // Add Vote From Search Page
  $(document).on("click", ".addVoteFromSearch", function (e) {
    let check = null;
    let id = $(this).data("idvoter");
    let username = $(this).data("username");
    let classname = $(this).data("classname");

    $.ajax({
      url: "_supervisor/ajax_frontend.php",
      method: "POST",
      data: {
        action: "checkSearchVoters",
        check: check,
        idUser: $(".idUser").val(),
        id: id,
        username: username,
        classname: classname,
        idFrontend: $(".idFrontend").val(),
        idSupervisor: $(".idSupervisor").val(),
      },
      success: function (data) {
        var table = $(".tableSearchDatatables").DataTable();
        table.ajax.reload();

        var table = $(".tableDamanatiDatatables").DataTable();
        table.ajax.reload();
      },
    });
  });

  var idMandoub = $("#deleteMandoub").data("id");
  $("#almandub").on("change", function () {
    idMandoub = $(this).val();
  });

  $("#deleteMandoub").click(function (e) {
    e.preventDefault();

    $.ajax({
      url: "_supervisor/ajax_frontend.php",
      method: "POST",
      dataType: "json",
      data: {
        action: "deleteMandoub",
        id: idMandoub,
        idSupervisor: $(".idSupervisor").val(),
      },
      success: function (response) {
        if (response.status == 1) {
          $("#almandub").empty();
          $.each(response.data, function (index, val) {
            $("#almandub").append(`
               <option value='${val["id"]}'>${val["name"]}</option>
               `);
          });
        }
      },
    });
  });

  $("#add_new_order_to_form").on("submit", function (e) {
    e.preventDefault();
    let id_user = $(".idSupervisor").val();
    let name = $(this).find("#name").val();

    $.ajax({
      url: "_supervisor/ajax_frontend.php",
      type: "post",
      dataType: "json",
      data: { action: "add_new_order_to", name: name, id_user: id_user },
      success: function (response) {
        if (response.status == 1) {
          $("#add_new_order_to_form")[0].reset();
          $("#add_new_order_to_modal").modal("hide");
          $("#direct_to").empty();

          $.each(response.data, function (index, val) {
            $("#direct_to").append(`
                <option value='${val["id"]}'>${val["name"]}</option>
                `);
          });
        }
      },
    });
  });

  // حذف موجه الي

  var idOrderTo = $("#deleteMandoub").data("id");
  $("#direct_to").on("change", function () {
    idOrderTo = $(this).val();
  });

  $("#deleteOrderTo").click(function (e) {
    e.preventDefault();
    $.ajax({
      url: "_supervisor/ajax_frontend.php",
      method: "POST",
      dataType: "json",
      data: {
        action: "deleteOrderTo",
        id: idOrderTo,
        idSupervisor: $(".idSupervisor").val(),
      },
      success: function (response) {
        if (response.status == 1) {
          $("#direct_to").empty();
          $.each(response.data, function (index, val) {
            $("#direct_to").append(`
               <option value='${val["id"]}'>${val["name"]}</option>
               `);
          });
        }
      },
    });
  });

  // Delete Images Trans
  $(document).on("click", ".delete_trans_image", function (e) {
    e.preventDefault();
    let con = confirm("سوف تقوم بحذف الصورة");
    let id = $(this).data("id");
    let filename = $(this).data("filename");
    if (con) {
      $.ajax({
        url: "_supervisor/ajax_frontend.php",
        method: "POST",
        data: {
          action: "deleteTransImage",
          id: id,
          filename: filename,
        },
        success: function (response) {
          $(this).prev().remove();

          $(this).remove();
        },
      });
    }
  });

  $(document).on("keyup", ".reviews", function () {
    let iduser = $(this).data("iduser");
    let idusernow = $(this).data("idusernow");
    let value = $(this).val();
    $.ajax({
      url: "_supervisor/ajax_frontend.php",
      method: "POST",
      data: {
        action: "insertreview",
        iduser: iduser,
        idusernow: idusernow,
        value: value,
      },
      success: function (response) {},
    });
  });
});
