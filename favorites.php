<?php

include 'header.php';  //header
$getFavoritePitch = getFavoritePitch($user_id);

?>
<section>
       <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-3">Your Favorite</h2>
                <div class="w-20 h-1 bg-teal-600 mx-auto"></div>
                <p class="text-gray-600 mt-4 max-w-2xl mx-auto">Discover your favorite futsal venues</p>
            </div>
            <div class="overflow-x-auto no-scrollbar place-items-center">
            <div class="flex gap-8 min-w-max">
               
    <?php foreach($getFavoritePitch as  $row): ?>
        
<div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100 ground-card transition-all duration-300">
                    <div class="relative">
                        <?php

if($row['pitch_image']){
$image = $row['pitch_image'];
}
else{
    $image = 'images/futsal1.jpg';
}
$imageUrl = strpos($image, '?') !== false 
    ? $image . '&auto=format&fit=crop&w=80&q=80' 
    : $image . '?auto=format&fit=crop&w=80&q=80';
?>
                        <img src="<?= htmlspecialchars($imageUrl) ?>" alt="Futsal Ground" class="w-full h-64 object-cover md:object-top ">
                        <div class="absolute top-4 right-4 price-tag text-white px-4 py-1 rounded-full text-sm font-bold">
                            Rs: <?= htmlspecialchars($row['Peak_rate']) ?>/= </div> <div class="absolute top-4 left-4 price-tag text-white px-4 py-1 rounded-full text-sm font-bold">Rs: <?= htmlspecialchars($row['Offpeak_rate']) ?>/=
                        </div>
                        <div class="absolute bottom-4 left-4 bg-white text-teal-600 px-3 py-1 rounded-full text-xs font-bold flex items-center">
                            <i class="fas fa-star mr-1"></i> 
                            <?php 
                            if ($row['average_rating']){
                                echo $row['average_rating']; 
                            }
                            else{
                                echo '0';
                            }
                            ?>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-3 flex-col sm:flex-row sm:items-center sm:gap-4">
     <h3 class="text-xl font-bold text-gray-800"><?= htmlspecialchars($row['Stadium_name']) ?><br>
        <span class="text-sm font-bold text-gray-800 mt-1">[<?= htmlspecialchars($row['Court_Name']) ?>]</span></h3>
   
    <div class="flex items-center text-gray-500 mt-2 sm:mt-0">
        <i class="fas fa-map-marker-alt mr-1 text-teal-600"></i>
        <span class="text-sm" id="distance-<?php echo $row['id']; ?>"></span>
    </div>
</div>
                     
                        <div class="flex items-center text-gray-600 mb-4">
                            <i class="fas fa-map-marker-alt mr-2 text-teal-600"></i>
                            <span class="text-sm"><?= htmlspecialchars($row['Stadium_address']) ?></span>
                        </div>
                        <div class="flex flex-wrap gap-2 mb-5">
                            <?php
                            if ($row['tagline']){
$courts = explode(',', $row['tagline']);
foreach ($courts as $tag) {
    echo '<span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-xs font-medium mr-1">'
        . htmlspecialchars(trim($tag)) .
        '</span>';
}}
else{
    echo '<span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-xs font-medium mr-1"> </span>';
}
?>
                        </div>
                       <div class="flex justify-between">
<button class="review-button px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all flex items-center" onclick="openPopup(this)" pitch-id-data="<?= htmlspecialchars($row['id']) ?>" type="button">
                                <i class="far fa-star mr-2 star-icon"></i><span class="sm:block hidden"> Review</span>
                            </button>

<?php
                            if($user_id){?>
<button class="favor-button px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all flex items-center" onclick="markFavor(this)" pitch-id-data="<?= htmlspecialchars($row['id']) ?>" type="button">
                                <i class="far fa-heart mr-2 heart-icon"></i><span class="sm:block hidden"> Favorite</span>
                            </button>
                            <?php
}?>

                            <form id="redirectForm" action="FutsalDetailPage.php" method="POST">
  <input type="hidden" name="value" id="valueInput" value="<?= htmlspecialchars($row['id']) ?>">
  <button class="px-4 py-2 btn-primary text-white rounded-lg hover:shadow-lg transition-all" onclick="submitForm(<?= htmlspecialchars($row['id']) ?>)" type="button">
                                Book Now
                            </button>
</form>
                            
                        </div>
                    </div>
                </div>
                    <?php endforeach; ?>
                    
</div>

            </div>
            
            <div class="text-center mt-12">
                <a href="#" class="inline-block px-8 py-3 border-2 border-teal-600 text-teal-600 font-medium rounded-lg hover:bg-teal-600 hover:text-white transition-all">
                    View All Grounds <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
</section>