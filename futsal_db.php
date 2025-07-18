<?php
/*$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gpedfqte_WP3RP";*/

$servername = "yamabiko.proxy.rlwy.net";
$username = "root";
$port ="13665";
$password = "elJbKEDUyyGYKVVHqkrcKZNNynrFtGHw";
$dbname = "gpedfqte_WP3RP";

// Create Database Connection
$conn = new mysqli($servername, $username, $password, $dbname,$port);

// Check connection
if ($conn->connect_error) {
    // If the connection fails, return error message
    echo json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]);
    exit();  // stop further execution
}
// Handle Different API Actions
$action = $_GET['action'] ?? '';
switch ($action) {
    case 'getphone':
        getphone($conn,$_GET['phoneNo']);
        break;
 
    case 'checkUsername':
        checkUsername($conn,$_GET['username']);
        break;
    case 'getLocations':
        getLocations($conn);
        break;
    case 'getLocByCourt':
        getLocByCourt($conn,$_GET['court_type']);
        break;
    case 'createBooking':
        createBooking($_POST, $conn);
        break;
    case 'insert_location':
        insert_location($_POST, $conn);
        break;
		
	case 'insert_gameType':
        insert_gameType($_POST, $conn);
        break;
    case 'cancelBooking':
        cancelBooking($_POST, $conn);
        break;
    case 'upload_images':
        upload_images($_POST);
        break;
    case 'login':
        login($_POST, $conn);
        break;
    case 'updatebooking':
        updatebooking($_POST, $conn);
        break;
    case 'create_pitch':
        create_pitch($_POST, $conn);
        break;
    case 'stadium_dp':
        stadium_dp($_POST, $conn);
        break;
    case 'delete_image':
        delete_image($_POST, $conn);
        break;
    case 'update_pitch':
        update_pitch($_POST, $conn);
        break;
    case 'getCourtType':
        getCourtType($conn);
        break;
    case 'getFutsalcenters':
        getFutsalcenters( $_GET['location']);
        break;
    case 'getFutsalDetails':
        getFutsalDetails($conn, $_GET['location'],$_GET['futsal']);
        break;
    case 'getCourtDetails':
        getCourtDetails($conn, $_GET['location'],$_GET['court']);
        break;
    case 'getverifybytel':
        getverifybytel($conn, $_GET['tel'], $_GET['futsal_id']);
        break;
    case 'calculateCost':
        calculateCost($_POST, $conn);
        break;
    case 'saveUser':
        saveUser($_POST, $conn);
        break;
    case 'updatereview':
        updatereview($_POST, $conn);
        break;
    case 'saveclient':
        saveclient($_POST, $conn);
        break;
    case 'update_user':
        update_user($_POST, $conn);
        break;

    case 'update_client':
        update_client($_POST, $conn);
        break;

    case 'getbookingstatus':
        getbookingstatus($conn, $_GET['location'], $_GET['pitch'],$_GET['timeslot'],$_GET['date']);
        break;
    case 'getcities':
        getcities($conn,$_GET['query']);
        break;
    case 'getDistinctDistricts':
        getDistinctDistricts($conn);
        break;
		

    case 'getDistinctProvince':
        getDistinctProvince($conn, $_GET['district']);
        break;
	case 'getDistrictByProvince':
        getDistrictByProvince($conn, $_GET['province']);
        break;
	case 'getCitiesByDistrict':
        getCitiesByDistrict($conn, $_GET['district']);
        break;

        
    default:
    '';
        /* echo json_encode([   "error" => "Invalid action",
        "Type" => $action,
        "GET" => $_GET,
        "POST" => $_POST,
        "Referrer" => $_SERVER['HTTP_REFERER'] ?? 'Unavailable',
        "Trace" => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)]); */
}

// Retrieve Available Locations



/* function getImagesOfPitch($id){
global $conn;


$sql ="select p.* from pitch as p join court as c ON c.court_id = p.court_id join stadium as s on s.stadium_id = c.stadium_id where s.stadium_id ='$id';";

 $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[$row['pitch_id']]['pitch_name'] = $row['pitch_name'];
$data[$row['pitch_id']]['images'][] = $row['image_url'];
        //$data[] = $row;
    }

    

    return  $data;


}
 */

function stadium_dp($data,$conn){
    $upload_folder = __DIR__  . '/uploads/';
    $upload_url_base = 'uploads/';
    $id = $data['stadium_id'];
    $image_path = null;
    // Handle image if uploaded
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
  // Ensure upload folder exists
  if (!file_exists($upload_folder)) {
    mkdir($upload_folder, 0755, true);
}
        $tmp_name = $_FILES['profile_picture']['tmp_name'];
        $original_name = basename($_FILES['profile_picture']['name']);
        $file_type = mime_content_type($tmp_name);

        if (strpos($file_type, 'image') === 0) {
            $unique_name = time() . '_' . $original_name;
            $server_path = $upload_folder . $unique_name;
            $public_url = $upload_url_base . $unique_name;

            if (move_uploaded_file($tmp_name, $server_path)) {
                $image_path = $public_url;
                $sql = "UPDATE stadium 
                SET image_path=? 
                WHERE stadium_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $image_path,$id);
            } else {
                echo json_encode(["success" => false,"message" => "Failed to upload image."]);
                
                return;
            }
        } else {
            echo json_encode(["success" => false,"message" => "Error updating details: Only image files are allowed."]);
            
            return;
        }
        if ($stmt->execute()) {
            echo json_encode(["success" => true,"message" => "Image uploaded successfully.","image_url"=>$public_url]);
            
    } else {
        echo json_encode(["success" => false,"message" => "Error updating details: " . $stmt->error]);
            
    }

    $stmt->close();
    }

}

function delete_image($data,$conn){
    $image_id = $data['image_id'];
    $query = "SELECT image_url FROM pitch_images WHERE image_id = $image_id LIMIT 1";
    $result = mysqli_query($conn, $query);
   if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }
    if ($row = mysqli_fetch_assoc($result)) {
        $imagePath = $row['image_url'];

        // Delete file from server
        /* if (file_exists($imagePath)) {
            unlink($imagePath);
        } */

        // Delete record from DB
        $deleteQuery = "DELETE FROM pitch_images WHERE image_id = $image_id";
        mysqli_query($conn, $deleteQuery);

        echo "success";
    } else {
        echo "Image not found";
    }
} 


function getImagesOfPitch($stadiumId) {
    global $conn;

    $stadiumId = mysqli_real_escape_string($conn, $stadiumId); // basic SQL injection protection

    $sql = "
        SELECT 
            p.pitch_id,
            p.pitch_name,
            pi.image_url,
            pi.image_id
        FROM pitch AS p
        JOIN court AS c ON c.court_id = p.court_id
        JOIN stadium AS s ON s.stadium_id = c.stadium_id
        LEFT JOIN pitch_images AS pi ON pi.pitch_id = p.pitch_id
        WHERE s.stadium_id = '$stadiumId';
    ";

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $pitchId = $row['pitch_id'];

        // Store pitch name once
        if (!isset($data[$pitchId])) {
                        
            $data[$pitchId] = [
                'pitch_id' =>  $row['pitch_id'],
                'pitch_name' => $row['pitch_name'],
                'images' => []
            ];
        }

        // Append image if available
        if (!empty($row['image_url'])) {
            $data[$pitchId]['images'][] = [
                'url' => $row['image_url'],
                'id' => $row['image_id']]
            ;
        }
    }

    return $data;
}


function getReviews(){
    global $conn;

    $sql = "SELECT r.rating, r.comment, r.review_date, r.pitch_id, p.pitch_name, s.name, c.cus_id, c.full_name, c.image_path FROM reviews AS r JOIN pitch AS p ON r.pitch_id = p.pitch_id JOIN stadium AS s ON s.stadium_id = p.stadium_id left join customer as c on c.cus_id = r.user_id ORDER BY r.rating DESC LIMIT 5";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    

    return  $data;
}
function getAllCourtDetails() {
    global $conn;
    $sql = "SELECT 
pitch.pitch_id as id, pitch.image_path as pitch_image, stadium.name as Stadium_name,stadium.address as Stadium_address,stadium.contact_info as Stadium_no,stadium.image_path as Stadium_image, court.court_type as Sport_type, pitch.pitch_name as Court_Name,pitch.opening_time as Opening_Time, pitch.closing_time as Closing_time, pricing.peak_rate as Peak_rate, pricing.offpeak_rate as Offpeak_rate, pricing.offpeak_start_time as offpeak_start, pricing.offpeak_end_time as Offpeak_end, pitch.Amenities as tagline,
ROUND(AVG(r.rating), 1) AS average_rating,
    COUNT(r.review_id) AS review_count
FROM stadium 
JOIN court ON court.stadium_id = stadium.stadium_id 
JOIN pitch ON pitch.court_id = court.court_id 
JOIN pricing ON pitch.pitch_id = pricing.pitch_id 
left JOIN reviews as r On r.pitch_id = pitch.pitch_id
GROUP by pitch.pitch_id ORDER BY average_rating DESC limit 5";


    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    

    return  $data;
}





