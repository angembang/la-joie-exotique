document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".btn-increment").forEach(button => {
        button.addEventListener("click", function () {
            const productGroup = this.closest(".products-group");
            if (productGroup) {
                const productId = productGroup.dataset.productId;
                updateQuantity(productId, 'increment');
            } else {
                const card = this.closest(".card");
                const productId = card.dataset.productId;
                updateQuantity(productId, 'increment');
            }
        });
    });

    document.querySelectorAll(".btn-decrement").forEach(button => {
        button.addEventListener("click", function () {
            const productGroup = this.closest(".products-group");
            if (productGroup) {
                const productId = productGroup.dataset.productId;
                updateQuantity(productId, 'decrement');
            } else {
                const card = this.closest(".card");
                const productId = card.dataset.productId;
                updateQuantity(productId, 'decrement');
            }
        });
    });

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
            if (!data.error) {
                const productGroup = document.querySelector(`.products-group[data-product-id='${productId}']`);
                if (productGroup) {
                    productGroup.querySelector(".quantity").value = data.quantity;
                    productGroup.querySelector(".subtotal").textContent = data.subtotal + "€";
                } else {
                    const card = document.querySelector(`.card[data-product-id='${productId}']`);
                    card.querySelector(".quantity").value = data.quantity;
                    card.querySelector(".subtotal").textContent = data.subtotal + "€";
                }
                document.getElementById("total-amount").textContent = data.totalAmount + "€";
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
            if (!data.error) {
                const productGroup = document.querySelector(`.products-group[data-product-id='${productId}']`);
                if (productGroup) {
                    productGroup.remove();
                } else {
                    const card = document.querySelector(`.card[data-product-id='${productId}']`);
                    card.remove();
                }
                document.getElementById("total-amount").textContent = data.totalAmount + "€";
            }
        })
        .catch(error => console.error("Error:", error));
    }
});