let private = document.getElementById("private");
let public = document.getElementById("public");

private.addEventListener("click", function () {
  document.getElementById("addUser").classList.remove("hidden");
});

public.addEventListener("click", function () {
  document.getElementById("addUser").classList.add("hidden");
  document.getElementById("users").value = "";
});

let tabUsers = [];

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
    tabUsers.push(user);
    document.getElementById("users").value = JSON.stringify(tabUsers);
    let div2 = document.createElement("div");
    div2.dataset.id = tabUsers.length - 1;
    div2.innerHTML = user + " X";
    document.getElementById("statutUsers").appendChild(div2);
    div2.addEventListener("click", function (e) {
      e.target.remove();
      index = e.target.dataset.id;
      tabUsers.splice(index, 1);
      document.getElementById("users").value = JSON.stringify(tabUsers);
    });
  });
