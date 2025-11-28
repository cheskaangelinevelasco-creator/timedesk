<?php

include 'config.php';
session_start();

// page uses fixed hall labels — keep UI consistent and not show unrelated room types

// page redirect
$usermail="";
$usermail=$_SESSION['usermail'];
if($usermail == true){

}else{
  header("location: index.php");
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/home.css">
    <title>Hotel blue bird</title>
    <!-- boot -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <!-- fontowesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <!-- sweet alert -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="./admin/css/roombook.css">
    <style>
      /* Make the booking modal larger and inner area scrollable so freebies are readable */
      #guestdetailpanel{
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
      }

      /* Keep modal form large on desktop but responsive on small screens */
      #guestdetailpanel .guestdetailpanelform{
        width: 94%;
        max-width: 980px;
        max-height: 86vh;
        overflow: auto;
        border-radius: 10px;
      }

      /* increase content area so lists are readable */
      #guestdetailpanel .middle{
        min-height: 520px;
      }

      /* freebies list inside modal */
      #freebies-list, #freebies-checkboxes {
        font-size: 1.05rem;
        line-height: 1.6;
        color: #222;
        max-height: 220px;
        overflow: auto;
        padding: 8px 10px;
        background: #fff;
        border-radius: 6px;
      }

      /* responsive grid for freebies cards on page */
      .freebie-grid{
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 10px;
      }
      .freebie-grid .freebie-item{
        background: #f8fbff;
        padding: 8px 10px;
        border-radius: 6px;
        border: 1px solid #eaeff7;
        color: #172b4d;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.98rem;
      }

      /* calendar widget styles */
      .bd-calendar { background:#fff;border:1px solid #e6edf8;border-radius:8px;padding:8px; }
      .bd-calendar .cal-head{ display:flex;justify-content:space-between;align-items:center;padding:6px 8px;border-bottom:1px solid #eef6ff }
      .bd-calendar .cal-head button{ border:none;background:transparent;cursor:pointer;padding:6px;border-radius:6px }
      .bd-calendar .cal-grid{ display:grid;grid-template-columns:repeat(7,1fr);gap:6px;padding:8px }
      .cal-cell{ height:36px;display:flex;align-items:center;justify-content:center;border-radius:6px;font-size:0.95rem; }
      .cal-cell.weekday{ font-weight:600;color:#6b7280;height:28px }
      .cal-cell.day{ background:transparent;border:1px solid transparent }
      .cal-cell.day.booked{ background: linear-gradient(90deg,#ffefef,#ffe9e9);border-color:#ffd5d5; color:#821313; position:relative; box-shadow:0 0 0 0 rgba(255,0,0,0.0); animation: bookedPulse 1.6s ease-in-out infinite;}
      .cal-cell.day.today{ border:1px dashed #b9e6c9 }

      @keyframes bookedPulse{ 0% { box-shadow:0 0 0 0 rgba(255,0,0,0.0) } 70% { box-shadow:0 0 0 8px rgba(255,0,0,0.06) } 100% { box-shadow:0 0 0 0 rgba(255,0,0,0.0) } }

      /* page freebies cards readability */
      #freebies .card-body h5.card-title { font-size: 1.05rem; margin-bottom: 0.6rem; }
      #freebies .card-body ul li { font-size: 1rem; margin-bottom: 8px; color: #333; }

      /* availability and modal message styling */
      #modal-av-result, #av-result { font-size: 0.98rem; }
    </style>
</head>

<body>
  <nav>
    <div class="logo">
      <img class="bluebirdlogo" src="./image/logo1.png" alt="logo">
      <p>TIMEDESK</p>
    </div>
    <ul>
      <li><a href="#firstsection">Home</a></li>
      <li><a href="#secondsection">Rooms</a></li>
      <li><a href="#thirdsection">Facilites</a></li>
      <li><a href="#contactus">contact us</a></li>
      <a href="./logout.php"><button class="btn btn-danger">Logout</button></a>
    </ul>
  </nav>

  <section id="firstsection" class="carousel slide carousel_section" data-bs-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img class="carousel-image" src="./image/wp.jpg">
        </div>
        <div class="carousel-item">
            <img class="carousel-image" src="./image/gt.jpg">
        </div>
        <div class="carousel-item">
            <img class="carousel-image" src="./image/sum.jpg">
        </div>
        <div class="carousel-item">
            <img class="carousel-image" src="./image/hotel4.jpg">
        </div>

        <div class="welcomeline">
          <h1 class="welcometag">Welcome to Time Desk</h1>
        </div>

      <!-- bookbox -->
      <div id="guestdetailpanel">
        <form action="" method="POST" class="guestdetailpanelform">
            <div class="head">
                <h3>RESERVATION</h3>
                <i class="fa-solid fa-circle-xmark" onclick="closebox()"></i>
            </div>
            <div class="middle">
                <div class="guestinfo">
                    <h4>Guest information</h4>
                    <input type="text" name="Name" placeholder="Enter Full name">
                    <input type="email" name="Email" placeholder="Enter Email">

                    <?php
                    $countries = array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");
                    ?>

                    <select name="Country" class="selectinput">
						<option value selected >Select your country</option>
                        <?php
							foreach($countries as $key => $value):
							echo '<option value="'.$value.'">'.$value.'</option>';
                            //close your tags!!
							endforeach;
						?>
                    </select>
                    <input type="text" name="Phone" placeholder="Enter Phone no">
                </div>

                <div class="line"></div>

                <div class="reservationinfo">
                    <h4>Reservation information</h4>
                    <select name="RoomType" class="selectinput">
                      <option value selected>What type of Hall</option>
                      <option value="Mini Convention">Mini Convention</option>
                      <option value="Ampitheater">Ampitheater</option>
                      <option value="Nieto Hall">Nieto Hall</option>
                      <option value="Rico Fajardo Hall">Rico Fajardo Hall</option>
                    </select>
                    <select name="Bed" class="selectinput">
						<option value selected >How many pax? </option>
                        <option value="Single">100-200</option>
                        <option value="Double">300-400</option>
						<option value="Triple">400-500</option>
                        <option value="Quad">500-600</option>
						<option value="None">None</option>
                    </select>
                    <select name="NoofRoom" class="selectinput">
						<option value selected >No of Room</option>
                        <option value="1">1</option>
                        <!-- <option value="1">2</option>
                        <option value="1">3</option> -->
                    </select>
                    <select name="Meal" class="selectinput">
						<option value selected >Meal</option>
                        <option value="Room only">Room only</option>
                        <option value="Breakfast">Breakfast</option>
						<option value="Half Board">Half Board</option>
						<option value="Full Board">Full Board</option>
					</select>
                    <div class="datesection">
                        <span>
                            <label for="cin"> Check-In</label>
                            <input name="cin" type ="date">
                        </span>
                        <span>
                            <label for="cin"> Check-Out</label>
                            <input name="cout" type ="date">
                        </span>
                    </div>
                    <!-- Freebies preview for selected hall -->
                    <div id="freebies-preview" style="margin-top:12px;">
                      <h5 style="margin-bottom:6px">Included free items</h5>
                      <div id="freebies-list">Select a hall to view included items</div>
                    </div>

                    <!-- availability status inside modal -->
                    <div id="modal-availability" style="margin-top:12px;display:flex;gap:12px;align-items:flex-start;flex-wrap:wrap;">
                      <div style="flex:1;min-width:220px;">
                        <h5 style="margin-bottom:6px">Availability</h5>
                        <div id="modal-av-result" style="padding:8px 12px;border:1px solid #e7e7e7;border-radius:6px;min-height:46px;color:#666;">Pick dates to check availability</div>
                      </div>
                      <div id="modal-calendar-wrap" style="min-width:220px;max-width:320px;flex:0 0 320px;"></div>
                    </div>
                </div>
            </div>
            <div class="footer">
              <button id="guestdetailsubmitBtn" class="btn btn-success" name="guestdetailsubmit">Submit</button>
            </div>
        </form>

        <!-- ==== room book php ====-->
        <?php       
            if (isset($_POST['guestdetailsubmit'])) {
                $Name = $_POST['Name'];
                $Email = $_POST['Email'];
                $Country = $_POST['Country'];
                $Phone = $_POST['Phone'];
                $RoomType = $_POST['RoomType'];
                $Bed = $_POST['Bed'];
                $NoofRoom = $_POST['NoofRoom'];
                $Meal = $_POST['Meal'];
                $cin = $_POST['cin'];
                $cout = $_POST['cout'];

                if($Name == "" || $Email == "" || $Country == ""){
                    echo "<script>swal({
                        title: 'Fill the proper details',
                        icon: 'error',
                    });
                    </script>";
                }
                else{
                  // server-side availability check to prevent overbooking
                  $reqQty = intval($NoofRoom);
                  // total rooms of this type
                  $total = 0;
                  if ($stmt = $conn->prepare("SELECT COUNT(*) as total FROM room WHERE type = ?")) {
                    $stmt->bind_param('s', $RoomType);
                    $stmt->execute();
                    $res = $stmt->get_result();
                    if ($res && ($r = $res->fetch_assoc())) $total = intval($r['total']);
                    $stmt->close();
                  }

                  // allow bookings even if rooms aren't configured — inventory enforcement is optional
                  $inventory_enforced = ($total > 0);

                  if (!$inventory_enforced) {
                      // no capacity configured; treat as unbounded availability
                      $booked = 0;
                      $available = PHP_INT_MAX;
                  } else {
                    // count already-confirmed bookings overlapping the requested dates
                    $booked = 0;
                    if ($stmt2 = $conn->prepare("SELECT COALESCE(SUM(NoofRoom),0) AS booked FROM roombook WHERE RoomType = ? AND stat = 'Confirm' AND NOT (cout <= ? OR cin >= ?)") ) {
                      $stmt2->bind_param('sss', $RoomType, $cin, $cout);
                      $stmt2->execute();
                      $r2 = $stmt2->get_result();
                      if ($r2 && ($rr = $r2->fetch_assoc())) $booked = intval($rr['booked']);
                      $stmt2->close();
                    }

                    $available = $total - $booked;
                    if ($available < $reqQty) {
                      // not enough rooms available
                      try { file_put_contents(__DIR__ . DIRECTORY_SEPARATOR . 'roombook_debug.log', date('c') . " - availability_failed: room={$RoomType} cin={$cin} cout={$cout} total={$total} booked={$booked} req={$reqQty}\n", FILE_APPEND | LOCK_EX); } catch(Exception $e){}
                      echo "<script>swal({title: 'Not enough rooms available', text: 'Requested: " . addslashes($reqQty) . " — Available: " . addslashes($available) . "', icon: 'error'});</script>";
                    }
                  }

                  // If inventory is enforced and there aren't enough rooms, don't insert; otherwise proceed
                  if ($inventory_enforced && $available < $reqQty) {
                      // already handled above with a swal error
                  } else {
                      $sta = "NotConfirm";
                      $sql = "INSERT INTO roombook(Name,Email,Country,Phone,RoomType,Bed,NoofRoom,Meal,cin,cout,stat,nodays) VALUES ('$Name','$Email','$Country','$Phone','$RoomType','$Bed','$NoofRoom','$Meal','$cin','$cout','$sta',datediff('$cout','$cin'))";
                      $result = mysqli_query($conn, $sql);
                      if ($result) {
                        echo "<script>swal({title: 'Reservation successful', icon: 'success'});</script>";
                      } else {
                        echo "<script>swal({title: 'Something went wrong', icon: 'error'});</script>";
                      }
                    }
                  }
            }
            ?>
          </div>

    </div>
  </section>
    
  <section id="secondsection"> 
    <img src="./image/homeanimatebg.svg">
    <div class="ourroom">
      <h1 class="head">≼ Our room ≽</h1>
      <div class="roomselect">
        <div class="roombox">
          <div class="hotelphoto h1"></div>
          <div class="roomdata">
            <h2>Mini Convention</h2>
            <div class="services">
              <i class="fa-solid fa-wifi"></i>
              <i class="fa-solid fa-burger"></i>
              <i class="fa-solid fa-spa"></i>
              <i class="fa-solid fa-dumbbell"></i>
              <i class="fa-solid fa-person-swimming"></i>
            </div>
            <button class="btn btn-primary bookbtn" onclick="openbookbox('Mini Convention')">Book</button>
          </div>
        </div>
        <div class="roombox">
          <div class="hotelphoto h2"></div>
          <div class="roomdata">
            <h2>Ampitheater</h2>
            <div class="services">
              <i class="fa-solid fa-wifi"></i>
              <i class="fa-solid fa-burger"></i>
              <i class="fa-solid fa-spa"></i>
              <i class="fa-solid fa-dumbbell"></i>
            </div>
            <button class="btn btn-primary bookbtn" onclick="openbookbox('Ampitheater')">Book</button>
          </div>
        </div>
        <div class="roombox">
          <div class="hotelphoto h3"></div>
          <div class="roomdata">
            <h2>Nieto Hall</h2>
            <div class="services">
              <i class="fa-solid fa-wifi"></i>
              <i class="fa-solid fa-burger"></i>
              <i class="fa-solid fa-spa"></i>
            </div>
            <button class="btn btn-primary bookbtn" onclick="openbookbox('Nieto Hall')">Book</button>
          </div>
        </div>
        <div class="roombox">
          <div class="hotelphoto h4"></div>
          <div class="roomdata">
            <h2>Rico Fajardo Hall</h2>
            <div class="services">
              <i class="fa-solid fa-wifi"></i>
              <i class="fa-solid fa-burger"></i>
            </div>
            <button class="btn btn-primary bookbtn" onclick="openbookbox('Rico Fajardo Hall')">Book</button>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- Freebies / Included items for each hall -->
  <section id="freebies" style="padding:40px 0;background:#fff;">
    <div class="container">
      <h2 class="head">≼ Freebies Included with Each Hall ≽</h2>
      <p class="lead">Each hall booking includes the following complimentary items.</p>

      <div class="row g-3 mt-4">
        <div class="col-md-6 col-lg-3">
          <div class="card h-100 shadow-sm">
            <div class="card-body">
              <h5 class="card-title">Mini Convention</h5>
              <div class="freebie-grid mt-3 mb-0">
                <div class="freebie-item"><i class="fa-solid fa-video me-2"></i>Projector</div>
                <div class="freebie-item"><i class="fa-solid fa-tv me-2"></i>White Screen</div>
                <div class="freebie-item"><i class="fa-solid fa-volume-up me-2"></i>Speaker</div>
                <div class="freebie-item"><i class="fa-solid fa-tv me-2"></i>LED Screen</div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-3">
          <div class="card h-100 shadow-sm">
            <div class="card-body">
              <h5 class="card-title">Ampitheater</h5>
                <div class="freebie-grid mt-3 mb-0">
                  <div class="freebie-item"><i class="fa-solid fa-chair me-2"></i>300 chairs</div>
                  <div class="freebie-item"><i class="fa-solid fa-video me-2"></i>Projector</div>
                  <div class="freebie-item"><i class="fa-solid fa-tv me-2"></i>White Screen</div>
                  <div class="freebie-item"><i class="fa-solid fa-volume-up me-2"></i>Speaker</div>
                  <div class="freebie-item"><i class="fa-solid fa-tv me-2"></i>LED Screen</div>
                </div>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-3">
          <div class="card h-100 shadow-sm">
            <div class="card-body">
              <h5 class="card-title">Nieto Hall</h5>
                <div class="freebie-grid mt-3 mb-0">
                  <div class="freebie-item"><i class="fa-solid fa-chair me-2"></i>150 chairs with 30 tables</div>
                  <div class="freebie-item"><i class="fa-solid fa-video me-2"></i>Projector</div>
                  <div class="freebie-item"><i class="fa-solid fa-tv me-2"></i>White Screen</div>
                  <div class="freebie-item"><i class="fa-solid fa-volume-up me-2"></i>Speaker</div>
                  <div class="freebie-item"><i class="fa-solid fa-tv me-2"></i>LED Screen</div>
                </div>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-3">
          <div class="card h-100 shadow-sm">
            <div class="card-body">
              <h5 class="card-title">Rico Fajardo Hall</h5>
                <div class="freebie-grid mt-3 mb-0">
                  <div class="freebie-item"><i class="fa-solid fa-chair me-2"></i>80 chairs with 10 tables</div>
                  <div class="freebie-item"><i class="fa-solid fa-video me-2"></i>Projector</div>
                  <div class="freebie-item"><i class="fa-solid fa-tv me-2"></i>White Screen</div>
                  <div class="freebie-item"><i class="fa-solid fa-volume-up me-2"></i>Speaker</div>
                  <div class="freebie-item"><i class="fa-solid fa-tv me-2"></i>LED Screen</div>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  
  <!-- Availability checker -->
  <section id="availability" style="padding:40px 0;background:#f4f6f8;">
    <div class="container">
      <h2 class="head">≼ Check Date Availability ≽</h2>
      

      <div style="max-width:700px;margin:18px auto;">
        <form id="availabilityForm" class="row g-2">
          <div class="col-md-4">
            <label class="form-label">Hall</label>
            <select id="av-room" class="form-control">
              <option value="Mini Convention">Mini Convention</option>
              <option value="Ampitheater">Ampitheater</option>
              <option value="Nieto Hall">Nieto Hall</option>
              <option value="Rico Fajardo Hall">Rico Fajardo Hall</option>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">Check-in</label>
            <input id="av-cin" type="date" class="form-control" required>
          </div>
          <div class="col-md-3">
            <label class="form-label">Check-out</label>
            <input id="av-cout" type="date" class="form-control" required>
          </div>
          <div class="col-md-2" style="display:flex;align-items:end;">
            <button id="av-check" class="btn btn-primary w-100" type="submit">Check</button>
          </div>
        </form>

        <div id="av-result" style="margin-top:12px;min-height:46px;padding:12px;border-radius:8px;border:1px dashed #e2e2e2;color:#333;"></div>
      </div>

      <!-- Booked dates / next available -->
      <div style="max-width:700px;margin:6px auto 36px;">
        <h5 style="margin-bottom:8px">See booked date ranges & next available</h5>
        <div class="row g-2 align-items-center">
          <div class="col-md-5">
            <select id="bd-room" class="form-control">
              <option value="Mini Convention">Mini Convention</option>
              <option value="Ampitheater">Ampitheater</option>
              <option value="Nieto Hall">Nieto Hall</option>
              <option value="Rico Fajardo Hall">Rico Fajardo Hall</option>
            </select>
          </div>
          <div class="col-md-3">
            <button id="bd-refresh" class="btn btn-outline-primary w-100">Show</button>
          </div>
          <div class="col-md-4 text-end"><small class="text-muted">Bookings shown are confirmed reservations only</small></div>
        </div>

        <div id="bd-result" style="margin-top:12px;padding:14px;border-radius:8px;border:1px dashed #e2e2e2;color:#333;min-height:120px;">
          <div style="display:flex;flex-direction:column;gap:10px;">
            <div id="bd-list" style="min-height:40px;">Choose a hall and click <strong>Show</strong> to see confirmed booked ranges and the next available date.</div>
            <div style="display:flex;gap:14px;align-items:flex-start;flex-wrap:wrap;">
              <div id="bd-calendar-wrap" style="flex:1;min-width:240px;max-width:520px;"></div>
              <div id="bd-meta" style="min-width:160px;max-width:260px;">
                <div id="bd-next" style="padding:10px;border-radius:6px;background:#fff;border:1px solid #eee">Next available: <strong id="bd-next-date">—</strong></div>
                <div id="bd-legend" style="margin-top:8px;padding:10px;border-radius:6px;background:#fff;border:1px solid #eee">
                  <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px"><span class="badge bg-danger" style="min-width:18px;height:18px"></span> <small>Booked</small></div>
                  <div style="display:flex;align-items:center;gap:8px"><span class="badge bg-success" style="min-width:18px;height:18px"></span> <small>Available</small></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

    <!-- My Reservations (shows logged-in user's bookings) -->
    <section id="myreservations" style="padding:40px;background:#f9f9f9;">
    <div class="container">
      <h1 class="head">≼ My Reservations ≽</h1>
      <p>Here are your recent bookings and their confirmation status.</p>

      <?php
      // Fetch bookings for the currently logged-in user
      if (!empty($usermail)) {
        $stmt = $conn->prepare("SELECT id, Name, RoomType, Bed, NoofRoom, cin, cout, nodays, stat FROM roombook WHERE Email = ? ORDER BY id DESC");
        if ($stmt) {
          $stmt->bind_param('s', $usermail);
          $stmt->execute();
          $res = $stmt->get_result();

          if ($res && $res->num_rows > 0) {
            echo '<div class="table-responsive mt-3"><table class="table table-striped">';
            echo '<thead><tr><th>ID</th><th>Guest</th><th>Room</th><th>How many pax?</th><th>Qty</th><th>Check-in</th><th>Check-out</th><th>Days</th><th>Status</th></tr></thead><tbody>';

            while ($row = $res->fetch_assoc()) {
              $statusRaw = strtolower(trim($row['stat']));
              if ($statusRaw === 'confirm' || $statusRaw === 'confirmed') {
                $statusLabel = 'Confirmed';
                $statusClass = 'badge bg-success';
              } elseif ($statusRaw === 'notconfirm' || $statusRaw === 'not confirmed' || $statusRaw === 'not_confirm' || $statusRaw === 'pending') {
                $statusLabel = 'Not confirmed';
                $statusClass = 'badge bg-warning text-dark';
              } else {
                $statusLabel = htmlspecialchars($row['stat']);
                $statusClass = 'badge bg-secondary';
              }

              echo '<tr>';
              echo '<td>' . htmlspecialchars($row['id']) . '</td>';
              echo '<td>' . htmlspecialchars($row['Name']) . '</td>';
              echo '<td>' . htmlspecialchars($row['RoomType']) . '</td>';
              // show the booking's bed value as the current "pax range" label if known
              $bedMap = array(
                'Single' => '100-200',
                'Double' => '300-400',
                'Triple' => '400-500',
                'Quad' => '500-600',
                'None' => 'None'
              );
              $bedDisplay = isset($bedMap[$row['Bed']]) ? $bedMap[$row['Bed']] : $row['Bed'];
              echo '<td>' . htmlspecialchars($bedDisplay) . '</td>';
              echo '<td>' . htmlspecialchars($row['NoofRoom']) . '</td>';
              echo '<td>' . htmlspecialchars($row['cin']) . '</td>';
              echo '<td>' . htmlspecialchars($row['cout']) . '</td>';
              echo '<td>' . htmlspecialchars($row['nodays']) . '</td>';
              echo '<td><span class="' . $statusClass . '">' . $statusLabel . '</span></td>';
              echo '</tr>';
            }

            echo '</tbody></table></div>';
          } else {
            echo '<div class="alert alert-info mt-3">You have no reservations yet.</div>';
          }

          $stmt->close();
        } else {
          echo '<div class="alert alert-danger mt-3">Unable to load reservations.</div>';
        }
      }
      ?>

    </div>
    </section>

  <section id="thirdsection">
    <h1 class="head">≼ Facilities ≽</h1>
    <div class="facility">
      <div class="box">
        <h2>Swiming pool</h2>
      </div>
      <div class="box">
        <h2>Gym</h2>
      </div>
      <div class="box">
        <h2>Cafeteria</h2>
      </div>
    </div>
  </section>

  
</body>

<script>

    var bookbox = document.getElementById("guestdetailpanel");

    openbookbox = (roomName = null) =>{
      bookbox.style.display = "flex";
      try {
        if (roomName && roomSelect) {
          // prefer exact match from select values
          roomSelect.value = roomName;
          loadFreebiesFor(roomName);
          try { loadModalBookedRangesFor(roomName); } catch(e){}
          setTimeout(checkModalAvailability, 40);
        }
      } catch (e) { console.error('openbookbox initialization error', e); }
    }

    // Booked ranges -> show confirmed booking ranges for a selected hall and next available date
    const bdRoom = document.getElementById('bd-room');
    const bdRefresh = document.getElementById('bd-refresh');
    const bdResult = document.getElementById('bd-result');

    async function loadBookedRanges(room) {
      if (!room || !bdResult) return;
      bdResult.innerHTML = 'Loading booked ranges…';
      try {
        const q = new URLSearchParams({room});
        const r = await fetch('get_booked_ranges.php?' + q.toString());
        const j = await r.json();
        if (!j.success) {
          bdResult.innerHTML = '<div class="text-danger">' + escapeHtml(j.error || 'Unable to load booked ranges') + '</div>';
          return;
        }

        const items = j.items || [];
        if (items.length === 0) {
          bdResult.innerHTML = '<div class="alert alert-success">No confirmed bookings found for <strong>' + escapeHtml(room) + '</strong>. Next available: <strong>Today</strong>.</div>';
          return;
        }

        // Render the list of ranges and prepare booked-set for calendar
        let html = '<div style="display:flex;flex-direction:column;gap:8px;">';
        // build a set of ISO date strings that are booked (each day in range)
        const bookedSet = new Set();
        items.forEach(it => {
          html += '<div class="badge bg-light text-dark border" style="padding:10px;display:flex;justify-content:space-between;align-items:center;">'
               + '<span>' + escapeHtml(it.cin) + ' — ' + escapeHtml(it.cout) + '</span>'
               + '<span class="text-muted">Confirmed</span>'
               + '</div>';
          // add each day in [cin, cout) to bookedSet
          const start = new Date(it.cin);
          const end = new Date(it.cout);
          for (let d = new Date(start); d < end; d.setDate(d.getDate()+1)) {
            bookedSet.add(d.toISOString().slice(0,10));
          }
        });

        // compute next available date starting from today
        // cursor = today
        const today = new Date();
        // convert booked ranges into Date objects
        const ranges = items.map(x => ({cin: new Date(x.cin), cout: new Date(x.cout)}));

        // sort by cin (should already be sorted)
        ranges.sort((a,b) => a.cin - b.cin);

        // cursor starts at today
        let cursor = new Date(today.getFullYear(), today.getMonth(), today.getDate());

        for (let r0 of ranges) {
          if (cursor < r0.cin) {
            // gap found, cursor is available
            break;
          }
          // if cursor within a booking, move cursor to booking cout (the first day after booking)
          if (cursor >= r0.cin && cursor < r0.cout) {
            cursor = new Date(r0.cout.getFullYear(), r0.cout.getMonth(), r0.cout.getDate());
          }
        }

        const nextAvailable = cursor.toISOString().slice(0,10);
        // render calendar with highlighted booked days if bd-calendar-wrap exists
        if (document.getElementById('bd-calendar-wrap')) renderCalendar(document.getElementById('bd-calendar-wrap'), ranges, bookedSet, today);
        html += '</div>';
        html += '<div style="margin-top:10px;">Next available date: <strong>' + escapeHtml(nextAvailable) + '</strong></div>';
        bdResult.innerHTML = html;
      } catch (err) {
        console.error('loadBookedRanges failed', err);
        bdResult.innerHTML = '<div class="text-danger">Unable to load booked ranges — network error.</div>';
      }
    }

    if (bdRefresh) bdRefresh.addEventListener('click', function(ev){ ev.preventDefault(); loadBookedRanges(bdRoom.value); });

    // calendar renderer (simple, no dependencies)
    function renderCalendar(container, ranges, bookedSet, startDate) {
      // container: DOM element
      container.innerHTML = '';
      const cal = document.createElement('div'); cal.className = 'bd-calendar';
      const hh = document.createElement('div'); hh.className = 'cal-head';

      // month / navigation
      let current = new Date(startDate.getFullYear(), startDate.getMonth(), 1);
      const monthLabel = document.createElement('div');
      const prev = document.createElement('button'); prev.type = 'button'; prev.innerHTML = '<i class="fa-solid fa-chevron-left"></i>';
      const next = document.createElement('button'); next.type = 'button'; next.innerHTML = '<i class="fa-solid fa-chevron-right"></i>';
      monthLabel.style.fontWeight = '700';
      const updateLabel = () => { monthLabel.textContent = current.toLocaleString(undefined, {month:'long', year:'numeric'}); }
      updateLabel();
      prev.addEventListener('click', () => { current.setMonth(current.getMonth()-1); updateLabel(); draw(); });
      next.addEventListener('click', () => { current.setMonth(current.getMonth()+1); updateLabel(); draw(); });

      hh.appendChild(prev); hh.appendChild(monthLabel); hh.appendChild(next);
      cal.appendChild(hh);

      const grid = document.createElement('div'); grid.className = 'cal-grid';

      function draw() {
        grid.innerHTML = '';
        // weekdays headers
        const weekdays = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
        weekdays.forEach(w => { const el = document.createElement('div'); el.className = 'cal-cell weekday'; el.textContent = w; grid.appendChild(el); });

        const year = current.getFullYear(); const month = current.getMonth();
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month+1, 0);
        const startOffset = firstDay.getDay();

        // blanks until first day
        for (let i=0; i<startOffset; i++) { const c = document.createElement('div'); c.className = 'cal-cell day'; grid.appendChild(c); }

        for (let d=1; d<=lastDay.getDate(); d++) {
          const date = new Date(year, month, d);
          const iso = date.toISOString().slice(0,10);
          const cell = document.createElement('div'); cell.className = 'cal-cell day'; cell.textContent = d;
          if (bookedSet.has(iso)) {
            cell.classList.add('booked');
            const icon = document.createElement('i'); icon.className = 'fa-solid fa-calendar-xmark'; icon.style.marginLeft = '6px'; icon.style.fontSize = '0.8rem';
            cell.appendChild(icon);
          }
          // highlight today
          const now = new Date(); const isToday = (date.getFullYear()==now.getFullYear() && date.getMonth()==now.getMonth() && date.getDate()==now.getDate());
          if (isToday) { cell.classList.add('today'); }
          grid.appendChild(cell);
        }
      }

      cal.appendChild(grid);
      container.appendChild(cal);
      draw();
    }

    async function loadModalBookedRangesFor(room) {
      const wrap = document.getElementById('modal-calendar-wrap');
      const avResult = document.getElementById('modal-av-result');
      if (!room || !wrap) return;
      avResult.innerHTML = 'Loading calendar…';
      try {
        const q = new URLSearchParams({room});
        const r = await fetch('get_booked_ranges.php?' + q.toString());
        const j = await r.json();
        if (!j.success) {
          avResult.innerHTML = '<div class="text-danger">' + escapeHtml(j.error || 'Unable to load booked ranges') + '</div>';
          return;
        }
        const items = j.items || [];
        // build bookedSet
        const bookedSet = new Set();
        items.forEach(it => {
          const s = new Date(it.cin);
          const e = new Date(it.cout);
          for (let d = new Date(s); d < e; d.setDate(d.getDate()+1)) bookedSet.add(d.toISOString().slice(0,10));
        });

        // current selected dates in the modal
        const selCin = document.querySelector('#guestdetailpanel input[name="cin"]').value || null;
        const selCout = document.querySelector('#guestdetailpanel input[name="cout"]').value || null;

        renderModalCalendar(wrap, bookedSet, new Date(), selCin, selCout, (cinIso, coutIso) => {
          // set fields in the modal when user picks dates
          try { if (cinIso) document.querySelector('#guestdetailpanel input[name="cin"]').value = cinIso; } catch(e){}
          try { if (coutIso) document.querySelector('#guestdetailpanel input[name="cout"]').value = coutIso; } catch(e){}
          // re-run availability check
          checkModalAvailability();
        });

        avResult.innerHTML = items.length ? 'Loaded calendar — click an available date to pick check-in or check-out.' : 'No confirmed bookings for this hall; all days appear available.';
      } catch (err) {
        console.error('loadModalBookedRangesFor failed', err);
        avResult.innerHTML = '<div class="text-danger">Unable to load calendar — network error.</div>';
      }
    }

    // --- Modal calendar renderer with selection support ---
    function renderModalCalendar(container, bookedSet, startDate, selectedCinIso, selectedCoutIso, onDateSelected) {
      container.innerHTML = '';
      const cal = document.createElement('div'); cal.className = 'bd-calendar';
      const hh = document.createElement('div'); hh.className = 'cal-head';
      let current = new Date(startDate.getFullYear(), startDate.getMonth(), 1);
      const monthLabel = document.createElement('div');
      const prev = document.createElement('button'); prev.type = 'button'; prev.innerHTML = '<i class="fa-solid fa-chevron-left"></i>';
      const next = document.createElement('button'); next.type = 'button'; next.innerHTML = '<i class="fa-solid fa-chevron-right"></i>';
      const updateLabel = () => { monthLabel.textContent = current.toLocaleString(undefined, {month:'short', year:'numeric'}); }
      updateLabel(); prev.addEventListener('click', () => { current.setMonth(current.getMonth()-1); updateLabel(); draw(); }); next.addEventListener('click', () => { current.setMonth(current.getMonth()+1); updateLabel(); draw(); });
      hh.appendChild(prev); hh.appendChild(monthLabel); hh.appendChild(next); cal.appendChild(hh);

      const grid = document.createElement('div'); grid.className = 'cal-grid';

      function draw() {
        grid.innerHTML = '';
        const weekdays = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat']; weekdays.forEach(w => { const el = document.createElement('div'); el.className = 'cal-cell weekday'; el.textContent = w; grid.appendChild(el); });
        const year = current.getFullYear(); const month = current.getMonth();
        const firstDay = new Date(year, month, 1); const lastDay = new Date(year, month+1, 0); const startOffset = firstDay.getDay();
        for (let i=0;i<startOffset;i++){ const c=document.createElement('div'); c.className='cal-cell day'; grid.appendChild(c); }
        for (let d=1; d<= lastDay.getDate(); d++){
          const date = new Date(year, month, d); const iso = date.toISOString().slice(0,10);
          const cell = document.createElement('div'); cell.className='cal-cell day'; cell.textContent = d;
          // mark booked
          if (bookedSet.has(iso)) { cell.classList.add('booked'); }
          // mark selected start/end and in-range
          if (selectedCinIso && selectedCinIso === iso) { cell.style.border = '2px solid #0d6efd'; cell.style.fontWeight = '700'; }
          if (selectedCoutIso && selectedCoutIso === iso) { cell.style.border = '2px solid #0d6efd'; cell.style.fontWeight = '700'; }
          if (selectedCinIso && selectedCoutIso && selectedCinIso < iso && iso < selectedCoutIso) { cell.style.background = 'linear-gradient(90deg,#e6f7ff,#eafcff)'; }
          // click handler to pick date
          cell.addEventListener('click', function(){
            // ignore clicks on booked days
            if (bookedSet.has(iso)) return;
            if (!selectedCinIso || (selectedCinIso && selectedCoutIso)) {
              // start new range
              selectedCinIso = iso; selectedCoutIso = null;
            } else if (selectedCinIso && !selectedCoutIso) {
              // set cout only if after cin; if before, swap
              if (iso <= selectedCinIso) { selectedCoutIso = selectedCinIso; selectedCinIso = iso; }
              else selectedCoutIso = iso;
            }
            draw();
            if (typeof onDateSelected === 'function') onDateSelected(selectedCinIso, selectedCoutIso);
          });
          grid.appendChild(cell);
        }
      }
      cal.appendChild(grid); container.appendChild(cal); draw();
    }
    closebox = () =>{
      bookbox.style.display = "none";
    }

    // load freebies for current selection inside the booking modal
    const roomSelect = document.querySelector('#guestdetailpanel select[name="RoomType"]');
    const freebiesList = document.getElementById('freebies-list');

    async function loadFreebiesFor(room) {
      if (!room) {
        freebiesList.innerText = 'Select a hall to view included items';
        return;
      }

      freebiesList.innerText = 'Loading...';
      try {
        const resp = await fetch('get_freebies.php?room=' + encodeURIComponent(room));
        const data = await resp.json();
        if (data.success) {
          if (data.items.length === 0) {
            freebiesList.innerHTML = '<div class="text-muted">No freebies set for this hall yet.</div>';
            return;
          }
          const html = '<ul style="margin:0;padding-left:18px;">' + data.items.map(i => '<li>' + escapeHtml(i.item) + (i.quantity ? ' — <small class="text-muted">' + escapeHtml(i.quantity) + '</small>' : '') + '</li>').join('') + '</ul>';
          freebiesList.innerHTML = html;
        } else {
          // use server-provided message if available so we can see what's wrong
          const errMsg = data && data.error ? data.error : 'Unable to load freebies';
          freebiesList.innerHTML = '<div class="text-danger">' + escapeHtml(errMsg) + '</div>';
        }
      } catch (err) {
        console.error('fetch get_freebies error:', err);
        freebiesList.innerHTML = '<div class="text-danger">Unable to load freebies (network)</div>';
      }
    }

    function escapeHtml(s) {
      return String(s).replace(/[&<>"']/g, function (m) {
        return {
          '&': '&amp;',
          '<': '&lt;',
          '>': '&gt;',
          '"': '&quot;',
          "'": '&#39;'
        }[m];
      });
    }

    // modal availability: check availability inside booking modal and block submit when not enough
    const modalCin = document.querySelector('#guestdetailpanel input[name="cin"]');
    const modalCout = document.querySelector('#guestdetailpanel input[name="cout"]');
    const modalNoofRoom = document.querySelector('#guestdetailpanel select[name="NoofRoom"]');
    const modalAvResult = document.getElementById('modal-av-result');
    const modalSubmitBtn = document.getElementById('guestdetailsubmitBtn');

    async function checkModalAvailability() {
      try {
        if (!roomSelect || !modalCin || !modalCout || !modalAvResult) return;
        const room = roomSelect.value;
        const cin = modalCin.value;
        const cout = modalCout.value;
        const reqQty = modalNoofRoom ? parseInt(modalNoofRoom.value || '1', 10) : 1;
        if (!room || !cin || !cout) {
          modalAvResult.innerHTML = 'Pick room and dates to check availability';
          if (modalSubmitBtn) modalSubmitBtn.disabled = false; // don't block for empty selection
          return;
        }
        modalAvResult.innerHTML = 'Checking availability…';
        const q = new URLSearchParams({room, cin, cout});
        const r = await fetch('get_availability.php?' + q.toString());
        const j = await r.json();
        if (!j.success) {
          modalAvResult.innerHTML = '<div class="text-danger">' + escapeHtml(j.error || 'Unable to check availability') + '</div>';
          if (modalSubmitBtn) modalSubmitBtn.disabled = false; // allow submit — availability checks are advisory
          return;
        }
        const avail = parseInt(j.available || 0, 10);
        const total = parseInt(j.total_rooms || 0, 10);
        const booked = parseInt(j.booked || 0, 10);
        if (j.inventory_enforced === false) {
          // server indicates capacity isn't enforced for this hall — allow booking
          modalAvResult.innerHTML = '<div class="text-info">No configured capacity for this hall — bookings are allowed (inventory not enforced).</div>';
          if (modalSubmitBtn) modalSubmitBtn.disabled = false;
        } else if (avail >= reqQty) {
          modalAvResult.innerHTML = '<div class="text-success">Available: <strong>' + avail + '</strong> rooms available for these dates.</div>';
          if (modalSubmitBtn) modalSubmitBtn.disabled = false;
        } else {
          // distinguish between no inventory vs fully booked vs not enough
          if (total === 0 && j.inventory_enforced === false) {
            modalAvResult.innerHTML = '<div class="text-info">No configured capacity for this hall — bookings are allowed (inventory not enforced).</div>';
          } else if (avail === 0) {
            modalAvResult.innerHTML = '<div class="text-danger">Fully booked for selected dates — total: <strong>' + total + '</strong>, booked: <strong>' + booked + '</strong>.</div>';
          } else {
            modalAvResult.innerHTML = '<div class="text-danger">Not enough rooms available — requested: <strong>' + reqQty + '</strong>, available: <strong>' + avail + '</strong>.</div>';
          }
          if (modalSubmitBtn) modalSubmitBtn.disabled = true;
        }
      } catch (err) {
        console.error('modal availability check failed', err);
        if (modalAvResult) modalAvResult.innerHTML = '<div class="text-danger">Unable to check availability — network error.</div>';
        if (modalSubmitBtn) modalSubmitBtn.disabled = false;
      }
    }

    // wire modal fields to check availability when values change
    if (roomSelect) roomSelect.addEventListener('change', checkModalAvailability);
    if (modalCin) modalCin.addEventListener('change', checkModalAvailability);
    if (modalCout) modalCout.addEventListener('change', checkModalAvailability);
    if (modalNoofRoom) modalNoofRoom.addEventListener('change', checkModalAvailability);

    // ensure availability is checked whenever modal is opened
    const origOpen = openbookbox;
    openbookbox = function(roomName){
      try { origOpen(roomName); } catch(e) { bookbox.style.display = 'flex'; }
      // slight delay to allow UI changes (roomSelect value may be set by caller)
      setTimeout(function(){ checkModalAvailability(); if (roomSelect) loadModalBookedRangesFor(roomSelect.value); }, 60);
    }

    if (roomSelect) {
      roomSelect.addEventListener('change', function(){
        loadFreebiesFor(this.value);
        // refresh modal calendar when user changes room inside modal
        try { loadModalBookedRangesFor(this.value); } catch(e){}
      });
      // pre-load when modal opens or page loads
      // if a default is selected, load items now
      if (roomSelect.value) { loadFreebiesFor(roomSelect.value); loadModalBookedRangesFor(roomSelect.value); }
    }

    // Availability check handler
    const availForm = document.getElementById('availabilityForm');
    const availResult = document.getElementById('av-result');
    if (availForm) {
      availForm.addEventListener('submit', async function(ev){
        ev.preventDefault();
        const room = document.getElementById('av-room').value;
        const cin = document.getElementById('av-cin').value;
        const cout = document.getElementById('av-cout').value;
        if (!room || !cin || !cout) {
          availResult.innerHTML = '<div class="text-danger">Please select hall, check-in and check-out dates.</div>';
          return;
        }
        availResult.innerHTML = 'Checking availability…';
        try {
          const q = new URLSearchParams({room, cin, cout});
          const r = await fetch('get_availability.php?' + q.toString());
          const j = await r.json();
          if (!j.success) {
            availResult.innerHTML = '<div class="text-danger">' + escapeHtml(j.error || 'Unable to check availability') + '</div>';
            return;
          }
          const avail = parseInt(j.available, 10);
          if (avail > 0) {
            availResult.innerHTML = '<div class="alert alert-success">Good news — <strong>' + escapeHtml(j.room) + '</strong> is available for those dates. <br>Available rooms: <strong>' + avail + '</strong></div>';
          } else {
            availResult.innerHTML = '<div class="alert alert-warning">Sorry — <strong>' + escapeHtml(j.room) + '</strong> appears fully booked for the selected dates. <br>Total rooms: ' + j.total_rooms + ', booked: ' + j.booked + '.</div>';
          }
        } catch (err) {
          console.error('availability check failed', err);
          availResult.innerHTML = '<div class="text-danger">Unable to check availability — network error.</div>';
        }
      });
    }
</script>
</html>