function getCities($conn, $query) {

    $stmt = $conn->prepare("SELECT * FROM `location` WHERE city LIKE CONCAT(?, '%')");

    if (!$stmt) {
        die("Prepare failed: " . $conn->error); // Debug error if prepare fails
    }

    $stmt->bind_param("s", $query);  // no wildcards in the value; added in SQL
    $stmt->execute();

    $result = $stmt->get_result();
    $cities = [];

    while ($row = $result->fetch_assoc()) {
        $cities[] = $row;
    }

    echo json_encode($cities);
}



function getThisPitchDetails($pitch_id) {
    global $conn;

    // Sanitize input
    $pitch_id = mysqli_real_escape_string($conn, $pitch_id);

    // Main pitch details (assuming only one result)
    $sql = "SELECT p.*, s.*, c.*, pr.*,ROUND(AVG(r.rating), 1) AS average_rating,
    COUNT(r.review_id) AS review_count
            FROM pitch AS p 
            JOIN court AS c ON p.court_id = c.court_id
            JOIN stadium AS s ON c.stadium_id = s.stadium_id
            JOIN pricing AS pr ON p.pitch_id = pr.pitch_id
            left Join reviews as r On p.pitch_id = r.pitch_id
            WHERE p.pitch_id = '$pitch_id'
            LIMIT 1";

    $result = $conn->query($sql);
    if (!$result) {
        die("Query failed: " . $conn->error);
    }

    $data = $result->fetch_assoc();
    if (!$data) {
        return null; // Pitch not found
    }

    // Fetch all pitch images
    $imagesql = "SELECT * FROM pitch_images WHERE pitch_id = '$pitch_id'";
    $images = [];

    $result_img = $conn->query($imagesql);
    if (!$result_img) {
        die("Image query failed: " . $conn->error);
    }

    while ($row = $result_img->fetch_assoc()) {
        $images[] = $row;
    }

    $data['Images'] = $images;

    $reviewsql = "SELECT * FROM reviews as r left join customer as c on r.user_id = c.cus_id WHERE pitch_id =  '$pitch_id'";
    $reviews = [];

    $result_rev = $conn->query($reviewsql);
    if (!$result_img) {
        die("Image query failed: " . $conn->error);
    }

    while ($row = $result_rev->fetch_assoc()) {
        $reviews[] = $row;
    }

    $data['Reviews'] = $reviews;


    return $data;
}

function getLocations($conn) {
    $sql = "SELECT DISTINCT location FROM stadium";
    $result = $conn->query($sql);
    $locations = [];

    // Check if the query was successful
    if ($result === false) {
        // Query failed, log the error
        die("Query failed: " . $conn->error);
    }
    while ($row = $result->fetch_assoc()) {
        $locations[] = $row['location'];
    }
	
    echo json_encode($locations);
	
}

function getLocByCourt($conn, $courtType) {
    $sql = "SELECT DISTINCT s.location 
            FROM stadium s
            JOIN court c ON s.stadium_id = c.stadium_id
            WHERE c.court_type = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $courtType);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $locations = [];

            while ($row = $result->fetch_assoc()) {
                $locations[] = $row['location'];
            }

            $stmt->close(); // Close the statement
            echo json_encode($locations);
        } else {
            echo json_encode(["error" => "Query execution failed"]);
        }
    } else {
        echo json_encode(["error" => "SQL preparation failed"]);
    }
}

function getCourtType($conn) {
    $sql = "SELECT DISTINCT court_type FROM court";
    $result = $conn->query($sql);
    $courtTypes = [];
	
    // Check if the query was successful
    if ($result === false) {
        // Query failed, log the error
        die("Query failed: " . $conn->error);
    }
    while ($row = $result->fetch_assoc()) {
        $courtTypes[] = $row['court_type'];
    }
	
    echo json_encode($courtTypes);
	
}

function getSportType($conn) {
    $sql = "SELECT * FROM sport";
    $result = $conn->query($sql);
    $courtTypes = [];
	
    // Check if the query was successful
    if ($result === false) {
        // Query failed, log the error
        die("Query failed: " . $conn->error);
    }
    while ($row = $result->fetch_assoc()) {
        $courtTypes[] = ["sid" => $row["sport_id"],"name" => $row['sport_name']];
    }
	
    echo json_encode($courtTypes);
	
}

function getverifybytel($conn,$tel,$futsal_id) {
    $sql = "SELECT cus_id, fullname, email FROM customer WHERE phonenumber = ?";
    $stmt = $conn->prepare($sql);
    
	$stmt->bind_param("s", $tel);
    $stmt->execute();
    $result = $stmt->get_result();
    $customer = $result->fetch_assoc();

    if ($customer) {
        $cus_id = $customer['cus_id'];
        $full_name = $customer['fullname'];
        $email = $customer['email'];

        // Count bookings for the current month
		$sql_cus_detail = "SELECT COUNT(*) as total_bookings 
                                FROM cus_booking 
                                WHERE cus_id = ? 
                                AND futsal_id = ? 
                                AND MONTH(booked_date) = MONTH(CURRENT_DATE()) 
                                AND YEAR(booked_date) = YEAR(CURRENT_DATE())";
        $stmt = $conn->prepare($sql_cus_detail);
        $stmt->bind_param("ii", $cus_id, $futsal_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $booking_data = $result->fetch_assoc();
        $total_bookings = $booking_data['total_bookings'];

echo json_encode([
        "script" => "if (confirm('Customer found: $full_name ($email). Total bookings this month: $total_bookings. Proceed?')) {
                        document.getElementById('customerDetails').innerHTML = '<p>Name: $full_name</p><p>Email: $email</p>';
                    } else {
                        window.location.href='customer_registration.php';
                    }"
    ]);
} else {
    echo json_encode([
        "script" => "alert('Customer not found! Redirecting to registration page.');
                     window.location.href='customer_registration.php';"
    ]);
}
}

function getFutsalcenters($location) {
    global $conn;
    $sql = "SELECT p.pitch_id as pitch_id,
    c.court_id as court_id,
    p.pitch_name as pitch_name,
    p.opening_time as opening_time,
    p.closing_time as closing_time,
    p.image_path as pitch_image,
    p.tag as tagline,
    p.Amenities as amenities,
    p.pitch_id as pitch_id,
    c.court_type as court_type,
    c.initial_cost as initial_cost,
    c.extra_cost as extra_cost,
    pr.offpeak_start_time as offpeak_start_time,
    pr.offpeak_end_time as offpeak_end_time,
    pr.peak_rate as peak_rate, 
    pr.offpeak_rate as offpeak_rate, 
    pr.weekend_peak_rate as weekend_peak_rate, 
    pr.weekend_offpeak_rate as weekend_offpeak_rate, 
    pr.holiday_peak_rate as holiday_peak_rate, 
    pr.holiday_offpeak_rate as holiday_offpeak_rate, 
    s.name as stadium_name, 
    s.location as stadium_Location, 
    s.contact_info as contact_no, 
    s.address as stadium_address, 
    s.email_id as email_id, 
    s.image_path as stadium_image
    FROM pitch as p
    JOIN court as c On c.court_id = p.court_id
    JOIN stadium as s ON c.stadium_id = s.stadium_id
    JOIN pricing as pr ON pr.pitch_id = p.pitch_id
    WHERE s.location = '$location'";
    
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    return  $data;
}

function getphone($conn, $phone) {
	$sql = "SELECT * FROM customer WHERE phone_number = ?";
	$stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $phone);
	$stmt->execute();
    $result = $stmt->get_result();
	 if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode(["exists" => true,"id"=>$row["cus_id"], "name" => $row['full_name'], "address" => $row['address'], "NIC" => $row['NIC'], "Email" => $row['email']]);
    } else {
        echo json_encode(["exists" => false]);
    }
   
}

