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
  