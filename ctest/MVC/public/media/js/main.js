function e(element,type='id'){
    if(type=='id') return document.getElementById(element);
    if(type=='class') return document.querySelector(element);
    if(type=='classall') return document.querySelectorAll(element);
}

//pass array of element as parameter to set  url on click event
function set_location(elementsArray,url){
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href=url+elem.id;
           ;
        });
    });
}