function getReviewDetails($id) {
    global $conn;

    $sql = "SELECT p.pitch_name, r.rating, r.comment, r.review_date, cu.full_name, cu.image_path,cu.cus_id 
            FROM stadium AS s 
            JOIN court AS c ON c.stadium_id = s.stadium_id 
            JOIN pitch AS p ON p.court_id = c.court_id 
            inner JOIN reviews AS r ON r.pitch_id = p.pitch_id 
            LEFT JOIN customer AS cu ON r.user_id = cu.cus_id 
            WHERE s.stadium_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id); // "i" for integer
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
}


/*
function getReviewDetails($id){
global $conn;

$sql = "select p.pitch_name, r.rating, r.comment, r.review_date,cu.full_name,cu.image_path from stadium as s join court as c on c.stadium_id = s.stadium_id join pitch as p on p.court_id = c.court_id left join reviews as r on r.pitch_id = p.pitch_id left join customer as cu on r.user_id = cu.cus_id where s.stadium_id = '$id'";
$stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user);
	$stmt->execute();
    $result = $stmt->get_result();
	 if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row;
    } else {
        echo json_encode(["exists" => false]);
    }
}*/

function checkUsername($conn, $user) {
	$sql = "SELECT * FROM stadium WHERE username  = ?";
	$stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user);
	$stmt->execute();
    $result = $stmt->get_result();
	 if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode(["exists" => true]);
    } else {
        echo json_encode(["exists" => false]);
    }
   
}



function getDistinctDistricts($conn){

$sql_d ="SELECT DISTINCT district FROM location";
 $result = mysqli_query($conn, $sql_d);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        
        $data[] = $row;
    }
 echo json_encode($data);
}

function getDistinctProvinces() {
	global $conn;
    $sql_d = "SELECT DISTINCT province FROM location";
    $result = mysqli_query($conn, $sql_d);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }


    while ($row = mysqli_fetch_assoc($result)) {
        
        $data[] = $row['province'];
    }
 return $data;
}


function getCitiesByDistrict($conn, $district) {
	$sql_d = "SELECT DISTINCT city FROM location where district = '$district'";
    $result = mysqli_query($conn, $sql_d);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }


    while ($row = mysqli_fetch_assoc($result)) {
        
        $data[] = $row['city'];
    }
 echo json_encode ($data);
}


function getDistrictByProvince($conn, $province) {
	$sql_d = "SELECT DISTINCT district FROM location where province = '$province'";
    $result = mysqli_query($conn, $sql_d);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }


    while ($row = mysqli_fetch_assoc($result)) {
        
        $data[] = $row['district'];
    }
 echo json_encode ($data);
	
	
    /*$stmt = $conn->prepare("SELECT DISTINCT district FROM location where province = ?");
    
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(['error' => 'Query failed: ' . $conn->error]);
        return;
    }

    $stmt->bind_param("s", $province);
    $stmt->execute();
    $result = $stmt->get_result();

    $districts = $result->fetch_assoc();

    // Respond with JSON (e.g., { "province": "Central" })
    return $districts;
	*/
}

function getDistinctProvince($conn, $district) {
    $stmt = $conn->prepare("SELECT DISTINCT province FROM location WHERE district = ?");
    
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(['error' => 'Query failed: ' . $conn->error]);
        return;
    }

    $stmt->bind_param("s", $district);
    $stmt->execute();
    $result = $stmt->get_result();

    $province = $result->fetch_assoc();

    // Respond with JSON (e.g., { "province": "Central" })
    echo json_encode($province);
}

function getCourtDetails($conn, $location, $courttype) {
    $sql = "SELECT 
    s.name AS stadium_name,
    p.pitch_name AS court_name,
    c.initial_cost AS initial_cost,
    c.extra_cost AS extra_cost,
    c.discount as court_discount,
    s.discount as stadium_discount,
    c.discount_type as court_dis_type,
    s.discount_type as stadium_dis_type,
    p.opening_time AS open_time,
    p.closing_time AS close_time,
    p.WeekEnd_opentime AS w_open_time,
    p.WeekEnd_closetime AS w_close_time,
    pr.offpeak_start_time AS offpeak_start,
    pr.offpeak_end_time AS offpeak_end,
    pr.peak_rate AS peak_rate,
    pr.offpeak_rate AS offpeak_rate,
    pr.weekend_peak_rate AS weekend_peak_rate,
    pr.weekend_offpeak_rate AS weekend_offpeak_rate,
    pr.holiday_peak_rate AS holiday_peak_rate,
    pr.holiday_offpeak_rate AS holiday_offpeak_rate
FROM stadium s
JOIN court c ON s.stadium_id = c.stadium_id
JOIN pitch p ON c.court_id = p.court_id
JOIN pricing pr ON p.pitch_id = pr.pitch_id
WHERE c.court_type = ? AND s.location = ?";

    $stmt = $conn->prepare($sql);
    
    // Debugging: Check for SQL errors
    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }

    $stmt->bind_param("ss", $courttype, $location);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $courtDetails = [];
    while ($row = $result->fetch_assoc()) {
        $courtDetails[] = [
            "stadium_name" => $row['stadium_name'],
            "court_name" => $row['court_name'],
            "peak_start" => $row['offpeak_start'],
            "peak_end" => $row['offpeak_end'],
            "off_peak_rate" => $row['offpeak_rate'],
            "peak_rate" => $row['peak_rate'],
            "weekend_peak_rate" => $row['weekend_peak_rate'],
            "weekend_offpeak_rate" => $row['weekend_offpeak_rate'],
            "holiday_peak_rate" => $row['holiday_peak_rate'],
            "holiday_offpeak_rate" => $row['holiday_offpeak_rate'],
            "open_time" => $row['open_time'],
            "close_time" => $row['close_time'],
            "w_open_time" => $row['w_open_time'],
            "w_close_time" => $row['w_close_time'],
            "initial_cost" =>$row['initial_cost'],
            "extra_cost" => $row['extra_cost'],
            "court_discount" => $row['court_discount'],
            "stadium_discount" => $row['stadium_discount'],
            "court_discount_type" => $row['court_dis_type'],
            "stadium_discount_type" => $row['stadium_dis_type']
        ];
    }
    echo json_encode($courtDetails);
}
/* 
function getbookingstatus($conn, $location, $courttype,$timeslot,$date) {
    $sql = "SELECT 
    b.status as status
FROM stadium s
JOIN court c ON s.stadium_id = c.stadium_id
JOIN pitch p ON c.court_id = p.court_id
JOIN booking b ON p.pitch_id = b.pitch_id
WHERE c.court_type = ? AND s.location = ? AND b.timeslot = ? and b.booking_date=?";

    $stmt = $conn->prepare($sql);
    
    // Debugging: Check for SQL errors
    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }

    $stmt->bind_param("ssss", $courttype, $location,$timeslot,$date);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode(["status" => $row['status']]);
    } else {
        echo json_encode(["status" => ""]);
    
}
} */


function getbookingstatus($conn, $location, $courttype,$timeslot,$date) {
    $sql = "SELECT 
    b.status as status
FROM stadium s
JOIN court c ON s.stadium_id = c.stadium_id
JOIN pitch p ON c.court_id = p.court_id
JOIN booking b ON p.pitch_id = b.pitch_id
WHERE p.pitch_name = ? AND s.location = ? AND b.timeslot = ? and b.booking_date=?";

    $stmt = $conn->prepare($sql);
    
    // Debugging: Check for SQL errors
    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }

    $stmt->bind_param("ssss", $courttype, $location,$timeslot,$date);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode(["status" => $row['status']]);
    } else {
        echo json_encode(["status" => ""]);
    
}
}

function getFutsalDetails($conn, $location, $futsal) {
    $sql = "SELECT * FROM futsal_info WHERE location = ? and futsal_name = ?";
	$stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $location, $futsal);
    $stmt->execute();
    $result = $stmt->get_result();

    $futsalCenters = [];
    while ($row = $result->fetch_assoc()) {
        $futsalCenters[] = [
            "name" => $row['futsal_name'],
            "peak_start" => $row['peak_start'],
            "peak_end" => $row['peak_end'],
            "off_peak_rate" => $row['offpeak_rate'],
            "peak_rate" => $row['peak_rate'],
            "open_time" => $row['open_time'],
            "close_time" => $row['close_time']
        ];
    }
    echo json_encode($futsalCenters);
}

// Calculate Cost Based on Selected Slots and Peak/Off-Peak Rates
function calculateCost($data, $conn) {
    $slots = $data['slots'];  // array of selected slots
    $futsalName = $data['futsalName'];
    $totalCost = 0;

    $sql = "SELECT peak_start, peak_end, off_peak_rate, peak_rate FROM futsal_centers WHERE name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $futsalName);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    foreach ($slots as $slot) {
        $hour = intval(explode(':', $slot)[0]);
        if ($hour >= intval($result['peak_start']) && $hour < intval($result['peak_end'])) {
            $totalCost += $result['peak_rate'];
        } else {
            $totalCost += $result['off_peak_rate'];
        }
    }
    echo json_encode(["totalCost" => $totalCost]);
}





