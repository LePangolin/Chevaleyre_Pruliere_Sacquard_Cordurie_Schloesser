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
    addTag(tag);
  });

function addTag(tag) {
  if (tag != "") {
    document.getElementById("tag").value = "";
    tab.push(tag);
    document.getElementById("tags").value = JSON.stringify(tab);
    let div = document.createElement("div");
    div.classList.add("tagsDisplay_tagItem");
    div.dataset.id = tab.length - 1;
    div.innerHTML = tag;
    let iconSpan = document.createElement("span");
    iconSpan.classList.add("tagItem_icon", "material-symbols-outlined");
    iconSpan.innerHTML = " close ";
    div.appendChild(iconSpan);
    document.getElementById("tagsDiv").appendChild(div);
    div.addEventListener("click", function (e) {
      let index = e.target.dataset.id;
      e.target.remove();
      tab.splice(index, 1);
      document.getElementById("tags").value = JSON.stringify(tab);
    });
  }
}

function restoreTags(arraytags) {
  arraytags.forEach((tag) => {
    addTag(tag);
  });
}
