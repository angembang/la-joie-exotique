document.addEventListener("DOMContentLoaded", function () {
  const deliveryOptions = document.getElementById("delivery-options");
  const paymentForm = document.getElementById("payment-form");
  const loginOption = document.getElementById("login-option");
  const guestOption = document.getElementById("guest-option");

  loginOption.addEventListener("click", function () {
      window.location.href = "index.php?route=register-login&redirect=payment";
  });

  guestOption.addEventListener("click", function () {
      deliveryOptions.classList.add("hidden");
      paymentForm.classList.remove("hidden");
  });

  // Check for redirect parameter
  const urlParams = new URLSearchParams(window.location.search);
  if (urlParams.get('redirect') === 'payment') {
      deliveryOptions.classList.add("hidden");
      paymentForm.classList.remove("hidden");
  }
});