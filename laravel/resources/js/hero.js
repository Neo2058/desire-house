document.addEventListener("DOMContentLoaded", () => {

    const burger = document.getElementById("hero-burger");
    const menu = document.getElementById("mobile-menu");

    if (!burger || !menu) {
        return;
    }

    const overlay = menu.querySelector(".mobile-menu__overlay");
    const close = menu.querySelector(".mobile-menu__close");
    const links = menu.querySelectorAll(".mobile-menu__link");

    function openMenu() {

        burger.classList.add("active");
        menu.classList.add("active");

        document.body.style.overflow = "hidden";

    }

    function closeMenu() {

        burger.classList.remove("active");
        menu.classList.remove("active");

        document.body.style.overflow = "";

    }

    burger.addEventListener("click", () => {

        if (menu.classList.contains("active")) {

            closeMenu();

        } else {

            openMenu();

        }

    });

    close.addEventListener("click", closeMenu);

    overlay.addEventListener("click", closeMenu);

    links.forEach(link => {

        link.addEventListener("click", closeMenu);

    });

    document.addEventListener("keydown", (event) => {

        if (event.key === "Escape") {

            closeMenu();

        }

    });

});
