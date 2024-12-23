$(document).ready(function(){

});
$("#collapse_formulir").on("click", function(event) {
    const form = $("#formulir_tambah_tindakan");
    if (form.hasClass("show")) {
        form.collapse("hide");
        $(this).text("Tampilkan Formulir");
    } else {
        form.collapse("show");
        $(this).text("Sembunyikan Formulir");
    }
})