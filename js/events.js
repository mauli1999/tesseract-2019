function toggleOverlay() {
  var overlay = document.getElementById('overlay');
  var specialBox =document.getElementById('specialBox');

  if(overlay.style.display == "block"){
    overlay.style.display = "none";
    specialBox.style.display = "none";
  } else{
    overlay.style.display = "block";
    specialBox.style.display = "block";
  }
}
