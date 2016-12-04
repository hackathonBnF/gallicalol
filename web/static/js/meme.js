var canvas = document.getElementById('canvas');
var ctx = canvas.getContext("2d");

var $scale = $('#scale');
var $textOne = $('#text-1');
var $textTwo = $('#text-2');
var $download = $('#download');
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

/* test touch events*/
var mousePos = { x:0, y:0 };
var lastPos = mousePos;



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
  $download.attr('href', dataURL);
  $download_hidden.attr('value', dataURL);
}

$scale.on('input', renderMeme);

// $download.on('click', function(e) {
//   e.preventDefault();
//   var dataURL = canvas.toDataURL('image/jpeg');
//   dataURL = dataURL.replace(/^data:image\/[^;]/, 'data:application/octet-stream');
//   window.open(dataURL);
// });

$textOne.on('keyup', renderMeme);
$textTwo.on('keyup', renderMeme);
/*
$(canvas).mousedown(function(){
  drag = true;

  prevX=0;
  prevY=0;

  $(canvas).addClass("drag");
});
*/
canvas.addEventListener("mousedown", function (e) {
  drag = true;
  prevX=0;
  prevY=0;
  lastPos = getMousePos(canvas, e);
  $(canvas).addClass("drag");
}, false);

canvas.addEventListener("touchstart", function (e) {
  drag = true;
  mousePos = getTouchPos(canvas, e);
  var touch = e.touches[0];
  var mouseEvent = new MouseEvent("mousedown", {
    clientX: touch.clientX,
    clientY: touch.clientY
  });
  $(canvas).addClass("drag");
  //$(canvas).dispatchEvent(mouseEvent);
}, false);

canvas.addEventListener("mouseup", function (e) {
  drag = false;

  prevX=0;
  prevY=0;

  $(canvas).removeClass("drag");
});

canvas.addEventListener("touchend", function (e) {
  drag = false;

  prevX=0;
  prevY=0;
  $(canvas).removeClass("drag");
  //var mouseEvent = new MouseEvent("mouseup", {});
  //$(canvas).dispatchEvent(mouseEvent);
}, false);

$(window).mousemove(function(event) {
  if(drag){

      if(prevX>0 || prevY>0){
        moveXAmount += event.pageX - prevX;
        moveYAmount += event.pageY - prevY;
        renderMeme();
      }

      prevX = event.pageX;
      prevY = event.pageY;
  }
});

window.addEventListener("touchmove", function (e) {
  if(drag){

      if(prevX>0 || prevY>0){
        moveXAmount += e.pageX - prevX;
        moveYAmount += e.pageY - prevY;
        renderMeme();
      }

      prevX = e.pageX;
      prevY = e.pageY;
      console.log(prevX);

      var touch = e.touches[0];
      var mouseEvent = new MouseEvent("mousemove", {
        clientX: touch.clientX,
        clientY: touch.clientY
      });
  }
});
/*
canvas.addEventListener("touchmove", function (e) {
    if(drag){
    console.log(prevX);
    if( prevX>0 || prevY>0){
      console.log("yes");
      moveXAmount += e.pageX - prevX;
      moveYAmount += e.pageY - prevY;
      renderMeme();
    }

    prevX = e.pageX;
    prevY = e.pageY;
  }

  var touch = e.touches[0];
  var mouseEvent = new MouseEvent("mousemove", {
    clientX: touch.clientX,
    clientY: touch.clientY
  });

  //$(canvas).dispatchEvent(mouseEvent);
}, false);
*/
// Get the position of the mouse relative to the canvas
function getMousePos(canvasDom, mouseEvent) {
  var rect = canvasDom.getBoundingClientRect();
  return {
    x: mouseEvent.clientX - rect.left,
    y: mouseEvent.clientY - rect.top
  };
}

// Get the position of a touch relative to the canvas
function getTouchPos(canvasDom, touchEvent) {
  var rect = canvasDom.getBoundingClientRect();
  return {
    x: touchEvent.touches[0].clientX - rect.left,
    y: touchEvent.touches[0].clientY - rect.top
  };
}

// Set up touch events for mobile, etc
/*
canvas.addEventListener("touchend", function (e) {
  var mouseEvent = new MouseEvent("mouseup", {});
  canvas.dispatchEvent(mouseEvent);
}, false);
canvas.addEventListener("touchmove", function (e) {
  var touch = e.touches[0];
  var mouseEvent = new MouseEvent("mousemove", {
    clientX: touch.clientX,
    clientY: touch.clientY
  });
  canvas.dispatchEvent(mouseEvent);
}, false);
*/

// Prevent scrolling when touching the canvas

document.body.addEventListener("touchstart", function (e) {
  if (e.target == canvas) {
    e.preventDefault();
  }
}, false);
/*
document.body.addEventListener("touchend", function (e) {
  if (e.target == canvas) {
    e.preventDefault();
  }
}, false);
document.body.addEventListener("touchmove", function (e) {
  if (e.target == canvas) {
    e.preventDefault();
  }
}, false);
*/
(function() {
  loadImageIntoCanvas();
})();
