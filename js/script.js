$(document).ready(function() {
	
	var date = new Date();
	var year = date.getFullYear();
	var month = String(date.getMonth() + 1).padStart(2,'0');
	var day = String(date.getDate()).padStart(2,'0');
	var currentDate = `${year}-${month}-${day}`;
	
	var timerInterval;
	var breakCount = parseInt(localStorage.getItem('breakCount')) || 0;
	var startTime = parseInt(localStorage.getItem('startTime')) || null;
	var timeLeft = parseInt(localStorage.getItem('timeLeft')) || 10 * 60;
	var isOnBreak = localStorage.getItem('isOnBreak') === 'true';

	$('#breakCount').text('Breaks Taken: ' + breakCount);

	function updateTimerDisplay(seconds) {
		var minutes = Math.floor(seconds / 60);
		var remainingSeconds = seconds % 60;
		minutes = minutes < 10 ? '0' + minutes : minutes;
		remainingSeconds = remainingSeconds < 10 ? '0' + remainingSeconds : remainingSeconds;
		$('#timer').text(minutes + ':' + remainingSeconds);
	}

	var sessionUsername = sessionStorage.getItem("username");
	if (sessionUsername) {
		$("#displayUsername").text(sessionUsername);
	}

	function startTimer(duration, isLunchBreak = false) {
		clearInterval(timerInterval);
		timerInterval = setInterval(function() {
			if (timeLeft <= 0) {
				clearInterval(timerInterval);
				localStorage.removeItem('startTime');
				localStorage.removeItem('timeLeft');
				localStorage.removeItem('isOnBreak');
				$('#status').text(isLunchBreak ? 'Lunch Break is over,':'Your short break is over'+', please back to work');
				$('#timer').text('00:00');
				$('#breakButton').prop('disabled', false);
				return;
			}
			timeLeft--;
			localStorage.setItem('timeLeft', timeLeft);
			updateTimerDisplay(timeLeft);
		}, 1000);
	}

	function resetBreakCountIfNeeded() {
		var now = new Date();
		var currentTime = now.getHours() * 60 + now.getMinutes();

		// Reset break count if it's after 12 PM
		if (currentTime >= 12 * 60 && breakCount > 0 ) {
			breakCount = 0;
			localStorage.setItem('breakCount', breakCount);
			$('#breakCount').text('Breaks Taken: ' + breakCount);
		}
	}

	function handleLunchBreak() {
		var now = new Date();
		var currentTime = now.getHours() * 60 + now.getMinutes();
		var lunchStart = 1 * 60; // 1 AM in minutes
		var lunchEnd = 2 * 60; // 2 AM in minutes

		if (currentTime >= lunchStart && currentTime < lunchEnd) {
			$('#breakButton').prop('disabled', true);
			$('#status').text('On Lunch Break');
			timeLeft = lunchEnd - currentTime;
			startTimer(timeLeft * 60, true); 
			localStorage.setItem('isOnBreak', true);
		}
	}

	if (isOnBreak && startTime) {
		var currentTime = Math.floor(Date.now() / 1000);
		var elapsedTime = currentTime - startTime;
		timeLeft -= elapsedTime;
		if (timeLeft > 0) {
			$('#breakButton').prop('disabled', true);
			$('#status').text('On Short Break');
			startTimer(timeLeft);
		} else {
			localStorage.removeItem('startTime');
			localStorage.removeItem('timeLeft');
			localStorage.removeItem('isOnBreak');
		}
	}

	// Reset the break count if needed
	resetBreakCountIfNeeded();

	// Automatically handle lunch break
	handleLunchBreak();

	$('#breakButton').click(function() {
		$('#breakButton').prop('disabled', true);
		$('#status').text('Status: On Break');
		breakCount++;
		$('#breakCount').text('Breaks Taken: ' + breakCount);
		localStorage.setItem('breakCount', breakCount);

		timeLeft = 10 * 60;
		startTime = Math.floor(Date.now() / 1000);
		localStorage.setItem('startTime', startTime);
		localStorage.setItem('timeLeft', timeLeft);
		localStorage.setItem('isOnBreak', true);
		startTimer(timeLeft);
	});

	$("#add_task").click(function(e){
		e.preventDefault();
		var task = $("#txt_task").val();
	
		var addTask = JSON.parse(localStorage.getItem('addTask')) || [];
		var taskNo = addTask.length > 0 ? addTask[addTask.length - 1].no + 1 : 1;
	
		addTask.push({
			'no': taskNo,
			'tasks': task,
			'curr_date':currentDate
		});
	
		// Save the updated task list to local storage
		localStorage.setItem('addTask', JSON.stringify(addTask));
	
		// Display tasks
		var retrievedTask = JSON.parse(localStorage.getItem('addTask'));
		var display_task = $("#displayTasks");
		display_task.empty();

		for (var i = 0; i < retrievedTask.length; i++) {
			var tr = "<tr>";
				tr += "<td>" + retrievedTask[i].no + "</td>";
				tr += "<td>" + retrievedTask[i].tasks + "</td>";
				tr += "<td></td>";
				tr += "<td></td>";
				tr += "<td></td>";
				tr += "<td></td>";
				tr += "<td></td>";
				tr += "<td>" + retrievedTask[i].curr_date + "</td>";
			tr += "</tr>";
			
			display_task.append(tr);
		}
	});

	$("#btnLogin").click(function(e){
		e.preventDefault();

		var username = $("#username").val();
		var password = $("#password").val();
  
		if (username == "betson" && password == "betson") {
			Swal.fire({
				title: "Login Success",
				text: "Welcome to BetsonTasker",
				icon: "success"
			}).then((result) => {
				if (result.isConfirmed) {
					sessionStorage.setItem("username",username);
					window.location.href = 'home.html';
				}
			});
		} else {
			Swal.fire({
				title: "Username or Password is incorrect",
				text: "Error",
				icon: "error"
			});
		}
	})

	$("#btnLogout").click(function(){
		sessionStorage.removeItem("username");
		window.location.href="index.html";

	});

	$("#tblTasks").DataTable({
		"scrollX": true,
		"info": true,
		"lengthChange": true,
		"paging": true,
		"searching": true,
        "pageLength":10,
		"order": [],
	});
	
});