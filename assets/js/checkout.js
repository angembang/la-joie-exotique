document.addEventListener("DOMContentLoaded", function () {
  const stripe = Stripe('pk_test_51PTNZZJ3Pl4ECRsy750C4Y5fcqwGJ4cK0ABwGoBYrckWnNi7WP6TdzuBlXlyAzD8zjYdPYFIgmR0S3tbgK3eeJVy00ek3o2pds');

  let amount;
  // Retrieve the value of the amount input
  let amountInput = document.getElementById("total-amount").value;

  amount = parseFloat(amountInput);

  // Check if the amount is superior to 1
  if (amount >= 1) {
    initialise();
  }

  let elements;

  checkStatus();

  document.querySelector("#payment-form").addEventListener("submit", handleSubmit);

  // Fetches a payment intent and captures the client secret
  async function initialise() {
    const { clientSecret } = await fetch("./index.php?route=create-paiement-stripe", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ amount }),
    }).then((r) => r.json());

    elements = stripe.elements({ clientSecret });

    const paymentElementOptions = {
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

    const form = document.querySelector("#payment-form");
    const guestName = form.querySelector("input[name='guest-name']").value;
    const totalAmount = form.querySelector("input[name='total-amount']").value;
    const userId = form.querySelector("input[name='user-id']").value || null;

    console.log("Guest Name:", guestName);
    console.log("Total Amount:", totalAmount);
    console.log("User ID:", userId);

    try {
      const { error, paymentIntent } = await stripe.confirmPayment({
        elements,
        confirmParams: {
          return_url: `https://angekamwangmbang.sites.3wa.io/php/la-joie-exotique/index.php?route=payment-success&guest_name=${encodeURIComponent(guestName)}&total_amount=${encodeURIComponent(totalAmount)}&user_id=${encodeURIComponent(userId)}`,
        },
      });

      if (error) {
        showMessage(error.message);
        setLoading(false);
      } else if (paymentIntent && paymentIntent.status === 'succeeded') {
        // Payment succeeded, perform the redirection
        window.location.href = `https://angekamwangmbang.sites.3wa.io/php/la-joie-exotique/index.php?route=payment-success&payment_intent=${paymentIntent.id}&guest_name=${encodeURIComponent(guestName)}&total_amount=${encodeURIComponent(totalAmount)}&user_id=${encodeURIComponent(userId)}`;
      } else {
        showMessage("An error occurred during the operation");
        setLoading(false);
      }
    } catch (err) {
      console.error('Error confirming payment:', err);
      showMessage('An error occurred during the payment process');
      setLoading(false);
    }
  }

  // Fetches the payment intent status after payment submission
  async function checkStatus() {
    const clientSecret = new URLSearchParams(window.location.search).get("payment_intent_client_secret");
    if (!clientSecret) {
      return;
    }

    try {
      const { paymentIntent } = await stripe.retrievePaymentIntent(clientSecret);

      if (paymentIntent && paymentIntent.status === 'succeeded') {
        showMessage('Paiement réussi');
      } else {
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

  function setLoading(isLoading) {
    if (isLoading) {
      document.querySelector("#submit").disabled = true;
      document.querySelector("#spinner").classList.remove("hidden");
      document.querySelector("#button-text").classList.add("hidden");
    } else {
      document.querySelector("#submit").disabled = false;
      document.querySelector("#spinner").classList.add("hidden");
      document.querySelector("#button-text").classList.remove("hidden");
    }
  }

  if (window.location.href.indexOf("payment_intent") > -1) {
    window.history.replaceState({}, document.title, "/index.php?route=clear-cart");
  }
});