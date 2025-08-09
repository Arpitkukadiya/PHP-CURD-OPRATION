// script.js

// Check if the user is logged in on page load
checkLoginStatus();

// Login function
function login() {
    // For simplicity, let's assume any non-empty username and password is a successful login
    var username = document.getElementById("username").value;
    var password = document.getElementById("password").value;

    if (username && password) {
        // Mock authentication success
        localStorage.setItem("loggedInUser", username);
        checkLoginStatus();
    } else {
        alert("Invalid credentials. Please enter a username and password.");
    }
}

// Logout function
function logout() {
    // Clear the logged-in user from local storage
    localStorage.removeItem("loggedInUser");
    checkLoginStatus();
}

// Check and update the login status
function checkLoginStatus() {
    var loggedInUser = localStorage.getItem("loggedInUser");
    var loginLink = document.getElementById("loginLink");
    var logoutLink = document.getElementById("logoutLink");
    var welcomeMessage = document.getElementById("welcomeMessage");

    if (loggedInUser) {
        // User is logged in
        loginLink.style.display = "none";
        logoutLink.style.display = "inline-block";
        welcomeMessage.style.display = "block";
        welcomeMessage.innerHTML = "Welcome, " + loggedInUser + "!";
    } else {
        // User is not logged in
        loginLink.style.display = "inline-block";
        logoutLink.style.display = "none";
        welcomeMessage.style.display = "none";
    }
}

// 
// Check if the user is logged in on page load
checkLoginStatus();

// Login function
function login() {
    // For simplicity, let's assume any non-empty username and password is a successful login
    var username = document.getElementById("username").value;
    var password = document.getElementById("password").value;

    if (username && password) {
        // Mock authentication success
        localStorage.setItem("loggedInUser", username);
        checkLoginStatus();
    } else {
        alert("Invalid credentials. Please enter a username and password.");
    }
}

// script.js

// Check if the user is logged in on page load
checkLoginStatus();

// Handle payment method change
document.getElementById('paymentMethod').addEventListener('change', function () {
    // Hide all payment fields
    hideAllPaymentFields();

    // Get the selected payment method
    var selectedPaymentMethod = this.value;

    // Show the specific payment fields based on the selection
    switch (selectedPaymentMethod) {
        case 'cod':
            showPaymentFields('codFields');
            break;
        case 'upi':
            showPaymentFields('upiFields');
            break;
        case 'creditCard':
            showPaymentFields('creditCardFields');
            break;
        case 'debitCard':
            showPaymentFields('debitCardFields');
            break;
        default:
            // No specific action for other methods
    }
});

// Function to show a specific payment field section
function showPaymentFields(fieldId) {
    var paymentFields = document.getElementById(fieldId);
    if (paymentFields) {
        paymentFields.style.display = 'block';
    }
}

// Function to hide all payment field sections
function hideAllPaymentFields() {
    var allFields = document.getElementsByClassName('payment-fields');
    for (var i = 0; i < allFields.length; i++) {
        allFields[i].style.display = 'none';
    }
}

// Function to simulate processing the payment
function processPayment() {
    var selectedPaymentMethod = document.getElementById('paymentMethod').value;
    
    // You can add more sophisticated logic here, such as contacting a payment gateway
    // For simplicity, we'll just display an alert
    alert('Payment processed successfully using ' + selectedPaymentMethod);
}


function showProductModal(productId) {
    // This function can be used to fetch product details from the server based on productId
    // For the sake of simplicity, let's just display a static message
    const productDetails = document.getElementById('productDetails');
    productDetails.innerHTML = `<p>Details of Product ${productId}</p>`;
    $('#productModal').modal('show');
}