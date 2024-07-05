// Import the slider product from product.js
//import {productSlider} from "./modules/product.js";
//import {addProductToCart} from "./modules/shoppingCart.js";

// Listen for the DOMContentLoaded event
document.addEventListener("DOMContentLoaded", () => {
    // Call the function for the product image slider
    //productSlider();

    /*function getRoute()
    {
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);

        return urlParams.get('route');
    }

    const route = getRoute();
    console.log("Current route:", route);


    if(route === 'category-products') {
        let addToCartBtns = document.querySelectorAll(".ajouter-produit");
        addToCartBtns.forEach(function (btn) {
            btn.addEventListener('click', function (event) {
                event.preventDefault();
                let product_id = btn.getAttribute("data-product");
                console.log(`Adding product ID ${product_id} to cart`);
                addProductToCart(product_id);
                
            });
        });
    }*/
})