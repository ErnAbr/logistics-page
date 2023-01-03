const form = document.getElementById("submit-form");
const formModal = document.getElementById("submit-form-modal");

function formValidation(event) {
  if (!form.checkValidity()) {
    event.preventDefault();
  }
  // form.classList.add("was-validated");
}

function formValidationModal(event) {
  if (formModal.checkValidity()) {
    event.preventDefault();
    alert("jūsų užsakymas priimtas");
  }
  formModal.classList.add("was-validated");
}

const submitButton = document.getElementById("submit-form-button");
const modalButton = document.getElementById("send-order-button");

submitButton.addEventListener("click", formValidation);
modalButton.addEventListener("click", formValidationModal);
