class ShortcutsMenu {
    overallMenuWidth = 0;
    menuItems = null;
    initialDynamicMenuWidth = 0;

    resizeObserver = new ResizeObserver(() => {
        if (this.hasResponsiveMenusSize) {
            this.applyResponsiveClassNames();
        } else {
            this.hideShortcutsMenu();
        }
    });

    get hasResponsiveMenusSize() {
        const header = document.querySelector('header');

        return header.getAttribute('data-responsive-shortcut-menu') === 'true';
    }

    get menuContainerEl() {
        return document.querySelector('.shortcuts-menu');
    }

    get serviceMenuWidth() {
        return document.querySelector('#service-menu');
    }

    get hasAbsoluteLogo() {
        const style = window.getComputedStyle(document.querySelector('.brand'));
        return style.position === 'absolute';
    }

    get logoWidthOffset() {
        const width = document.querySelector('.brand')?.offsetWidth || 0;
        return this.hasAbsoluteLogo ? width + 30 : 0;
    }

    get menuContainerWidth() {
        return this.menuContainerEl ? this.menuContainerEl.offsetWidth - this.logoWidthOffset : null;
    }

    get menuIsTooWide() {
        return this.overallMenuWidth > this.menuContainerWidth;
    }

    get hasUnifiedShortcutsMenu() {
        const headerEl = document.getElementById('header-v2');

        if (!headerEl) {
            return false;
        }

        const headerClasslist = headerEl.classList;

        return ['variant-shortcuts-unified', 'variant-shortcuts-unified-alt'].some(className => headerClasslist.contains(className));
    }

    assignMenuItems() {
        const shortcutMenu = document.querySelector('.shortcuts-menu');
        const menuItems = Array.from(shortcutMenu.querySelectorAll('li'));

        return menuItems.map((item, index) => {
            let width;

            if (index < menuItems.length) {
                width = item.offsetWidth + 15;
            } else {
                width = item.offsetWidth;
            }

            return { item, width };
        });
    }

    applyResponsiveClassNames() {
        if (this.menuItems) {
            let dynamicMenuWidth = 0;
            let index = 0;

            for (const { width } of this.menuItems) {
                dynamicMenuWidth += width;

                if (dynamicMenuWidth < this.menuContainerWidth) {
                    index++;
                } else {
                    break;
                }
            }

            const menuItemsToShow = this.menuItems.slice(0, index + 1);
            const menuItemsToHide = this.menuItems.slice(index, this.menuItems.length);

            menuItemsToShow.forEach(({ item }) => {
                if (item.classList.contains('hidden')) {
                    item.classList.remove('hidden');
                }
            });

            menuItemsToHide.forEach(({ item }) => {
                item.classList.add('hidden');
            });
        }
    }

    hideShortcutsMenu() {
        const menusWidth = document.querySelector('.menus').offsetWidth;
        const menusChildren = document.querySelector('.menus').children;
        const availableSpace = Array.from(menusChildren).reduce((width, child) => {
            if (!child.classList.contains('shortcuts-menu')) {
                width -= child.offsetWidth;
            }

            return width;
        }, menusWidth);

        if (availableSpace < this.initialDynamicMenuWidth) {
            this.menuItems.forEach(({ item }) => item.classList.add('hidden'));
        } else {
            this.menuItems.forEach(({ item }) => item.classList.remove('hidden'));
        }
    }

    calculateDynamicMenuWidth() {
        if (this.menuItems) {
            let totalWidth = 0;

            for (const { width } of this.menuItems) {
                totalWidth += width;
            }

            return totalWidth;
        }
    }

    init() {
        if (this.hasUnifiedShortcutsMenu) {
            this.menuItems = this.assignMenuItems();
            this.overallMenuWidth = this.menuItems.reduce((totalWidth, item) => {
                return totalWidth += item.width;
            }, 0);
            this.initialDynamicMenuWidth = this.calculateDynamicMenuWidth();
            this.resizeObserver.observe(document.querySelector('body'));
        }
    }
}

export const shortcutsMenu = new ShortcutsMenu;
