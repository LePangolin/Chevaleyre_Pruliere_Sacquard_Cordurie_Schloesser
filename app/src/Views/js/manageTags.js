let tab = [];

document.getElementById("tag").addEventListener("keydown", function (e) {
  var key = e.charCode || e.keyCode || 0;
  if (key == 13) {
    e.preventDefault();
    document.getElementsByClassName("addTag")[0].click();
  }
});

document
  .getElementsByClassName("addTag")[0]
  .addEventListener("click", function (e) {
    let tag = document.getElementById("tag").value;
    tab.push(tag);
    document.getElementById("tags").value = JSON.stringify(tab);
    let div = document.createElement("div");
    div.dataset.id = tab.length - 1;
    div.innerHTML = tag + " X";
    document.getElementById("statutDiv").appendChild(div);
    div.addEventListener("click", function (e) {
      e.target.remove();
      index = e.target.dataset.id;
      tab.splice(index, 1);
      document.getElementById("tags").value = JSON.stringify(tab);
    });
  });
