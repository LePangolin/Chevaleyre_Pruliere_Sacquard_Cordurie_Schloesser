/* Recuperations des elements du DOM */
let searchNameInput = document.getElementsByClassName(
  "advancedSearchForm_nameInput"
)[0];

/* Supression de l'action par defaut */
searchNameInput.onkeyup = function (e) {
  var key = e.charCode || e.keyCode || 0;
  if (key == 13) {
    e.preventDefault();
  }
};
