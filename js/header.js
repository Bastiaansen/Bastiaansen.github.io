// When the user scrolls the page, execute stickyHeader 
window.onscroll = function() {scrollFunction();stickyHeader(); modifyHeader();};

var general=document.getElementById("linkGeneral");
var publications=document.getElementById("linkPublications");
var talks=document.getElementById("linkTalks");
var teaching=document.getElementById("linkTeaching");
var media=document.getElementById("linkMedia");
var other=document.getElementById("linkOther");

general.addEventListener("click", function() {modifyView();});
publications.addEventListener("click", function() {modifyView();});
talks.addEventListener("click", function() {modifyView();});
teaching.addEventListener("click", function() {modifyView();});
media.addEventListener("click", function() {modifyView();});
other.addEventListener("click", function() {modifyView();});

// Get the header
var header = document.getElementById("header");
var headerHeight = header.offsetHeight;
var shouldScroll = false;

// Get the offset position of the navbar
var sticky = header.offsetTop;

// Add the sticky class to the header when you reach its scroll position. Remove "sticky" when you leave the scroll position
function stickyHeader() {
  if (window.pageYOffset >= sticky) {
    header.classList.add("sticky");
	if (shouldScroll) window.scrollBy(0,-headerHeight);
  } else {
    header.classList.remove("sticky");
  }
  shouldScroll = false;
}

function modifyHeader(){

	
	general.classList.remove("currentMenu");
	publications.classList.remove("currentMenu");
	talks.classList.remove("currentMenu");
	teaching.classList.remove("currentMenu");
	media.classList.remove("currentMenu");
	other.classList.remove("currentMenu");
	
	
	var height = window.pageYOffset + headerHeight;
	
	if(height >= document.getElementById("other").offsetTop) {
	other.classList.add("currentMenu");
	}
	else if(height >= document.getElementById("media").offsetTop){
	media.classList.add("currentMenu");
	}
	else if(height >= document.getElementById("teaching").offsetTop) {
	teaching.classList.add("currentMenu");
	}
	else if(height >= document.getElementById("talks").offsetTop) {
	talks.classList.add("currentMenu");
	}
	else if(height >= document.getElementById("publications").offsetTop) {
	publications.classList.add("currentMenu");
	}
	else if(height >= document.getElementById("general").offsetTop) {
	general.classList.add("currentMenu");
	}
}


function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        document.getElementById("topButton").style.display = "block";
    } else {
        document.getElementById("topButton").style.display = "none";
    }
}

// When the user clicks on the button, scroll to the top of the document
function topFunction() {
    document.body.scrollTop = 0; // For Safari
    document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
}

function modifyView() {	
	if (window.pageYOffset < sticky) {
		shouldScroll = true;
	}
}