<?php
    use app\core\component\Component;
    $component=new Component();

?>
Reports page

<script>
    const btn=document.getElementById("new-advertisement");
    btn.addEventListener('click',function(){
        location.href="handle-advertisement";
    })
    elementsArray = document.querySelectorAll(".button--class-2");
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href='update-advertisement?mod=update&id='+elem.id;
        });
    });
    elementsArray = document.querySelectorAll(".button--class-3");
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href='handle-advertisement?cmd=delete&id='+elem.id;
        });
    });
</script>