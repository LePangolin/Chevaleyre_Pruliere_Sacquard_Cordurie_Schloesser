/* Recuperations des elements du DOM */
let searchNameInput = document.getElementsByClassName("search_name")[0];
let searchtagsInput = document.getElementsByClassName("search_tags")[0];

/* Supression de l'action par defaut */
searchNameInput.onkeypress = function (e) {
  var key = e.charCode || e.keyCode || 0;
  if (key == 13) {
    e.preventDefault();
  }
};

searchtagsInput.onkeypress = function (e) {
  var key = e.charCode || e.keyCode || 0;
  if (key == 13) {
    e.preventDefault();
  }
};

/* Remplacement de l'action */
searchtagsInput.addEventListener("keyup", function (event) {
  if (event.keyCode === 13) {
    let tag = searchtagsInput.value;
    if (tag != "") {
      searchtagsInput.value = "";
      document.getElementsByClassName("tags_tagsList")[0].innerHTML +=
        "<li class='tagsList_tagItem'>" + tag + "</li>";
    }
  }
});
