document.addEventListener("DOMContentLoaded", function () {
    function burgerMenu() {
        // Get the menu icon element
        const icon = document.getElementById("menu-icon");
        // Get the menu element
        const menu = document.querySelector(".home-nav-bar > ul > ul");

        // Add event listener to the menu icon for mouse enter event
        icon.addEventListener("mouseenter", function(event) {
            // Remove the 'display-none' class to show the menu
            menu.classList.remove("display-none");
            
            // Use setTimeout to define a delay of restoring the class display-none
            setTimeout(function() {
                // Add the 'display-none' class to hide the menu after 2 seconds
                menu.classList.remove("display-block");
                menu.classList.add("display-none");
            }, 2000);
        });
    }

    burgerMenu();
});