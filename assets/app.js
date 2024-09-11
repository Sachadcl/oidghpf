import "./bootstrap.js";
import "./styles/app.css";

const showLoader = () => {
  document.querySelector("#modal-loader").style.display = "flex";
  document.querySelector("#formToFill").style.display = "none";
};

const hideLoader = () => {
  document.querySelector("#modal-loader").style.display = "none";
  document.querySelector("#formToFill").style.display = "block";
};

const fillModalInfo = async (type, id) => {
  showLoader();
  let data;
  try {
    if (type === "outingCancel") {
      const response = await fetch(`/outing/ajax/${id}`);
      data = await response.json();
      const submitButton = document.getElementById("submitButton");
      const cancelButton = document.getElementById("cancelButton");

      submitButton.addEventListener("click", async () => {
        await fetch(`/outing/cancel/${id}`);
      });

      submitButton.style.display = "block";
      cancelButton.style.display = "block";
      document.querySelector("#modal-title").textContent = "Annuler la sortie";
      document.querySelector("#modal-label-1").textContent = "Nom de la sortie";
      document.querySelector("#modal-value-1").textContent = data.outing_name;
      document.querySelector("#modal-label-2").textContent =
        "Date de la sortie";
      document.querySelector("#modal-value-2").textContent =
        data.outing_date.split("T")[0];
      document.querySelector("#modal-label-3").textContent = "Campus";
      document.querySelector("#modal-value-3").textContent =
        data.id_campus.campus_name;
      document.querySelector("#modal-label-4").textContent = "Lieu";
      document.querySelector("#modal-value-4").textContent =
        data.id_city.place_name;
      document.querySelector("#reason-section").style.display = "block";
      document.querySelector("#modal-image").style.display = "none";
    } else if (type === "planner") {
      const response = await fetch(`user/planner/${id}`);
      data = await response.json();
      const submitButton = document.getElementById("submitButton");
      const cancelButton = document.getElementById("cancelButton");

      submitButton.style.display = "none";
      cancelButton.style.display = "none";
      const imageContainer = document.getElementById("modal-image");
      imageContainer.innerHTML = ""; // Clear previous content
      if (data.profile_picture) {
        const image = document.createElement("img");
        image.src = data.profile_picture;
        image.alt = "Photo de profil";
        image.className = "w-32 h-32 rounded-full object-cover mx-auto";
        imageContainer.appendChild(image);
        imageContainer.style.display = "block";
      } else {
        imageContainer.style.display = "none";
      }
      document.querySelector("#modal-title").textContent =
        "Informations de l'organisateur";
      document.querySelector("#modal-label-1").textContent = "Nom";
      document.querySelector(
        "#modal-value-1"
      ).textContent = `${data.last_name} ${data.first_name}`;
      document.querySelector("#modal-label-2").textContent = "Email";
      document.querySelector("#modal-value-2").textContent = data.email;
      document.querySelector("#modal-label-3").textContent = "Téléphone";
      document.querySelector("#modal-value-3").textContent = data.telephone;
      document.querySelector("#modal-label-4").textContent = "Campus";
      document.querySelector("#modal-value-4").textContent =
        data.id_campus.campus_name;
      document.querySelector("#reason-section").style.display = "none";
    }
  } catch (error) {
    console.error("Error fetching data:", error);
    // Here you could add some error handling, like showing an error message in the modal
  } finally {
    hideLoader();
  }
};

document
  .querySelectorAll('[data-modal-toggle="crud-modal"]')
  .forEach(function (button) {
    button.addEventListener("click", async function () {
      switch (button.id) {
        case "plannerLink": {
          const plannerEmail = this.getAttribute("data-planner-email");
          plannerEmail && (await fillModalInfo("planner", plannerEmail));
          break;
        }
        case "outingCancel": {
          const outingId = this.getAttribute("data-outing-id");
          outingId && (await fillModalInfo("outingCancel", outingId));
          break;
        }
      }
    });
  });

console.log("This log comes from assets/app.js - welcome to AssetMapper! 🎉");
