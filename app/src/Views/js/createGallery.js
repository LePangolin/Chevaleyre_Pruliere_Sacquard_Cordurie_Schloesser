let tab = [];

document.querySelector(".addTag").addEventListener("click", function(e){
    e.preventDefault();
    let tag = document.getElementById("tag").value;
    tab.push(tag);
    document.getElementById("tags").value = JSON.stringify(tab);
    let div = document.createElement("div");
    div.dataset.id = tab.length-1;
    div.innerHTML = tag + ' X';
    document.getElementById("statutDiv").appendChild(div);
    div.addEventListener("click", function(e){
        e.target.remove();
        index = e.target.dataset.id;
        tab.splice(index,1);
        document.getElementById("tags").value = JSON.stringify(tab);
    })
})

