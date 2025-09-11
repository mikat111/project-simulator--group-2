function validateGoal() {
  let goalType = document.getElementById("goalType").value;
  let targetValue = document.getElementById("targetValue").value;
  let deadline = document.getElementById("deadline").value;
  let description = document.getElementById("description").value;

  let targetError = document.getElementById("targetError");
  let deadlineError = document.getElementById("deadlineError");
  let descriptionError = document.getElementById("descriptionError");

  targetError.innerHTML = "";
  deadlineError.innerHTML = "";
  descriptionError.innerHTML = "";

  let hasError = false;

  if (targetValue === "" || isNaN(targetValue) || targetValue <= 0) {
    targetError.style.color = "red";
    targetError.innerHTML = "Please enter a valid target value.";
    hasError = true;
  }

  if (deadline === "") {
    deadlineError.style.color = "red";
    deadlineError.innerHTML = "Please select a deadline.";
    hasError = true;
  } else if (new Date(deadline) <= new Date()) {
    deadlineError.style.color = "red";
    deadlineError.innerHTML = "Deadline must be a future date.";
    hasError = true;
  }

  if (description.trim() === "") {
    descriptionError.style.color = "red";
    descriptionError.innerHTML = "Please enter a goal description.";
    hasError = true;
  }

  return !hasError;
}
