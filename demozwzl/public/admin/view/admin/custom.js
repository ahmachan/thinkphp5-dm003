$(document).ready(function(){
    $('#demo-fileInput-6').filer({
        changeInput: window.filer_default_opts.changeInput2,
        showThumbs: true,
        limit:["jpg","jpeg","bmp","tiff","gif","png"],
        maxSize:1024*1024,//单位byte
        extensions:["jpg","jpeg","bmp","tiff","gif","png"],
        theme: "dragdropbox",
        templates: window.filer_default_opts.templates,
        dragDrop: window.filer_default_opts.dragDrop,
        uploadFile: window.filer_default_opts.uploadFile,
        onRemove: window.filer_default_opts.onRemove,
        captions:window.filer_default_opts.captions
    });
});
