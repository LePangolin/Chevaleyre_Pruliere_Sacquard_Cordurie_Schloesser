  

// Get the modal
var modal = document.querySelector(".addImage");

// Get the button that opens the modal
var btn1 = document.querySelector(".add_image_button");
var btn2 = document.querySelector(".add_image_middle_button");

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

// When the user clicks on <span> (x), close the modal
span.onclick = function () {
  modal.style.display = "none";
};

// When the user clicks anywhere outside of the modal, close it
window.onclick = function (event) {
  if (event.target == modal) {
    modal.style.display = "none";
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

