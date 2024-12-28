$(document).ready(function() {
    toggleDarkMode();
});
function toggleDarkMode() {
    if(localStorage.getItem('theme') === 'dark') {
        $('body').addClass('dark-mode');
        $('#moon-icon').hide(); 
        $('#sun-icon').show(); 
    } else {
        $('body').removeClass('dark-mode');
        $('#moon-icon').show(); 
        $('#sun-icon').hide(); 
    }

    $('#mode-toggle').click(function() {
        $('body').toggleClass('dark-mode');
        $('#moon-icon').toggle();
        $('#sun-icon').toggle();
    })
    if ($('body').hasClass('dark-mode')) {
        localStorage.setItem('theme', 'dark');
    } else {
        localStorage.setItem('theme', 'light');
    }
}
function dataURLToBlob(dataURL) {
    const byteString = atob(dataURL.split(',')[1]);
    const mimeString = dataURL.split(',')[0].split(':')[1].split(';')[0];
    const arrayBuffer = new ArrayBuffer(byteString.length);
    const uint8Array = new Uint8Array(arrayBuffer);

    for (let i = 0; i < byteString.length; i++) {
        uint8Array[i] = byteString.charCodeAt(i);
    }

    return new Blob([arrayBuffer], { type: mimeString });
}
  