document.addEventListener("DOMContentLoaded", function () {

    function openLightbox(src) {
    console.log("Opening:", src);
    document.getElementById('lightbox-img').src = src;
    document.getElementById('lightbox').classList.add('show');
    }

    function closeLightbox() {
    document.getElementById('lightbox').classList.remove('show');
    }

    // Optional: close with ESC key
    document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        closeLightbox();
    }
    });

});
