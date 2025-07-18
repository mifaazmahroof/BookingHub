<?php
$client_id = $_SESSION['user_id'];

if ($client_id) {
    $imagesPitches = getImagesOfPitch($client_id); // Pass the parameter to the function
} else {
    $imagesPitches = [];
}


foreach ($imagesPitches as $paths) {
	foreach ($paths['images'] as $img){
    echo '<div class="img-box">';
    echo '<img src="'. htmlspecialchars($img['url']) . '" width="100">';
    echo '<button onclick="delete_Image(' . $img['id'] . ')">Delete</button>';
    echo '</div>';}
}
?>