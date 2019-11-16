function charUpper(string) {
	return string.charAt(0).toUpperCase() + string.slice(1);
}
let base64 = "";

function getBase64(file) {
	if(file.size < 2000000) {
		var reader = new FileReader();
		reader.readAsDataURL(file);
		//alert(file.size);
		reader.onload = function () {
			//console.log(reader.result);
			if(reader.result.indexOf("image/jpeg") != -1 || reader.result.indexOf("image/png") != -1) {
				base64 = reader.result;
			}
			else {
				Swal.fire({
					type: 'error',
					title: 'Invalid image',
					text: 'We accept only JPG or PNG image.'
				});
				$("#paymentImg").val('');
			}
		};
		reader.onerror = function (error) {
			console.log('Error: ', error);
			$("#paymentImg").val('');
		};
	}
	else {
		Swal.fire({
			type: 'error',
			title: 'Image is too big',
			text: 'Image size must be less than 2 MB.'
		});
		$("#paymentImg").val('');
	}
}

$(document).on("submit", "#regForm", function(e) {
	e.preventDefault();
	$.ajax({
		url: "https://api.ipdata.co/?api-key=0a75b9f2d177c28c4cfaa0796ae2b736dcd2a30340cba5e3fa27a9ed",
		method: "GET",
		headers: {
			'Accept': 'application/json'
		},
		success: function(res) {
			let ipAddr = res.ip;
			let loctn = res.city + ", " + res.region + ", " + res.country_name + ", " + res.continent_code;
			if($("select[name='eventsList']").val() != "selectOne") {
				$("button[type='submit']").hide();
				$("#insDiv").hide();
				$("#submitMsg").text("Registering...");
				$.ajax({
					url: "register.php",
					type: "POST",
					//dataType: "jsonp",
					data: {
						"tkn": "RegisterNow",
						"sName": charUpper($("input[name='sName']").val()),
						"fName": charUpper($("input[name='fName']").val()),
						"lName": charUpper($("input[name='lName']").val()),
						"phoneNo": $("input[name='phoneNo']").val(),
						"emailid": $("input[name='emailid']").val(),
						"clgName": charUpper($("input[name='clgName']").val()),
						"eventName": $("select[name='eventsList']").val(),
						"base64": base64,
						"ipAddr": ipAddr,
						"loctn": loctn
					},
					success: function(res) {
						console.log("RESULT:");
						console.log(res);
						if(res == "111") {
							$("#submitMsg").html("<br><br><h4 style='color: darkblue;'>Thank you for registration.</h4>");
							$("#insDiv").show();
							$("#insDiv").html("<br>");
							$("button[type='submit']").show();
							document.getElementById("regForm").reset();
						}
						else if(res == 0) {
							Swal.fire({
								type: 'error',
								title: 'Invalid email address',
								text: 'Please provide valid email address.'
							});
						}
						else {
							console.log(res);
						}
					},
					error: function(res) {
						console.log(res);
						Swal.fire({
							type: 'error',
							title: 'Oops!!',
							text: 'Something went wrong!'
						});
					}
				});
			}
			else {
				$("#eventErr").html("<i style='color: red;'>Select an event.</i>");
			}
		}
	});
});
$(document).ready(function() {
	document.getElementById("regForm").reset();
	//$("select[name='eventsList'] option[value='selectOne']").prop('selected', true);

	$("select[name='eventsList']").change(function() {
		$("select[name='eventsList']").css("color", "black");
	});

	$("input[name='emailid']").focusout(function(e) {
		if($("input[name='emailid']").val() != "") {
			$.ajax({
				url: "register.php",
				type: "POST",
				data: {
					"tkn": "CheckEmail",
					"emailid": $("input[name='emailid']").val()
				},
				success: function(res) {
					if(res == 0) {
						$("#emailMsg").css("display", "inline-block").html("<i style='color: red;'>Invalid email address</i>");
					}
					else {
						$("#emailMsg").css("display", "none");
					}
				}
			});
		}
	});
});


/*	
let dt = "";
var request = new XMLHttpRequest();
request.open('GET', 'https://api.ipdata.co/?api-key=0a75b9f2d177c28c4cfaa0796ae2b736dcd2a30340cba5e3fa27a9ed');
request.setRequestHeader('Accept', 'application/json');
request.onreadystatechange = function () {
  if (this.readyState === 4) {
	dt = JSON.parse(this.responseText);
  }
};
//this.ipAddr = dt.ip;
request.send();

function GodView() {
	console.log(request.responseText);
}*/