const seatInput = document.getElementById("seats");
const courseTotal = document.getElementById("courseTotal");
const finalAmount = document.getElementById("finalAmount");
const errorMsg = document.getElementById("error");
const discountMsg = document.getElementById("discountMsg");
const classType = document.getElementById("classType");
const confirmCheck = document.getElementById("confirm");
const submitBtn = document.getElementById("submitBtn");
const enrollmentForm = document.getElementById("enrollmentForm");
const nameInput = document.getElementById("name");
const emailInput = document.getElementById("email");
const formError = document.getElementById("formError");

const PRICE_PER_SEAT = 1000;

function calculateTotal() {
    let seats = parseInt(seatInput.value);

    // Validation
    if (seats <= 0 || isNaN(seats)) {
        errorMsg.textContent = "Seats must be at least 1.";
        seatInput.value = 1;
        seats = 1;
    } else {
        errorMsg.textContent = "";
    }

    let total = seats * PRICE_PER_SEAT;
    courseTotal.textContent = total;

    // Discount check
    if (total > 5000) {
        discountMsg.textContent = "You are eligible for a special discount.";
    } else {
        discountMsg.textContent = "";
    }

    updateFinalAmount(total);
}

function updateFinalAmount(courseFee) {
    let extraFee = 0;

    if (classType.value === "online") {
        extraFee = 100;
    } else {
        extraFee = 250;
    }

    finalAmount.textContent = courseFee + extraFee;
}

// Event listeners
seatInput.addEventListener("input", calculateTotal);

classType.addEventListener("change", () => {
    let currentCourseFee = parseInt(courseTotal.textContent);
    updateFinalAmount(currentCourseFee);
});

confirmCheck.addEventListener("change", () => {
    submitBtn.style.display = confirmCheck.checked ? "block" : "none";
    formError.textContent = "";
});

enrollmentForm.addEventListener("submit", (event) => {
    event.preventDefault();

    const fullName = nameInput.value.trim();
    const email = emailInput.value.trim();
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!fullName) {
        formError.textContent = "Full name is required.";
        nameInput.focus();
        return;
    }

    if (!emailPattern.test(email)) {
        formError.textContent = "Enter a valid email address.";
        emailInput.focus();
        return;
    }

    if (!confirmCheck.checked) {
        formError.textContent = "Please confirm your enrollment details to submit.";
        return;
    }

    formError.textContent = "Enrollment submitted successfully.";
});

// Initial calculation
calculateTotal();
submitBtn.style.display = confirmCheck.checked ? "block" : "none";