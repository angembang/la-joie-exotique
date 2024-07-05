document.addEventListener("DOMContentLoaded", function () {
  const stripe = Stripe('pk_test_51PTNZZJ3Pl4ECRsy750C4Y5fcqwGJ4cK0ABwGoBYrckWnNi7WP6TdzuBlXlyAzD8zjYdPYFIgmR0S3tbgK3eeJVy00ek3o2pds');

  let amount;
  // Retrieve the value of the amount input
  let amountInput = document.getElementById("total-amount").value;

  amount = parseFloat(amountInput);

  // Check if the amount is superior to 1
  if(amount >= 1) {
    initialise();
  }

  let elements;

  checkStatus();

  document
  .querySelector("#payment-form")
  .addEventListener("submit", handleSubmit);

  // Fetches a payment intent and captures the client secret
  async function initialise() {
    const {clientSecret} = await fetch("././create.php", {
      method: "POST",
      headers: {"Content-Type": "application/json"},
      body: JSON.stringify({ amount }),
    }).then((r) =>r.json());

    elements = stripe.elements({clientSecret});

    const paymentElementOptions  = {
      layout: "tabs",
    };

    const paymentElement = elements.create("payment", paymentElementOptions);
    paymentElement.mount("#payment-element");

    const buttonSubmit = document.getElementById("submit");
    buttonSubmit.disabled = false;
    buttonSubmit.querySelector("#button-text").textContent = "Payer " + amount + "€";
  }

  async function handleSubmit(e) {
    e.preventDefault();
    setLoading(true);

    const {error} = await stripe.confirmPayment({
      elements,
      confirmParams: {
        // Return to the payment page after payment succeded
        return_url: "http://localhost/index.php?route=clean-url",
      },
    });

    if(error.type === "card_error" || ErrorEvent.type === "validation_error") {
      showMessage(error.message);
    } else {
      showMessage("An error occurs during the operation");
    }
    setLoading(false);

  }

  // Fetches the payment intent status after payment submission
  async function checkStatus() {
    const clientSecret = new URLSearchParams(window.location.search).get(
      "payment_intent_client_secret"
    );
    if(!clientSecret) {
      return;
    }

    try {
      const { paymentIntent } = await stripe.retrievePaymentIntent(clientSecret);

      if (paymentIntent && paymentIntent.status === 'succeeded') {
        showMessage('Paiement réussi');
      } else {
          // Handle other statuses like 'processing', 'requires_payment_method', etc.
          showMessage('Payment processing or requires action');
      }
  } catch (error) {
      console.error('Error fetching payment intent:', error);
      showMessage('Error fetching payment status');
  }

    }

  // ------------------UI Helpers --------------
  function showMessage(messageText) {
    const messageContainer = document.querySelector("#payment-message");
    messageContainer.classList.remove("hidden");
    messageContainer.textContent = messageText;

    setTimeout(function () {
      messageContainer.classList.add("hidden");
      messageContainer.textContent = "";
    }, 4000);
  }

  // Show a spinner on payment submission
  function setLoading(isLoading) {
    if (isLoading) {
      // Disable the button and show a spinner
      document.querySelector("#submit").disabled = true;
      document.querySelector("#spinner").classList.remove("hidden");
      document.querySelector("#button-text").classList.add("hidden");
    } else {
      document.querySelector("#submit").disabled = false;
      document.querySelector("#spinner").classList.add("hidden");
      document.querySelector("#button-text").classList.remove("hidden");
    }
  }


  // Clean the URL after loading
  if (window.location.href.indexOf("payment_intent") > -1) {
    window.history.replaceState({}, document.title, "/index.php?route=clear-cart");
  }


});



