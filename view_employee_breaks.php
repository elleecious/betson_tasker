<?php include('includes/header.php') ?>
<?php include('library/functions.php') ?>
<?php $page_title="Betson Tasker"; ?>
<div class="row mx-auto mt-3">
    <div class="col-md-12 mb-2">
        <div class="row">
            <div class="col-md-12 mb-2">
                <div class="card">
                    <div class="card-header p-3 bg-primary text-white">
                        Employee Breaks
                    </div>
                    <div class="card-body mt-3">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered table-sm text-center" width="100%" cellspacing="0" cellpadding="0" id="tblEmployeeBreaks">
                                    <thead>
                                        <tr>
                                            <?php
                                                $thead = explode(",","Name,Break Type,Break Time,Start Break,End Break");
                                                foreach ($thead as $thead_value) {
                                                    echo "<th>$thead_value</th>";
                                                }
                                            ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $getBreakEmployee=retrieve("SELECT 
                                                CONCAT(users.firstname, ' ', users.lastname) AS name,
                                                users.position AS position,
                                                breaks.break_type AS break_type,
                                                breaks.break_time AS break_time,
                                                breaks.break_start AS start_break,
                                                breaks.break_end AS end_break
                                                FROM breaks 
                                                LEFT JOIN users ON breaks.user_id=users.id",array());

                                                for ($i=0; $i < COUNT($getBreakEmployee); $i++) { 
                                                    $time = secondsToHoursMinutes($getBreakEmployee[$i]['break_time']);
                                                    echo "<tr>
                                                        <td>".$getBreakEmployee[$i]['name']."</td>
                                                        <td>".$getBreakEmployee[$i]['break_type']."</td>
                                                        <td>".$time['hours']." hours and ".$time['minutes']." minutes</td>
                                                        <td>".$getBreakEmployee[$i]['start_break']."</td>
                                                        <td>".$getBreakEmployee[$i]['end_break']."</td>
                                                    </tr>";
                                                }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('includes/footer.php') ?>
<script>
$(document).ready(function () {
    $("#tblEmployeeBreaks").DataTable({
		"scrollX": true,
		"info": true,
		"lengthChange": true,
		"paging": true,
		"searching": true,
        "pageLength":20,
		"order": [],
	});
});
</script>