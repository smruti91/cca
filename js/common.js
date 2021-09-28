/*==============Function to create form elements and post the required data(D-06-10-2017)============*/

		function ccadatapost(path, params, method) {
			//alert(path);
			method = method || "post"; // Set method to post by default if not specified.
			var form = document.createElement("form");
			form.setAttribute("method", method);
			form.setAttribute("action", path);
			for(var key in params) {
				if(params.hasOwnProperty(key)) {
					var hiddenField = document.createElement("input");
					hiddenField.setAttribute("type", "hidden");
					hiddenField.setAttribute("name", key);
					hiddenField.setAttribute("value", params[key]);
					form.appendChild(hiddenField);
				 }
			}
			document.body.appendChild(form);
			form.submit();
		}

// show messges

function showMessage(){
	if ( sessionStorage.type=="success" ) {
        $('#alert_msg').show();
        $('#btn_records_mtnc').show();
        console.log(123);
        $("#alert_msg").addClass("alert alert-success").html(sessionStorage.message);
        closeAlertBox();
        //sessionStorage.reloadAfterPageLoad = false;

        sessionStorage.removeItem("message");
        sessionStorage.removeItem("type");
    }
    if (sessionStorage.type == "error") {
        $('#alert_msg').show();
        $("#alert_msg").addClass("alert alert-danger").html(sessionStorage.message);
        closeAlertBox();
        sessionStorage.removeItem("message");
        sessionStorage.removeItem("type");
    }
}



function closeAlertBox(){
window.setTimeout(function () {
$("#alert_msg").fadeOut(300)
}, 3000);
}