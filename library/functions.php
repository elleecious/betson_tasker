<?php

    function getLevel($level){
        $level_sql = retrieve("SELECT * FROM users WHERE level=?", array($level));
        $result = ($level_sql[0]['level'] == 1) ? "Director" : 
        (($level_sql[0]['level'] == 2) ? "Admin" : 
        (($level_sql[0]['level'] == 3) ? "Leadership" :
        (($level_sql[0]['level'] == 4) ? "Agent" : "Unknown")));
        echo $result;
    }

    function getTaskStatus($status){
        $status_sql = retrieve("SELECT * FROM task WHERE task_status=?",array($status));
        switch ($status_sql[0]['task_status']) {
            case 1:
                $status_name = "New";
                $color = "bg-info";
                break;
            case 2:
                $status_name = "Pending";
                $color = "bg-warning";
                break;
            case 3:
                $status_name = "On Going";
                $color = "bg-primary";
                break;
            case 4:
                $status_name = "Completed";
                $color = "bg-success";
                break;
            case 5:
                $status_name = "Paused";
                $color = "bg-secondary";
                break;
            default:
                $status_name = "Unknown";
                $color = "bg-danger";
        }

        return "<td class={$color} font-weight-bold'>{$status_name}</td>";
    }

    function secondsToHoursMinutes($seconds) {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        return [
            'hours'   => $hours,
            'minutes' => $minutes,
        ];
    }

    function getPublicIP() {
        $url = 'https://ipinfo.io/json';
        $response = @file_get_contents($url);
        $data = json_decode($response, true);
        return $data['ip'];
    }
?>
