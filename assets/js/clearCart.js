document.addEventListener("DOMContentLoaded", function() {
  const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('payment-success')) {
        fetch('index.php?route=payment-success')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showModal(data.message);
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
        }, 5000);

        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    }
});