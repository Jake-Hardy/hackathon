$(document).ready(function(){
	console.log("empty j");
});

function trim(s)
{
  if ((s==null) || (typeof(s) != 'string') || !s.length){
    return'';
  }
  return s.replace(/\s+/g,'')
}

var cnt=0;
function calcNum(max)
{
 var x = '';
 if (trim(document.firstForm.lastName.value) != ''){
    x++;
 }
 if (trim(document.firstForm.lastNameSp.value) != ''){
    x++;
 }
 for(y=0;y<max;y++){
    if (trim(eval("document.firstForm.lastName"+y).value) != ''){
       x++;
    }
 }
 document.firstForm.familySize.value = x-cnt;
}

function chgFood()
{
  updFood();
  document.firstForm.food.value = food[document.firstForm.agency.value];
}

function updFood()
{
  var opts = document.firstForm.food.options;
  var i=0;
  for(;i<opts.length;i++){
    if ( opts[i].value == 2 ) {
      if (hideFood[document.firstForm.agency.value] == 1) {
	  	document.firstForm.food.remove(i);
      }
	  break;
    }
  }
  if (hideFood[document.firstForm.agency.value] == 0 && i == opts.length ) {
    try {
		document.firstForm.food.add( new Option("Needed From SA", 2), opts[opts.length-1] );
	} catch (e) {
		document.firstForm.food.add( new Option("Needed From SA", 2), opts.length-1 );
	}
  }
}

function chgToys()
{
  updToys();
  document.firstForm.toys.value = toys[document.firstForm.agency.value];
}

function updToys()
{

	var opts = document.firstForm.toys.options; 
	for(var i=0;i<opts.length;i++){
		if ( opts[i].value == 2 ) {
			if ((hideToys[document.firstForm.agency.value] == 1 || globalHideToys==1)) {
			document.firstForm.toys.remove(i);
		  }
		  break;
		}
	}
	
	/* TOYS FOR TOTS
	if (hideToys[document.firstForm.agency.value] == 0 && globalHideToys!=1 && i == opts.length && document.firstForm.agency.value!==20) {
		try {
			document.firstForm.toys.add( new Option("Needed From TFT", "2"), opts[opts.length-1] );
		} catch (e) {
			document.firstForm.toys.add( new Option("Needed From TFT", "2"), opts.length-1 );
		}
	}
	*/
	
	if (document.firstForm.agency.value==20 && globalhideToys==1)
	{
		try {
			document.firstForm.toys.add( new Option("Needed From TFT", "2"), opts[opts.length-1] );
		} catch (e) {
			document.firstForm.toys.add( new Option("Needed From TFT", "2"), opts.length-1 );
		}
	}

}

function timer()
{
  setTimeout('window.location.href=\"/logoff.php\"',60000*45); /* 45 Minutes */
}


function toggle(n)
{
	var checkboxes = document.getElementsByName('show');
	for(var i = 0; i < checkboxes.length; i++) {
		if(checkboxes[i].checked == true) {
			document.getElementById('row'+i).className='';
			
		} else {
			document.getElementById('row'+i).className='hid';
			
		}
	}
}

function populate()
{
	var checkboxes = document.getElementsByName('show');
	if (document.forms[0].wishlist0.value != "") {
		checkboxes[0].checked = true;
	}
	else {
		checkboxes[0].checked = false;
	}
	if (document.forms[0].wishlist1.value != "") {
		checkboxes[1].checked = true;
	}
	else {
		checkboxes[1].checked = false;
	}
	if (document.forms[0].wishlist2.value != "") {
		checkboxes[2].checked = true;
	}
	else {
		checkboxes[2].checked = false;
	}
	if (document.forms[0].wishlist3.value != "") {
		checkboxes[3].checked = true;
	}
	else {
		checkboxes[3].checked = false;
	}
	if (document.forms[0].wishlist4.value != "") {
		checkboxes[4].checked = true;
	}
	else {
		checkboxes[4].checked = false;
	}
	if (document.forms[0].wishlist5.value != "") {
		checkboxes[5].checked = true;
	}
	else {
		checkboxes[5].checked = false;
	}
	if (document.forms[0].wishlist6.value != "") {
		checkboxes[6].checked = true;
	}
	else {
		checkboxes[6].checked = false;
	}
	if (document.forms[0].wishlist7.value != "") {
		checkboxes[7].checked = true;
	}
	else {
		checkboxes[7].checked = false;
	}
	if (document.forms[0].wishlist8.value != "") {
		checkboxes[8].checked = true;
	}
	else {
		checkboxes[8].checked = false;
	}
	if (document.forms[0].wishlist9.value != "") {
		checkboxes[9].checked = true;
	}
	else {
		checkboxes[9].checked = false;
	}
	if (document.forms[0].wishlist10.value != "") {
		checkboxes[10].checked = true;
	}
	else {
		checkboxes[10].checked = false;
	}
	if (document.forms[0].wishlist11.value != "") {
		checkboxes[11].checked = true;
	}
	else {
		checkboxes[11].checked = false;
	}
	toggle(0);
}

function clickOn(x) {
	document.getElementById('hide'+x).className='';
	document.getElementById('show'+x).className='hid';
	document.getElementById('row'+x).className='';
}

function clickOff(x) {
	/* var hider = document.getElementsByName("hide");
	var shower = document.getElementsByName("show");
	shower[x].className = '';
	hider[x].className = 'hid'; */
	document.getElementById('hide'+x).className='hid';
	document.getElementById('show'+x).className='';
	document.getElementById('row'+x).className='hid';
}

var ssn_length = 0;
function tabIt(obj,event,len,next_field) {
	if (event == "down") {
		ssn_length=obj.value.length;
	}
	else if (event == "up") {
		if (obj.value.length != ssn_length) {
			ssn_length=obj.value.length;
			if (ssn_length == len) {
				next_field.focus();
			}
		}
	}
}
