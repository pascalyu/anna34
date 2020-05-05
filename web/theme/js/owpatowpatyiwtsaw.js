var stripe = Stripe('pk_test_JMn7Aufsq49eq5ESDxeJUsjy00BuLFiMjK');

// Create an instance of Elements.
var elements = stripe.elements();

// Custom styling can be passed to options when creating an Element.
// (Note that this demo uses a wider set of styles than the guide below.)
var style = {
    base: {
        color: "#32325d",
    }
};

var card = elements.create("card", { style: style });
card.mount("#card-element");

// Handle real-time validation errors from the card Element.
card.addEventListener('change', function (event) {
    var displayError = document.getElementById('card-errors');
    if (event.error) {
        displayError.textContent = event.error.message;
    } else {
        displayError.textContent = '';
    }
});

var form = document.getElementById('payment-form');

form.addEventListener('submit', function (ev) {

    ev.preventDefault();
    stripe.confirmCardPayment($("#submit").data('secret'), {
        payment_method: {
            card: card,
            billing_details: {
                name: $('#user-name').val()
            }
        }
    }).then(function (result) {
        if (result.error) {
            // Show error to your customer (e.g., insufficient funds)
            console.log(result.error.message);
        } else {
            // The payment has been processed!*

            if (result.paymentIntent.status === 'succeeded') {

            }
        }
    });
});
// Submit the form with the token ID.