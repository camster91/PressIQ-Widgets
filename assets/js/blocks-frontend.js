/**
 * AC Starter Toolkit - Blocks Frontend Scripts
 *
 * Handles interactive behavior for native WordPress blocks:
 * accordion toggles, tab switching, countdown timers, and AJAX post filtering.
 *
 * @package AC_Starter_Toolkit
 */

( function() {
    'use strict';

    /* =========================================================================
       Accordion
       ========================================================================= */

    function initAccordions() {
        document.querySelectorAll( '.acst-accordion' ).forEach( function( accordion ) {
            if ( accordion.dataset.acstInit ) return;
            accordion.dataset.acstInit = '1';

            var collapseOthers = accordion.dataset.collapseOthers === 'true';

            accordion.querySelectorAll( '.acst-accordion__header' ).forEach( function( header ) {
                header.addEventListener( 'click', function() {
                    var item      = header.closest( '.acst-accordion__item' );
                    var contentEl = item.querySelector( '.acst-accordion__content' );
                    var isOpen    = header.getAttribute( 'aria-expanded' ) === 'true';

                    if ( collapseOthers && ! isOpen ) {
                        accordion.querySelectorAll( '.acst-accordion__item.is-active' ).forEach( function( otherItem ) {
                            if ( otherItem !== item ) {
                                otherItem.classList.remove( 'is-active' );
                                var otherHeader  = otherItem.querySelector( '.acst-accordion__header' );
                                var otherContent = otherItem.querySelector( '.acst-accordion__content' );
                                otherHeader.setAttribute( 'aria-expanded', 'false' );
                                otherContent.setAttribute( 'hidden', '' );
                            }
                        } );
                    }

                    if ( isOpen ) {
                        item.classList.remove( 'is-active' );
                        header.setAttribute( 'aria-expanded', 'false' );
                        contentEl.setAttribute( 'hidden', '' );
                    } else {
                        item.classList.add( 'is-active' );
                        header.setAttribute( 'aria-expanded', 'true' );
                        contentEl.removeAttribute( 'hidden' );
                    }
                } );
            } );
        } );
    }

    /* =========================================================================
       Tabs
       ========================================================================= */

    function initTabs() {
        document.querySelectorAll( '.acst-tabs' ).forEach( function( tabsContainer ) {
            if ( tabsContainer.dataset.acstInit ) return;
            tabsContainer.dataset.acstInit = '1';

            tabsContainer.querySelectorAll( '.acst-tabs__tab' ).forEach( function( tab ) {
                tab.addEventListener( 'click', function() {
                    var tabNum = tab.dataset.tab;

                    // Deactivate all tabs.
                    tabsContainer.querySelectorAll( '.acst-tabs__tab' ).forEach( function( t ) {
                        t.classList.remove( 'is-active' );
                        t.setAttribute( 'aria-selected', 'false' );
                    } );

                    // Hide all panels.
                    tabsContainer.querySelectorAll( '.acst-tabs__panel' ).forEach( function( p ) {
                        p.classList.remove( 'is-active' );
                        p.setAttribute( 'hidden', '' );
                    } );

                    // Activate clicked tab.
                    tab.classList.add( 'is-active' );
                    tab.setAttribute( 'aria-selected', 'true' );

                    // Show corresponding panel.
                    var panel = tabsContainer.querySelector( '.acst-tabs__panel[data-tab="' + tabNum + '"]' );
                    if ( panel ) {
                        panel.classList.add( 'is-active' );
                        panel.removeAttribute( 'hidden' );
                    }
                } );
            } );

            // Keyboard navigation.
            tabsContainer.querySelector( '.acst-tabs__nav' ).addEventListener( 'keydown', function( e ) {
                var tabs = Array.from( tabsContainer.querySelectorAll( '.acst-tabs__tab' ) );
                var index = tabs.indexOf( document.activeElement );
                if ( index < 0 ) return;

                var next;
                if ( e.key === 'ArrowRight' || e.key === 'ArrowDown' ) {
                    e.preventDefault();
                    next = tabs[ ( index + 1 ) % tabs.length ];
                } else if ( e.key === 'ArrowLeft' || e.key === 'ArrowUp' ) {
                    e.preventDefault();
                    next = tabs[ ( index - 1 + tabs.length ) % tabs.length ];
                }

                if ( next ) {
                    next.focus();
                    next.click();
                }
            } );
        } );
    }

    /* =========================================================================
       Countdown Timer
       ========================================================================= */

    function initCountdowns() {
        document.querySelectorAll( '.acst-countdown' ).forEach( function( countdown ) {
            if ( countdown.dataset.acstInit ) return;
            countdown.dataset.acstInit = '1';

            var expireAction = countdown.dataset.expireAction || 'hide';
            var targetTime;

            if ( countdown.dataset.evergreen === 'yes' ) {
                var evergreenSeconds = parseInt( countdown.dataset.evergreenSeconds, 10 ) || 86400;
                var storageKey       = 'acst_eg_' + window.location.pathname;
                var stored           = localStorage.getItem( storageKey );

                if ( stored ) {
                    targetTime = parseInt( stored, 10 );
                } else {
                    targetTime = Date.now() + ( evergreenSeconds * 1000 );
                    localStorage.setItem( storageKey, targetTime );
                }
            } else {
                var dueDate = countdown.dataset.dueDate;
                if ( dueDate ) {
                    targetTime = new Date( dueDate ).getTime();
                }
            }

            if ( ! targetTime ) return;

            function updateCountdown() {
                var now       = Date.now();
                var remaining = Math.max( 0, Math.floor( ( targetTime - now ) / 1000 ) );

                if ( remaining <= 0 ) {
                    handleExpire();
                    return;
                }

                var days    = Math.floor( remaining / 86400 );
                var hours   = Math.floor( ( remaining % 86400 ) / 3600 );
                var minutes = Math.floor( ( remaining % 3600 ) / 60 );
                var seconds = remaining % 60;

                setUnit( 'days', pad( days ) );
                setUnit( 'hours', pad( hours ) );
                setUnit( 'minutes', pad( minutes ) );
                setUnit( 'seconds', pad( seconds ) );

                requestAnimationFrame( function() {
                    setTimeout( updateCountdown, 1000 );
                } );
            }

            function setUnit( unit, value ) {
                var el = countdown.querySelector( '[data-countdown="' + unit + '"]' );
                if ( el ) el.textContent = value;
            }

            function pad( n ) {
                return n < 10 ? '0' + n : '' + n;
            }

            function handleExpire() {
                if ( expireAction === 'message' ) {
                    var message = countdown.dataset.expireMessage || '';
                    countdown.innerHTML = '<div class="acst-countdown__message">' + escapeHtml( message ) + '</div>';
                    countdown.classList.add( 'acst-countdown--expired' );
                } else {
                    countdown.style.display = 'none';
                }
            }

            function escapeHtml( str ) {
                var div = document.createElement( 'div' );
                div.appendChild( document.createTextNode( str ) );
                return div.innerHTML;
            }

            updateCountdown();
        } );
    }

    /* =========================================================================
       Post Filter (AJAX)
       ========================================================================= */

    function initPostFilters() {
        document.querySelectorAll( '.acst-post-filter' ).forEach( function( filterBlock ) {
            if ( filterBlock.dataset.acstInit ) return;
            filterBlock.dataset.acstInit = '1';

            var queryId     = filterBlock.dataset.queryId;
            var postType    = filterBlock.dataset.postType || 'post';
            var perPage     = parseInt( filterBlock.dataset.perPage, 10 ) || 9;
            var resultsWrap = filterBlock.querySelector( '.acst-post-filter__results' );
            var debounceTimer;
            var currentPage = 1;

            // Search input.
            var searchInput = filterBlock.querySelector( '.acst-filter__search-input' );
            var clearBtn    = filterBlock.querySelector( '.acst-filter__search-clear' );

            if ( searchInput ) {
                searchInput.addEventListener( 'input', function() {
                    if ( clearBtn ) {
                        clearBtn.hidden = ! searchInput.value;
                    }
                    clearTimeout( debounceTimer );
                    debounceTimer = setTimeout( function() {
                        currentPage = 1;
                        doFilter();
                    }, 300 );
                } );
            }

            if ( clearBtn ) {
                clearBtn.addEventListener( 'click', function() {
                    searchInput.value = '';
                    clearBtn.hidden = true;
                    currentPage = 1;
                    doFilter();
                } );
            }

            // Select filters (taxonomy + sort).
            filterBlock.querySelectorAll( '.acst-filter__select' ).forEach( function( select ) {
                select.addEventListener( 'change', function() {
                    currentPage = 1;
                    doFilter();
                } );
            } );

            // Checkbox filters.
            filterBlock.querySelectorAll( '.acst-filter__checkbox' ).forEach( function( cb ) {
                cb.addEventListener( 'change', function() {
                    currentPage = 1;
                    doFilter();
                } );
            } );

            // Load more.
            var loadMoreBtn = filterBlock.querySelector( '.acst-post-filter__load-more' );
            if ( loadMoreBtn ) {
                loadMoreBtn.addEventListener( 'click', function() {
                    currentPage++;
                    doFilter( true );
                } );
            }

            function collectFilters() {
                var filters = {};

                // Search.
                if ( searchInput && searchInput.value ) {
                    filters.search = searchInput.value;
                }

                // Sort.
                var sortSelect = filterBlock.querySelector( '[data-filter-type="sort"]' );
                if ( sortSelect && sortSelect.value ) {
                    filters.sort = sortSelect.value;
                }

                // Taxonomy filters.
                filterBlock.querySelectorAll( '.acst-filter--select [data-filter-id]' ).forEach( function( select ) {
                    if ( select.tagName === 'SELECT' && select.value ) {
                        filters[ select.dataset.filterId ] = select.value;
                    }
                } );

                // Checkbox filters.
                var checkboxFilters = {};
                filterBlock.querySelectorAll( '.acst-filter__checkbox:checked' ).forEach( function( cb ) {
                    var fid = cb.dataset.filterId;
                    if ( ! checkboxFilters[ fid ] ) checkboxFilters[ fid ] = [];
                    checkboxFilters[ fid ].push( cb.value );
                } );
                Object.keys( checkboxFilters ).forEach( function( fid ) {
                    filters[ fid ] = checkboxFilters[ fid ];
                } );

                return filters;
            }

            function doFilter( append ) {
                var config = window.acstBlocks || {};
                if ( ! config.ajaxUrl ) return;

                var filters = collectFilters();

                var formData = new FormData();
                formData.append( 'action', 'acst_filter' );
                formData.append( 'nonce', config.nonce );
                formData.append( 'query_id', queryId );
                formData.append( 'post_type', postType );
                formData.append( 'posts_per_page', perPage );
                formData.append( 'paged', currentPage );
                formData.append( 'filters', JSON.stringify( filters ) );

                resultsWrap.classList.add( 'acst-post-filter__results--loading' );

                fetch( config.ajaxUrl, {
                    method: 'POST',
                    body: formData,
                    credentials: 'same-origin'
                } )
                .then( function( response ) { return response.json(); } )
                .then( function( data ) {
                    resultsWrap.classList.remove( 'acst-post-filter__results--loading' );

                    if ( data.success && data.data && data.data.html ) {
                        if ( append ) {
                            resultsWrap.insertAdjacentHTML( 'beforeend', data.data.html );
                        } else {
                            resultsWrap.innerHTML = data.data.html;
                        }

                        // Update load more visibility.
                        var pagination = filterBlock.querySelector( '.acst-post-filter__pagination' );
                        if ( pagination ) {
                            var maxPages = data.data.max_pages || 1;
                            pagination.style.display = currentPage >= maxPages ? 'none' : '';
                        }
                    } else if ( ! append ) {
                        resultsWrap.innerHTML = '<p class="acst-post-filter__no-results">' + ( config.i18n.noResults || 'No results found.' ) + '</p>';
                    }
                } )
                .catch( function() {
                    resultsWrap.classList.remove( 'acst-post-filter__results--loading' );
                } );
            }
        } );
    }

    /* =========================================================================
       Init on DOM ready & after dynamic block render
       ========================================================================= */

    function initAll() {
        initAccordions();
        initTabs();
        initCountdowns();
        initPostFilters();
    }

    if ( document.readyState === 'loading' ) {
        document.addEventListener( 'DOMContentLoaded', initAll );
    } else {
        initAll();
    }

    // Re-initialize when new blocks are rendered (e.g., Infinite Scroll, AJAX navigation).
    if ( typeof MutationObserver !== 'undefined' ) {
        var observer = new MutationObserver( function( mutations ) {
            var shouldInit = mutations.some( function( m ) {
                return m.addedNodes.length > 0;
            } );
            if ( shouldInit ) initAll();
        } );
        observer.observe( document.body, { childList: true, subtree: true } );
    }

} )();
