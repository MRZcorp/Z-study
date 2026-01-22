// Toggle the visibility of a dropdown menu
const toggleDropdown = (dropdown, menu, isOpen) => {
    dropdown.classList.toggle("open", isOpen);
    menu.style.height = isOpen ? `${menu.scrollHeight}px` : 0;
};

// Close all open dropdowns
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

// Attach click event to all dropdown toggles
document.querySelectorAll(".dropdown-toggle").forEach((dropdownToggle) => {
    dropdownToggle.addEventListener("click", (e) => {
        e.preventDefault();

        const dropdown = dropdownToggle.closest(".dropdown-container");
        const menu = dropdown.querySelector(".dropdown-menu");
        const isOpen = dropdown.classList.contains("open");

        closeAllDropdowns(); // Close all open dropdowns
        toggleDropdown(dropdown, menu, !isOpen); // Toggle current dropdown visibility
    });
});

// Attach click event to sidebar toggle buttons
document
    .querySelectorAll(".sidebar-toggler, .sidebar-menu-button")
    .forEach((button) => {
        button.addEventListener("click", () => {
            closeAllDropdowns(); // Close all open dropdowns
            document.querySelector(".sidebar").classList.toggle("collapsed"); // Toggle collapsed class on sidebar
        });
    });

// Collapse sidebar by default on small screens
if (window.innerWidth <= 1024)
    document.querySelector(".sidebar").classList.add("collapsed");

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
