var canvas = document.getElementById('canvas');
var ctx = canvas.getContext("2d");

var $scale = $('#scale');
var $textOne = $('#text-1');
var $textTwo = $('#text-2');
var $download_hidden = $('#download_hidden');
var $sourceImage = $('#source-image');

var img = new Image();
// img.setAttribute('crossOrigin', 'anonymous');
var imageURL;

var x, y;

var drag = false;

var prevX = 0;
var prevY = 0;

var moveXAmount = 0;
var moveYAmount = 0;

function loadImageIntoCanvas() {

  imageURL = '/proxy.php?url=' + encodeURIComponent($sourceImage.attr('src'));

  img.onload = function() {
    x = canvas.width / 2 - img.width / 2;
    y = canvas.height / 2 - img.height / 2;

    // Always fit width
    var ratio = canvas.width / img.width;

    scale(ratio);
    $scale.val(ratio);
    refreshDownloadLink();
  };

  img.src = imageURL;
}

function writeText(text, x, y) {

  ctx.textAlign = "center";
  ctx.fillStyle = "white";
  ctx.strokeStyle = "black";
  ctx.lineWidth = 2;

  var f = 36; // Font size (in pt)
  for (; f >= 0; f -=1) {
    ctx.font = "bold " + f + "pt Impact, Charcoal, sans-serif";
    if (ctx.measureText(text).width < canvas.width - 10) {
      ctx.fillText(text, x, y);
      ctx.strokeText(text, x, y);
      break;
    }
  }
}

function writeTexts() {
  writeText($textOne.val(), canvas.width / 2, 50);
  writeText($textTwo.val(), canvas.width / 2, canvas.height - 20);
}

function scale(ratio) {
  ctx.save();
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  ctx.translate(canvas.width/2, canvas.height/2);
  ctx.scale(ratio, ratio);
  ctx.translate(-canvas.width/2, -canvas.height/2);
  ctx.drawImage(img, x+moveXAmount, y+moveYAmount);
  ctx.restore();
}

function renderMeme() {
  scale($scale.val());
  writeTexts();
  refreshDownloadLink();
}

function refreshDownloadLink() {
  var dataURL = canvas.toDataURL('image/jpeg');
  dataURL = dataURL.replace(/^data:image\/[^;]/, 'data:application/octet-stream');
  $download_hidden.attr('value', dataURL);
}

$scale.on('input', renderMeme);

$textOne.on('keyup', renderMeme);
$textTwo.on('keyup', renderMeme);

$(canvas).mousedown(function(){
  drag = true;

  prevX=0;
  prevY=0;

  $(canvas).addClass("drag");
});

$(canvas).mouseup(function(){
  drag = false;

  prevX=0;
  prevY=0;

  $(canvas).removeClass("drag");
});

$(window).mousemove(function(event) {
  if(drag){

      if( prevX>0 || prevY>0){
        moveXAmount += event.pageX - prevX;
        moveYAmount += event.pageY - prevY;
        renderMeme();
      }

      prevX = event.pageX;
      prevY = event.pageY;
  }
});

(function() {
  loadImageIntoCanvas();
})();
