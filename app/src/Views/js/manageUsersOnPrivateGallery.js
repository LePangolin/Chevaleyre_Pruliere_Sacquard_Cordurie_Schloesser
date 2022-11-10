let privateMod = document.getElementById("private");
let publicMod = document.getElementById("public");


restoreUsers(tabUsersFromedit);

if(privateMod.checked){
    document
    .getElementsByClassName("statutDiv_userInput")[0]
    .classList.remove("hidden");
}

privateMod.addEventListener("click", function () {
  document
    .getElementsByClassName("statutDiv_userInput")[0]
    .classList.remove("hidden");
});

publicMod.addEventListener("click", function () {
  document
    .getElementsByClassName("statutDiv_userInput")[0]
    .classList.add("hidden");
  document.getElementsByClassName("usersArray")[0].value = "";
});

let tabUsers = [];

if (
  document.body.contains(document.getElementsByClassName("tagsFromEdit")[0])
) {
  let tabUserFromEdit = document.getElementsByClassName("tagsFromEdit")[0];

  if (tabUserFromEdit.length > 0) {
    for (let i = 0; i < tabUserFromEdit.length; i++) {
      tabUsers.push(tabUserFromEdit[i].dataset.tag);
    }
    document.getElementsByClassName("usersArray")[0].value =
      JSON.stringify(tabUsers);
  }
}

document
  .getElementsByClassName("userInput_input")[0]
  .addEventListener("keydown", function (e) {
    var key = e.charCode || e.keyCode || 0;
    if (key == 13) {
      e.preventDefault();
      document.getElementsByClassName("userInput_addButton")[0].click();
    }
  });

document
  .getElementsByClassName("userInput_addButton")[0]
  .addEventListener("click", function (e) {
    let user = document.getElementsByClassName("userInput_input")[0].value;
    if (user != "") {
      addElement(
        tabUsers,
        user,
        "userInput_input",
        "usersArray",
        "usersDisplay_userItem",
        "userItem_icon",
        "usersDisplay"
      );
    }
  });


  function restoreUsers(arraytags) {
    arraytags.forEach((user) => {
      addElement(
        tabUsers,
        user,
        "userInput_input",
        "usersArray",
        "usersDisplay_userItem",
        "userItem_icon",
        "usersDisplay"
      );
    });
  }