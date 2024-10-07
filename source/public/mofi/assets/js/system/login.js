$("#btn_login" ).on( "click", function() {
    if ($("#namapengguna").val() == "" || $("#katasandi").val() == "") return  createToast('Kesalahan Formulir','top-right', 'Nama Pengguna / Surel serta kata sandi wajib diisi sebagai data!', 'error', 3000);
    $('#btn_login').prop("disabled",true);$('#btn_login').html('<i class="fa fa-spin fa-refresh"></i> Proses Autentifikasi');
    $.get('/generate-csrf-token', function(response) {
        $.ajax({
            url: baseurlapi + '/auth/pintumasuk',
            type: 'POST',
            dataType: 'json',
            data: {
                _token: response.csrf_token,
                username: $("#username").val().trim(),
                password: $("#password-input").val().trim(),
                access_form: "web_login",
            },
            complete: function() {
                $('#btn_login').prop("disabled", false);$('#btn_login').html('Masuk Ke Panel Beranda DocuMess');
            },
            success: function(response) {
                if (response.success == false) {
                    return toastr.error(response.message, 'Pesan Kesalahan Code : ' + response.rc);
                }
                localStorage.setItem("session_id_browser", response.token);
                window.location.href = baseurl + '/beranda';
            },
            error: function(xhr, status, error) {
                $('#btn_login').prop("disabled", false);$('#btn_login').html('Masuk Ke Panel Beranda DocuMess');
                toastr.error('Terjadi kesalahan proses LOGIN. Silahkan hubungi TIM Terkiat. Pesan Kesalahan : ' + xhr.responseJSON.message, 'Pesan REST API Login');
            }
        });
    });
});