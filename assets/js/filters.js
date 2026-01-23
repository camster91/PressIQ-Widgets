/**
 * AC Starter Toolkit - Smart Filters JavaScript
 *
 * Handles AJAX filtering for Elementor widgets.
 */

(function() {
    'use strict';

    // Configuration from WordPress
    const config = window.acstFilters || {
        ajaxUrl: '/wp-admin/admin-ajax.php',
        nonce: '',
        i18n: {
            loading: 'Loading...',
            noResults: 'No results found.',
            error: 'An error occurred.'
        }
    };

    /**
     * Filter Manager Class
     * Manages all filter instances on a page.
     */
    class FilterManager {
        constructor() {
            this.filters = new Map();
            this.activeFilters = {};
            this.debounceTimers = new Map();
            this.init();
        }

        /**
         * Initialize the filter manager
         */
        init() {
            this.discoverFilters();
            this.bindEvents();
            this.restoreFromUrl();
        }

        /**
         * Discover all filter widgets on the page
         */
        discoverFilters() {
            const filterElements = document.querySelectorAll('.acst-filter');

            filterElements.forEach(element => {
                const filterId = element.dataset.filterId;
                const queryId = element.dataset.queryId || '';
                const filterType = element.dataset.filterType;

                this.filters.set(filterId, {
                    element,
                    queryId,
                    filterType,
                    value: null
                });
            });
        }

        /**
         * Bind event listeners
         */
        bindEvents() {
            // Select filter change
            document.addEventListener('change', (e) => {
                if (e.target.matches('.acst-filter__select')) {
                    this.handleFilterChange(e.target);
                }
                if (e.target.matches('.acst-filter__checkbox')) {
                    this.handleCheckboxChange(e.target);
                }
                if (e.target.matches('.acst-filter__radio')) {
                    this.handleFilterChange(e.target);
                }
            });

            // Text input (search filter) with debounce
            document.addEventListener('input', (e) => {
                if (e.target.matches('.acst-filter__text-input')) {
                    this.handleTextInput(e.target);
                }
                if (e.target.matches('.acst-filter__search-input')) {
                    this.handleOptionSearch(e.target);
                }
            });

            // Range slider change
            document.addEventListener('input', (e) => {
                if (e.target.matches('.acst-filter__range-input')) {
                    this.handleRangeChange(e.target);
                }
            });

            // Collapsible toggles
            document.addEventListener('click', (e) => {
                if (e.target.matches('.acst-filter__toggle') || e.target.closest('.acst-filter__toggle')) {
                    this.handleToggle(e.target.closest('.acst-filter__toggle') || e.target);
                }
                if (e.target.matches('.acst-filter__show-more')) {
                    this.handleShowMore(e.target);
                }
            });

            // Browser back/forward
            window.addEventListener('popstate', () => {
                this.restoreFromUrl();
                this.applyFilters();
            });
        }

        /**
         * Handle select/radio filter change
         */
        handleFilterChange(input) {
            const filter = input.closest('.acst-filter');
            const filterId = filter.dataset.filterId;
            const value = input.value;

            // Handle sorting filter specially
            if (filter.dataset.filterType === 'sorting' && value) {
                const [orderby, order] = value.split('|');
                this.activeFilters['_orderby'] = orderby;
                this.activeFilters['_order'] = order;
            } else if (value) {
                this.activeFilters[filterId] = value;
            } else {
                delete this.activeFilters[filterId];
                if (filter.dataset.filterType === 'sorting') {
                    delete this.activeFilters['_orderby'];
                    delete this.activeFilters['_order'];
                }
            }

            this.applyFilters();
        }

        /**
         * Handle checkbox filter change
         */
        handleCheckboxChange(checkbox) {
            const filter = checkbox.closest('.acst-filter');
            const filterId = filter.dataset.filterId;
            const checkedBoxes = filter.querySelectorAll('.acst-filter__checkbox:checked');

            if (checkedBoxes.length > 0) {
                this.activeFilters[filterId] = Array.from(checkedBoxes).map(cb => cb.value);
            } else {
                delete this.activeFilters[filterId];
            }

            this.applyFilters();
        }

        /**
         * Handle text input with debounce
         */
        handleTextInput(input) {
            const filter = input.closest('.acst-filter');
            const filterId = filter.dataset.filterId;

            // Clear existing timer
            if (this.debounceTimers.has(filterId)) {
                clearTimeout(this.debounceTimers.get(filterId));
            }

            // Set new timer
            this.debounceTimers.set(filterId, setTimeout(() => {
                const value = input.value.trim();

                if (value.length >= 2) {
                    this.activeFilters['_search'] = value;
                } else {
                    delete this.activeFilters['_search'];
                }

                this.applyFilters();
            }, 300));
        }

        /**
         * Handle range filter change
         */
        handleRangeChange(input) {
            const filter = input.closest('.acst-filter');
            const filterId = filter.dataset.filterId;
            const minInput = filter.querySelector('.acst-filter__range-min');
            const maxInput = filter.querySelector('.acst-filter__range-max');

            if (minInput && maxInput) {
                const min = parseFloat(minInput.value) || 0;
                const max = parseFloat(maxInput.value) || 0;

                // Update display
                const minDisplay = filter.querySelector('.acst-filter__range-min-value');
                const maxDisplay = filter.querySelector('.acst-filter__range-max-value');
                if (minDisplay) minDisplay.textContent = this.formatNumber(min);
                if (maxDisplay) maxDisplay.textContent = this.formatNumber(max);

                // Clear existing timer
                if (this.debounceTimers.has(filterId)) {
                    clearTimeout(this.debounceTimers.get(filterId));
                }

                // Apply with debounce
                this.debounceTimers.set(filterId, setTimeout(() => {
                    this.activeFilters[filterId] = `${min}|${max}`;
                    this.applyFilters();
                }, 300));
            }
        }

        /**
         * Handle option search within checkbox filters
         */
        handleOptionSearch(input) {
            const filter = input.closest('.acst-filter');
            const searchText = input.value.toLowerCase().trim();
            const options = filter.querySelectorAll('.acst-filter__option');

            options.forEach(option => {
                const label = option.querySelector('.acst-filter__option-label');
                const text = label ? label.textContent.toLowerCase() : '';

                if (searchText === '' || text.includes(searchText)) {
                    option.style.display = '';
                } else {
                    option.style.display = 'none';
                }
            });
        }

        /**
         * Handle collapsible toggle
         */
        handleToggle(button) {
            const header = button.closest('.acst-filter__header');
            const body = header.nextElementSibling;
            const isCollapsed = header.dataset.collapsed === 'true';

            header.dataset.collapsed = isCollapsed ? 'false' : 'true';
            button.setAttribute('aria-expanded', isCollapsed ? 'true' : 'false');

            if (isCollapsed) {
                body.style.display = '';
            } else {
                body.style.display = 'none';
            }
        }

        /**
         * Handle show more/less toggle
         */
        handleShowMore(button) {
            const filter = button.closest('.acst-filter');
            const hiddenOptions = filter.querySelectorAll('.acst-filter__option--hidden');
            const isExpanded = button.dataset.expanded === 'true';

            hiddenOptions.forEach(option => {
                option.style.display = isExpanded ? 'none' : '';
            });

            button.dataset.expanded = isExpanded ? 'false' : 'true';
            button.textContent = isExpanded
                ? button.dataset.showMoreText
                : button.dataset.showLessText;
        }

        /**
         * Apply all active filters via AJAX
         */
        async applyFilters() {
            // Update URL
            this.updateUrl();

            // Get all unique query IDs
            const queryIds = new Set();
            this.filters.forEach(filter => {
                if (filter.queryId) {
                    queryIds.add(filter.queryId);
                }
            });

            // If no specific query IDs, try to find Elementor posts widgets
            if (queryIds.size === 0) {
                queryIds.add('');
            }

            // Apply filters to each target
            for (const queryId of queryIds) {
                await this.fetchResults(queryId);
            }
        }

        /**
         * Fetch filtered results via AJAX
         */
        async fetchResults(queryId) {
            const target = this.findTargetContainer(queryId);

            if (!target) {
                console.warn('ACST Filters: No target container found for query:', queryId);
                return;
            }

            // Show loading state
            this.setLoading(target, true);

            try {
                const formData = new FormData();
                formData.append('action', 'acst_filter');
                formData.append('nonce', config.nonce);
                formData.append('query_id', queryId);
                formData.append('page_id', this.getPageId());
                formData.append('paged', 1);
                formData.append('filters', JSON.stringify(this.activeFilters));

                // Add sorting if present
                if (this.activeFilters['_orderby']) {
                    formData.append('orderby', this.activeFilters['_orderby']);
                    formData.append('order', this.activeFilters['_order'] || 'DESC');
                }

                // Add search if present
                if (this.activeFilters['_search']) {
                    formData.append('search', this.activeFilters['_search']);
                }

                const response = await fetch(config.ajaxUrl, {
                    method: 'POST',
                    body: formData,
                    credentials: 'same-origin'
                });

                const data = await response.json();

                if (data.success) {
                    this.updateResults(target, data.data);
                } else {
                    console.error('ACST Filters: Error', data.data?.message);
                    this.showError(target);
                }
            } catch (error) {
                console.error('ACST Filters: Fetch error', error);
                this.showError(target);
            } finally {
                this.setLoading(target, false);
            }
        }

        /**
         * Find the target container for results
         */
        findTargetContainer(queryId) {
            // Try to find by query ID attribute
            if (queryId) {
                const byQueryId = document.querySelector(`[data-query-id="${queryId}"]`);
                if (byQueryId) {
                    return byQueryId.querySelector('.elementor-posts-container, .elementor-loop-container') || byQueryId;
                }
            }

            // Fallback: find Elementor posts widgets
            const postsWidget = document.querySelector('.elementor-widget-posts .elementor-posts-container');
            if (postsWidget) return postsWidget;

            const loopWidget = document.querySelector('.elementor-widget-loop-grid .elementor-loop-container');
            if (loopWidget) return loopWidget;

            // Last resort: look for common grid classes
            return document.querySelector('.acst-filter-results, .posts-container, .products');
        }

        /**
         * Update results in container
         */
        updateResults(container, data) {
            if (data.html) {
                container.innerHTML = data.html;
            }

            // Update count display if exists
            const countDisplay = document.querySelector('.acst-results-count');
            if (countDisplay && data.found_posts !== undefined) {
                countDisplay.textContent = data.found_posts;
            }

            // Trigger event for other scripts
            const event = new CustomEvent('acst:filtered', {
                detail: {
                    container,
                    foundPosts: data.found_posts,
                    maxPages: data.max_pages
                }
            });
            document.dispatchEvent(event);
        }

        /**
         * Set loading state on container
         */
        setLoading(container, isLoading) {
            if (isLoading) {
                container.classList.add('acst-loading');
                container.setAttribute('aria-busy', 'true');

                // Add loading overlay if not exists
                if (!container.querySelector('.acst-loading-overlay')) {
                    const overlay = document.createElement('div');
                    overlay.className = 'acst-loading-overlay';
                    overlay.innerHTML = `<span class="acst-loading-spinner"></span>`;
                    container.style.position = 'relative';
                    container.appendChild(overlay);
                }
            } else {
                container.classList.remove('acst-loading');
                container.setAttribute('aria-busy', 'false');

                const overlay = container.querySelector('.acst-loading-overlay');
                if (overlay) {
                    overlay.remove();
                }
            }
        }

        /**
         * Show error message
         */
        showError(container) {
            container.innerHTML = `<div class="acst-error"><p>${config.i18n.error}</p></div>`;
        }

        /**
         * Update URL with current filters
         */
        updateUrl() {
            const url = new URL(window.location.href);

            // Clear existing filter params
            Array.from(url.searchParams.keys()).forEach(key => {
                if (key.startsWith('filter_') || key === 'orderby' || key === 'order' || key === 's') {
                    url.searchParams.delete(key);
                }
            });

            // Add active filters
            Object.entries(this.activeFilters).forEach(([key, value]) => {
                if (key === '_orderby') {
                    url.searchParams.set('orderby', value);
                } else if (key === '_order') {
                    url.searchParams.set('order', value);
                } else if (key === '_search') {
                    url.searchParams.set('s', value);
                } else {
                    const paramValue = Array.isArray(value) ? value.join(',') : value;
                    url.searchParams.set(`filter_${key}`, paramValue);
                }
            });

            // Update URL without reload
            window.history.pushState({}, '', url.toString());
        }

        /**
         * Restore filters from URL
         */
        restoreFromUrl() {
            const url = new URL(window.location.href);
            this.activeFilters = {};

            url.searchParams.forEach((value, key) => {
                if (key.startsWith('filter_')) {
                    const filterId = key.replace('filter_', '');
                    // Handle comma-separated values (checkboxes)
                    this.activeFilters[filterId] = value.includes(',') ? value.split(',') : value;
                } else if (key === 'orderby') {
                    this.activeFilters['_orderby'] = value;
                } else if (key === 'order') {
                    this.activeFilters['_order'] = value;
                } else if (key === 's') {
                    this.activeFilters['_search'] = value;
                }
            });

            // Update filter UI to match
            this.syncFilterUI();
        }

        /**
         * Sync filter UI with active filters
         */
        syncFilterUI() {
            this.filters.forEach((filter, filterId) => {
                const value = this.activeFilters[filterId];

                if (!value) return;

                const element = filter.element;

                // Handle select filters
                const select = element.querySelector('.acst-filter__select');
                if (select) {
                    if (filter.filterType === 'sorting' && this.activeFilters['_orderby']) {
                        select.value = `${this.activeFilters['_orderby']}|${this.activeFilters['_order'] || 'DESC'}`;
                    } else {
                        select.value = value;
                    }
                }

                // Handle checkbox filters
                const checkboxes = element.querySelectorAll('.acst-filter__checkbox');
                if (checkboxes.length > 0) {
                    const values = Array.isArray(value) ? value : [value];
                    checkboxes.forEach(cb => {
                        cb.checked = values.includes(cb.value);
                    });
                }

                // Handle radio filters
                const radios = element.querySelectorAll('.acst-filter__radio');
                radios.forEach(radio => {
                    radio.checked = radio.value === value;
                });

                // Handle text input
                const textInput = element.querySelector('.acst-filter__text-input');
                if (textInput && this.activeFilters['_search']) {
                    textInput.value = this.activeFilters['_search'];
                }
            });
        }

        /**
         * Get current page ID
         */
        getPageId() {
            const body = document.body;
            const classes = body.className.split(' ');

            for (const cls of classes) {
                if (cls.startsWith('page-id-') || cls.startsWith('postid-')) {
                    return cls.split('-').pop();
                }
            }

            return 0;
        }

        /**
         * Format number for display
         */
        formatNumber(num) {
            return new Intl.NumberFormat().format(num);
        }
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            window.acstFilterManager = new FilterManager();
        });
    } else {
        window.acstFilterManager = new FilterManager();
    }

})();
