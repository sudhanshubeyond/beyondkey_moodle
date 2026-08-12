
define(['core/ajax', 'core/templates'], function(Ajax, Templates) {

    let currentPage = 0;
    const perPage = 10;
    let currentSearch = '';
    let currentSort = 'timemodified';
    let currentDir = 'DESC';

    /**
     * Initialize report.
     *
     * @param {Number} courseid Course ID.
     * @param {Number} cmid Course module ID.
     * @param {Number} studentid Student ID.
     * @returns {void}
     */
    const init = (courseid, cmid, studentid) => {

        /*
         * Initial page load.
         *
         * IMPORTANT:
         * showStudentReport is false here.
         * Therefore only the main report is displayed.
         */
        loadData(courseid, cmid, null, '', null, false);

        /*
         * Search.
         */
        const searchElement = document.getElementById('searchKey');

        if (searchElement) {
            searchElement.addEventListener('keyup', function(e) {

                currentSearch = e.target.value;
                currentPage = 0;

                loadData(courseid, cmid, null, currentSearch, null, false);
            });
        }

        /*
         * Sorting.
         */
        attachSorting(courseid, cmid);
        updateSortIcons();

        /*
         * View Images.
         *
         * The action menu is generated dynamically,
         * so event delegation is required.
         */
        document.addEventListener('click', function(e) {

            const link = e.target.closest('.view-student-report');

            if (!link) {
                return;
            }

            e.preventDefault();
            e.stopPropagation();

            const selectedCourseId = link.dataset.courseid;
            const selectedCmid = link.dataset.cmid;
            const selectedStudentId = link.dataset.studentid;
            const selectedReportId = link.dataset.reportid;

            if (!selectedStudentId || !selectedReportId) {
                console.error(
                    'studentid or reportid is missing.'
                );
                return;
            }

            currentPage = 0;

            /*
             * Load student report ONLY now.
             */
            loadData(selectedCourseId, selectedCmid, selectedStudentId, '', selectedReportId, true);
        });
    };


    /**
     * Attach sorting.
     *
     * @param {Number} courseid Course ID.
     * @param {Number} cmid Course module ID.
     * @returns {void}
     */
    function attachSorting(courseid, cmid) {

        document.querySelectorAll('[data-sort]').forEach(function(column) {

            column.addEventListener('click', function(e) {

                e.preventDefault();

                const newSort = column.getAttribute('data-sort');

                if (currentSort === newSort) {
                    currentDir = currentDir === 'ASC' ? 'DESC' : 'ASC';
                } else {
                    currentSort = newSort;
                    currentDir = 'ASC';
                }

                currentPage = 0;

                /*
                 * Sorting should show only
                 * the main report.
                 */
                updateSortIcons();

                loadData(courseid, cmid, null, currentSearch, null, false);
            });
        });
    }

    // Sort Icons
    function updateSortIcons() {
        document.querySelectorAll('[data-sort]').forEach(function(column) {

            const sortField = column.getAttribute('data-sort');
            const icon = column.querySelector('.sort-icon');

            if (!icon) {
                return;
            }

            if (sortField === currentSort) {

                if (currentDir === 'ASC') {
                    icon.textContent = ' ↑';
                } else {
                    icon.textContent = ' ↓';
                }

            } else {
                icon.textContent = '';
            }
        });
    }


    /**
     * Load report data using AJAX.
     *
     * @param {Number} courseid Course ID.
     * @param {Number} cmid Course module ID.
     * @param {Number|null} studentid Student ID.
     * @param {String} currentSearch Search text.
     * @param {Number|null} reportid Report ID.
     * @param {Boolean} showStudentReport Whether to display student report.
     * @returns {void}
     */
    function loadData(courseid, cmid, studentid, currentSearch = '', reportid = null, showStudentReport = false) {
        const args = {
            courseid: parseInt(courseid, 10),
            cmid: parseInt(cmid, 10),

            studentid: studentid ?
                parseInt(studentid, 10) : 0,

            page: parseInt(currentPage, 10),

            perpage: parseInt(perPage, 10),

            searchkey: currentSearch || '',

            sort: currentSort,

            dir: currentDir,

            reportid: reportid ? parseInt(reportid, 10) : 0
        };

        Ajax.call([{
            methodname: 'quizaccess_proctoring_get_report',
            args: args

        }])[0].then(function(result) {
            if (!result) {
                throw new Error(
                    'Empty response received from AJAX.'
                );
            }

            /*
             * Render pagination.
             */
            if (result.records && typeof result.records.total !== 'undefined') {
                renderPagination(result.records.total, courseid, cmid);
            }

            /*
             * --------------------------------------------------
             * MAIN REPORT
             * --------------------------------------------------
             */
            let reportPromise = Promise.resolve('');

            if (result.records) {
                reportPromise = Templates.render('quizaccess_proctoring/report', result.records);
            }

            /*
             * --------------------------------------------------
             * STUDENT REPORT
             * --------------------------------------------------
             *
             * Only render this when View Images was clicked.
             */
            let studentReportPromise = Promise.resolve('');

            if (showStudentReport && result.records2 && typeof result.records2 === 'object') {
                studentReportPromise = Templates.render(
                    'quizaccess_proctoring/studentreport',
                    result.records2
                );
            }

            return Promise.all([
                reportPromise,
                studentReportPromise
            ]);

        }).then(function(htmls) {

            const reportHtml = htmls[0];
            const reportTemp = document.createElement('div');
            reportTemp.innerHTML = reportHtml;

            const newTbody = reportTemp.querySelector('#report-body');

            const currentTbody = document.getElementById('report-body');

            if (newTbody && currentTbody) {
                currentTbody.innerHTML = newTbody.innerHTML;
            }
            const studentReportHtml = htmls[1];

            /*
             * ==================================================
             * Render MAIN REPORT
             * ==================================================
             */
            if (reportHtml) {

                const reportTemp = document.createElement('div');

                reportTemp.innerHTML = reportHtml;

                const newTbody = reportTemp.querySelector('#report-body');

                const currentTbody = document.getElementById('report-body');

                if (newTbody && currentTbody) {
                    currentTbody.innerHTML = newTbody.innerHTML;
                }

                updateSortIcons();

                /*
                 * Update report action buttons.
                 */
                const newActions = reportTemp.querySelector('#report-actions');

                const currentActions = document.getElementById('report-actions');

                if (newActions && currentActions) {
                    currentActions.innerHTML = newActions.innerHTML;
                }
            }

            /*
             * ==================================================
             * Render STUDENT REPORT
             * ==================================================
             *
             * This happens ONLY after clicking View Images.
             */
            if (showStudentReport && studentReportHtml) {
                const studentContainer = document.getElementById('student-report');
                if (studentContainer) {
                    studentContainer.innerHTML = studentReportHtml;

                    /*
                     * Scroll to student report.
                     */
                    studentContainer.scrollIntoView({behavior: 'smooth', block: 'start'});

                } else {
                    console.error('#student-report element was not found.');
                }
            }

        }).catch(function(error) {
            console.error('Error loading proctoring report:', error);
        });
    }


    /**
     * Render pagination.
     *
     * @param {Number} total Total records.
     * @param {Number} courseid Course ID.
     * @param {Number} cmid Course module ID.
     * @returns {void}
     */
    function renderPagination(total, courseid, cmid) {

        const pagination = document.getElementById('report-pagination');

        if (!pagination) {
            return;
        }

        pagination.innerHTML = '';

        const totalPages = Math.ceil(total / perPage);

        if (totalPages <= 1) {
            return;
        }

        for (let i = 0; i < totalPages; i++) {

            const button = document.createElement('button');

            button.type = 'button';

            button.className = 'btn btn-sm btn-secondary mx-1';

            if (i === currentPage) {
                button.classList.add('active');
            }

            button.textContent = i + 1;

            button.addEventListener('click', function() {

                currentPage = i;

                /*
                 * Pagination should show
                 * only the main report.
                 */
                loadData(courseid, cmid, null, currentSearch, null, false);
            });

            pagination.appendChild(button);
        }
    }

    return {
        init: init
    };
});
