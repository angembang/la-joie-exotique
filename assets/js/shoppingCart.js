document.addEventListener("DOMContentLoaded", function() {
    const cartContainer = document.querySelector(".shopping-cart-page");

    if (cartContainer) {
        cartContainer.addEventListener("click", function(event) {
            if (event.target.classList.contains("btn-increment")) {
                const card = event.target.closest(".card");
                const productId = card.dataset.productId;
                updateQuantity(productId, 'increment');
            }

            if (event.target.classList.contains("btn-decrement")) {
                const card = event.target.closest(".card");
                const productId = card.dataset.productId;
                updateQuantity(productId, 'decrement');
            }

        });
    }
    
    document.querySelectorAll(".btn-delete").forEach(button => {
        button.addEventListener("click", function (e) {
            e.preventDefault();
            const productGroup = this.closest(".products-group");
            if (productGroup) {
                const productId = productGroup.dataset.productId;
                deleteProduct(productId);
            } else {
                const card = this.closest(".card");
                const productId = card.dataset.productId;
                deleteProduct(productId);
                
            }
        });
    });

    function updateQuantity(productId, action) {
        fetch("index.php?route=update-quantity", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ product_id: productId, action: action }),
        })
        .then(response => response.json())
        .then(data => {
            console.log("Response from server:", data); // Debug message
            if (!data.error) {
                const card = document.querySelector(`.card[data-product-id='${productId}']`);
                if (card) {
                    card.querySelector(".quantity").value = data.quantity;
                    card.querySelector(".subtotal").textContent = data.subtotal + "€";
                }
                document.getElementById("total-amount").textContent = data.totalAmount + "€";
                updateCartItemCount(data.cart);
                showModal(data.message);
            } else {
                showModal(data.message);
            }
        })
        .catch(error => console.error("Error:", error));
    }

    function deleteProduct(productId) {
        fetch("index.php?route=delete-product", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ product_id: productId }),
        })
        .then(response => response.json())
        .then(data => {
            console.log("Response from server (delete):", data); // Debug message
            if (!data.error) {
                const card = document.querySelector(`.card[data-product-id='${productId}']`);
                if (card) {
                    console.log(`Removing card with productId: ${productId}`);
                    card.remove();
                }
                document.getElementById("total-amount").textContent = data.totalAmount + "€";
                updateCartItemCount(data.cart);
                showModal(data.message);
            } else {
                showModal(data.message);
            }
        })
        .catch(error => console.error("Error (delete):", error));
    }

    function updateCartItemCount(cart) {
        const cartItemCount = document.querySelector('.cart-item-count');
        cartItemCount.textContent = Object.values(cart).reduce((sum, qty) => sum + qty, 0);
    }

    function showModal(message) {
        const modal = document.getElementById("successModal");
        const modalMessage = document.getElementById("modalMessage");
        const closeButton = document.querySelector(".modal .close");

        modalMessage.textContent = message;
        modal.style.display = "block";

        closeButton.onclick = function () {
            modal.style.display = "none";
        }

        setTimeout(function () {
            modal.style.display = "none";
        }, 3000);
    }

    window.onclick = function (event) {
        const modal = document.getElementById("successModal");
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
});