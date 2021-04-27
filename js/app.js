// Variables

// Links to Needed Items
var doc = document; // General link to the document
var form = doc.getElementById("orderForm");
var name = doc.getElementById("name");

var sprinkles = doc.getElementById("sprinkles");
var chocolate = doc.getElementById("chocolate");
var caramel = doc.getElementById("caramel");
var raspberry = doc.getElementById("raspberry");
var strawberry = doc.getElementById("strawberry");
var blueberry = doc.getElementById("blueberry");

var sprinklesCost = doc.getElementById("sprinklesCost");
var chocolateCost = doc.getElementById("chocolateCost");
var caramelCost = doc.getElementById("caramelCost");
var raspberryCost = doc.getElementById("raspberryCost");
var strawberryCost = doc.getElementById("strawberryCost");
var blueberryCost = doc.getElementById("blueberryCost");

var noDoughnuts = doc.getElementById("totalDoughnuts");
var orderTotal = doc.getElementById("totalPrice");
var errorMsg = doc.getElementById("errorMessage");
var submitBtn = doc.getElementById("submitButton");

// Prices
var sprinklesPrice = 1.0;
var chocolatePrice = 1.2;
var caramelPrice = 1.0;
var raspberryPrice = 0.8;
var strawberryPrice = 0.8;
var blueberryPrice = 0.8;

// Validate entries
function isNumber(val) {
  if (
    typeof val == "number" ||
    typeof val == "null" ||
    typeof val == "undefined"
  ) {
    // is a number or is empty
    errorMsg.hidden = false;
    return true;
  } else {
    // is not a number
    errorMsg.hidden = true;
    return false;
  }
}

// Total up Doughnuts
function totalDoughnuts() {
  var total =
    Number(sprinkles.value) +
    Number(chocolate.value) +
    Number(caramel.value) +
    Number(raspberry.value) +
    Number(strawberry.value) +
    Number(blueberry.value);

  noDoughnuts.innerHTML = total;
  //   var remaining = 12 - total;
  if (total < 0 || total > 12) {
    displayError("error", "Your order needs to be a total of 12 doughnuts.");
    submitBtn.disabled = true;
  } else if (total > 0 && total < 12) {
    displayError(
      "Warn",
      `Almost there, select another ${12 - total
      } doughnuts to submit your order 😃`
    );
    submitBtn.disabled = true;
  } else if (total == 12) {
    displayError(
      "success",
      "Amazing selection 🙌, you can now submit your order 🎉"
    );
    submitBtn.disabled = false;
  } else {
    errorMsg.hidden = true;
    submitBtn.disabled = false;
  }
}

// Workout Line Total
function updateLineTotals() {
  sprinklesCost.innerHTML = "£" + (sprinkles.value * sprinklesPrice).toFixed(2);
  chocolateCost.innerHTML = "£" + (chocolate.value * chocolatePrice).toFixed(2);
  caramelCost.innerHTML = "£" + (caramel.value * caramelPrice).toFixed(2);
  raspberryCost.innerHTML = "£" + (raspberry.value * raspberryPrice).toFixed(2);
  strawberryCost.innerHTML =
    "£" + (strawberry.value * strawberryPrice).toFixed(2);
  blueberryCost.innerHTML = "£" + (blueberry.value * blueberryPrice).toFixed(2);
}

// Workout Order Total
function updateOrderTotal() {
  var orderCost =
    sprinkles.value * sprinklesPrice +
    chocolate.value * chocolatePrice +
    caramel.value * caramelPrice +
    raspberry.value * raspberryPrice +
    strawberry.value * strawberryPrice +
    blueberry.value * blueberryPrice;

  orderTotal.innerHTML = "£" + orderCost.toFixed(2);
}

// Update Totals on Form Change

form.addEventListener("change", updateOrder);

function updateOrder() {
  console.log("Update the order...");
  updateLineTotals(); // Update the total costs for each line of doughnuts
  totalDoughnuts(); // Update the total doughnuts on this order
  updateOrderTotal(); // Update the total cost for this order
}

// Display an Error
function displayError(type, msg) {
  var type = type.toLowerCase();
  errorMsg.classList.remove("success", "warn", "error");
  errorMsg.classList.add(type);
  errorMsg.innerText = msg;
  errorMsg.hidden = false;
}

// Go back one page
function goBack() {
  window.history.back();
}

// Perform Search for Customers and show orders in table
function ordersByName(str) {
  if (str == "") {
    document.getElementById("results").innerHTML = "";
    return;
  } else {
    var type = "name";
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("results").innerHTML = this.responseText;
      }
    };
    xmlhttp.open("GET", "getOrders.php?type=" + type + "&q=" + str, true);
    xmlhttp.send();
  }
}

// Perform Search by Date and show orders in table
function ordersByDate(str) {
  if (str == "") {
    document.getElementById("results").innerHTML = "";
    return;
  } else {
    var type = "date";
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("results").innerHTML = this.responseText;
      }
    };
    xmlhttp.open("GET", "getOrders.php?type=" + type + "&q=" + str, true);
    xmlhttp.send();
  }
}

// Perform Retrieve All Orders and show in table
function fetchAll() {
  var type = "all";
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("results").innerHTML = this.responseText;
    }
  };
  xmlhttp.open("GET", "getOrders.php?type=" + type, true);
  xmlhttp.send();
}

function newOrder() {
  window.location = "./index.php";
}
function newCustomer() {
  window.location = "./createCustomer.php";
}
function viewCustomers() {
  window.location = "./customers.php";
}

function mobileMenu() {
  var x = document.getElementById("myLinks");
  if (x.style.display === "block") {
    x.style.display = "none";
  } else {
    x.style.display = "block";
  }
}

//lightbox js
// Open the Modal
function openModal() {
  document.getElementById("myModal").style.display = "block";
}

// Close the Modal
function closeModal() {
  document.getElementById("myModal").style.display = "none";
}

var slideIndex = 1;
showSlides(slideIndex);

// Next/previous controls
function plusSlides(n) {
  showSlides(slideIndex += n);
}

// Thumbnail image controls
function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  var i;
  var slides = document.getElementsByClassName("mySlides");
  var dots = document.getElementsByClassName("demo");
  var captionText = document.getElementById("caption");
  if (n > slides.length) { slideIndex = 1 }
  if (n < 1) { slideIndex = slides.length }
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }
  for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex - 1].style.display = "block";
  dots[slideIndex - 1].className += " active";
  captionText.innerHTML = dots[slideIndex - 1].alt;
}

