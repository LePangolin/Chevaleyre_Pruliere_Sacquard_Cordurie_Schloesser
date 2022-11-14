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

let jsonMetadata = " { } ";


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

    // retire le } de la fin
    jsonMetadata = jsonMetadata.slice(0, -1);
    // ajoute les valeurs
    jsonMetadata + " , " + " \" " + tabMD[0] + " : " + tabMD[1].name + " \" " + " } " ;

    console.log(jsonMetadata);

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