function saveUser($data, $conn) {

    $fname = $data['fname'] ?? '';
    $lname = $data['lname'] ?? '';
    $door = $data['door'] ?? '';
    $street = $data['street'] ?? '';
    $phone = $data['phone'] ?? '';
    $password = $data['password'] ?? '';
    $nic = $data['nic'] ?? '';
    $email = $data['email'] ?? '';
    $city = $data['cityInput'] ?? '';
    $district = $data['districtDropdown'] ?? '';
    $province = $data['provinceDropdown'] ?? '';

    $name = "{$fname} {$lname}";
    $address = "{$door}, {$street}, {$city}, {$district}, {$province}";
    print_r($name);
    var_dump($address);
    date_default_timezone_set('Asia/Colombo');
    $currentDateTime = date('Y-m-d H:i:s');
    print_r($currentDateTime);
    $query = $conn->prepare("INSERT INTO customer (
        phone_number, full_name, email, address, NIC, password,
        first_name, last_name, city, created_at
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    var_dump($query);
    if (!$query) {
        echo json_encode(["message" => "Prepare failed: " . $conn->error, "status" => false]);
        return;
    }

    $query->bind_param(
        "ssssssssss", 
        $phone, $name, $email, $address, $nic, $password, $fname, $lname, $city, $currentDateTime
    );

    if ($query->execute()) {
        $cus_id = $conn->insert_id;
        print_r($cus_id);
        echo json_encode(["message" => "User registered successfully!", "cus_id" => $cus_id, "status" => true]);
    } else {
        echo json_encode(["message" => "Error registering user: " . $query->error, "status" => false]);
    }
}


function updatereview($data, $conn) {
    $user_id  = $data['user_id'] ?? '';
    $rate     = $data['rate'] ?? '';
    $pitch_id = $data['pitch_id'] ?? '';
    $comment  = $data['comment'] ?? '';

    // Proper debug log (for development only, avoid in production)

    date_default_timezone_set('Asia/Colombo');
    $currentDateTime = date('Y-m-d H:i:s');

    // Prepare and bind
    $query = $conn->prepare("
        INSERT INTO reviews (pitch_id, user_id, rating, comment, review_date)
        VALUES (?, ?, ?, ?, ?)
    ");
    $query->bind_param("iiiss", $pitch_id, $user_id, $rate, $comment, $currentDateTime);

    if ($query->execute()) {
        $review_id = $conn->insert_id;
        echo json_encode([
            "message"   => "Thanks for your review",
            "review_id" => $review_id,
            "status"    => true
        ]);
    } else {
        echo json_encode([
            "message" => "Error submitting review.",
            "status"  => false
        ]);
    }
}


function saveclient($data, $conn) {
	session_start();
    header('Content-Type: application/json');

    $stadiumName = $data['indoor_name'] ?? '';
    $door_street = $data['address'] ?? '';
    $province = $data['province'] ?? '';
    $district = $data['district'] ?? '';
    $city = $data['city'] ?? '';
    $phone = $data['phone'] ?? '';
    $email = $data['email'] ?? '';
    $username = $data['username'] ?? '';
    $password = password_hash($data['password'] ?? '', PASSWORD_DEFAULT); // hashed
    $status = 'inactive';

	

    $address = "{$door_street}, {$city}, {$district}, {$province}";

    $query = $conn->prepare("INSERT INTO stadium (name, address, contact_info, location, email_id, username, password, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
	
	if (!$query) {
    echo json_encode([
        "message" => "Prepare failed: " . $conn->error,
        "status" => false
    ]);
    exit; // stop execution
}

	
    $query->bind_param("ssssssss", $stadiumName, $address, $phone, $city, $email, $username, $password, $status);


    if ($query->execute()) {
        $stadium_id = $conn->insert_id;
        $_SESSION['user_id'] = $stadium_id;
        $_SESSION['role'] = 'client';

        echo json_encode([
            "message" => "Stadium registered successfully!",
            "stadiumId" => $_SESSION['user_id'],
            "status" => true
        ]);
    } else {
        echo json_encode([
            "message" => "Error registering Stadium.". $query->error,
            "status" => false
        ]);
    }
}


/*function saveclient($data,$conn){
    $stadiumName = $data['indoor_name'] ?? '';
    $door_street = $data['address'] ?? '';
    $province = $data['province'] ?? '';
    $district = $data['district'] ?? '';
    $city = $data['city'] ?? '';
    $phone = $data['phone'] ?? '';
    $email = $data['email'] ?? '';
    $username = $data['username'] ?? '';
    $password = $data['password'] ?? '';
	$status = 'inactive';
	$address = "{$door_street}, {$city}, {$district}, {$province}";

    $query = $conn->prepare("INSERT INTO stadium (name, address,contact_info, location,email_id,username,password,status) VALUES (?, ?, ?, ?, ?,?,?,?)");
    $query->bind_param("ssssssss", $stadiumName, $address, $phone, $city, $email, $username, $password, $status);
    if ($query->execute()) {
        $stadium_id = $conn->insert_id;
		$_SESSION['user_id'] = $stadium_id;
        $_SESSION['role'] = 'client';
        echo json_encode(["message" => "User registered successfully!","stadiumId" => $stadium_id,"status" => True]);
    } else {
        echo json_encode(["message" => "Error registering user.","status" => False]);
    }
}*/
function updatebooking($data, $conn) {
    $book_id = $data["id"];
    $status = $data["status"];
    date_default_timezone_set('Asia/Colombo');
    $currentDateTime = date('Y-m-d H:i:s');


    $query = "UPDATE booking
              SET status = ?, created_at = ?
              WHERE booking_id = ?";
    
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("ssi", $status, $currentDateTime, $book_id);

        if ($stmt->execute()) {
            echo json_encode(["status" => "Success", "message" => "Booking confirmed successfully"]);
        } else {
            echo json_encode(["status" => "Failure", "message" => "Failed to update booking"]);
        }

        $stmt->close();
    } else {
        echo json_encode(["status" => "Failure", "message" => "Database error: " . $conn->error]);
    }
}





function insert_location($data, $conn) {
    $province = $data["province"];
    $district  = $data["district"];
    $city  = $data["city"];
    
    if ($province && $district && $city) {
        $check_query = "SELECT * FROM location WHERE province = ? AND district = ? AND city = ?";
$check_stmt = $conn->prepare($check_query);
$check_stmt->bind_param("sss", $province, $district, $city);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows > 0) {
    // Location already exists
    echo json_encode(["status" => "Success", "message"=>"Location already exists."]);
} else {
    
        $query = "insert into location (province, district, city) values (?, ?, ?)";

    
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("sss", $province, $district, $city);

        if ($stmt->execute()) {
            echo json_encode(["status" => "Success", "message" => "Location inserted successfully"]);
        } else {
            echo json_encode(["status" => "Failure", "message" => "Failed to insert location booking"]);
        }

        $stmt->close();
    } else {
        echo json_encode(["status" => "Failure", "message" => "Database error: " . $conn->error]);
    }
}
}
}



function insert_gameType($data, $conn) {
    $gameType = $data["new_game_type"];
    $stadiumId = $data["stadium_id"];
    
    if ($gameType) {
        $check_query = "SELECT * FROM gametype WHERE gamename = ?";
$check_stmt = $conn->prepare($check_query);
$check_stmt->bind_param("s", $gameType);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows > 0) {
    // Location already exists
    echo json_encode(["status" => "Exist", "message"=>"This {$gameType} is already available"]);
} else {
    
        $query = "insert into gametype (gamename) values (?)";

    
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("s", $gameType,$stadiumId);

        if ($stmt->execute()) {
            echo json_encode(["status" => "Success", "message" => "New Game is added successfully"]);
$courttype ="insert into court (court_type,stadium_id) values (?,?)";
$stmt = $conn->prepare($courttype);

    if ($stmt) {
        $stmt->bind_param("si", $gameType);
        if ($stmt->execute()) {}
        echo json_encode(["status" =>"Success", "message" => "New court type is added successfully"]);
    }



        } else {
            echo json_encode(["status" => "Failure", "message" => "Failed to add new game"]);
        }

        $stmt->close();
    } else {
        echo json_encode(["status" => "Failure", "message" => "Database error: " . $conn->error]);
    }
}
}
}


