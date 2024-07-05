document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".add-to-cart").forEach(button => {
    button.addEventListener("click", function (e) {
        e.preventDefault();
        const productId = this.getAttribute("data-product-id");
        addToCart(productId);
    });
});

function addToCart(productId) {
    fetch(`index.php?route=cart&id=${productId}`, {
        method: "GET",
        headers: {
            "Content-Type": "application/json",
        }
    })
    .then(response => response.json()) // Utiliser json() pour analyser la réponse
    .then(data => {
        if (data.success) {
            showModal(data.message);
            // Mettre à jour l'interface utilisateur du panier si nécessaire
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error("Error:", error));
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