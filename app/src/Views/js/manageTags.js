let tabTags = [];


restoreTags(tabTagsFromedit);

if (
  document.body.contains(
    document.getElementsByClassName("advancedSearchForm_nameInput")[0]
  )
) {
  document
    .getElementsByClassName("advancedSearchForm_nameInput")[0]
    .addEventListener("keydown", function (e) {
      var key = e.charCode || e.keyCode || 0;
      if (key == 13) {
        e.preventDefault();
      }
    });
}

document
  .getElementsByClassName("tagInput_input")[0]
  .addEventListener("keydown", function (e) {
    var key = e.charCode || e.keyCode || 0;
    if (key == 13) {
      e.preventDefault();
      document.getElementsByClassName("tagInput_addButton")[0].click();
    }
  });

document
  .getElementsByClassName("tagInput_addButton")[0]
  .addEventListener("click", function (e) {
    let inputValue = document.getElementsByClassName("tagInput_input")[0].value;
    if (inputValue != "") {
      addElement(
        tabTags,
        inputValue,
        "tagInput_input",
        "tagsArray",
        "tagsDisplay_tagItem",
        "tagItem_icon",
        "tagsDisplay"
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
  document.getElementsByClassName(inputName)[0].value = "";
  tab.push(inputValue);
  document.getElementsByClassName(hiddenArray)[0].value = JSON.stringify(tab);
  console.log(document.getElementsByClassName(hiddenArray)[0].value);
  let div = document.createElement("div");
  div.classList.add(newItemClass);
  div.dataset.id = tab.length - 1;
  div.innerHTML = inputValue;
  let iconSpan = document.createElement("span");
  iconSpan.classList.add(iconClass, "material-symbols-outlined");
  iconSpan.innerHTML = " close ";
  div.appendChild(iconSpan);
  document.getElementsByClassName(elementContainer)[0].appendChild(div);
  div.addEventListener("click", function (e) {
    let index = e.target.dataset.id;
    e.target.remove();
    tab.splice(index, 1);
    document.getElementsByClassName(hiddenArray)[0].value = JSON.stringify(tab);
  });

}

function restoreTags(arraytags) {
  arraytags.forEach((tag) => {
    addElement(
      tabTags,
      tag,
      "tagInput_input",
      "tagsArray",
      "tagsDisplay_tagItem",
      "tagItem_icon",
      "tagsDisplay"
    );
  });
}
