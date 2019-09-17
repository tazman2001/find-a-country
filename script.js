
function showData() {
  
}

function showHint(str) {
    document.getElementById("comp"+str.id+"Suggest").style.display = 'block';
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
    	if (this.readyState == 4 && this.status == 200) {
        	document.getElementById("txt"+str.id+"Hint").innerHTML = this.responseText;
    	}
    }

    xmlhttp.open("GET", "getHint.php?elemId="+str.id+"&"+str.id+"="+str.value, true);
    xmlhttp.send();
}

function getCountry(obj) {
	var cName = obj.getAttribute('data-name');
	var dialCode = obj.getAttribute('data-dialcode');
	var capital = obj.getAttribute('data-capital');
	var cucode = obj.getAttribute('data-cucode');
    var lang = obj.getAttribute('data-lang');
    var cCode = obj.getAttribute('data-cCode');
    var elemId = obj.getAttribute('data-elemId');
    var flag = obj.getAttribute('data-flag');
    var region = obj.getAttribute('data-region');
    var timezones = obj.getAttribute('data-timezones');
	var source = obj.getAttribute('data-source');

	document.getElementById("cName").value = cName;
	document.getElementById("cCode").value = cCode;
	document.getElementById("cCity").value = capital;
	document.getElementById("cuCode").value = cucode;
	document.getElementById("Lang").value = lang;
	document.getElementById("flag_image").value = flag;
	document.getElementById("dialing_code").value = dialCode;
	document.getElementById("timezone").value = timezones;
	document.getElementById("region").value = region;
	document.getElementById("source").value = source;
    document.getElementById("countrySubmit").style.display = 'block';
	document.getElementById("comp"+elemId+"Suggest").style.display = 'none';
}

