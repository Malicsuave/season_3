
<?php include 'connections.php'; ?>

<table border = 1>
    <tr>
        <td>#</td>
        <td>Student No.</td>
        <td>Student Name</td>
        <td>Tuition Fee</td>
        <td>Miscellaneous Fee</td>
    </tr>
    <?php

    $i = 1;
    $rows = mysqli_query($connections, "SELECT * FROM student ");
    foreach($rows as $row):
    ?>

    <tr>
        <td> <?php echo $i++; ?> </td>
        <td> <?php echo $row["student_no"]; ?> </td>
        <td> <?php echo $row["name"];?> </td>
        <td> <?php echo $row["tuition_fee"];?> </td>
        <td> <?php echo $row["misc_fee"];?> </td>
    </tr>

    <?php endforeach; ?>




</table>