function cancelBooking($data, $conn) {
    $book_id = $data["id"];


    $query = "DELETE FROM booking WHERE booking_id = ?";
    
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("i",  $book_id);

        if ($stmt->execute()) {
            echo json_encode(["status" => "Success", "message" => "Booking is cancelled successfully"]);
        } else {
            echo json_encode(["status" => "Failure", "message" => "Failed to cancel the booking"]);
        }

        $stmt->close();
    } else {
        echo json_encode(["status" => "Failure", "message" => "Database error: " . $conn->error]);
    }
}

function login($data, $conn) {
    session_start();
    $username = $data['username'] ?? '';
    $password = $data['password'] ?? '';
    $logintype = strtolower($data['logintype'] ?? '');
    $status = 'active';

    $table = $logintype === 'client' ? 'stadium' : 'customer';
    $userfield = $logintype === 'client' ? 'username' : 'phone_number';
    $passfield = $logintype === 'client' ? 'password' : 'NIC';
    // First fetch the hashed password from DB for the given username/phone number
    $query = "SELECT * FROM $table WHERE BINARY $userfield = ? AND status = ?";
	$debugQuery = "SELECT * FROM $table WHERE BINARY $userfield = '$username' AND status = '$status'";

    $stmt = $conn->prepare($query);
    
if ($stmt) {
    $stmt->bind_param("ss", $username, $status);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Handle prepare error
    die("Prepare failed: " . $conn->error);
}

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $storedHash = $row[$passfield];

        // Use password_verify only for 'client' where password is hashed
        if (
            ($logintype === 'client' && password_verify($password, $storedHash)) || //password_verify($password, $storedHash)
            ($logintype !== 'client' && $password === $storedHash) // direct compare for NIC
        ) {
            $_SESSION['user_id'] = $row[$logintype === 'client' ? 'stadium_id' : 'cus_id'];
            $_SESSION['role'] = $logintype;
            
            echo json_encode([
                'success' => true,
                'message' => 'Login successful'
            ]);
        } else {
            
            echo json_encode([
                'success' => false,
                'message' => 'Invalid credentials'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'User not found or inactive',
				'query'=> $debugQuery
        ]);
    }
}



     /*function login($data, $conn) {
     
        session_start();
        $username = $data['username'];
        //$password = $data['password'];
        $password = password_hash($data['password'] ?? '', PASSWORD_DEFAULT);
		$status = 'active';
        $logintype = strtolower($data['logintype']);
		var_dump($password);

        $table = $logintype === 'client' ? 'stadium' : 'customer';
        $userfield = $logintype === 'client' ? 'username' : 'phone_number';
        $passfield = $logintype === 'client' ? 'password' : 'NIC';
        // Prepare the query to check for the user's credentials
        $query = "SELECT * FROM $table WHERE BINARY $userfield = ? AND BINARY $passfield = ? AND status = $status";
        
        // Prepare statement to prevent SQL injection
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $username, $password); // 'ss' means both username and password are strings
        $stmt->execute();
        
        // Get the result
        $result = $stmt->get_result();
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $_SESSION['user_id'] = $row[$logintype === 'client' ? 'stadium_id' : 'cus_id'];
            $_SESSION['role'] = $logintype;
            // Return JSON with redirect URL
            echo json_encode([
                'success' => true,
                'message' => 'Login successful'/* ,
                'role' => $logintype,
                'redirect' => $logintype === 'customer' ? home_url('/customer_dashboard.php') : home_url('/client_dashboard.php') *-/
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid credentials'
            ]);
        }
        
        
    }*/
    
function createBooking($data,$conn){
$cus_id = $data["cus_id"];
$stadium_name = $data["stadium_name"];
$pitch_name = $data["pitch_name"];
$booking_date = $data["booking_date"];
$timeslot = $data["timeslot"];
$rate_applied = $data["rate_applied"];
$status = $data["status"];
date_default_timezone_set('Asia/Colombo');

// Current date and time
$currentDateTime = date('Y-m-d H:i:s');
$query = "SELECT p.pitch_id 
          FROM pitch p
          JOIN court c ON p.court_id = c.court_id
          JOIN stadium s ON c.stadium_id = s.stadium_id
          WHERE s.name = ? AND p.pitch_name = ?
          LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $stadium_name, $pitch_name);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $pitch_id = $row["pitch_id"];

    // Insert booking
    $insertQuery = "INSERT INTO booking (cus_id, pitch_id, booking_date, timeslot, rate_applied, status, created_at) 
                    VALUES (?, ?, ?, ?, ?, ?,?)";
    $stmt_2 = $conn->prepare($insertQuery);
    if (!$stmt_2) {
        die("Prepare failed: " . $conn->error);
        
    }
    
    $stmt_2->bind_param("iissdss", $cus_id, $pitch_id, $booking_date, $timeslot, $rate_applied, $status,$currentDateTime);

    if ($stmt_2->execute()) {
        $book_id = $conn->insert_id;
        
        echo json_encode(["status" => "Success", "message" => "Booking added successfully","Booking_id" => $book_id]);
    } else {
        echo json_encode(["status" => "Failer", "message" => "Failed to insert booking"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Pitch not found"]);
}
}

function getCustomerDetails($cus_id){
    global $conn;
    $query = $conn->prepare("
    SELECT *
    FROM customer
    WHERE cus_id = ?
");

if ($query === false) {
    die('Error preparing query: ' . $conn->error);
}

$query->bind_param("i", $cus_id);
$query->execute();
$result = $query->get_result();

$cus_name = '';
while ($row = $result->fetch_assoc()) {
    $cus_name = $row;
}
return $cus_name;
}

/* function update_user($formData,$conn) {
    
$upload_folder = get_template_directory() . '/uploads/';
$upload_url_base = get_template_directory_uri() . '/uploads/';

    $fullname = $formData['fullname'];
    $email = $formData['email'];
    $phone = $formData['phone'];
    $address = $formData['address'];
    $user_id = $formData['user_id'];
    $NIC = $formData['NIC'];
    
    if (isset($formData['image'])) {
        
    $tmp_name = $_FILES['image']['tmp_name'];
    $original_name = basename($_FILES['image']['name']);
    $file_type = mime_content_type($tmp_name);

    if (strpos($file_type, 'image') !== 0) {
        echo "<div class='alert alert-danger'>Only image files are allowed.</div>";
    } else {
        $unique_name = time() . '_' . $original_name;
        $server_path = $upload_folder . $unique_name;
        $public_url = $upload_url_base . $unique_name;
        move_uploaded_file($tmp_name, $server_path);
        $sql = "UPDATE customer SET full_name='$fullname', email='$email', phone_number='$phone', address='$address',NIC='$NIC',image_path='$public_url' WHERE cus_id='$user_id'";
    }
}
else{
    $sql = "UPDATE customer SET full_name='$fullname', email='$email', phone_number='$phone', address='$address',NIC='$NIC' WHERE cus_id='$user_id'";
}

    

    if ($conn->query($sql) === TRUE) {
      echo "Details updated successfully.";
    } else {
      echo "Error updating details: " . $conn->error;
    }

} */




function upload_images($data){
    $id = $data['pitch_id'];

foreach ($_FILES['image']['tmp_name'] as $key => $tmp_name) {
        $imageName = $_FILES['image']['name'][$key];
        $imageTmp = $_FILES['image']['tmp_name'][$key];
        $imageSize = $_FILES['image']['size'][$key];
        $imageType = $_FILES['image']['type'][$key];

        // Optional: Validate file type (e.g., allow only jpg, png)
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!in_array($imageType, $allowedTypes)) {
            echo "Unsupported file type: $imageType";
            continue;
        }

        // Optional: Check file size (e.g., max 5MB)
        if ($imageSize > 5 * 1024 * 1024) {
            echo "File too large: $imageName";
            continue;
        }

        // Move file to upload folder
        $uploadDir = 'uploads/pitch_images/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $newFileName = uniqid('img_') . '_' . basename($imageName);
        $destination = $uploadDir . $newFileName;

        if (move_uploaded_file($imageTmp, $destination)) {
            // Insert into DB
            savePitchImage($id, $destination);
        } else {
            echo "Upload failed: $imageName";
        }
    }

}

function savePitchImage($pitchId, $imagePath) {
    global $conn;
    $imagePath = mysqli_real_escape_string($conn, $imagePath);

    $sql = "INSERT INTO pitch_images (pitch_id, image_url) VALUES ('$pitchId', '$imagePath')";
    mysqli_query($conn, $sql);
}

function update_user($formData, $conn) {
    $upload_folder = __DIR__ . 'uploads/';
    $upload_url_base = 'uploads/';

    // Sanitize user input
    $fullname = $formData['fullname'];
    $email = $formData['email'];
    $phone = $formData['phone'];
    $address = $formData['address'];
    $user_id = (int)$formData['user_id'];
    $NIC = $formData['NIC'];
    $image_path = null;
    // Handle image if uploaded
    if (isset($_FILES['dp_image']) && $_FILES['dp_image']['error'] === UPLOAD_ERR_OK) {
  // Ensure upload folder exists
  if (!file_exists($upload_folder)) {
    mkdir($upload_folder, 0755, true);
}
        $tmp_name = $_FILES['dp_image']['tmp_name'];
        $original_name = basename($_FILES['dp_image']['name']);
        $file_type = mime_content_type($tmp_name);

        if (strpos($file_type, 'image') === 0) {
            $unique_name = time() . '_' . $original_name;
            $server_path = $upload_folder . $unique_name;
            $public_url = $upload_url_base . $unique_name;

            if (move_uploaded_file($tmp_name, $server_path)) {
                $image_path = $public_url;
            } else {
                echo "<div class='alert alert-danger'>Failed to upload image.</div>";
                return;
            }
        } else {
            echo "<div class='alert alert-danger'>Only image files are allowed.</div>";
            return;
        }
    }

    // Build SQL query
    if ($image_path) {
        $sql = "UPDATE customer 
                SET full_name=?, email=?, phone_number=?, address=?, NIC=?, image_path=? 
                WHERE cus_id=?";
                print_r($sql);
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssi", $fullname, $email, $phone, $address, $NIC, $image_path, $user_id);
    } else {
        $sql = "UPDATE customer 
                SET full_name=?, email=?, phone_number=?, address=?, NIC=? 
                WHERE cus_id=?";
                print_r($sql);
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $fullname, $email, $phone, $address, $NIC, $user_id);
    }

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Details updated successfully.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error updating details: " . $stmt->error . "</div>";
    }

    $stmt->close();
}



