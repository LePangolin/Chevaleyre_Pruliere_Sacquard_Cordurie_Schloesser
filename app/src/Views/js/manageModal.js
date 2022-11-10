// Get the modal
var modal = document.querySelector(".addImage");

// Get the button that opens the modal
var btn1 = document.querySelector(".add_image_button");
var btn2 = document.querySelector(".add_image_middle_button");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks on the button, open the modal
if(document.body.contains(btn1)){
    btn1.onclick = function() {
        modal.style.display = "block";
      }
}
if(document.body.contains(btn2)){
    btn2.onclick = function() {
        modal.style.display = "block";
    }
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}