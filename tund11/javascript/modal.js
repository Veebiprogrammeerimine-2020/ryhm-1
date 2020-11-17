let modal;
let modalimg;
let captiontext;
let photoid;
let photodir = "../photoupload_normal/";

window.onload = function(){
	modal = document.getElementById("modalarea");
	modalimg = document.getElementById("modalimg");
	captiontext = document.getElementById("modalcaption");
	let allThumbs = document.getElementById("galleryarea").getElementsByTagName("img");
	for (let i = 0; i < allThumbs.length; i ++){
		allThumbs[i].addEventListener("click", openModal);
	}
	document.getElementById("modalclose").addEventListener("click", closeModal);
	document.getElementById("storeRating").addEventListener("click", storeRating);
}

function openModal(e){
	document.getElementById("avgRating").innerHTML = "";
	for(let i = 1; i < 6; i ++){
		document.getElementById("rate" + i).checked = false;
	}
	//modalimg.src = photodir + e.target.dataset.fn;
	modalimg.src = "showphoto.php?photo=" + e.target.dataset.fn;
	
	photoid = e.target.dataset.id;
	modalimg.alt = e.target.alt;
	captiontext.innerHTML = e.target.alt;
	modal.style.display = "block";
}

function closeModal(){
	modal.style.display = "none";
}

function storeRating(){
	let rating = 0;
	for(let i = 1; i < 6; i ++){
		if(document.getElementById("rate" + i).checked){
			rating = i;
		}
	}
	if(rating > 0){
		//AJAX
		let webrequest = new XMLHttpRequest();
		webrequest.onreadystatechange = function(){
			if(this.readyState == 4 && this.status == 200){
				//nüüd see, mis õnnestumise korral tehakse
				document.getElementById("avgRating").innerHTML = "Keskmine hinne: " + this.responseText;
			}
		};
		webrequest.open("GET", "storePhotoRating.php?rating=" + rating + "&photoid=" + photoid, true);
		webrequest.send();
		//AJAX lõppeb
	}
}




