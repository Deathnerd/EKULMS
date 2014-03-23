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
			description = $("#description").val();
			courseId = $("#courseId").val();
			courseName = $("#courseName").val();
			$.ajax({
				url: site("course.php"),
				success: function(message){
					$("#message").text(message);
					$("#message").css('display', 'block');
				},
				data: "courseId="+courseId+"&courseName="+courseName+"&description="+description+"&action="+add,
			});
		}
	}, "#addCourse");

	//handles sending a request to list coures
	$(document).on({
		click: function(){
			$.ajax({
				url: site("course.php"),
				success: function(results){
					$('#listResults').text(results);
					$('#listResults').css('display', 'block');
					console.log(results);
					console.log(typeof results);
				},
				data: "action=list",
			});
		}
	}, '#listCourses');
});