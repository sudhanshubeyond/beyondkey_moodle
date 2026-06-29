(function() {
  function $(sel) { return document.querySelector(sel); }

  function loadTable(courseid) {
    var tbody = $('#xplb-tbody');
    var loading = $('#xplb-loading');
    var select = $('#xplb-course');

    if (!tbody || !loading || !select) return;

    loading.style.display = 'block';

    var url = select.getAttribute('data-ajax');
    var sesskey = select.getAttribute('data-sesskey');

    var form = new FormData();
    form.append('courseid', courseid);
    form.append('sesskey', sesskey);

    fetch(url, { method: 'POST', body: form, credentials: 'same-origin' })
      .then(function(r) { return r.text(); })
      .then(function(html) {
        tbody.innerHTML = html;
      })
      .catch(function() {
        tbody.innerHTML = '<tr><td colspan="3">Error loading data</td></tr>';
      })
      .finally(function() {
        loading.style.display = 'none';
      });
  }

  document.addEventListener('DOMContentLoaded', function() {
    var select = $('#xplb-course');
    if (!select) return;

    // Initial load for default-selected course.
    loadTable(select.value);

    // Refresh on change without page reload.
    select.addEventListener('change', function(e) {
      loadTable(e.target.value);
    });
  });
})();
