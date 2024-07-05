document.addEventListener("DOMContentLoaded", function() {
  const addToCartButtons = document.querySelectorAll('.add-to-cart');

  addToCartButtons.forEach(button => {
      button.addEventListener('click', function(event) {
          event.preventDefault();
          const productId = this.dataset.productId;
          const url = `index.php?route=cart&id=${productId}`;

          fetch(url)
              .then(response => response.json())
              .then(data => {
                  if (data.success) {
                      updateCartItemCount(data.cart);
                      showModal(data.message);
                  } else {
                      showModal(data.message);
                  }
              })
              .catch(error => console.error('Error:', error));
      });
  });

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