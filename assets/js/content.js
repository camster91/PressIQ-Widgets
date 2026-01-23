/**
 * AC Starter Toolkit - Content Widgets JavaScript
 *
 * @package AC_Starter_Toolkit
 */

(function() {
    'use strict';

    /**
     * Countdown Timer functionality
     */
    class CountdownTimer {
        constructor(element) {
            this.element = element;
            this.dueDate = element.dataset.dueDate ? new Date(element.dataset.dueDate) : null;
            this.evergreenSeconds = parseInt(element.dataset.evergreenSeconds, 10) || 0;
            this.expireAction = element.dataset.expireAction || 'hide';
            this.expireMessage = element.dataset.expireMessage || '';
            this.expireRedirect = element.dataset.expireRedirect || '';
            this.isEvergreen = element.dataset.evergreen === 'yes';

            this.daysEl = element.querySelector('[data-countdown="days"]');
            this.hoursEl = element.querySelector('[data-countdown="hours"]');
            this.minutesEl = element.querySelector('[data-countdown="minutes"]');
            this.secondsEl = element.querySelector('[data-countdown="seconds"]');

            this.interval = null;
            this.evergreenEndTime = null;

            this.init();
        }

        init() {
            if (this.isEvergreen) {
                this.initEvergreen();
            }

            this.update();
            this.interval = setInterval(() => this.update(), 1000);
        }

        initEvergreen() {
            const storageKey = 'acst_countdown_' + this.element.id;
            const stored = localStorage.getItem(storageKey);

            if (stored) {
                this.evergreenEndTime = parseInt(stored, 10);
            } else {
                this.evergreenEndTime = Date.now() + (this.evergreenSeconds * 1000);
                localStorage.setItem(storageKey, this.evergreenEndTime.toString());
            }
        }

        getTimeRemaining() {
            let endTime;

            if (this.isEvergreen) {
                endTime = this.evergreenEndTime;
            } else if (this.dueDate) {
                endTime = this.dueDate.getTime();
            } else {
                return { total: 0, days: 0, hours: 0, minutes: 0, seconds: 0 };
            }

            const total = endTime - Date.now();

            if (total <= 0) {
                return { total: 0, days: 0, hours: 0, minutes: 0, seconds: 0 };
            }

            const seconds = Math.floor((total / 1000) % 60);
            const minutes = Math.floor((total / 1000 / 60) % 60);
            const hours = Math.floor((total / (1000 * 60 * 60)) % 24);
            const days = Math.floor(total / (1000 * 60 * 60 * 24));

            return { total, days, hours, minutes, seconds };
        }

        update() {
            const time = this.getTimeRemaining();

            if (this.daysEl) this.daysEl.textContent = time.days.toString().padStart(2, '0');
            if (this.hoursEl) this.hoursEl.textContent = time.hours.toString().padStart(2, '0');
            if (this.minutesEl) this.minutesEl.textContent = time.minutes.toString().padStart(2, '0');
            if (this.secondsEl) this.secondsEl.textContent = time.seconds.toString().padStart(2, '0');

            if (time.total <= 0) {
                this.onExpire();
            }
        }

        onExpire() {
            clearInterval(this.interval);
            this.element.classList.add('acst-countdown--expired');

            switch (this.expireAction) {
                case 'hide':
                    this.element.style.display = 'none';
                    break;

                case 'message':
                    this.element.innerHTML = '<div class="acst-countdown__message">' +
                        this.escapeHtml(this.expireMessage) + '</div>';
                    break;

                case 'redirect':
                    if (this.expireRedirect && this.isValidRedirectUrl(this.expireRedirect)) {
                        window.location.href = this.expireRedirect;
                    }
                    break;
            }
        }

        escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        /**
         * Validate redirect URL to prevent open redirect attacks
         * Only allows same-origin URLs or relative paths
         */
        isValidRedirectUrl(url) {
            try {
                const redirectUrl = new URL(url, window.location.origin);
                // Only allow same-origin redirects
                return redirectUrl.origin === window.location.origin;
            } catch (e) {
                // If URL parsing fails, check if it's a relative path
                return url.startsWith('/') && !url.startsWith('//');
            }
        }

        destroy() {
            if (this.interval) {
                clearInterval(this.interval);
            }
        }
    }

    /**
     * Tabs functionality
     */
    class Tabs {
        constructor(element) {
            this.element = element;
            this.tablist = element.querySelector('[role="tablist"]');
            this.tabs = Array.from(element.querySelectorAll('[role="tab"]'));
            this.panels = Array.from(element.querySelectorAll('[role="tabpanel"]'));

            this.init();
        }

        init() {
            this.tabs.forEach((tab, index) => {
                tab.addEventListener('click', (e) => this.onTabClick(e, index));
                tab.addEventListener('keydown', (e) => this.onTabKeydown(e, index));
            });

            // Activate first tab if none is active
            const activeTab = this.tabs.find(tab => tab.getAttribute('aria-selected') === 'true');
            if (!activeTab && this.tabs.length > 0) {
                this.activateTab(0);
            }
        }

        onTabClick(event, index) {
            event.preventDefault();
            this.activateTab(index);
        }

        onTabKeydown(event, index) {
            let newIndex;
            const isVertical = this.element.classList.contains('acst-tabs--vertical');

            switch (event.key) {
                case 'ArrowRight':
                    if (!isVertical) {
                        newIndex = (index + 1) % this.tabs.length;
                    }
                    break;

                case 'ArrowLeft':
                    if (!isVertical) {
                        newIndex = (index - 1 + this.tabs.length) % this.tabs.length;
                    }
                    break;

                case 'ArrowDown':
                    if (isVertical) {
                        newIndex = (index + 1) % this.tabs.length;
                    }
                    break;

                case 'ArrowUp':
                    if (isVertical) {
                        newIndex = (index - 1 + this.tabs.length) % this.tabs.length;
                    }
                    break;

                case 'Home':
                    newIndex = 0;
                    break;

                case 'End':
                    newIndex = this.tabs.length - 1;
                    break;
            }

            if (newIndex !== undefined) {
                event.preventDefault();
                this.activateTab(newIndex);
                this.tabs[newIndex].focus();
            }
        }

        activateTab(index) {
            // Deactivate all tabs
            this.tabs.forEach((tab, i) => {
                tab.setAttribute('aria-selected', 'false');
                tab.setAttribute('tabindex', '-1');
                this.panels[i].setAttribute('aria-hidden', 'true');
            });

            // Activate selected tab
            this.tabs[index].setAttribute('aria-selected', 'true');
            this.tabs[index].setAttribute('tabindex', '0');
            this.panels[index].setAttribute('aria-hidden', 'false');
        }
    }

    /**
     * Accordion functionality
     */
    class Accordion {
        constructor(element) {
            this.element = element;
            this.items = Array.from(element.querySelectorAll('.acst-accordion__item'));
            this.collapseOthers = element.dataset.collapseOthers === 'yes';

            this.init();
        }

        init() {
            this.items.forEach((item, index) => {
                const header = item.querySelector('.acst-accordion__header');
                const content = item.querySelector('.acst-accordion__content');

                if (header && content) {
                    header.addEventListener('click', () => this.toggle(index));
                    header.addEventListener('keydown', (e) => this.onKeydown(e, index));
                }
            });
        }

        toggle(index) {
            const item = this.items[index];
            const header = item.querySelector('.acst-accordion__header');
            const content = item.querySelector('.acst-accordion__content');
            const isExpanded = header.getAttribute('aria-expanded') === 'true';

            if (this.collapseOthers && !isExpanded) {
                this.collapseAll();
            }

            header.setAttribute('aria-expanded', (!isExpanded).toString());
            content.setAttribute('aria-hidden', isExpanded.toString());
        }

        collapseAll() {
            this.items.forEach(item => {
                const header = item.querySelector('.acst-accordion__header');
                const content = item.querySelector('.acst-accordion__content');

                if (header && content) {
                    header.setAttribute('aria-expanded', 'false');
                    content.setAttribute('aria-hidden', 'true');
                }
            });
        }

        onKeydown(event, index) {
            switch (event.key) {
                case 'Enter':
                case ' ':
                    event.preventDefault();
                    this.toggle(index);
                    break;

                case 'ArrowDown':
                    event.preventDefault();
                    this.focusItem((index + 1) % this.items.length);
                    break;

                case 'ArrowUp':
                    event.preventDefault();
                    this.focusItem((index - 1 + this.items.length) % this.items.length);
                    break;

                case 'Home':
                    event.preventDefault();
                    this.focusItem(0);
                    break;

                case 'End':
                    event.preventDefault();
                    this.focusItem(this.items.length - 1);
                    break;
            }
        }

        focusItem(index) {
            const header = this.items[index].querySelector('.acst-accordion__header');
            if (header) {
                header.focus();
            }
        }
    }

    /**
     * Initialize all content widgets
     */
    function initContentWidgets() {
        // Initialize Countdown Timers
        document.querySelectorAll('.acst-countdown').forEach(element => {
            if (!element.dataset.initialized) {
                new CountdownTimer(element);
                element.dataset.initialized = 'true';
            }
        });

        // Initialize Tabs
        document.querySelectorAll('.acst-tabs').forEach(element => {
            if (!element.dataset.initialized) {
                new Tabs(element);
                element.dataset.initialized = 'true';
            }
        });

        // Initialize Accordions
        document.querySelectorAll('.acst-accordion').forEach(element => {
            if (!element.dataset.initialized) {
                new Accordion(element);
                element.dataset.initialized = 'true';
            }
        });
    }

    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initContentWidgets);
    } else {
        initContentWidgets();
    }

    // Re-initialize when Elementor editor updates content
    if (typeof elementorFrontend !== 'undefined') {
        jQuery(window).on('elementor/frontend/init', function() {
            elementorFrontend.hooks.addAction('frontend/element_ready/acst-countdown.default', function($element) {
                const element = $element[0].querySelector('.acst-countdown');
                if (element && !element.dataset.initialized) {
                    new CountdownTimer(element);
                    element.dataset.initialized = 'true';
                }
            });

            elementorFrontend.hooks.addAction('frontend/element_ready/acst-tabs.default', function($element) {
                const element = $element[0].querySelector('.acst-tabs');
                if (element && !element.dataset.initialized) {
                    new Tabs(element);
                    element.dataset.initialized = 'true';
                }
            });

            elementorFrontend.hooks.addAction('frontend/element_ready/acst-accordion.default', function($element) {
                const element = $element[0].querySelector('.acst-accordion');
                if (element && !element.dataset.initialized) {
                    new Accordion(element);
                    element.dataset.initialized = 'true';
                }
            });
        });
    }

    // Expose classes for external use
    window.ACST = window.ACST || {};
    window.ACST.CountdownTimer = CountdownTimer;
    window.ACST.Tabs = Tabs;
    window.ACST.Accordion = Accordion;
    window.ACST.initContentWidgets = initContentWidgets;

})();
