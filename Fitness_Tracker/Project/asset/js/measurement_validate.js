function validateMeasurement() {
  // Get form values
  let date = document.getElementById("date").value;
  let weight = document.getElementById("weight").value;
  let waist = document.getElementById("waist").value;
  let chest = document.getElementById("chest").value;
  let arms = document.getElementById("arms").value;

  // Get error message containers
  let dateError = document.getElementById("dateError");
  let weightError = document.getElementById("weightError");
  let waistError = document.getElementById("waistError");
  let chestError = document.getElementById("chestError");
  let armsError = document.getElementById("armsError");
  let measurementMsg = document.getElementById("measurementMsg");

  // Clear previous messages
  dateError.innerHTML = "";
  weightError.innerHTML = "";
  waistError.innerHTML = "";
  chestError.innerHTML = "";
  armsError.innerHTML = "";
  measurementMsg.innerHTML = "";

  let hasError = false;

  // Validate date (allow today, block future)
  if (date === "") {
    dateError.style.color = "red";
    dateError.innerHTML = "Please select a date.";
    hasError = true;
  } else {
    let selectedDate = new Date(date);
    let today = new Date();

    // Strip time from both dates
    selectedDate.setHours(0, 0, 0, 0);
    today.setHours(0, 0, 0, 0);

    if (selectedDate > today) {
      dateError.style.color = "red";
      dateError.innerHTML = "Date cannot be in the future.";
      hasError = true;
    }
  }

  // Validate weight
  if (weight === "" || isNaN(weight) || weight <= 0) {
    weightError.style.color = "red";
    weightError.innerHTML = "Please enter a valid weight.";
    hasError = true;
  }

  // Validate waist
  if (waist === "" || isNaN(waist) || waist <= 0) {
    waistError.style.color = "red";
    waistError.innerHTML = "Please enter a valid waist measurement.";
    hasError = true;
  }

  // Validate chest
  if (chest === "" || isNaN(chest) || chest <= 0) {
    chestError.style.color = "red";
    chestError.innerHTML = "Please enter a valid chest measurement.";
    hasError = true;
  }

  // Validate arms
  if (arms === "" || isNaN(arms) || arms <= 0) {
    armsError.style.color = "red";
    armsError.innerHTML = "Please enter a valid arms measurement.";
    hasError = true;
  }

  return !hasError;
}
