let privateMod = document.getElementById("private");
let publicMod = document.getElementById("public");

privateMod.addEventListener("click", function () {
  document.getElementById("addUser").classList.remove("hidden");
});

publicMod.addEventListener("click", function () {
  document.getElementById("addUser").classList.add("hidden");
  document.getElementById("users").value = "";
});

let tabUsers = [];

let tabUserFromEdit = document.getElementsByClassName("tagsFromEdit");

if (tabUserFromEdit.length > 0) {
  for (let i = 0; i < tabUserFromEdit.length; i++) {
    tab.push(tabUserFromEdit[i].dataset.tag);
  }
  document.getElementById("users").value = JSON.stringify(tabUsers);
}

document.getElementById("user").addEventListener("keydown", function (e) {
  var key = e.charCode || e.keyCode || 0;
  if (key == 13) {
    e.preventDefault();
    document.getElementsByClassName("userAdd")[0].click();
  }
});

document
  .getElementsByClassName("userAdd")[0]
  .addEventListener("click", function (e) {
    let user = document.getElementById("user").value;
    if (user != "") {
      addElement(
        tabUsers,
        user,
        "user",
        "users",
        "userItem",
        "userItem_icon",
        "statutUsers"
      );
    }
  });
