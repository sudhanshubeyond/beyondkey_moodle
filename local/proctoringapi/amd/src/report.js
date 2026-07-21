define(['core/ajax'], function(Ajax) {
    let currentSort = 'timecreated';
    let currentDir = 'DESC';
    let currentCmid = null;
    let currentInitial = '';
    let currentSearch = '';
    let currentPage = 0;
    let perPage = 10;
    let totalRecords = 0;

    function loadData() {

        Ajax.call([{
            methodname: 'local_proctoringapi_get_ai_report',
            args: {
                cmid: currentCmid,
                sort: currentSort,
                dir: currentDir,
                firstinitial: currentInitial,
                search: currentSearch,
                page: currentPage,
                perpage: perPage
            }
        }])[0].done(function(response) {

            totalRecords = response.total;

            let pagination = response.enablepagenation;

            let tbody = document.querySelector('#proctoring-table tbody');
            tbody.innerHTML = '';

            response.records.forEach(function(row) {

                let tr = document.createElement('tr');

                tr.innerHTML = `
                    <td>${row.fullname}</td>
                    <td>${row.attemptid}</td>
                    <td>${row.focus_score_percent}%</td>
                    <td>${row.cheating_risk_percent}%</td>
                    <td>${row.risk_level}</td>
                    <td>${row.finalobservations ?? ''}</td>
                    <td>${new Date(row.timecreated * 1000).toLocaleString()}</td>
                `;

                tbody.appendChild(tr);
            });

            const paginationContainer = document.getElementById('pagination-container');

            if (pagination) {
                paginationContainer.style.display = 'flex';
                updatePagination();
            } else {
                paginationContainer.style.display = 'none';
            }

        }).fail(function(error) {
            console.error(error);
        });
    }

    function updatePagination() {

        let totalPages = Math.ceil(totalRecords / perPage);

        let pageInfo = document.getElementById('pageinfo');

        if (pageInfo) {
            pageInfo.innerHTML =
                `Page ${currentPage + 1} of ${totalPages} (${totalRecords} Records)`;
        }

        let prev = document.getElementById('prevpage');
        let next = document.getElementById('nextpage');

        if (prev) {
            prev.disabled = (currentPage === 0);
        }

        if (next) {
            next.disabled = (currentPage >= totalPages - 1);
        }
    }

    function attachSorting() {

        document.querySelectorAll('#proctoring-table th[data-sort]').forEach(function(th) {

            th.style.cursor = 'pointer';

            th.addEventListener('click', function() {

                let sortField = this.dataset.sort;

                if (currentSort === sortField) {
                    currentDir = currentDir === 'ASC' ? 'DESC' : 'ASC';
                } else {
                    currentSort = sortField;
                    currentDir = 'ASC';
                }

                currentPage = 0;

                highlightSort();

                loadData();
            });

        });
    }

    function highlightSort() {

        document.querySelectorAll('#proctoring-table th[data-sort]').forEach(function(th) {

            th.innerHTML = th.innerHTML
                .replace(' ↑', '')
                .replace(' ↓', '');

        });

        let active = document.querySelector(
            '#proctoring-table th[data-sort="' + currentSort + '"]'
        );

        if (active) {
            active.innerHTML += currentDir === 'ASC' ? ' ↑' : ' ↓';
        }
    }

    function attachAlphaFilter() {

        document.querySelectorAll('.proctoring-filters a').forEach(function(a) {

            a.addEventListener('click', function(e) {

                e.preventDefault();

                currentInitial = this.dataset.initial;

                currentPage = 0;

                loadData();

            });

        });

    }

    function attachSearch() {

        let box = document.getElementById('searchbox');

        if (!box) {
            return;
        }

        let timer;

        box.addEventListener('keyup', function() {

            clearTimeout(timer);

            timer = setTimeout(function() {

                currentSearch = box.value;

                currentPage = 0;

                loadData();

            }, 400);

        });

    }

    function attachPagination() {

        let prev = document.getElementById('prevpage');

        if (prev) {

            prev.addEventListener('click', function() {

                if (currentPage > 0) {

                    currentPage--;

                    loadData();

                }

            });

        }

        let next = document.getElementById('nextpage');

        if (next) {

            next.addEventListener('click', function() {

                let totalPages = Math.ceil(totalRecords / perPage);

                if (currentPage < totalPages - 1) {

                    currentPage++;

                    loadData();

                }

            });

        }

    }

    function attachPerPage() {

        let select = document.getElementById('perpage');

        if (!select) {
            return;
        }

        select.addEventListener('change', function() {

            perPage = parseInt(this.value);

            currentPage = 0;

            loadData();

        });

    }

    return {

        init: function(cmid) {

            currentCmid = cmid;

            attachSorting();
            attachAlphaFilter();
            attachSearch();
            attachPagination();
            attachPerPage();

            highlightSort();

            loadData();

        }

    };

});
