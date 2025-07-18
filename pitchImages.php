<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include DB and functions explicitly (don't assume it's inherited)
require_once 'futsal_db.php';
$client_id = $_SESSION['user_id'];
if ($client_id) {
    $imagesPitches = getImagesOfPitch($client_id); // Pass the parameter to the function
} else {
    $imagesPitches = [];
}
/* $pitchId = isset($_GET['pitch_id']) ? (int)$_GET['pitch_id'] : null;

if (!isset($pitch_Id)) {
    echo "Pitch ID is not set.";
    exit;
} */



if (isset($pitch_id)) {
    $pitch_Id = (int)$pitch_id;
} elseif (isset($_GET['pitch_id']) && is_numeric($_GET['pitch_id'])) {
    $pitch_Id = (int)$_GET['pitch_id'];
} else {
    echo "Pitch ID is not set or invalid.";
    exit;
}


$matchedPitch = null;
foreach ($imagesPitches as $pitch) {
    if ($pitch['pitch_id'] == $pitch_Id) {
        $matchedPitch = $pitch;
        break;
    }
}



?>
<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
<?php foreach ($matchedPitch['images'] as $img): ?>
          <div class="bg-white rounded overflow-hidden shadow">
            <input type="hidden" id="getPitchId" value="<?= $pitch_Id ?>">
            <div class="relative w-32 h-32" data-img="<?= $img['id'] ?>">
          <img src="<?= htmlspecialchars($img['url']) ?>" class="object-cover w-full h-full rounded shadow" />
          <button type="button" onclick="deleteImage(<?= $img['id'] ?>)" 
                  class="absolute top-0 right-0 text-black bg-red-600 hover:bg-red-800 hover:text-red rounded-full w-6 h-6 text-md`1   flex items-center justify-center">&times;</button>
        </div>
            <!-- <img src="<?= htmlspecialchars($img['url']) ?>" alt="" value="" class="w-full h-40 object-cover"> -->
          </div>
        <?php endforeach; ?>
        </div>
  
