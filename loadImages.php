<?php
require_once 'futsal_db.php'; // Update this with the actual filename

// Validate pitch_id from query string
if (!isset($_GET['pitch_id']) || !is_numeric($_GET['pitch_id'])) {
    http_response_code(400);
    echo "Invalid pitch ID";
    exit;
}

$pitch_id = (int)$_GET['pitch_id'];

// Get related stadium ID
function getStadiumIdByPitch($pitch_id) {
    global $conn;

    $sql = "
        SELECT s.stadium_id
        FROM stadium s
        JOIN court c ON c.stadium_id = s.stadium_id
        JOIN pitch p ON p.court_id = c.court_id
        WHERE p.pitch_id = $pitch_id
        LIMIT 1
    ";

    $result = mysqli_query($conn, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
        return $row['stadium_id'];
    }

    return null;
}

$stadium_id = getStadiumIdByPitch($pitch_id);

// Load image data for that stadium
$imagesPitches = getImagesOfPitch($stadium_id);

// Return only the section for the given pitch
if (!isset($imagesPitches[$pitch_id])) {
    echo "<div class='mb-10'>No images found for this pitch.</div>";
    exit;
}

$paths = $imagesPitches[$pitch_id];
?>

<div class="mb-10">
  <div class="flex justify-between items-center mb-4">
    <h2 class="text-xl font-bold capitalize text-gray-800"><?= htmlspecialchars($paths['pitch_name']) ?> Images</h2>

    <form class="uploadForm" enctype="multipart/form-data" data-pitch-id="<?= htmlspecialchars($pitch_id) ?>">
      <div class="response"></div>
      <input type="hidden" name="pitch_id" value="<?= htmlspecialchars($pitch_id) ?>">
      <label class="cursor-pointer bg-teal-600 text-white px-4 py-2 rounded-xl hover:bg-teal-700">
        + Add Images
        <input type="file" name="image[]" class="hidden imageInput" multiple accept="image/*">
      </label>
    </form>
  </div>

  <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
    <?php foreach ($paths['images'] as $img): ?>
      <div class="bg-white rounded overflow-hidden shadow">
        <div class="relative w-32 h-32" data-img="<?= $img['id'] ?>">
          <img src="<?= htmlspecialchars($img['url']) ?>" class="object-cover w-full h-full rounded shadow" />
          <button type="button" onclick="deleteImage(<?= $img['id'] ?>)"
                  class="absolute top-0 right-0 text-black bg-red-600 hover:bg-red-800 hover:text-red rounded-full w-6 h-6 text-md flex items-center justify-center">&times;</button>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
