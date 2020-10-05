var acc = document.getElementsByClassName("paper");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    this.classList.toggle("paperActive");
    var panel = this.nextElementSibling;
    if (panel.style.maxHeight){
      panel.style.maxHeight = null;
	  panel.style.border="none";
    } else {
      panel.style.maxHeight = panel.scrollHeight + "px";
	  panel.style.border="1px solid darkblue";
    } 
  });
}