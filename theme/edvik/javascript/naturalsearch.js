let searchTimer = null;

const init = () => {

    const searchBox =
        document.getElementById('edvik-natural-search');

    const searchForm =
        document.getElementById('edvik-natural-search-form');

    const resultsContainer =
        document.getElementById('edvik-natural-search-results');

    if (!searchBox || !searchForm || !resultsContainer) {
        return;
    }

    /*
     * Handle form submit.
     */
    searchForm.addEventListener('submit', function(event) {

        event.preventDefault();

        const query = searchBox.value.trim();

        performSearch(query, resultsContainer);
    });


    /*
     * Search while typing.
     *
     * 500ms debounce prevents an API request
     * for every single character.
     */
    searchBox.addEventListener('input', function() {

        const query = this.value.trim();

        clearTimeout(searchTimer);

        if (!query) {
            resultsContainer.innerHTML = '';
            return;
        }

        searchTimer = setTimeout(function() {

            performSearch(
                query,
                resultsContainer
            );

        }, 500);
    });
};


/*
 * Call Moodle AJAX service directly.
 *
 * This replaces Moodle's AMD core/ajax module.
 */

const performSearch = (
    query,
    resultsContainer
) => {

    if (!query) {
        return;
    }

    resultsContainer.innerHTML =
        '<div class="p-3">Searching...</div>';

    const url =
        M.cfg.wwwroot +
        '/lib/ajax/service.php?sesskey=' +
        encodeURIComponent(M.cfg.sesskey);

    fetch(url, {
        method: 'POST',

        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },

        body: JSON.stringify([
            {
                index: 0,
                methodname: 'local_naturalsearch_search',
                args: {
                    query: query,
                    page: 0,
                    limit: 20
                }
            }
        ])
    })
    .then(function(response) {

        console.log(
            'HTTP status:',
            response.status
        );

        return response.text().then(function(text) {

            console.log(
                'Raw Moodle response:',
                text
            );

            if (!response.ok) {
                throw new Error(
                    'HTTP ' +
                    response.status +
                    ': ' +
                    text
                );
            }

            if (!text) {
                throw new Error(
                    'Moodle returned an empty response.'
                );
            }

            try {
                return JSON.parse(text);
            } catch (error) {
                throw new Error(
                    'Invalid JSON returned by Moodle: ' +
                    text
                );
            }
        });
    })
    .then(function(response) {

        console.log(
            'Parsed Natural Search response:',
            response
        );

        if (
            response &&
            response[0] &&
            response[0].exception
        ) {

            console.error(
                'Moodle AJAX exception:',
                response[0].exception
            );

            console.error(
                'Moodle error message:',
                response[0].message
            );

            resultsContainer.innerHTML =
                '<div class="p-3 text-danger">' +
                'Search error: ' +
                (
                    response[0].message ||
                    'Moodle AJAX error'
                ) +
                '</div>';

            return;
        }

        if (
            response &&
            response[0] &&
            response[0].data
        ) {

            let data = response[0].data;

            if (typeof data === 'string') {

                try {
                    data = JSON.parse(data);
                } catch (error) {

                    console.error(
                        'Unable to parse AJAX data:',
                        error
                    );

                    renderResults(
                        null,
                        resultsContainer
                    );

                    return;
                }
            }

            renderResults(
                data,
                resultsContainer
            );

            return;
        }

        console.error(
            'Unexpected Moodle response:',
            response
        );

        renderResults(
            null,
            resultsContainer
        );
    })
    .catch(function(error) {

        console.error(
            'Natural Search AJAX error:',
            error
        );

        resultsContainer.innerHTML =
            '<div class="p-3 text-danger">' +
            'Unable to perform search.<br>' +
            '<small>' +
            error.message +
            '</small>' +
            '</div>';
    });
};

/*
 * Display search results.
 */
const renderResults = (
    response,
    container
) => {

    container.innerHTML = '';

    if (
        !response ||
        response.status !== 'success' ||
        !response.results ||
        response.results.length === 0
    ) {

        container.innerHTML =
            '<div class="p-3">' +
            'No results found.' +
            '</div>';

        return;
    }


    response.results.forEach(function(result) {

        const item =
            document.createElement('div');

        item.className =
            'edvik-natural-search-result p-3';


        const link =
            document.createElement('a');

        link.href = result.url;

        link.textContent =
            result.type.charAt(0).toUpperCase() +
            result.type.slice(1) +
            ' : ' +
            result.title;

        link.style.color = '#fff';
        link.style.textDecoration = 'none';

        const type =
            document.createElement('small');

        type.className =
            'd-block text-muted';

        type.textContent =
            result.type;


        const description =
            document.createElement('div');

        description.className =
            'small';

        description.textContent =
            result.matchedcontent || '';


        item.appendChild(link);

        item.appendChild(description);

        container.appendChild(item);
    });
};


/*
 * Initialise after the page has loaded.
 */
document.addEventListener(
    'DOMContentLoaded',
    function() {
        init();
    }
);