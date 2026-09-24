<?php
    include '../../includes/dbconn.php';

    $sql = "SELECT id from tblstudents";
    $query = $dbh -> prepare($sql);
    $query->execute();
    $results=$query->fetchAll(PDO::FETCH_OBJ);
    $studentcount=$query->rowCount();

    echo htmlentities($studentcount);
?>