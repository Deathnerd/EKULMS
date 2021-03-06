//Client logic goes here
$(document).ready(function () {
	//set globals
	//construct the url to pass to the ajax function
	var site = function (file) {
		var url = "http://";
		var pathArray = window.location['href'].split('/');
		for (var i = 2; i < pathArray.length - 1; i++) {
			url = url + pathArray[i] + '/';
		}
		return url + file;
	};
	//handles sending a course creation form
	$(document).on({
		click: function () {
			var description = $("#description").val();
			var courseId = $("#courseId").val();
			var courseName = $("#courseName").val();
			var message = $('#message');
			//check for whitespace strings in course id
			if (/\s/.test(courseId)) {
				message.css("display", "block").text("Course id cannot have whitespace");
				return;
			}
			$.ajax({
				url: site("course.php"),
				success: function (message) {
					message.text(message).css('display', 'block');
					;
				},
				data: {
					courseId: courseId,
					courseName: courseName,
					description: description,
					action: 'createCourse'
				}
			});
		}
	}, "#addCourse");
	//handles sending a request to list course
	$(document).on({
		click: function () {
			$.ajax({
				url: site("course.php"),
				success: function (results) {
					var listResults = $('#listResults');
					listResults.css('display', 'block');
					var table = listResults.find('tbody');
					for (var i = 0; i < results.length; i++) {
						var courseName = results[i].courseName;
						var courseId = results[i].courseId;
						var description = results[i].description;
						//append to the table
						table.append("<tr>" +
						"<td>" + courseName + "</td>" +
						"<td>" + courseId + "</td>" +
						"<td>" + description + "</td>" + "<tr>");
					}
				},
				data: {
					action: 'list'
				}
			});
		}
	}, '#listCourses');

	//handles adding a user to either the instructors course table or the student course table
	$(document).on({
		click: function () {
			var addUserToCourse = $("#addUserToCourse");
			var userName = addUserToCourse.find('#userName').val();
			var courseId = addUserToCourse.find('#courseId').val();
			var message = $('#message');
			//check for whitespace in the course id and username
			if (/\s/.test(courseId) || /\s/.test(userName)) {
				message.css("display", "block").text("Course id and Username may not contain spaces");
				return;
			}
			//check if adding an instructor
			var instructor = $("#instructor").prop('checked');
			var action = null;
			if (!instructor) {
				action = "addStudent";
			} else {
				action = "addInstructor";
			}
			$.ajax({
				url: site("course.php"),
				success: function (response) {
					message.text(response).css('display', 'block');
				},
//				data:    "action=" + action + "&courseId=" + courseId + "&userName=" + userName
				data: {
					action: action,
					courseId: courseId,
					userName: userName
				}
			});
		}
	}, '#addUser');
});