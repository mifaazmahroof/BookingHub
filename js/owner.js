
document.querySelectorAll('.uploadForm').forEach(form => {
  const fileInput = form.querySelector('.imageInput');
  const restext = document.getElementById('imgresponse');
  fileInput.addEventListener('change', function () {
    const formData = new FormData(form);
    if (restext) restext.innerText = "Uploading...";
    fetch('/futsal_db.php?action=upload_images', {
    method: 'POST',
    body: formData
  })
    .then(res => res.text())
    .then(result => {
      console.log(result);
      alert(result);
      if (restext) restext.innerText = result;
       refreshImages();
      // Optional: refresh images section dynamically
    })
    .catch(err => {
      console.error("Upload failed:", err);
    });
  });



    

if (restext) restext.innerText = "";

});