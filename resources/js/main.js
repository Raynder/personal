/**
 * Main
 */

'use strict';

import {Menu} from "./menu";

let isRtl = false,
    isDarkStyle = false,
    menu,
    animate,
    assetsPath = '/',
    isHorizontalLayout = true;

(function () {
    // Initialize menu
    //-----------------

    let layoutMenuEl = document.querySelectorAll('#layout-menu');
    layoutMenuEl.forEach(function (element) {
        menu = new Menu(element, {
            orientation: isHorizontalLayout ? 'horizontal' : 'vertical',
            closeChildren: isHorizontalLayout ? true : false,
            // ? This option only works with Horizontal menu
            showDropdownOnHover: true
        });
        // Change parameter to true if you want scroll animation
        window.Helpers.scrollToActive((animate = false));
        window.Helpers.mainMenu = menu;
    });

    // Initialize menu togglers and bind click on each
    let menuToggler = document.querySelectorAll('.layout-menu-toggle');
    menuToggler.forEach(item => {
        item.addEventListener('click', event => {
            event.preventDefault();
            window.Helpers.toggleCollapsed();
            // Enable menu state with local storage support if enableMenuLocalStorage = true from config.js
            if (config.enableMenuLocalStorage && !window.Helpers.isSmallScreen()) {
                try {
                    localStorage.setItem(
                        'templateCustomizer-' + templateName + '--LayoutCollapsed',
                        String(window.Helpers.isCollapsed())
                    );
                } catch (e) {
                }
            }
        });
    });

    // Menu swipe gesture

    // Detect swipe gesture on the target element and call swipe In
    window.Helpers.swipeIn('.drag-target', function (e) {
        window.Helpers.setCollapsed(false);
    });

    // Detect swipe gesture on the target element and call swipe Out
    window.Helpers.swipeOut('#layout-menu', function (e) {
        if (window.Helpers.isSmallScreen()) window.Helpers.setCollapsed(true);
    });

    // Display in main menu when menu scrolls
    let menuInnerContainer = document.getElementsByClassName('menu-inner'),
        menuInnerShadow = document.getElementsByClassName('menu-inner-shadow')[0];
    if (menuInnerContainer.length > 0 && menuInnerShadow) {
        menuInnerContainer[0].addEventListener('ps-scroll-y', function () {
            if (this.querySelector('.ps__thumb-y').offsetTop) {
                menuInnerShadow.style.display = 'block';
            } else {
                menuInnerShadow.style.display = 'none';
            }
        });
    }

    // Navbar Scroll class
    //---------------------
    function scrollTopFn() {
        if (document.body.scrollTop > 10 || document.documentElement.scrollTop > 10) {
            document.getElementById('layout-navbar').classList.add('navbar-elevated');
        } else {
            document.getElementById('layout-navbar').classList.remove('navbar-elevated');
        }
    }

    window.onscroll = function () {
        scrollTopFn();
    };

    // Notification
    // ------------
    const notificationMarkAsReadAll = document.querySelector('.dropdown-notifications-all');
    const notificationMarkAsReadList = document.querySelectorAll('.dropdown-notifications-read');

    // Notification: Mark as all as read
    if (notificationMarkAsReadAll) {
        notificationMarkAsReadAll.addEventListener('click', event => {
            notificationMarkAsReadList.forEach(item => {
                item.closest('.dropdown-notifications-item').classList.add('marked-as-read');
            });
        });
    }
    // Notification: Mark as read/unread onclick of dot
    if (notificationMarkAsReadList) {
        notificationMarkAsReadList.forEach(item => {
            item.addEventListener('click', event => {
                item.closest('.dropdown-notifications-item').classList.toggle('marked-as-read');
            });
        });
    }

    // Notification: Mark as read/unread onclick of dot
    const notificationArchiveMessageList = document.querySelectorAll('.dropdown-notifications-archive');
    notificationArchiveMessageList.forEach(item => {
        item.addEventListener('click', event => {
            item.closest('.dropdown-notifications-item').remove();
        });
    });

    // Init helpers & misc
    // --------------------

    // Init BS Tooltip
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // If layout is RTL add .dropdown-menu-end class to .dropdown-menu
    if (isRtl) {
        Helpers._addClass('dropdown-menu-end', document.querySelectorAll('#layout-navbar .dropdown-menu'));
    }

    // Auto update layout based on screen size
    window.Helpers.setAutoUpdate(true);

    // Toggle Password Visibility
    window.Helpers.initPasswordToggle();

    // Speech To Text
    window.Helpers.initSpeechToText();

    // Init PerfectScrollbar in Navbar Dropdown (i.e notification)
    window.Helpers.initNavbarDropdownScrollbar();

    // On window resize listener
    // -------------------------
    window.addEventListener(
        'resize',
        function (event) {
            // Hide open search input and set value blank
            if (window.innerWidth >= window.Helpers.LAYOUT_BREAKPOINT) {
                if (document.querySelector('.search-input-wrapper')) {
                    document.querySelector('.search-input-wrapper').classList.add('d-none');
                    document.querySelector('.search-input').value = '';
                }
            }
            // Horizontal Layout : Update menu based on window size
            let horizontalMenuTemplate = document.querySelector("[data-template^='horizontal-menu']");
            if (horizontalMenuTemplate) {
                setTimeout(function () {
                    if (window.innerWidth < window.Helpers.LAYOUT_BREAKPOINT) {
                        if (document.getElementById('layout-menu').classList.contains('menu-horizontal')) {
                            menu.switchMenu('vertical');
                        }
                    } else {
                        if (document.getElementById('layout-menu').classList.contains('menu-vertical')) {
                            menu.switchMenu('horizontal');
                        }
                    }
                }, 100);
            }
        },
        true
    );

    // Manage menu expanded/collapsed with templateCustomizer & local storage
    //------------------------------------------------------------------

    // If current layout is horizontal OR current window screen is small (overlay menu) than return from here
    if (isHorizontalLayout || window.Helpers.isSmallScreen()) {
        return;
    }

    // If current layout is vertical and current window screen is > small

    // Auto update menu collapsed/expanded based on the themeConfig
    if (typeof TemplateCustomizer !== 'undefined') {
        if (window.templateCustomizer.settings.defaultMenuCollapsed) {
            window.Helpers.setCollapsed(true, false);
        }
    }

    // Manage menu expanded/collapsed state with local storage support If enableMenuLocalStorage = true in config.js
    if (typeof config !== 'undefined') {
        if (config.enableMenuLocalStorage) {
            try {
                if (
                    localStorage.getItem('templateCustomizer-' + templateName + '--LayoutCollapsed') !== null &&
                    localStorage.getItem('templateCustomizer-' + templateName + '--LayoutCollapsed') !== 'false'
                )
                    window.Helpers.setCollapsed(
                        localStorage.getItem('templateCustomizer-' + templateName + '--LayoutCollapsed') === 'true',
                        false
                    );
            } catch (e) {
            }
        }
    }
})();
