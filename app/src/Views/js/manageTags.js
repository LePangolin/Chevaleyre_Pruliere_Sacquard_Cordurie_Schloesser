let tab = [];

document.querySelector(".addTag").addEventListener("click", function (e) {
  e.preventDefault();
  let tag = document.getElementById("tag").value;
  addTag(tag);
});

function addTag(tag) {
  if (tag != "") {
    document.getElementById("tag").value = "";
    tab.push(tag);
    document.getElementById("tags").value = JSON.stringify(tab);
    let div = document.createElement("div");
    div.dataset.id = tab.length - 1;
    div.innerHTML = tag + " X";
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
