// 🌐 Common JS for DataTable Initialization
$(function () {
  $('table[data-url]').each(function () {
    const $table = $(this);
    const tableClass = '.' + $table.attr('class').split(' ').filter(c => c.startsWith('table-')).join('.');
    const ajaxUrl = $table.data('url');
    if (ajaxUrl && tableClass) {
      initDataTable(tableClass, ajaxUrl, false, false);
    }
  });
});
