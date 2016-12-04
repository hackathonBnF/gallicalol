// Set up the canvas
var canvas = document.getElementById("canvas");
var ctx = canvas.getContext("2d");
ctx.strokeStyle = "#222222";
ctx.lineWith = 2;

var $scale = $('#scale');
var $textOne = $('#text-1');
var $textTwo = $('#text-2');
var $download = $('#download');
var $download_hidden = $('#download_hidden');
var $sourceImage = $('#source-image');
var img = new Image();
var imageURL;
var x, y;
var drag = false;

var prevX = 0;
var prevY = 0;

var moveXAmount = 0;
var moveYAmount = 0;

// Set up mouse events for drawing
var drawing = false;
var mousePos = { x:0, y:0 };
var lastPos = mousePos;

$('#scale').on('input', renderCanvas);


canvas.addEventListener("mousedown", function (e) {
  drag = true;
  lastPos = getMousePos(canvas, e);
}, false);
canvas.addEventListener("mouseup", function (e) {
  drag = false;
  lastPos = getMousePos(canvas, e);
}, false);
canvas.addEventListener("mousemove", function (e) {
  mousePos = getMousePos(canvas, e);

  if(drag){
      if(prevX>0 || prevY>0){
        moveXAmount += e.pageX - prevX;
        moveYAmount += e.pageY - prevY;
      }

      prevX = e.pageX;
      prevY = e.pageY;
  }


}, false);

// Get the position of the mouse relative to the canvas
function getMousePos(canvasDom, mouseEvent) {
  var rect = canvasDom.getBoundingClientRect();

  return {
    x: mouseEvent.clientX - rect.left,
    y: mouseEvent.clientY - rect.top
  };


}

// Get a regular interval for drawing to the screen
window.requestAnimFrame = (function (callback) {
        return window.requestAnimationFrame || 
           window.webkitRequestAnimationFrame ||
           window.mozRequestAnimationFrame ||
           window.oRequestAnimationFrame ||
           window.msRequestAnimaitonFrame ||
           function (callback) {
        window.setTimeout(callback, 1000/60);
           };
})();

// Set up touch events for mobile, etc
canvas.addEventListener("touchstart", function (e) {
        mousePos = getTouchPos(canvas, e);
  var touch = e.touches[0];
  var mouseEvent = new MouseEvent("mousedown", {
    clientX: touch.clientX,
    clientY: touch.clientY
  });
  canvas.dispatchEvent(mouseEvent);
}, false);
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

// Get the position of a touch relative to the canvas
function getTouchPos(canvasDom, touchEvent) {
  var rect = canvasDom.getBoundingClientRect();
  return {
    x: touchEvent.touches[0].clientX - rect.left,
    y: touchEvent.touches[0].clientY - rect.top
  };
}

// Draw to the canvas
function renderCanvas() {
	scale($scale.val());
  if (drag) {
	
  	ctx.clearRect(0, 0, canvas.width, canvas.height);
	
 // ctx.translate(-canvas.width/2, -canvas.height/2);
    ctx.drawImage(img, x+moveXAmount, y+moveYAmount);

    lastPos = mousePos;
    //moveXAmount = 0;
    //moveYAmount = 0;
  }
  console.log("renderCanvas");
}

// Allow for animation
(function drawLoop () {
  requestAnimFrame(drawLoop);
  renderCanvas();
})();


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

function scale(ratio) {
  ctx.save();
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  ctx.translate(canvas.width/2, canvas.height/2);
  ctx.scale(ratio, ratio);
  ctx.translate(-canvas.width/2, -canvas.height/2);
  ctx.drawImage(img, x+moveXAmount, y+moveYAmount);
  ctx.restore();

}

function refreshDownloadLink() {
  var dataURL = canvas.toDataURL('image/jpeg');
  dataURL = dataURL.replace(/^data:image\/[^;]/, 'data:application/octet-stream');
  $download.attr('href', dataURL);
  $download_hidden.attr('value', dataURL);
}


// Prevent scrolling when touching the canvas

document.body.addEventListener("touchstart", function (e) {
  if (e.target == canvas) {
    e.preventDefault();
  }
}, false);

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


(function() {
  loadImageIntoCanvas();
})();
