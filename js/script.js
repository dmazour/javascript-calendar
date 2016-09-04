document.addEventListener("DOMContentLoaded", function(event) {

	var currentMonth = new Month(2015, 09);
	var listOfCategories = [];

	// Change the month when the "next" button is pressed
	document.getElementById("next_month_btn").addEventListener("click", function(event){
		currentMonth = currentMonth.nextMonth(); // Previous month would be currentMonth.prevMonth()
		// $('#currentMonth').empty();
		initialize(); // Whenever the month is updated, we'll need to re-render the calendar in HTML
	}, false);


	//Previous button
	document.getElementById("prev_month_btn").addEventListener("click", function(event){
		currentMonth = currentMonth.prevMonth(); // Previous month would be currentMonth.prevMonth()
		// $('#currentMonth').empty();
		initialize(); // Whenever the month is updated, we'll need to re-render the calendar in HTML
	}, false);

	document.getElementById("signup").addEventListener("click", function(){
		// (console.log("clicked");)
		desiredusername = $('#desiredusername').val();
		desiredpassword = $('#desiredpassword').val();

		// console.log(desiredusername);
		// console.log(desiredpassword);

		var signInInfo = JSON.stringify({"desiredusername" : desiredusername, "desiredpassword" : desiredpassword});

		$.ajax({
			url: "signup.php",
			type: "POST",
			data: signInInfo,
			dataType: 'json',
			success: siteReset
		});


	});

	document.getElementById("validate").addEventListener("click", function(){
		// (console.log("clicked");)
		username = $('#username').val();
		password = $('#password').val();

		// console.log(desiredusername);
		// console.log(desiredpassword);

		var signInInfo = JSON.stringify({"username" : username, "password" : password});

		$.ajax({
			url: "validate.php",
			type: "POST",
			data: signInInfo,
			dataType: 'json',
			success: siteReset
		});


	});

	document.getElementById("createEvent").addEventListener("click", function(){
		title = $('#eventTitle').val();
		time = $('#eventTime').val();
		day = $('#eventDay').val();
		month = $('#eventMonth').val();
		year = $('#eventYear').val();
		sharedWith = $('#sharedWith').val();
		eventCategory = $('#eventCategory').val();

		var m = currentMonth.nextMonth().getDateObject(0);
		var lastDay = m.getDate();
		if (day > lastDay){
			alert("not okay");
		} else {

			var eventInfo = JSON.stringify({"title" : title, "time" : time, "day" : day, "month" : month, "year" : year, "sharedWith" : sharedWith, "eventCategory" : eventCategory});


			$.ajax({
				url: "createEvent.php",
				type: "POST",
				data: eventInfo,
				dataType: 'json',
				success: siteReset
			});

		}


	});


	function siteReset(param){
		console.log(param);
		if (param['success'] === true){
			initialize();
		} else {
			$('#youGoofed').css("visibility", "visible");
			closeErrorAlert();
			// alert("Woah there bessie...");
		}
	}

	function closeErrorAlert(){
		setTimeout(function() {
			$('#youGoofed').css("visibility", "hidden");
		}, 2000);
	}

	function initialize(){
		$('#currentMonth').empty();
		$('.jumbotron').hide();
		$('#categoryButtons').empty();
		listOfCategories = [];
		// $('.jumbotron').css("visibility", "hidden");
		$('#toggleSharedButton').css("visibility", "visible");
		$('#changeMonth').css("visibility", "visible");
		$('#categoryHeader').css("visibility", "visible");
		$('#changeMonthPrev').css("visibility", "visible");
		$('#currentMonth').append(numToMonth(currentMonth.month));
		$('#currentMonth').css("visibility", "visible");
		$('#addEvent').css("visibility", "visible");
		$('#displayCalendar').empty();
		$('#loginScreen').hide();
		$('#displayCalendar').append("<div class='container'><table class='table table-bordered' id='myTable'><thead> <tr class='info'> <th class='col-xs-1 weekHeader'>Sunday</th> <th class='col-xs-1 weekHeader'>Monday</th> <th class='col-xs-1 weekHeader'>Tuesday</th> <th class='col-xs-1 weekHeader'>Wednesday</th> <th class='col-xs-1 weekHeader'>Thursday</th> <th class='col-xs-1 weekHeader'>Friday</th> <th class='col-xs-1 weekHeader'>Saturday</th></tr> </thead>");
		$('#myTable').append("<tbody id='myBody'>");
		$('#myBody').append("<tr class='myCells'>");


		// get month length
		var m = currentMonth.nextMonth().getDateObject(0);
		var lastDay = m.getDate();

		// var weeks = currentMonth.getWeeks();
		// var days = weeks[0].getDates();
		// console.log(days[0].getDate());
		var x = currentMonth.getDateObject(1);
		var first = x.getDay();

		var firstDay = first;
		var monthLength = lastDay;
		var cellIter = 0;
		var dayIter = 0;
		while (dayIter < monthLength){
			if(cellIter < firstDay){
				$('.myCells').last().append("<td></td>");
			}
			if(cellIter >= firstDay){
				dayIter++;
				var textToAppend = '<td id="' + String(dayIter) + '">' + "<strong>" + String(dayIter) + "</strong>" + '</td>';
				$('.myCells').last().append(textToAppend);
			}
			if ((cellIter+8) % 7 === 0 && dayIter != monthLength){
				$('#myBody').append("</tr>");
				$('#myBody').append("<tr class='myCells'>");
			}
			cellIter++;
		}
		$('#myTable').append("</tr></tbody></table></div>");

		populateWithEvents();

	}


	function populateWithEvents(){
		// eventually get month and year **
		// var month = 0; // currentMonth.month;
		var month = currentMonth.month;
		// var year = 0; //currentMonth.year;
		var year = currentMonth.year;


		var monthYearinfo = JSON.stringify({"month" : month, "year" : year});

		$.ajax({
			url: "fetchEvents.php",
			type: "POST",
			data: monthYearinfo,
			dataType: 'json',
			success: addEventsToCalendar
		});


		$.ajax({
			url: "shareEvent.php",
			type: "POST",
			data: monthYearinfo,
			dataType: 'json',
			success: addSharedEventsToCalendar
		});

	}
	function addSharedEventsToCalendar(param){
		numSharedEvents = param['countOfSharedEvents'];
		console.log(numSharedEvents);
		if (numSharedEvents === 0){
			$('#toggleSharedButton').hide();
		}
		else {
			for (var i=0; i < param['allEvents'].length; i++){
				$('#toggleSharedButton').show();
				console.log(param['allEvents'][i]);
				console.log(param['title'] + " this is the title");
				var newEntry = param['allEvents'][i];
				newEntry = newEntry.split(",");
				var dayToAddTo = newEntry[1];
				var dayAlone = dayToAddTo;
				dayToAddTo = "#" + dayToAddTo;
				console.log(dayToAddTo);
				console.log(newEntry[4] + " this was the fourth");
				console.log(newEntry[5] + " this was the five");
				var toAdd = document.getElementById(dayAlone);
				var cleanInsert = newEntry[2] + ": " + newEntry[0];
				toAdd.innerHTML = toAdd.innerHTML + "<br class='thisEventWasShared'> " + "<div class='thisEventWasShared' id='" + newEntry[3] + param['username'] + "'>"+ cleanInsert + "</div>";

			}
		}
		createListeners();
	}

	function addEventsToCalendar(param){
		console.log(param);
		// console.log(param['allEvents']);
		for (var i=0; i < param['allEvents'].length; i++){
			console.log(param['allEvents'][i]);
			console.log(param['title'] + " this is the title");
			var newEntry = param['allEvents'][i];
			newEntry = newEntry.split(",");
			var dayToAddTo = newEntry[1];
			var dayAlone = dayToAddTo;
			dayToAddTo = "#" + dayToAddTo;
			console.log(dayToAddTo);
			console.log(newEntry[4] + " this was the fourth");
			console.log(newEntry[5] + " this was the five");
			var toAdd = document.getElementById(dayAlone);
			var cleanInsert = newEntry[2] + ": " + newEntry[0];
			var newId = "#" + newEntry[3] + param['username'];
			$(newId).empty();
				// toAdd.innerHTML = toAdd.innerHTML + "<br>" + "<div id='" + newEntry[3] + param['username'] + "'>"+ cleanInsert + "<button id='" + newEntry[3] + "delete" + "' class='btn btn-danger btn-xs deleteButton'>x</button>" + "</div>";
				toAdd.innerHTML = toAdd.innerHTML + "<br>" + "<div class='" + newEntry[5] + "99" + "' id='" + newEntry[3] + param['username'] + "'>"+ cleanInsert + "<button class='btn btn-danger btn-xs deleteButton' id='" + newEntry[3] + "'>x</button>" + '</div>';
				// toAdd.innerHTML = toAdd.innerHTML + "<br>" + "<div id='" + newEntry[3] + param['username'] + "'>"+ cleanInsert + "<button class='btn btn-danger btn-xs deleteButton' id='deleteEvent(" + newEntry[3] + ")'>x</button>" + "</div>";

				var inArray = $.inArray(newEntry[5], listOfCategories);
				if (inArray === -1){
					listOfCategories.push(newEntry[5]);
					addCategoryButton(newEntry[5]);
				}
			// console.log(listOfCategories);

		}
		createListeners();
	}

	function addCategoryButton(categoryName){


		$('#categoryButtons').append("<div id=' " + categoryName + "99'>" + "<button onclick='hideCalendarEvent(event)' id='" + categoryName + "99' class='btn btn-info categoryCalendar'>" + categoryName + "</button> </div>");
	}



	function createListeners(){
		$('.deleteButton').click(function(event){
			console.log(event.target.id);
			deleteClickedEvent(event.target.id);
		});

	}

	function deleteClickedEvent(idToDelete){
		var eventId = idToDelete;
		var eventInfo = JSON.stringify({"eventId" : eventId});
		console.log(eventInfo);
		$('#displayCalendar').empty();

		$.ajax({
			url: "deleteEvent.php",
			type: "POST",
			data: eventInfo,
			dataType: 'json',
			success: siteReset
		});

	}


	function numToMonth(monthNum){
		switch(monthNum)
		{
			case 0:
			month="January";
			break;
			case 1:
			month="February";
			break;
			case 2:
			month="March";
			break;
			case 3:
			month="April";
			break;
			case 4:
			month="May";
			break;
			case 5:
			month="June";
			break;
			case 6:
			month="July";
			break;
			case 7:
			month="August";
			break;
			case 8:
			month="September";
			break;
			case 9:
			month="October";
			break;
			case 10:
			month="November";
			break;
			case 11:
			month="December";
			break;
			default:
			month="Invalid month";
		}
		var monthAndYear = String(month) + ", " + String(currentMonth.year);
		return monthAndYear;
	}




});