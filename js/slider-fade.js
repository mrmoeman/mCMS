var images;
var length;

function setImageNumber(number, basesrc){
	images = new Array(number);
	length = number;
	var count = 0;
	for(count = 1; count < number + 1; count++){
	 	images[count] = new Image();
		images[count].src = basesrc + "slider" + count + ".jpg";
	}
	
}

function preload(MySrc){
	if(count < images.length){
	 	images[count] = new Image();
		images[count].src = MySrc;
	 	count++;
	}
}

function fadeslider(){
	var timer = 0;
	var imagenumber = 1;
	var nextimagenumber = 2;
	var fadeamount = 0;
	var myHeader = document.getElementById("head-1");
	var newfade = 0;
	
	for(var c = 2; c <= length; c++){
		var id = new String("head-" + c);
		document.getElementById(id).style.opacity="0";
	}
	
	setInterval(function(){
		timer++;
		
		if(timer>900){
			fadeamount+=0.003;
			var id = new String("head-" + imagenumber);
			newfade = 1-fadeamount;
			document.getElementById(id).style.opacity=newfade;
			var id = new String("head-" + nextimagenumber);
			newfade = fadeamount;
			document.getElementById(id).style.opacity=newfade;
			if(fadeamount > 1){
				fadeamount = 0;
				timer = 0;
				imagenumber = nextimagenumber;
				nextimagenumber++;
				if(nextimagenumber > length){
					nextimagenumber = 1;
				}
			}
		}
		
	})
}