//make GET variables into variables javascript can use
function $_GET(param) {
    var vars = {};

    window.location.href.replace(location.hash, '').replace(
        /[?&]+([^=&]+)=?([^&]*)?/gi, // regexp
        function (m, key, value) { // callback
            vars[key] = value !== undefined ? value : '';
        }
    );

    if (param) {
        return vars[param] ? vars[param] : null;
    }
    return vars;

}

function showView() {
    var employeeid = $_GET('employeeid'),
        view = $_GET('view');
    alert(view);
}

//used on manager login view
function getPassword() {
    x = document.getElementById("password").value;
    { setPassword() }
    function setPassword() { document.getElementById("editPassword").value = x }
}

function openTimeClock() {
    window.open("main.php", "newwindow", "width=350,height=375,toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no,addressbar=no,directories=no, status=no").focus();
}

function goToTimeclock() {
    window.open("main.php", "_self", "width=350,height=375,toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no, addressbar=no, directories=no, status=no").focus();
}
function goToDateFind() {
    var x = document.forms["name"]["employeeid"].value;
    if (x == "") {
        alert("Select your name first");
        return false;
    } else {
        window.location.href = "/timeclock/timeclock.php";
    }
}



//when clicking a button not in a form, this sets the view, if needed, for continuing
function proceed() {
    y = "main.php?view=";
    if (id !== "manager") {
        alert("Managers Only");
    }
    else {
        window.open(y + x, '_self', 'scrollbars=no, resizable=no, width=550, height=550');
        //alert(y + x);
    }

}
function managerReportSize() {
    window.resizeTo(825, 950);
}
function reportSize() {

    window.resizeTo(600, 800);
}
function defaultSize() {
    window.resizeTo(350, 385);
}
function editTimeSize() {
    window.resizeTo(625, 400);
}

function mytimeWindow(url) {
    var view = 'mytime_view',
        employeeid = $_GET('employeeid'),
        startdate = $_GET('startdate'),
        enddate = $_GET('enddate'),
        v = "?view=",
        emp = "&employeeid=",
        s = "&startdate=",
        e = "&enddate=";
    newwindow = window.open(url + v + view + emp + employeeid + s + startdate + e + enddate, '', 'height=800,width=650');
    if (window.focus) { newwindow.focus() }
}


