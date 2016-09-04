<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Calendar</title>
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.js"></script>
	<script src="js/script.js"></script>
	<script src="js/calendar.min.js"></script>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">

	<script>
	var displayShared = true;

	function removeSharedEvents(){
		console.log("this was hit to share off");
		if(displayShared){
			$('.thisEventWasShared').hide();
			$('.toggleCalendar').css('color', 'red');
			displayShared = false;
		} else{
			$('.thisEventWasShared').show();
			$('.toggleCalendar').css('color', 'white');
			displayShared = true;
		}

	}

	function hideCalendarEvent(event){
		console.log("you want to hide from " + event.target.id);
		var prepId = "#" + event.target.id;
		currentColor = $(prepId).css("color");
		console.log(currentColor + "is the current color");

		var className = "." + event.target.id;
		if (currentColor === "rgb(255, 255, 255)"){
			// document.getElementsByClassName(event.target.id).style.visibility = "hidden";
			$(className).hide();
			currentColor = $(prepId).css("color", "red");

		} else {
			$(className).show();
			currentColor = $(prepId).css("color", "white");
		}
		
	}
	</script>

</head>
<body>

	
	<div id="youGoofed" class="alert alert-danger fade in">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<strong>Woah Bessie!</strong> That doesn't look right...
	</div>

	<div class="jumbotron">
      <div class="container">
        <h1>Welcome to the Calendar!</h1>
        <p>Sign in or sign up below to use our calendar program.</p>
      </div>
    </div>


	<div id="currentMonth"></div>
	<div class="row">
		<div id="changeMonth" class="col-xs-2 col-xs-offset-4"><button id="prev_month_btn" class="btn btn-primary">Previous Month</button></div>
		<div id="changeMonthPrev" class="col-xs-2"><button id="next_month_btn" class="btn btn-primary">Next Month</button></div>
	</div>

	<div id="displayCalendar"></div>

	<div id="loginScreen">
		<h2>Sign In</h2>


		<div>
			<label for="username">User Name:</label>
			<input type="text" name="username" id="username" />
			<label for="password">Password:</label>
			<input type="password" name="password" id="password" />
			<button id="validate" class="btn btn-success">Sign In</button>
		</div>

		<br>

		<h2>Sign Up</h2>

		<div>
			<label for="desiredusername">Desired User Name:</label>
			<input type="text" name="desiredusername" id="desiredusername" />
			<label for="desiredpassword">Password:</label>
			<input type="password" name="desiredpassword" id="desiredpassword" />
			<button id="signup" class="btn btn-success">Sign Up</button>
		</div>
	</div>


	<div id="addEvent">
		<label for="eventTitle">Title:</label>
		<input type="text" name="eventTitle" id="eventTitle" />


		<label for="eventTime">Event:</label>
		<select name="eventTime" id="eventTime">
			<option value="12:00am">12:00am</option>
			<option value="1:00am">1:00am</option>
			<option value="2:00am">2:00am</option>
			<option value="3:00am">3:00am</option>
			<option value="4:00am">4:00am</option>
			<option value="5:00am">5:00am</option>
			<option value="6:00am">6:00am</option>
			<option value="7:00am">7:00am</option>
			<option value="8:00am">8:00am</option>
			<option value="9:00am">9:00am</option>
			<option value="10:00am">10:00am</option>
			<option value="11:00am">11:00am</option>
			<option value="12:00pm">12:00pm</option>
			<option value="1:00pm">1:00pm</option>
			<option value="2:00pm">2:00pm</option>
			<option value="3:00pm">3:00pm</option>
			<option value="4:00pm">4:00pm</option>
			<option value="5:00pm">5:00pm</option>
			<option value="6:00pm">6:00pm</option>
			<option value="7:00pm">7:00pm</option>
			<option value="8:00pm">8:00pm</option>
			<option value="9:00pm">9:00pm</option>
			<option value="10:00pm">10:00pm</option>
			<option value="11:00pm">11:00pm</option>
		</select>

		<!-- <input type="text" name="eventTime" id="eventTime" /> -->

		<label for="eventDay">Day:</label>
		<select name="eventDay" id="eventDay">
			<option value="01">01</option>
			<option value="02">02</option>
			<option value="03">03</option>
			<option value="04">04</option>
			<option value="05">05</option>
			<option value="06">06</option>
			<option value="07">07</option>
			<option value="08">08</option>
			<option value="09">09</option>
			<option value="10">10</option>
			<option value="11">11</option>
			<option value="12">12</option>
			<option value="13">13</option>
			<option value="14">14</option>
			<option value="15">15</option>
			<option value="16">16</option>
			<option value="17">17</option>
			<option value="18">18</option>
			<option value="19">19</option>
			<option value="20">20</option>
			<option value="21">21</option>
			<option value="22">22</option>
			<option value="23">23</option>
			<option value="24">24</option>
			<option value="25">25</option>
			<option value="26">26</option>
			<option value="27">27</option>
			<option value="28">28</option>
			<option value="29">29</option>
			<option value="30">30</option>
			<option value="31">31</option>
		</select>

		<!-- <input type="text" name="eventDay" id="eventDay" /> -->


		<label for="eventMonth">Month:</label>

		<select name="eventMonth" id="eventMonth">
			<option value="0">January</option>
			<option value="1">February</option>
			<option value="2">March</option>
			<option value="3">April</option>
			<option value="4">May</option>
			<option value="5">June</option>
			<option value="6">July</option>
			<option value="7">August</option>
			<option value="8">September</option>
			<option value="9">October</option>
			<option value="10">November</option>
			<option value="11">December</option>
		</select>
		<!-- <input type="text" name="eventMonth" id="eventMonth" /> -->

		<label for="eventYear">Year:</label>
		<input type="text" name="eventYear" id="eventYear" />
		<label for="sharedWith">Share With:</label>
		<input type="text" name="sharedWith" id="sharedWith" />
		<label for="eventCategory">Category:</label>
		<input type="text" name="eventCategory" id="eventCategory" />
		<input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
		<button id="createEvent" class="btn btn-success">Create Event</button>
	</div>

	<h3 id="categoryHeader">Categories (Toggle): </h3>
	<div id="toggleSharedButton">
		<button onclick="removeSharedEvents()" class="btn btn-info toggleCalendar">Toggle Shared Calendars</button>
	</div>

	<div id="categoryButtons">
		
	</div>

</body>
</html>