function update_client($formData,$conn) {
    $upload_folder = __DIR__. 'uploads/';
    $upload_url_base = 'uploads/';
    
    $fullname = $formData['fullname'];
    $address = $formData['address'];
    $city = $formData['city'];
    $email = $formData['email'];
    $phone = $formData['phone'];
    $user_id = $formData['user_id'];
    
    $image_path = null;
    // Handle image if uploaded
    if (isset($_FILES['dp_image']) && $_FILES['dp_image']['error'] === UPLOAD_ERR_OK) {
  // Ensure upload folder exists
  if (!file_exists($upload_folder)) {
    mkdir($upload_folder, 0755, true);
}
        $tmp_name = $_FILES['dp_image']['tmp_name'];
        $original_name = basename($_FILES['dp_image']['name']);
        $file_type = mime_content_type($tmp_name);

        if (strpos($file_type, 'image') === 0) {
            $unique_name = time() . '_' . $original_name;
            $server_path = $upload_folder . $unique_name;
            $public_url = $upload_url_base . $unique_name;

            if (move_uploaded_file($tmp_name, $server_path)) {
                $image_path = $public_url;
            } else {
                echo "<div class='alert alert-danger'>Failed to upload image.</div>";
                return;
            }
        } else {
            echo "<div class='alert alert-danger'>Only image files are allowed.</div>";
            return;
        }
    }

    // Build SQL query
    if ($image_path) {
        $sql = "UPDATE stadium SET name='$fullname', email_id='$email', contact_info='$phone', address='$address',location='$city',image_path='$image_path' WHERE stadium_id='$user_id'";


    } else {
        $sql = "UPDATE stadium SET name='$fullname', email_id='$email', contact_info='$phone', address='$address',location='$city' WHERE stadium_id='$user_id'";
        
    }
    if ($conn->query($sql) === TRUE) {
    
        echo "<div class='alert alert-success'>Details updated successfully.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error updating details: " . $conn->error . "</div>";
    }
    $conn->close();
}
   



  /*   $sql = "UPDATE stadium SET name='$fullname', email_id='$email', contact_info='$phone', address='$address',location='$city' WHERE stadium_id='$user_id'";

    if ($conn->query($sql) === TRUE) {
      echo "Details updated successfully.";
    } else {
      echo "Error updating details: " . $conn->error;
    } */



