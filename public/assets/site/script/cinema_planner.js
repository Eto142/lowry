/* global moment */

export default function initCinemaPlanner() {
    const cinema_root = document.querySelector('.cinema-planner');

    if (! cinema_root) {
        return;
    }
    const url = new URL(window.location.href);
    const param_days = url.searchParams.get('days');
    let custom_time = null;

    if (url.searchParams.get('pep_custom_time')) {
        custom_time = Date.parse(url.searchParams.get('pep_custom_time'));
    }

    // generate days of the week based on today
    const now = custom_time || Date.now();
    const one_day = 60 * 60 * 24 * 1000;
    const this_week = [];

    document.querySelector('.day-option.today').setAttribute('data-value', moment(now).format('YYYY-MM-DD'));

    for (let i = 1; i < 7; i++) {
        const date = new Date(now + one_day * i);
        this_week.push(moment(date).format('YYYY-MM-DD'));
    }

    function updateEmptyList(all_movies) {
        if (Array.from(all_movies).every((m) => window.getComputedStyle(m, null).getPropertyValue('display') === 'none')) {
            document.querySelector('.movie-cards').style.display = 'none';
            document.querySelector('.empty-list').style.display = 'block';
        } else {
            document.querySelector('.movie-cards').style.display = 'grid';
            document.querySelector('.empty-list').style.display = 'none';
        }
    }

    function renderDays(date) {
        const li = document.createElement('li');
        const button = document.createElement('div');
        button.classList.add('btn', 'btn-default', 'day-option');
        button.setAttribute('role', 'radio');
        button.setAttribute('data-value', date);
        button.setAttribute('aria-selected', 'false');

        button.innerHTML = button.innerHTML + moment(date).format('ddd');
        li.appendChild(button);
        document.querySelector('.day-filters').appendChild(li);
    }
    this_week.forEach(renderDays);

    const day_filters = Array.from(document.querySelectorAll('.day-filters .day-option'));
    const header = document.querySelector('.header');
    let isDragging = false;
    let startX;
    let scrollLeft;

    header.addEventListener('mousedown', e => {
        isDragging = true;
        startX = e.pageX - header.offsetLeft;
        scrollLeft = header.scrollLeft;
    });
    header.addEventListener('mouseleave', () => {
        isDragging = false;
    });
    header.addEventListener('mouseup', () => {
        isDragging = false;
    });
    header.addEventListener('mousemove', e => {
        if (!isDragging) {
            return;
        }
        e.preventDefault();
        const x = e.pageX - header.offsetLeft;
        const walk = x - startX;
        header.scrollLeft = scrollLeft - walk;
    });

    window.addEventListener('scroll', function() {
        if (window.scrollY > cinema_root.offsetTop + header.clientHeight * 2) {
            header.classList.add('pinned');
            document.querySelector('.header-buffer').classList.add('pinned');
        } else {
            header.classList.remove('pinned');
            document.querySelector('.header-buffer').classList.remove('pinned');
        }
    });


    function filterEvents(selected_day_filters) {
        const movies = document.querySelectorAll('.movie-card');

        for (let i = 0; i < movies.length; i++) {
            const movie = movies[i];
            const eventDays = movie.querySelectorAll('.events .day');

            eventDays.forEach((day) => {
                if (selected_day_filters.length > 0 && !selected_day_filters.includes(day.dataset.date)) {
                    day.style.display = 'none';
                } else {
                    day.style.display = null;
                }
            });

            // if no events on that day hide the movie on mobile
            if (Array.from(eventDays).filter((day) => day.style.display !== 'none').every((d) => d.querySelector('.no-show-placeholder').style.display === 'block')) {
                movie.classList.add('hide-on-mobile');
            } else {
                movie.classList.remove('hide-on-mobile');
            }
        }
        updateEmptyList(movies);
    }
    const all_tag = day_filters.find((f) => f.dataset.value === 'all');
    // toggle day filters on tap
    day_filters.forEach((filter) => {
        // pre-select the days that are on query param
        if (param_days !== null) {
            param_days.split(',').forEach((param) => {
                const name = moment(filter.dataset.value).locale('en').format('ddd').toLowerCase();
                if (param === name) {
                    all_tag.setAttribute('aria-selected', 'false');
                    filter.setAttribute('aria-selected', 'true');
                }
            });
        }
        filter.addEventListener('click', () => {
            if (isDragging) {
                return;
            }

            // Only scroll to top if the header is pinned. Otherwise it keeps re-positioning your scroll
            if (header.classList.contains('pinned')) {
                cinema_root.scrollIntoView({behavior: 'smooth', block: 'start'});
            }

            if (filter.dataset.value === 'all') {
                day_filters.forEach((f) => f.setAttribute('aria-selected', 'false'));
            } else {
                all_tag.setAttribute('aria-selected', 'false');
            }

            if (JSON.parse(filter.getAttribute('aria-selected'))) {
                filter.setAttribute('aria-selected', 'false');
            } else {
                filter.setAttribute('aria-selected', 'true');
            }
            const selected_day_filters = Array.from(document.querySelectorAll('.day-option[aria-selected="true"]'))
                .map((a) => a.dataset.value)
                .filter((a) => a !== 'all');
            if (selected_day_filters.length === 0) {
                all_tag.setAttribute('aria-selected', 'true');
            }

            filterEvents(selected_day_filters);
            if (selected_day_filters.join(',').length > 0) {
                url.searchParams.set('days', selected_day_filters.map((d) => moment(d).locale('en').format('ddd').toLowerCase()).join(','));
            } else {
                url.searchParams.delete('days');
            }

            window.history.replaceState(null, null, url);
        });
    });

    const movies = document.querySelectorAll('.movie-cards');

    function updateSeparator(shows, day) {
        const grid_computed_style = window.getComputedStyle(shows);

        // get number of grid rows so we can decide if days are wrapped
        const grid_row_count = grid_computed_style.getPropertyValue('grid-template-rows').split(' ').length;
        if (grid_row_count > 1) {
            day.querySelector('.separator').classList.add('not-empty');
        } else {
            day.querySelector('.separator').classList.remove('not-empty');
        }
    }

    function updateMovies() {
        if (movies === null || movies.length === 0) {
            return;
        }
        movies.forEach((movie) => {
            if (!movie) {
                return;
            }

            movie.querySelectorAll('.day').forEach((day) => {
                day.querySelectorAll('.shows').forEach((shows) => {
                    updateSeparator(shows, day);
                    if (Array.from(shows.children).length === 0) {
                        shows.parentNode.querySelector('.no-show-placeholder').style.display = 'block';
                        shows.style.display = 'none';
                    }

                    const info_buttons = shows.querySelectorAll('.status-info');
                    if (info_buttons && info_buttons.length) {
                        info_buttons.forEach((info_button) => info_button.classList.add('btn'));
                    }
                    const button_properties = shows.querySelectorAll('.property');

                    if (button_properties && button_properties.length) {
                        button_properties.forEach((button_property) => button_property.style.display = 'block');
                    }
                });
            });
        });
    }

    // Modify the movies - order button, running time
    updateMovies();

    const selected_day_filters = Array.from(document.querySelectorAll('.day-option[aria-selected="true"]'))
        .map((a) => a.dataset.value)
        .filter((a) => a !== 'all');

    filterEvents(selected_day_filters);

    window.addEventListener('resize', () => {
        window.requestAnimationFrame(() => {
            updateMovies();
        });
    });
}
