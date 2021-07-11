
// Loaded via <script> tag, create shortcut to access PDF.js exports.
var pdfjsLib = window['pdfjs-dist/build/pdf'];
// The workerSrc property shall be specified.
pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://mozilla.github.io/pdf.js/build/pdf.worker.js';
var fileReader = new FileReader();
fileReader.onload = function() {
    var pdfData = new Uint8Array(this.result);
    // Using DocumentInitParameters object to load binary data.
    var loadingTask = pdfjsLib.getDocument({data: pdfData});
    loadingTask.promise.then(function(pdf) {

    // Fetch the first page
    var pageNumber = 1;
    pdf.getPage(pageNumber).then(function(page) {

    var scale = 1.5;
    var viewport = page.getViewport({scale: scale});

    // Prepare canvas using PDF page dimensions
    var canvas = $("#pdfViewer")[0];
    var context = canvas.getContext('2d');
    canvas.height = viewport.height;
    canvas.width = viewport.width;

    // Render PDF page into canvas context
    var renderContext = {
    canvasContext: context,
    viewport: viewport
};
    var renderTask = page.render(renderContext);
    renderTask.promise.then(function () {
});
});
}, function (reason) {
    // PDF loading error
    console.error(reason);
});
};
$(".view").on("click", function(e) {
    e.preventDefault()
    let url = this.href
    console.log(url)
    const request = new XMLHttpRequest();
    request.open('GET', url, true);
    request.responseType = 'blob';
    request.onload = function() {
    fileReader.readAsArrayBuffer(request.response);
};
    request.send();
});
