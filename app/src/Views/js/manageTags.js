let tabTags = [];

let tabTagsFromedit = document.getElementsByClassName("tagsFromEdit")

if(tabTagsFromedit.length > 0){
  for (let i = 0; i < tabTagsFromedit.length; i++) {
    tab.push(tabTagsFromedit[i].dataset.tag);
  }
  document.getElementById("tags").value = JSON.stringify(tab);
}


document.getElementById("tag").addEventListener("keydown", function (e) {
  var key = e.charCode || e.keyCode || 0;
  if (key == 13) {
    e.preventDefault();
    document.getElementsByClassName("tagInput_addButton")[0].click();
  }
});

document
  .getElementsByClassName("tagInput_addButton")[0]
  .addEventListener("click", function (e) {
    let inputValue = document.getElementById("tag").value;
    if (inputValue != "") {
      addElement(
        tabTags,
        inputValue,
        "tag",
        "tags",
        "tagsDisplay_tagItem",
        "tagItem_icon",
        "tagsDiv"
      );
    }
  });

function addElement(
  tab,
  inputValue,
  inputName,
  hiddenArray,
  newItemClass,
  iconClass,
  elementContainer
) {
  document.getElementById(inputName).value = "";
  tab.push(inputValue);
  document.getElementById(hiddenArray).value = JSON.stringify(tab);
  let div = document.createElement("div");
  div.classList.add(newItemClass);
  div.dataset.id = tab.length - 1;
  div.innerHTML = inputValue;
  let iconSpan = document.createElement("span");
  iconSpan.classList.add(iconClass, "material-symbols-outlined");
  iconSpan.innerHTML = " close ";
  div.appendChild(iconSpan);
  document.getElementById(elementContainer).appendChild(div);
  div.addEventListener("click", function (e) {
    let index = e.target.dataset.id;
    e.target.remove();
    tab.splice(index, 1);
    document.getElementById(hiddenArray).value = JSON.stringify(tab);
  });
}

function restoreTags(arraytags) {
  arraytags.forEach((tag) => {
    addElement(
      tabTags,
      tag,
      "tag",
      "tags",
      "tagsDisplay_tagItem",
      "tagItem_icon",
      "tagsDiv"
    );
  });
}
