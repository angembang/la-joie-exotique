document.addEventListener("DOMContentLoaded", function() {
  const registerForm = document.querySelector(".register-form");
  const loginForm = document.querySelector(".login-form");
  const toggleToRegister = document.querySelector("#toggleToRegister");
  const toggleToLogin = document.querySelector("#toggleToLogin");

  // Initially show the login form and hide the register form
  registerForm.classList.add("hidden");

  // Toggle to register form
  toggleToRegister.addEventListener("click", function(event) {
      event.preventDefault();
      loginForm.classList.add("hidden");
      registerForm.classList.remove("hidden");
  });

  // Toggle to login form
  toggleToLogin.addEventListener("click", function(event) {
      event.preventDefault();
      registerForm.classList.add("hidden");
      loginForm.classList.remove("hidden");
  });
});