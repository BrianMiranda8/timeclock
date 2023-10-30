//Get the id of the button clicked to set as view in the GET variable for setting the next view type
function getView(event) {
	nextView = (event.target.id);
	//alert(x);
}

//when the form is submitted, change the view to the next view from variable x in previous script
document.getElementById("name").onsubmit = function () { changeView() };
function changeView() {
	document.getElementById("viewtype").value = nextView;
	//alert("The form was submitted");
}
