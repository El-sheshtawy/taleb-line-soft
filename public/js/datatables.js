$(document).ready(function() {
  $('.myTable').each(function() {
    var storedPageLength = localStorage.getItem('datatablePageLength');
    var pageLength = storedPageLength ? parseInt(storedPageLength) : 30;
    
    var table = $(this).DataTable({
      dom: '<"top"Bfl>rt<"bottom"ip>',
      buttons: [
        {
          extend: 'excel',
          text: 'تصدير Excel',
          exportOptions: { columns: ':visible' },
        },
        {
          text: 'طباعة pdf',
          action: function(e, dt, node, config) {
              node.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> جاري التصدير...');
              exportToPDF().finally(() => {
                node.prop('disabled', false).text('تصدير PDF');
              });
          },
          className: 'print-pdf'
        }
      ],
      language: {
        url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/ar.json'
      },
      pageLength: pageLength,
      lengthMenu: [[10, 30, 60, 90, 120, -1], [10, 30, 60, 90, 120, "الكل"]],
      initComplete: function() {
        $('.dataTables_filter input, .dataTables_length select, .dataTables_info').css('text-align', 'right');
      },
      drawCallback: function() {
        var api = this.api();
        api.column(0, {page: 'current'}).nodes().each(function(cell, i) {
          cell.innerHTML = i + 1;
        });
      }
    });
    
    table.on('order.dt search.dt page.dt', function() {
      table.column(0, {page: 'current'}).nodes().each(function(cell, i) {
        cell.innerHTML = i + 1;
      });
    });
    
    $(this).on('length.dt', function(e, settings, len) {
      localStorage.setItem('datatablePageLength', len);
    });
  });
  
  
  
  async function exportToPDF() {
    const title = document.title || 'كشف_متابعة_الطلاب';
    const { jsPDF } = window.jspdf;
    const element = document.body; 

    try {
      const canvas = await html2canvas(element, {
        scale: 3,
        useCORS: true
      });
      
      let imgData = canvas.toDataURL("image/png");
      let pdf = new jsPDF("p", "mm", "a2");
      let pageWidth = pdf.internal.pageSize.getWidth();
      let imgWidth = pageWidth;
      let imgHeight = (canvas.height * imgWidth) / canvas.width;

      pdf.addImage(imgData, "PNG", 0, 0, imgWidth, imgHeight);
      
      const cleanTitle = title
        .replace(/\s+/g, '_') 
        .replace(/[^\w\u0600-\u06FF_]/g, ''); 
      
      pdf.save(`${cleanTitle}.pdf`);
    } catch (error) {
      console.error('PDF export failed:', error);
      alert('حدث خطأ أثناء تصدير PDF');
    }
  }
});