let currentStep = 1;

function nextStep() {
  if (validateStep(currentStep)) {
    document.getElementById('step-' + currentStep).classList.remove('active');
    currentStep++;
    document.getElementById('step-' + currentStep).classList.add('active');
  } else {
    alert('Silakan isi semua field yang diperlukan.');
  }
}

function prevStep() {
  document.getElementById('step-' + currentStep).classList.remove('active');
  currentStep--;
  document.getElementById('step-' + currentStep).classList.add('active');
}

function validateStep(step) {
  const stepElement = document.getElementById('step-' + step);
  const inputs = stepElement.querySelectorAll('input, textarea, select');
  for (let input of inputs) {
    if (input.hasAttribute('required') && !input.value) {
      return false;
    }
  }
  return true;
}