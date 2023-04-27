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

function hide(element,hideClass='hide',visibleClass='field'){
    element.classList.remove(visibleClass);
    element.classList.add(hideClass);
}
function visible(element,hideClass='hide',visibleClass='field'){
    element.classList.remove(hideClass);
    element.classList.add(visibleClass);
}