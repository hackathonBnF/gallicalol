var canvas = document.getElementById('canvas');
var ctx = canvas.getContext("2d");

var $imageURL = $('#image-url');
var $downloadIntoCanvas = $('#download-into-canvas');
var $scale = $('#scale');
var $textOne = $('#text-1');
var $textTwo = $('#text-2');

var img = new Image();
var imageURL;

var x, y;

function loadImageIntoCanvas() {
  img.onload = function() {
    x = canvas.width / 2 - img.width / 2;
    y = canvas.height / 2 - img.height / 2;
    ctx.drawImage(img, x, y);
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

function renderMeme() {
  ctx.drawImage(img, x, y);
  writeTexts();
}

$downloadIntoCanvas.on('click', function(e) {
  e.preventDefault();
  imageURL = $imageURL.val();
  loadImageIntoCanvas();
});

$scale.on('change', function(e) {

  ctx.save();

  // Clear canvas
  ctx.clearRect(0, 0, canvas.width, canvas.height);

  // Translate to center so transformations will apply around this point
  ctx.translate(canvas.width / 2, canvas.height / 2);

  // Perform scale
  var val = $scale.val();
  ctx.scale(val, val);

  // Reverse the earlier translation
  ctx.translate(-canvas.width / 2, -canvas.height / 2);

  // Finally, draw the image
  ctx.drawImage(img, x, y);

  ctx.restore();

  writeTexts();
});

$textOne.on('keyup', renderMeme);
$textTwo.on('keyup', renderMeme);


// return;

// var e = {}, // A container for DOM elements
//     reader = new FileReader(),
//     image = new Image(),
//     ctxt = null, // For canvas' 2d context
//     renderMeme = null, // For a function to render memes
//     get = function (id) {
//         // Short for document.getElementById()
//         return document.getElementById(id);
//     };

// // Get elements (by id):
// e.box1 = get("box1");
// e.ifile = get("ifile");
// e.box2 = get("box2");
// e.topline = get("topline");
// e.bottomline = get("bottomline");
// e.c = get("c"); // canvas;
// e.downloadLink = get("downloadLink");
// // Get canvas context:
// ctxt = e.c.getContext("2d");
// // Function for rendering memes:
// renderMeme = function () {
//     var writeText = function (text, x, y) {
//         var f = 36; // Font size (in pt)
//         for (; f >= 0; f -=1) {
//             ctxt.font = "bold " + f + "pt Impact, Charcoal, sans-serif";
//             if (ctxt.measureText(text).width < e.c.width - 10) {
//                 ctxt.fillText(text, x, y);
//                 ctxt.strokeText(text, x, y);
//                 break;
//             }
//         }
//     };
//     e.c.width = image.width;
//     e.c.height = image.height;
//     ctxt.drawImage(image, 0, 0, e.c.width, e.c.height);
//     ctxt.textAlign = "center";
//     ctxt.fillStyle = "white";
//     ctxt.strokeStyle = "black";
//     ctxt.lineWidth = 2;
//     writeText(e.topline.value, e.c.width / 2, 50);
//     writeText(e.bottomline.value, e.c.width / 2, e.c.height - 20);
//     e.downloadLink.href = e.c.toDataURL();
// };
// // Event handlers:
// e.ifile.onchange = function () {
//     reader.readAsDataURL(e.ifile.files[0]);
//     reader.onload = function () {
//         image.src = reader.result;
//         image.onload = function () {
//             renderMeme();
//             e.box1.style.display = "none";
//             e.box2.style.display = "";
//         };
//     };
// };
// e.topline.onkeyup = renderMeme;
// e.bottomline.onkeyup = renderMeme;
