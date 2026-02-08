const SIDEBAR_COLLAPSED_KEY = "sidebar_collapsed";
const SIDEBAR_OPEN_DROPDOWN_KEY = "sidebar_open_dropdown";

const toggleDropdown = (dropdown, menu, isOpen) => {
    dropdown.classList.toggle("open", isOpen);
    menu.style.height = isOpen ? `${menu.scrollHeight}px` : 0;
};

const closeAllDropdowns = () => {
    document
        .querySelectorAll(".dropdown-container.open")
        .forEach((openDropdown) => {
            toggleDropdown(
                openDropdown,
                openDropdown.querySelector(".dropdown-menu"),
                false
            );
        });
};

const getDropdownId = (dropdown) => {
    return (
        dropdown?.dataset?.dropdownId ||
        dropdown?.querySelector(".nav-label")?.textContent?.trim() ||
        ""
    );
};

document.addEventListener("DOMContentLoaded", () => {
    const sidebar = document.querySelector(".sidebar");

    if (sidebar) {
        const storedCollapsed = localStorage.getItem(SIDEBAR_COLLAPSED_KEY);
        if (storedCollapsed !== null) {
            sidebar.classList.toggle("collapsed", storedCollapsed === "1");
        } else if (window.innerWidth <= 1024) {
            sidebar.classList.add("collapsed");
        }
    }

    const storedDropdownId = localStorage.getItem(SIDEBAR_OPEN_DROPDOWN_KEY);
    if (storedDropdownId) {
        const dropdown = document.querySelector(
            `.dropdown-container[data-dropdown-id="${storedDropdownId}"]`
        );
        if (dropdown) {
            const menu = dropdown.querySelector(".dropdown-menu");
            if (menu) toggleDropdown(dropdown, menu, true);
        }
    }

    document.querySelectorAll(".dropdown-toggle").forEach((dropdownToggle) => {
        dropdownToggle.addEventListener("click", (e) => {
            e.preventDefault();

            const dropdown = dropdownToggle.closest(".dropdown-container");
            const menu = dropdown.querySelector(".dropdown-menu");
            const isOpen = dropdown.classList.contains("open");
            const dropdownId = getDropdownId(dropdown);

            closeAllDropdowns();
            toggleDropdown(dropdown, menu, !isOpen);

            if (!isOpen && dropdownId) {
                localStorage.setItem(SIDEBAR_OPEN_DROPDOWN_KEY, dropdownId);
            } else {
                localStorage.removeItem(SIDEBAR_OPEN_DROPDOWN_KEY);
            }
        });
    });

    document
        .querySelectorAll(".sidebar-toggler, .sidebar-menu-button")
        .forEach((button) => {
            button.addEventListener("click", () => {
                closeAllDropdowns();
                if (!sidebar) return;
                sidebar.classList.toggle("collapsed");
                localStorage.setItem(
                    SIDEBAR_COLLAPSED_KEY,
                    sidebar.classList.contains("collapsed") ? "1" : "0"
                );
            });
        });
});

// navbar///////////////////////////////////////////////////////////////////

document.addEventListener("DOMContentLoaded", function () {
    // Dropdown profile handling
    const dropdowns = document.querySelectorAll(".dropdown");

    dropdowns.forEach((dropdown) => {
        const button = dropdown.querySelector("button");
        const menu = dropdown.querySelector(".navnbar-dropdown");

        if (!button || !menu) return;

        // Toggle on click
        button.addEventListener("click", (e) => {
            e.stopPropagation();

            const isOpen = !menu.classList.contains("opacity-0");

            // Close other dropdowns
            document.querySelectorAll(".dropdown-menu").forEach((m) => {
                if (m !== menu) {
                    m.classList.add("opacity-0", "invisible", "-translate-y-2");
                }
            });

            // Toggle current dropdown
            if (isOpen) {
                menu.classList.add("opacity-0", "invisible", "-translate-y-2");
            } else {
                menu.classList.remove(
                    "opacity-0",
                    "invisible",
                    "-translate-y-2"
                );
            }
        });

        // Hover (desktop only)
        if (window.innerWidth > 768) {
            dropdown.addEventListener("mouseenter", () => {
                menu.classList.remove(
                    "opacity-0",
                    "invisible",
                    "-translate-y-2"
                );
            });

            dropdown.addEventListener("mouseleave", () => {
                menu.classList.add("opacity-0", "invisible", "-translate-y-2");
            });
        }
    });

    // Close dropdown when clicking outside
    document.addEventListener("click", () => {
        document.querySelectorAll(".navbar-dropdown").forEach((menu) => {
            menu.classList.add("opacity-0", "invisible", "-translate-y-2");
        });
    });
});
