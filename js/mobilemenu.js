if(window.attachEvent) {
    window.attachEvent('onresize', function() {
        calculateMobileMenuHeight()
    });
}
else if(window.addEventListener) {
    window.addEventListener('resize', function() {
        calculateMobileMenuHeight()
    }, true);
}

var isMobileMenuVisible = 0;
var autoHeight = 400;

function toggleMobileMenu(){
	if(isMobileMenuVisible == 0){
		isMobileMenuVisible = 1;	
		document.getElementById("mobile-menu").style.height = autoHeight + "px";
	}
	else{
		isMobileMenuVisible = 0;	
		document.getElementById("mobile-menu").style.height = "0px";
	}
}

function calculateMobileMenuHeight(){
	document.getElementById("mobile-menu").style.height = "auto";
	autoHeight = document.getElementById("mobile-menu").clientHeight;
	document.getElementById("mobile-menu").style.height = "0px";
}