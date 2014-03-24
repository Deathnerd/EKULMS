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
			//check for whitespace strings in course id
			if(/\s/.test(courseId)){
				$("#message").css("display", "block");
				$("#message").text("Course id cannot have whitespace");
				return;
			}
			$.ajax({
				url: site("course.php"),
				success: function(message){
					$("#message").text(message);
					$("#message").css('display', 'block');
				},
				data: "courseId="+courseId+"&courseName="+courseName+"&description="+description+"&action=createCourse",
			});
		}
	}, "#addCourse");

	//handles sending a request to list coures
	$(document).on({
		click: function(){
			$.ajax({
				url: site("course.php"),
				success: function(results){
					$('#listResults').css('display', 'block');
					table = $('#listResults > table > tbody');
					for(i = 0; i < results.length; i++){
						courseName = results[i].courseName;
						courseId = results[i].courseId;
						description = results[i].description;
						//append to the table
						table.append("<tr>"+
								"<td>"+courseName+"</td>"+
								"<td>"+courseId+"</td>"+
								"<td>"+description+"</td>"+"<tr>");
					}
				},
				data: "action=list",
			});
		}
	}, '#listCourses');

	//handles adding a user to either the instructors course table or the student course table
	$(document).on({
		click: function(){
			userName = $("#addUserToCourse > #userName").val();
			courseId = $("#addUserToCourse > #courseId").val();
			//check for whitespace in the course id and username
			if(/\s/.test(courseId) || /\s/.test(userName)){
				$("#message").css("display", "block");
				$("#message").text("Course id and Username may not contain spaces");
				return;
			}
			//check if adding an instructor
			instructor = $("#instructor").prop('checked');
			if(!instructor){
				action = "addStudent";
			} else {
				action = "addInstructor";
			}
			$.ajax({
				url:site("course.php"),
				success: function(message){
					$('#addUserToCourse > div').text(message);
					$('#addUserToCourse > div').css('display', 'block');
				},
				data: "action="+action+"&courseId="+courseId+"&userName="+userName,
			});
		}
	}, '#addUser');
});