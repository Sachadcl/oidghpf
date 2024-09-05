import "./bootstrap.js";
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import "./styles/app.css";

console.log("This log comes from assets/app.js - welcome to AssetMapper! 🎉");

const isOrganizer = document.getElementById("isOrganizer");
const isSignedIn = document.getElementById("isSignedIn");
const isNotSignedIn = document.getElementById("isNotSignedIn");
const isDeadlinePassed = document.getElementById("isDeadlinePassed");

const BASE_URL = "http://localhost:8000/filter";

document.addEventListener("DOMContentLoaded", function () {
  function sendFilterRequest(filters) {
    fetch(BASE_URL, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(filters),
    })
      .then((response) => response.json())
      .then((data) => {
        document.querySelector(".outing-list").innerHTML = data.html;
      })
      .catch((error) => {
        console.error("Error:", error);
      });
  }

  isOrganizer.addEventListener("click", function () {
    sendFilterRequest({ organizer: isOrganizer.checked });
  });

  isSignedIn.addEventListener("click", function () {
    sendFilterRequest({ signedIn: isSignedIn.checked });
  });

  isNotSignedIn.addEventListener("click", async function () {
    sendFilterRequest({ notSignedIn: isNotSignedIn.checked });

    try {
      const response = await fetch(`${BASE_URL}`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ notSignedIn: isNotSignedIn.checked }),
      });
      console.log(response);

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      console.log(response);

      const json = await response.json();
      const html = json.html;
      console.log(json);

      document.getElementById("outings-list").innerHTML = html;
    } catch (error) {
      console.error("Error:", error);
    }
  });

  isDeadlinePassed.addEventListener("click", async function () {
    if (isDeadlinePassed.checked) {
    }

    try {
      const response = await fetch(`${BASE_URL}`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ deadline: isDeadlinePassed.checked }),
      });
      console.log(response);

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      console.log(response);

      const json = await response.json();
      const html = json.html;
      console.log(json);

      document.getElementById("outings-list").innerHTML = html;
    } catch (error) {
      console.error("Error:", error);
    }
  });
});
