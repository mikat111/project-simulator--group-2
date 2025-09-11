function validateAchievement() {
  let isValid = true;

  document.getElementById("titleError").textContent = "";
  document.getElementById("descriptionError").textContent = "";
  document.getElementById("dateError").textContent = "";

  const title = document.getElementById("title").value.trim();
  const description = document.getElementById("description").value.trim();
  const dateEarned = document.getElementById("date_earned").value;

  if (title === "") {
    document.getElementById("titleError").textContent = "Title is required.";
    isValid = false;
  }

  if (description === "") {
    document.getElementById("descriptionError").textContent = "Description is required.";
    isValid = false;
  }

  if (dateEarned === "") {
    document.getElementById("dateError").textContent = "Date earned is required.";
    isValid = false;
  }

  return isValid;
}
