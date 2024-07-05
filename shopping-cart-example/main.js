import { addToCart } from './cart.js';

document.addEventListener("DOMContentLoaded", () => {
    console.log("Le DOM est chargé.");

    function getRoute()
    {
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);

        return urlParams.get('route');
    }

    const route = getRoute();

    /*console.log(route);*/

    if(route === 'boutique') {
        let addTCartBtns = document.querySelectorAll('.btn-add-to-cart');

        addTCartBtns.forEach(function (btn) {
            btn.addEventListener('click', function (event) {
                let element = event.target;
                let product_id = element.getAttribute("data-product");
                addToCart(product_id);
            });
        });
    }
});
