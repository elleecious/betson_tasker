$(document).ready(function() {

    var now = new Date();
    var year = now.getFullYear();
    var month = String(now.getMonth() + 1).padStart(2, '0');
    var day = String(now.getDate()).padStart(2, '0');
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
                $('#status').text(isLunchBreak ? 'Status: Lunch break is over, please get back to work' : 'Status: Your break is over, please get back to work');
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
        var lastResetDate = localStorage.getItem('lastResetDate');
        var currentDate = now.toISOString().split('T')[0];

        var shiftStartTime = 21 * 60;
        var shiftEndTime = 7 * 60;

        if (currentTime >= shiftEndTime && currentTime < shiftStartTime && currentDate !== lastResetDate && breakCount > 0) {
            breakCount = 0;
            localStorage.setItem('breakCount', breakCount);
            $('#breakCount').text('Breaks Taken: ' + breakCount);
            localStorage.setItem('lastResetDate', currentDate);
        }
    }

    function handleLunchBreak() {
        var now = new Date();
        var currentTime = now.getHours() * 60 + now.getMinutes();
        var lunchStart = 1 * 60;
        var lunchEnd = 2 * 60;

        if (currentTime >= lunchStart && currentTime < lunchEnd) {
            $('#breakButton').prop('disabled', true);
            $('#status').text('Status: On Lunch Break');
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
            $('#status').text('Status: On Break');
            startTimer(timeLeft);
        } else {
            localStorage.removeItem('startTime');
            localStorage.removeItem('timeLeft');
            localStorage.removeItem('isOnBreak');
        }
    }


    resetBreakCountIfNeeded();
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
        startTimer(timeLeft); // No need to pass true, this is not a lunch break
    });

    $("#add_task").click(function(e){
        e.preventDefault();
        var task_title = $("#task_title").val();
    
        var addTask = JSON.parse(localStorage.getItem('addTask')) || [];
        var taskNo = addTask.length > 0 ? addTask[addTask.length - 1].no + 1 : 1;
    
        addTask.push({
            'no': taskNo,
            'task_title': task_title,
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
                tr += "<td></td>";
                tr += "<td>" + retrievedTask[i].task_title + "</td>";
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
                    window.location.href = 'home.php';
                }
            });
        } else {
            Swal.fire({
                title: "Username or Password is incorrect",
                text: "Error",
                icon: "error"
            });
        }
    });

	$("#nightMode").click(function(){
		$("body").toggleClass('bg-dark text-white');
		$(".jumbotron").toggleClass('bg-dark text-white');
		$("container").toggleClass('bg-dark text-light');
	});

    $("#btnLogout").click(function(){
        sessionStorage.removeItem("username");
        window.location.href="index.php";

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
