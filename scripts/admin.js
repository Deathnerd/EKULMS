//Client logic goes here
$(document).ready(function(){
	//set globals

	//construct the url to pass to the ajax function
	var site = function(file){
		url = "http://";
		pathArray = window.location['href'].split('/');
		for(var i = 2; i < pathArray.length-1; i++){
			url = url+pathArray[i]+'/';
		}
		return url+file;
	};

	//handles sending a course creation form
	$(document).on({
		click: function(){
			description = $("#courseDescription").val();
			if(description === ""){
				description = " ";
			}
			courseId = $("#courseId").val();
			courseName = $("#courseName").val();
			$.ajax({
				url: site("admin/createCourse.php"),
				success: function(message){
					$("#message").text(message);
					$("#message").css('display', 'block');
				},
				data: "courseId="+courseId+"&courseName="+courseName+"&description="+description,
				crossDomain: true
			});
		}
	}, "#addCourse");
});