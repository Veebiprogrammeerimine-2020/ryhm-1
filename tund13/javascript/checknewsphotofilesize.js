let filesizelimit = 2097152;

window.onload = function(){
	//document.getElementById("photosubmit").disabled = true;
	document.getElementById("photoinput").addEventListener("change", checkSize);
	document.getElementById("photoreset").addEventListener("click", resetPhoto);
}

function checkSize(){
	if(document.getElementById("photoinput").files[0].size <= filesizelimit){
		document.getElementById("newssubmit").disabled = false;
		document.getElementById("photonotice").innerHTML = "";
	} else {
		document.getElementById("newssubmit").disabled = true;
		document.getElementById("photonotice").innerHTML = "Valitud fail on liiga suur!";
	}
}

function resetPhoto(){
	document.getElementById("photoinput").value= "";
	document.getElementById("photonotice").innerHTML = "";
	document.getElementById("newssubmit").disabled = false;
}