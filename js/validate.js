document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("registerForm");

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const phoneRegex = /^[0-9+\-\s]{7,15}$/;

    function showError(input, message) {
        const field = document.getElementById(fieldId);
        if (!field) return;
        let span = document.getElementById(fieldId + "Error");
        if (!span) {
            span = document.createElement("span");
            span.id = fieldId + "Error";
            span.className = "error-message";
            span.style.color = "red";
            span.style.marginLeft = "8px";
            field.insertAdjacentElement("afterend", span);
        }
        span.textContent = message;
    }

    function clearError() {
        form.querySelectorAll(".error-message").forEach(function (span) {
            el.textContent = "";
        });
    }
     
    function validateRegistrationForm() {
        clearError();
        let isValid = true;
        
        const name = document.getElementById("customer_name").value.trim();
        const email = document.getElementById("customer_email").value.trim();
        const pass = document.getElementById("customer_pass").value.trim();
        const country = document.getElementById("customer_country").value.trim();
        const city = document.getElementById("customer_city").value.trim();
        const contact = document.getElementById("customer_contact").value.trim();

        if (name == "" || name.length > 100) {
            showError("customer_name", "Name is required and must be less than 100 characters.");
            isValid = false;
        }
        if (!emailRegex.test(email) || email.length > 100) {
            showError("customer_email", "Valid email is required and must be less than 100 characters.");
            isValid = false;
        }
        if (pass.length < 6) {
            showError("customer_pass", "Password must be at least 6 characters long.");
            isValid = false;
        }
        if (country == "") {
            showError("customer_country", "Country is required.");
            isValid = false;
        }
        if (city == "") {
            showError("customer_city", "City is required.");
            isValid = false;
        }
        if (!phoneRegex.test(contact)) {
            showError("customer_contact", "Valid contact number is required.");
            isValid = false;
        }

        return isValid;
    }

    form.addEventListener("submit", function (event) {
        if (!validateRegistrationForm()) {
            event.preventDefault();
        }
    });

    form.addEventListener("click", function (event) {
    const btn = event.target.closest("button");
    if (btn && btn.getAttribute("onclick") === "registerCustomer()") {
        if (!validateRegistrationForm()) {
            event.preventDefault();
            event.stopPropagation();
        }
    }
}, true);
});

