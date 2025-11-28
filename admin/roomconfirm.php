<?php

include '../config.php';

$id = $_GET['id'];

$sql ="Select * from roombook where id = '$id'";
$re = mysqli_query($conn,$sql);
while($row=mysqli_fetch_array($re))
{
	$Name = $row['Name'];
    $Email = $row['Email'];
    $Country = $row['Country'];
    $Phone = $row['Phone'];
    $RoomType = $row['RoomType'];
    $Bed = $row['Bed'];
    $NoofRoom = $row['NoofRoom'];
    $Meal = $row['Meal'];
    $cin = $row['cin'];
    $cout = $row['cout'];
    $noofday = $row['nodays'];
    $stat = $row['stat'];
    // pull selected_freebies if present in roombook (may be null)
    $selected_freebies = isset($row['selected_freebies']) ? $row['selected_freebies'] : null;
}


if($stat == "NotConfirm")
{
    $st = "Confirm";

    $sql = "UPDATE roombook SET stat = '$st' WHERE id = '$id'";
    $result = mysqli_query($conn,$sql);

    if($result){

        $type_of_room = 0;      
        if($RoomType=="Mini Convention")
        {
            $type_of_room = 3000;
        }
        else if($RoomType=="Ampitheater")
        {
            $type_of_room = 2000;
        }
        else if($RoomType=="Nieto Hall")
        {
            $type_of_room = 1500;
        }
        else if($RoomType=="Rico Fajardo")
        {
            $type_of_room = 1000;
        }
        
        
        if($Bed=="Single")
        {
            $type_of_bed = $type_of_room * 1/100;
        }
        else if($Bed=="Double")
        {
            $type_of_bed = $type_of_room * 2/100;
        }
        else if($Bed=="Triple")
        {
            $type_of_bed = $type_of_room * 3/100;
        }
        else if($Bed=="Quad")
        {
            $type_of_bed = $type_of_room * 4/100;
        }
            else if($Bed=="None")
        {
            $type_of_bed = $type_of_room * 0/100;
        }

        if($Meal=="Room only")
        {
            $type_of_meal=$type_of_bed * 0;
        }
        else if($Meal=="Breakfast")
        {
            $type_of_meal=$type_of_bed * 2;
        }
        else if($Meal=="Half Board")
        {
            $type_of_meal=$type_of_bed * 3;
        }
        else if($Meal=="Full Board")
        {
            $type_of_meal=$type_of_bed * 4;
        }
                                                            
        $ttot = $type_of_room *  $noofday * $NoofRoom;
        $mepr = $type_of_meal *  $noofday;
        $btot = $type_of_bed * $noofday;

        $fintot = $ttot + $mepr + $btot;

        // include selected_freebies in payment rows when available so invoices can be generated from payment table alone
        $sf_sql = $selected_freebies !== null ? "'" . mysqli_real_escape_string($conn, $selected_freebies) . "'" : 'NULL';
        $psql = "INSERT INTO payment(id,Name,Email,RoomType,Bed,NoofRoom,cin,cout,noofdays,roomtotal,bedtotal,meal,mealtotal,finaltotal,selected_freebies) VALUES ('$id', '$Name', '$Email', '$RoomType', '$Bed', '$NoofRoom', '$cin', '$cout', '$noofday', '$ttot', '$btot', '$Meal', '$mepr', '$fintot', $sf_sql)";

        mysqli_query($conn,$psql);

        header("Location:roombook.php");
    }
}
// else
// {
//     echo "<script>alert('Guest Already Confirmed')</script>";
//     header("Location:roombook.php");
// }


?>