function getClientName($cus_id){
    global $conn;
    $query = $conn->prepare("
    SELECT 
    s.*,
    ROUND(AVG(r.rating), 1) AS average_rating,
    COUNT(r.review_id) AS review_count
FROM stadium AS s
JOIN pitch AS p ON p.stadium_id = s.stadium_id
JOIN reviews AS r ON r.pitch_id = p.pitch_id
WHERE s.stadium_id = ?
GROUP BY s.stadium_id, s.name;
");

if ($query === false) {
    die('Error preparing query: ' . $conn->error);
}

$query->bind_param("i", $cus_id);
$query->execute();
$result = $query->get_result();

$cus_name = '';
while ($row = $result->fetch_assoc()) {
    $cus_name = $row;
}
return $cus_name;
}


function getClientBooking($client_id) {
    global $conn;

    // Initialize return structure
    $response = [
        "success" => false,
        "total_bookings" => 0,
        "top_customer" => null,
        "error" => null
    ];

    // Get total bookings
    $total_query = $conn->prepare("
        SELECT COUNT(*) AS total
        FROM booking
        WHERE pitch_id IN (
            SELECT p.pitch_id 
            FROM pitch p
            INNER JOIN court c ON p.court_id = c.court_id
            INNER JOIN stadium s ON c.stadium_id = s.stadium_id
            WHERE s.stadium_id = ?
        )
    ");

    if (!$total_query) {
        $response["error"] = "Prepare failed: " . $conn->error;
        return $response;
    }

    $total_query->bind_param("i", $client_id);
    $total_query->execute();
    $total_result = $total_query->get_result();
    $total = $total_result->fetch_assoc()['total'] ?? 0;
    $total_query->close();
// Get total Revenue
    $total_sum_query = $conn->prepare("
        SELECT sum(rate_applied) AS total_rev
        FROM booking
        WHERE pitch_id IN (
            SELECT p.pitch_id 
            FROM pitch p
            INNER JOIN court c ON p.court_id = c.court_id
            INNER JOIN stadium s ON c.stadium_id = s.stadium_id
            WHERE s.stadium_id = ?
        )
    ");

    if (!$total_sum_query) {
        $response["error"] = "Prepare failed: " . $conn->error;
        return $response;
    }

    $total_sum_query->bind_param("i", $client_id);
    $total_sum_query->execute();
    $total_sum_result = $total_sum_query->get_result();
    $total_sum = $total_sum_result->fetch_assoc()['total_rev'] ?? 0;
    $total_sum_query->close();

// Get upcoming bookings
    $upcoming_query = $conn->prepare("
        SELECT COUNT(*) AS upcoming
        FROM booking
        WHERE pitch_id IN (
            SELECT p.pitch_id 
            FROM pitch p
            INNER JOIN court c ON p.court_id = c.court_id
            INNER JOIN stadium s ON c.stadium_id = s.stadium_id
            WHERE s.stadium_id = ?
        ) and booking_date  >= CURRENT_DATE
    ");

    if (!$upcoming_query) {
        $response["error"] = "Prepare failed: " . $conn->error;
        return $response;
    }

    $upcoming_query->bind_param("i", $client_id);
    $upcoming_query->execute();
    $upcoming_result = $upcoming_query->get_result();
    $upcoming = $upcoming_result->fetch_assoc()['upcoming'] ?? 0;
    $upcoming_query->close();


    // Get top customer
    $top_query = $conn->prepare("
        SELECT b.cus_id, cust.full_name, COUNT(*) AS count
        FROM booking b
        INNER JOIN customer cust ON b.cus_id = cust.cus_id
        INNER JOIN pitch p ON b.pitch_id = p.pitch_id
        INNER JOIN court c ON p.court_id = c.court_id
        INNER JOIN stadium s ON c.stadium_id = s.stadium_id
        WHERE s.stadium_id = ?
        GROUP BY b.cus_id
        ORDER BY count DESC
        LIMIT 1
    ");

    if (!$top_query) {
        $response["error"] = "Prepare failed: " . $conn->error;
        return $response;
    }

    $top_query->bind_param("i", $client_id);
    $top_query->execute();
    $top_result = $top_query->get_result();
    $top = $top_result->fetch_assoc() ?? null;
    $top_query->close();

    $S_total_query = $conn->prepare("
 SELECT 
        cu.full_name,
        COUNT(b.booking_id) AS total
    FROM booking b
    INNER JOIN customer cu ON b.cus_id = cu.cus_id
    WHERE b.pitch_id IN (
        SELECT p.pitch_id 
        FROM pitch p
        INNER JOIN court c ON p.court_id = c.court_id
        INNER JOIN stadium s ON c.stadium_id = s.stadium_id
        WHERE s.stadium_id = ?
    )
    GROUP BY cu.cus_id
    ORDER BY total ASC
");

if (!$S_total_query) {
    $response["error"] = "Prepare failed: " . $conn->error;
    return $response;
}

$S_total_query->bind_param("i", $client_id);
$S_total_query->execute();
$S_total_result = $S_total_query->get_result();

$customer_totals = [];
while ($row = $S_total_result->fetch_assoc()) {
    $customer_totals[] = $row;
}

$S_total_query->close();

// You can include this in your response if needed


    $response["success"] = true;
    $response["total_bookings"] = $total;
    $response["top_customer"] = $top;
    $response["total_Revenue"]=$total_sum;
    $response["upcoming"] = $upcoming;
    $response["customer_totals"] = $customer_totals;




    return $response;
}



function getBookingCount($cus_id): array {
    global $conn;
    $query = $conn->prepare("
        SELECT 
            b.booking_id,
            p.pitch_name,
            b.created_at,
            b.timeslot,
            b.booking_date,
            b.rate_applied,
            b.status
        FROM booking b
        JOIN pitch p ON b.pitch_id = p.pitch_id
        WHERE b.cus_id = ?
        ORDER BY b.created_at DESC
    ");

    if ($query === false) {
        die('Error preparing query: ' . $conn->error);
    }

    $query->bind_param("i", $cus_id);
    $query->execute();
    $result = $query->get_result();

    $bookings = [];
    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }

    return $bookings;
}





function getBookingDetails($cus_id): array {
    global $conn;
    $query = $conn->prepare("
        SELECT      b.booking_id,
            p.pitch_name,
            c.court_type,
            b.created_at,
            b.timeslot,
            b.booking_date,
            b.rate_applied,
            b.status, 
            cu.full_name
        FROM booking b
JOIN customer cu ON b.cus_id = cu.cus_id
        JOIN pitch p ON b.pitch_id = p.pitch_id
        Join court c ON p.court_id = c.court_id
        Join stadium s ON s.stadium_id = c.court_id
        WHERE s.stadium_id = ?
        ORDER BY b.booking_date
    ");

    if ($query === false) {
        die('Error preparing query: ' . $conn->error);
    }

    $query->bind_param("i", $cus_id);
    $query->execute();
    $result = $query->get_result();

    $bookings = [];
    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }

    return $bookings;
}



// Function to fetch booking breakdown by pitch
function getBookingBreakdown($cus_id) {
    global $conn;
    $query = $conn->prepare("
    SELECT p.pitch_name, b.status, COUNT(*) AS count
    FROM booking b
    JOIN pitch p ON b.pitch_id = p.pitch_id
    WHERE b.cus_id = ?
    GROUP BY p.pitch_name, b.status
");
$query->bind_param("i", $cus_id);
$query->execute();
$result = $query->get_result();
$labels = [];
$data = [];
$status = [];
while ($row = $result->fetch_assoc()) {
    $labels[] = $row['pitch_name'];
    $data[] = $row['count'];
    $status[] = $row['status'];
}
return ['labels' => $labels, 'data' => $data, 'status' => $status];
}



function getStaduimBookingdata($cus_id) {
    global $conn;
    $query = $conn->prepare("
SELECT p.pitch_name, c.court_type, b.status, COUNT(*) AS count
    FROM booking b
    JOIN pitch p ON b.pitch_id = p.pitch_id
    JOIN court c ON p.court_id = c.court_id
    JOIN stadium s ON c.stadium_id = s.stadium_id
    WHERE s.stadium_id = ?
    GROUP BY p.pitch_name, c.court_type, b.status
");
$query->bind_param("i", $cus_id);
$query->execute();
$result = $query->get_result();
$labels = [];
$cType = [];
$data = [];
$status = [];
while ($row = $result->fetch_assoc()) {
    $labels[] = $row['pitch_name'];
    $cType[] = $row['court_type'];
    $data[] = $row['count'];
    $status[] = $row['status'];
}
return ['labels' => $labels, 'data' => $data, 'status' => $status, 'courtType' => $cType];
}

function getLocationDetails() {
global $conn;
$sql = "SELECT province, district, city FROM location ORDER BY province, district, city";
$result = $conn->query($sql);

// Organize data into groups
$locations = [];
while ($row = $result->fetch_assoc()) {
    $groupLabel = $row['province'] . ' - ' . $row['district'];
    $locations[$groupLabel][] = $row['city'];
}
return $locations;

}

function getPitchDetails($client_id){
    global $conn;

    $sql = "SELECT p.image_path,p.pitch_id, p.pitch_name, p.opening_time, p.closing_time, c.court_type, pr.offpeak_start_time, pr.offpeak_end_time, pr.peak_rate, pr.offpeak_rate, pr.weekend_offpeak_rate, pr.holiday_offpeak_rate, pr.weekend_peak_rate, pr.holiday_peak_rate FROM stadium s JOIN court c ON s.stadium_id = c.stadium_id JOIN pitch p ON c.court_id = p.court_id JOIN pricing pr ON p.pitch_id = pr.pitch_id WHERE s.stadium_id = $client_id";
$result = $conn->query($sql);

$pitchDetails = [];
while ($row = $result->fetch_assoc()) {
    $pitchDetails[] = $row;
}
return $pitchDetails;
}



/*function getCourttypes() {
global $conn;
$courts = $conn->query("SELECT court_id, court_type FROM court");
return $courts;

}*/

function getCourttypes() {
    global $conn;
    $sql = "SELECT gameid, gamename FROM gametype";
    $result = $conn->query($sql);

    $courts = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $courts[] = $row;
        }
    }

    return $courts;
}

function getGroundDetails($id){
    global $conn;
    $sql = "Select * from pitch as p Inner JOIN pricing pr on pr.pitch_id = p.pitch_id inner join gametype g on g.gameid = p.gametype_id where stadium_id = $id";
$result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    

    return  $data;


}



function create_pitch($data,$conn) {
    $upload_folder = __DIR__  . 'uploads/';
    $upload_url_base = 'uploads/';
    $pitch_name     = $data['pitch_name'];
    $opening_time   = $data['opening_time'];
    $closing_time   = $data['closing_time'];
    $court_id       = $data['court_id'];// Or dynamic
    $stadium_id      = $data['user_id'];
    $game_id       = $data['court_id'];
    $weekend_open = $data['weekend_open_time'];
    $weekend_close = $data['weekend_close_time'];
    $holidayPeakrate = $data['holiday_peak_rate'];
    $holidayOffPeakRate = $data['holiday_offpeak_rate'];
    $peak_start = $data['off_peak_start'];
    $peak_end = $data['off_peak_end'];
    $peak_rate = $data['peak_rate'];
    $offpeak_rate = $data['off_peak_rate'];
    $weekPeakRate = $data['weekend_peak_rate'];
    $weekOffPeakRate = $data['weekend_offPeak_rate'];
    $taglines =$data['tagValues'];
   

    $image_path = null;
    
$base64Image = $_POST['cropped_image_data'] ?? '';
if (!$base64Image) {
    exit("No image data received.");
}

// Extract and decode the base64 image
$imageParts = explode(";base64,", $base64Image);
if (count($imageParts) !== 2) {
    exit("Invalid image format.");
}

$imageType = explode("image/", $imageParts[0])[1]; // e.g., jpeg
$imageBase64 = base64_decode($imageParts[1]);

// Ensure upload folder exists
if (!file_exists($upload_folder)) {
    mkdir($upload_folder, 0755, true);
}

// Generate unique filename and save path
$unique_name = time() . '_' . uniqid('img_') . '.' . $imageType;
$server_path = $upload_folder . $unique_name;
$public_url = $upload_url_base . $unique_name;

// Save the image to the server
if (file_put_contents($server_path, $imageBase64)) {
    $image_path = $public_url;
} else {
    echo "<div class='alert alert-danger'>Failed to save cropped image.</div>";
    return;
}

 // Build SQL query
    if ($image_path)
     {$stmt = $conn->prepare("INSERT INTO pitch (pitch_name, opening_time, closing_time, court_id,image_path,WeekEnd_opentime,WeekEnd_closetime,stadium_id,gametype_id,Amenities) VALUES (?,?, ?, ?, ?, ?,?,?,?,?)");
        $stmt->bind_param("sssisssiis", $pitch_name, $opening_time, $closing_time, $court_id,$image_path,$weekend_open,$weekend_close,$stadium_id,$game_id,$taglines);
        
    } else {
   
    $stmt = $conn->prepare("INSERT INTO pitch (pitch_name, opening_time, closing_time, court_id,WeekEnd_opentime,WeekEnd_closetime,stadium_id,gametype_id,Amenities) VALUES (?,?, ?, ?, ?,?,?,?,?)");
    $stmt->bind_param("sssissiis", $pitch_name, $opening_time, $closing_time, $court_id,$weekend_open,$weekend_close,$stadium_id,$game_id,$taglines);
    }
    if ($stmt->execute()) {
        $pitch_id = $conn->insert_id;

        $stmt2 = $conn->prepare("INSERT INTO pricing (pitch_id, offpeak_start_time, offpeak_end_time, peak_rate, offpeak_rate, weekend_peak_rate, weekend_offpeak_rate, holiday_peak_rate, holiday_offpeak_rate
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt2->bind_param(
            "issssdddd",
            $pitch_id,
            $peak_start,
            $peak_end,
            $peak_rate,
            $offpeak_rate,
            $weekPeakRate,
            $weekOffPeakRate,
            $holidayPeakrate,            
            $holidayOffPeakRate
        );

        if ($stmt2->execute()) {
            echo "Pitch created successfully!";
        } else {
            echo "Error in pricing insert: " . $stmt2->error;
        }
    } else {
        echo "Error in pitch insert: " . $stmt->error;
    }

}



/* function update_pitch($data,$conn) {
    $upload_folder = get_template_directory() . '/uploads/';
    $upload_url_base = get_template_directory_uri() . '/uploads/';
    $pitch_name     = $data['pitch_name'];
    $opening_time   = $data['opening_time'];
    $closing_time   = $data['closing_time'];
    $court_id = $data['court_id'];
    $pitch_id       = $data['pitch_id'];// Or dynamic

    $off_peak_start =   $data['off_peak_start'];
    $off_peak_end   =   $data['off_peak_end'];
    $peak_rate      =   $data['peak_rate'];
    $off_peak_rate  =   $data['off_peak_rate'];
    $weekend_peak_rate      =   $data['weekend_peak_rate'];
    $weekend_offpeak_rate   =   $data['weekend_off_Peak_rate'];
    $holiday_peak_rate      =   $data['holiday_peak_rate'];
    $holiday_offpeak_rate   =   $data['holiday_off_peak_rate'];

    $image_path = null;
    // Handle image if uploaded
    if (isset($_FILES['dp_image']) && $_FILES['dp_image']['error'] === UPLOAD_ERR_OK) {
  // Ensure upload folder exists
  if (!file_exists($upload_folder)) {
    mkdir($upload_folder, 0755, true);
}
        $tmp_name = $_FILES['dp_image']['tmp_name'];
        $original_name = basename($_FILES['dp_image']['name']);
        $file_type = mime_content_type($tmp_name);

        if (strpos($file_type, 'image') === 0) {
            $unique_name = time() . '_' . $original_name;
            $server_path = $upload_folder . $unique_name;
            $public_url = $upload_url_base . $unique_name;

            if (move_uploaded_file($tmp_name, $server_path)) {
                $image_path = $public_url;
            } else {
                echo "<div class='alert alert-danger'>Failed to upload image.</div>";
                return;
            }
        } else {
            echo "<div class='alert alert-danger'>Only image files are allowed.</div>";
            return;
        }
    }
 // Build SQL query
    if ($image_path)
     {
        $sql = "UPDATE pitch SET pitch_name = '$pitch_name', opening_time ='$opening_time', closing_time='$closing_time', court_id='$court_id',image_path='$image_path' where pitch_id='$pitch_id'";
           print_r(`image sql: $sql`);
    } else {
   
     $sql = "UPDATE pitch SET pitch_name = '$pitch_name', opening_time ='$opening_time', closing_time='$closing_time', court_id='$court_id' where pitch_id='$pitch_id'";
     print_r(`non image sql: $sql`);
    }
    
    if ($conn->prepare($sql) === true) {

        $price_sql = "UPDATE pricing SET pitch_id='$pitch_id', offpeak_start_time='$off_peak_start', offpeak_end_time='$off_peak_end', 
                peak_rate='$peak_rate', offpeak_rate='$off_peak_rate', weekend_peak_rate='$weekend_peak_rate', weekend_offpeak_rate='$weekend_offpeak_rate', holiday_peak_rate='$holiday_peak_rate', holiday_offpeak_rate='$holiday_offpeak_rate'";

        if ($conn->prepare($price_sql) === true) {
            echo "Pitch Updated successfully!";
        } else {
            print_r(`Pricing error: $price_sql `);
            echo "Error in pricing insert: " . $conn->error;
        }
    } else {
        print_r(`error sql: $sql`);
        echo "Error in pitch update: " . $conn->error;
    }

} */


function update_pitch($data, $conn) {
    $upload_folder = __DIR__ . 'uploads/';
    $upload_url_base = 'uploads/';
    
    $pitch_id = $conn->real_escape_string($data['pitch_id']);
    $pitch_name = $conn->real_escape_string($data['pitch_name']);
    $opening_time = $conn->real_escape_string($data['opening_time']);
    $closing_time = $conn->real_escape_string($data['closing_time']);
    $court_id = $conn->real_escape_string($data['court_id']);

    $off_peak_start = $conn->real_escape_string($data['off_peak_start']);
    $off_peak_end = $conn->real_escape_string($data['off_peak_end']);
    $peak_rate = $conn->real_escape_string($data['peak_rate']);
    $off_peak_rate = $conn->real_escape_string($data['off_peak_rate']);
    $weekend_peak_rate = $conn->real_escape_string($data['weekend_peak_rate']);
    $weekend_offpeak_rate = $conn->real_escape_string($data['weekend_off_Peak_rate']);
    $holiday_peak_rate = $conn->real_escape_string($data['holiday_peak_rate']);
    $holiday_offpeak_rate = $conn->real_escape_string($data['holiday_off_peak_rate']);

    $image_path = null;

    // Handle image upload
    if (isset($_FILES['dp_image']) && $_FILES['dp_image']['error'] === UPLOAD_ERR_OK) {
        if (!file_exists($upload_folder)) {
            mkdir($upload_folder, 0755, true);
        }

        $tmp_name = $_FILES['dp_image']['tmp_name'];
        $original_name = basename($_FILES['dp_image']['name']);
        $file_type = mime_content_type($tmp_name);

        if (strpos($file_type, 'image') === 0) {
            $unique_name = time() . '_' . $original_name;
            $server_path = $upload_folder . $unique_name;
            $public_url = $upload_url_base . $unique_name;

            if (move_uploaded_file($tmp_name, $server_path)) {
                $image_path = $public_url;
            } else {
                echo "<div class='alert alert-danger'>Failed to upload image.</div>";
                return;
            }
        } else {
            echo "<div class='alert alert-danger'>Only image files are allowed.</div>";
            return;
        }
    }

    // Prepare SQL
    if ($image_path) {
        $sql = "UPDATE pitch 
                SET pitch_name = '$pitch_name', opening_time = '$opening_time', closing_time = '$closing_time', 
                    court_id = '$court_id', image_path = '$image_path' 
                WHERE pitch_id = '$pitch_id'";
    } else {
        $sql = "UPDATE pitch 
                SET pitch_name = '$pitch_name', opening_time = '$opening_time', closing_time = '$closing_time', 
                    court_id = '$court_id' 
                WHERE pitch_id = '$pitch_id'";
    }

    if ($conn->query($sql)) {
        // Assuming pricing table has pitch_id as unique key
        $price_sql = "UPDATE pricing 
                      SET offpeak_start_time = '$off_peak_start', offpeak_end_time = '$off_peak_end',
                          peak_rate = '$peak_rate', offpeak_rate = '$off_peak_rate', 
                          weekend_peak_rate = '$weekend_peak_rate', weekend_offpeak_rate = '$weekend_offpeak_rate',
                          holiday_peak_rate = '$holiday_peak_rate', holiday_offpeak_rate = '$holiday_offpeak_rate'
                      WHERE pitch_id = '$pitch_id'";

        if ($conn->query($price_sql)) {
            echo "Pitch updated successfully!";
        } else {
            echo "Error in pricing update: " . $conn->error;
        }
    } else {
        echo "Error in pitch update: " . $conn->error;
    }
}

?>