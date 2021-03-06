var objecto;
var patientId;
var jsonData;

function save(){
	createObject();
	patientId = $("#patientID").val();
	getName();
	getAddress();
	getContact();
	getData();
	getCaseInfo();
	getContactPerson();
	getTbData();
	getComorbidies();
	jsonData = JSON.stringify(objecto);
	postData();
}

function createObject(){
	objecto = {
		"patient_name" : [],
		"patient_address" : [],
		"patient_contact" : [],
		"patient_data" : [],
		"case_info" : [],
		"tb_contact_person" : [],
		"tb_patient_data" :[],
		"comorbidies" : []
	};
}

function getName(){
	objecto.patient_name.push({
		"patientId" : patientId,
		"firstname" : $("#firstname").val(),
		"middlename": $("#middlename").val(),
		"lastname"  : $("#lastname").val()
	});
}

function getAddress(){
	objecto.patient_address.push({
		"patientId" : patientId,
		"address1"  : $("#address1").val(),
		"address2"  : $("#address2").val(),
		"city"		: $("#city").val()
	});
}

function getContact(){
	objecto.patient_contact.push({
		"patientId"    : patientId,
		"phone_office" : $("#phone_office").val(),
		"phone_home"   : $("phone_home").val(),
		"phone_mobile" : $("phone_mobile").val(),
		"email"		   : $("email").val()
	});
}

function getData(){
	objecto.patient_data.push({
		"nic"         : $("#nic").val(),
		"patientId"   : patientId,
		"dob"         : $("#cFrom").val(),
		"title"       : $("#title option:selected").html(),
		"gender"      : $("#gender option:selected").html(),
		"civilstatus" : $("#civil_status option:selected").html(),
		"religion"    : $("#religion").val(),
		"blood_group" : $("bloodGroup option:selected").html()
	});
}

function getCaseInfo(){
	objecto.case_info.push({
		"patientId"        : patientId,
		"tbregno"          : $("#tbNo").val(),
		"fileno"  		   : $("#fileNo").val(),
		"notification_date": $("#don").val(),
		"nature_of_case"   : $("#noc option:selected").html(),
		"type"			   : $("#tob option:selected").html(),
		"start_date"       : $("#startedOn").val(),
		"end_date"		   : $("#completedOn").val()
	});
}

function getContactPerson(){
	objecto.tb_contact_person.push({
		"patientId" : patientId,
		"name"      : $("#contactName").val(),
		"address"   : $("#contactAddress").val(),
		"nic"       : $("#contactNic").val(),
		"telephone" : $("#contactTel").val(),
		"mobile"    : $("#contactMobile").val()
	});
}

function getTbData(){
	objecto.tb_patient_data.push({
		"patientId" : patientId,
		"weight"    : $("#weight").val(),
		"height"    : $("#height").val(),
		"bmi"       : $("#bmi").val(),
		"fbs"       : $("#fbs").val(),
		"smoking"   : $("#smoking option:selected").html(),
		"alcholism" : $("#alcholism option:selected").html(),
		"drug_use"  : $("#drugUse option:selected").html(),
		"residence" : $("#residence option:selected").html(),
		"education" : $("#education option:selected").html(),
		"lwf"       : $('#lofy').is(':checked')? "Yes" : "No",
		"occupation": $("#occupation").val()
	});
}

function getComorbidies(){
	var ids = ["dm", "copd", "ba", "hiv"];

	for(var i = 0; i < ids.length; i++){
		var id = "#" + ids[i];
		if($(id).is(':checked')){
			objecto.comorbidies.push({
				"patientId"   : patientId,
				"comorbidies" : $(id).val()
			});
		}
	}

	if($("#oth").is(":checked")){
		objecto.comorbidies.push({
			"patientId"   : patientId,
			"comorbidies" : $("#other").val()
		});
	}
}

function showOther(){
	if($("#oth").is(":checked"))
		$("#otherId").prop("hidden", false);
	else
		$("#otherId").prop("hidden", true);
}

function postData(){
	$.ajax({
		type: "POST",
		url: "../patient/tb/register",
		data: jsonData,
		success: function( data, textStatus, jQxhr ){
			alert("Success");
		},
		error: function( jqXhr, textStatus, errorThrown ){
			alert( errorThrown );
		}
	});
}
