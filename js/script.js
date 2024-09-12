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

    // var sessionUsername = sessionStorage.getItem("username");
    // if (sessionUsername) {
    //     $("#displayUsername").text(sessionUsername);
    // }

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


    //Login
    $("#btnLogin").click(function(e){
        e.preventDefault();

        $.ajax({
            url: './actions/login.php',
            type: 'POST',
            data: {
                username: $('#username').val(),
                password: $('#password').val()
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                Swal.fire({
                    title: response.status === 'success' ? 'Success!' : 'Error!',
                    text: response.message,
                    icon: response.status,
                    confirmButtonText: 'OK'
                });

                if (response.status === 'success') {
                    window.location.href = "home.php";
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    title: 'Error!',
                    text: 'An error occurred: ' + error,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    });


    //Create Account
    $("#register").click(function(e) {
        e.preventDefault();
    
        var password = $("#password").val();
        var confirm_password = $("#confirm_password").val();
    
        if (password === confirm_password) {
            $.ajax({
                url: "./actions/create_account.php",
                type: 'POST',
                data: {
                    lastname:$("#lastname").val(),
                    firstname:$("#firstname").val(),
                    position:$("#position").val(),
                    username:$("#username").val(),
                    password:$("#password").val(),
                },
                dataType: 'JSON',
                success: function(response) {
                    console.log(response);
                    Swal.fire({
                        title: response.status === 'success' ? 'Success!' : 'Error!',
                        text: response.message,
                        icon: response.status,
                        confirmButtonText: 'OK'
                    });
                },
                error: function(error) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'An error occurred: ' + error,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        } else {
            Swal.fire({
                title: 'Error!',
                text: 'Passwords do not match',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });

    $("#add_task").click(function(e){
        e.preventDefault();

        $.ajax({
            url: "./actions/add_task.php",
            type: 'POST',
            data: { 
                task_title:$("#task_title").val(),
                task_title:$("#task_title").val(),
                task_date:$("#task_date").val(),
            },
            dataType: 'JSON',
            success: function(response) { 
                console.log(response);
                Swal.fire({
                    title: response.status === 'success' ? 'Success!' : 'Error!',
                    text: response.message,
                    icon: response.status,
                    confirmButtonText: 'OK'
                });
            },
            error: function(error){
                Swal.fire({
                    title: 'Error!',
                    text: 'An error occurred: ' + error,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        })
    });
    
    
    
    $("#nightMode").click(function(){
		$("body").toggleClass('bg-dark text-white');
		$(".jumbotron").toggleClass('bg-dark text-white');
		$("container").toggleClass('bg-dark text-light');
        $(".card").toggleClass('bg-dark text-light');
	});

    $("#btnLogout").click(function(e){
        e.preventDefault();

        $.ajax({
            url: './actions/logout.php',
            type: 'POST',
            dataType: 'json', 
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        title: 'Logged Out',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(function() {
                        window.location.href = 'index.php';
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: response.message || 'An error occurred.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    title: 'Error',
                    text: 'An error occurred: ' + error,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    });
});
