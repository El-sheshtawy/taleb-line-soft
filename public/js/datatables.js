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
              const activeTable = document.querySelector('.tab-pane.active .myTable, .myTable');
              if (!activeTable) {
                alert('لم يتم العثور على جدول لتصديره');
                return;
              }

              const activeTabTitle = document.querySelector('.tab-pane.active h5, h5')?.textContent || 'تقرير';
              const tableHTML = activeTable.outerHTML;
              
              const form = document.createElement('form');
              form.method = 'POST';
              form.action = '/admin/export-pdf';
              form.target = '_blank';
              
              const csrfInput = document.createElement('input');
              csrfInput.type = 'hidden';
              csrfInput.name = '_token';
              csrfInput.value = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
              
              const titleInput = document.createElement('input');
              titleInput.type = 'hidden';
              titleInput.name = 'title';
              titleInput.value = activeTabTitle;
              
              const tableInput = document.createElement('input');
              tableInput.type = 'hidden';
              tableInput.name = 'tableData';
              tableInput.value = tableHTML;
              
              form.appendChild(csrfInput);
              form.appendChild(titleInput);
              form.appendChild(tableInput);
              
              document.body.appendChild(form);
              console.log('Form created:', form);
              console.log('CSRF token:', csrfInput.value);
              console.log('Title:', titleInput.value);
              console.log('Table HTML length:', tableInput.value.length);
              
              // Try direct window.open first
              const url = '/admin/export-pdf';
              const params = new URLSearchParams();
              params.append('_token', csrfInput.value);
              params.append('title', titleInput.value);
              params.append('tableData', tableInput.value);
              
              fetch(url, {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: params
              })
              .then(response => response.text())
              .then(html => {
                const newWindow = window.open('', '_blank');
                newWindow.document.write(html);
                newWindow.document.close();
              })
              .catch(error => {
                console.error('Error:', error);
                alert('حدث خطأ في التصدير');
              });
              
              document.body.removeChild(form);
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
});