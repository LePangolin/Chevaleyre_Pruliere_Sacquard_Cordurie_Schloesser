  

// Get the modal
var modal = document.querySelector(".addImage");
var picture_modal = document.querySelector(".picture_info");
// Get the button that opens the modal
var btn1 = document.querySelector(".add_image_button");
var btn2 = document.querySelector(".add_image_middle_button");
var pictures = document.querySelectorAll(".clickable_picture");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks on the button, open the modal
if (document.body.contains(btn1)) {
  btn1.onclick = function () {
    modal.style.display = "block";
  };
}
if (document.body.contains(btn2)) {
  btn2.onclick = function () {
    modal.style.display = "block";
  };
}
picture_modal.style.display = "none";
pictures.forEach(picture => {
    picture.onclick = function() {
      resetModal();
      const modal_content = document.querySelector(".modal-content-div");
      const img = document.createElement("img");
      img.src = picture.dataset.link;
      img.alt = picture.dataset.descr;
      const title = document.createElement("h1");
      title.innerHTML = picture.dataset.title;
      const desc = document.createElement("p");
      desc.innerHTML = "Description : " + picture.dataset.descr;
      modal_content.appendChild(title);
      modal_content.appendChild(img);
      modal_content.appendChild(desc);
      const tab = JSON.parse(picture.dataset.tags);
      if (tab !== null && tab.length !== 0) {
        const t = document.createElement("p");
        t.innerHTML = "Tags : ";
        modal_content.appendChild(t);
        tab.forEach(tag => {
          const p = document.createElement("p");
          p.innerHTML = tag;
          modal_content.appendChild(p);
        })
      }
      const metadatas = JSON.parse(picture.dataset.metadatas);
      if (metadatas !== null && tab.length !== 0) {
        const t = document.createElement("p");
        t.innerHTML = "Metadatas : ";
        modal_content.appendChild(t);
        Object.keys(metadatas).forEach(key => {
          const p = document.createElement("p");
          p.innerHTML = key + " : " + metadatas[key];
          modal_content.appendChild(p);
        })
      } 
      picture_modal.style.display = "block";
    }
}) 


// When the user clicks on <span> (x), close the modal
span.onclick = function () {
  modal.style.display = "none";
  picture_modal.style.display = "none";
};

// When the user clicks anywhere outside of the modal, close it
window.onclick = function (event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
  if (event.target == picture_modal.style.display) {
    picture_modal.style.display = "none";
  }
};

function resetModal() {
  const modal_content = document.querySelector(".modal-content-div");
  while (modal_content.firstChild) {
    modal_content.removeChild(modal_content.firstChild);
  }
};


let tabMD = document.getElementsByClassName("MD");

Array.from(tabMD).forEach(element => {
  element.addEventListener("keyup", nonVideMD);
});



function nonVideMD(){


  let nonVide = true;
  Array.from(tabMD).forEach(element => {
    if(element.value == ""){
      nonVide = false;
    }
  });
  
  if(nonVide){

    let div = document.createElement("div");
    div.classList.add("flex-input");
    div.innerHTML = `
      <input type="text" class="name MD">
      <input type="text" class="metadata MD">
      `;
    
    document.getElementsByClassName("metadata")[0].appendChild(div);

    // remove event listener
    Array.from(tabMD).forEach(element => {
      element.removeEventListener("keyup", nonVideMD);
    });
    
    // vide le tableau
    tmp = div.getElementsByClassName("MD");

    // merge les deux tableaux
    tabMD = [...tabMD, ...tmp];

    console.log(tabMD); 


    // desactive l'input précédent si le nouveau est est cliqué
    Array.from(tmp).forEach(element => {
      element.addEventListener("click", function(){
        Array.from(tabMD).forEach(element => {
          if(element != div.getElementsByClassName("MD")[0] && element != div.getElementsByClassName("MD")[1]){
            element.disabled = true;
          }
        });
      });
    });
    

    

    // ajoute l'event listener

    Array.from(tmp).forEach(element => {
      element.addEventListener("keyup", nonVideMD);
    });

  }
  
}

document.getElementById("confirmMetaData").addEventListener("click", function(){

  let tab = document.getElementsByClassName("MD");

  if(tab.length == 0){
    return true;
  }else{

    Array.from(tab).forEach(element => {
      element.disabled = true;
    });

    let json = "{";
    for(let i = 0; i < tab.length; i+=2){
      if(tab[i].value != "" && tab[i+1].value != ""){      
        json += '"' + tab[i].value + '":"' + tab[i+1].value + '"';
      }
      if(i+2 < tab.length  && tab[i+2].value != "" && tab[i+3].value != ""){
        json += ",";
      }
    }
    json += "}";
    document.getElementsByClassName("metadataArray")[0].value = json;
  }
});

