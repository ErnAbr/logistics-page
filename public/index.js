const form = document.getElementById("submit-form");
const formModal = document.getElementById("submit-form-modal");

function formValidation(event) {
  if (form.checkValidity()) {
    event.preventDefault();
    successMessage.classList =
      "alert alert-dismissible alert-success d-flex align-items-center";
  }
  form.classList.add("was-validated");
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

const successMessage = document.getElementById("registraton-success");

submitButton.addEventListener("click", formValidation);
modalButton.addEventListener("click", formValidationModal);
