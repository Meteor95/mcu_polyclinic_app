$(document).ready(function() {
    onload_datatables_gigi();
});

function onload_datatables_gigi() {
    let table_gigi = $('#datatables_gigi').DataTable({
        paging: false,
        ordering: false,
        searching: false,
        info: false,
        keys: true,
    }).on('key-focus', function(e, datatable, cell, originalEvent) {
        $('input', cell.node()).focus();
    }).on("focus", "td input", function() {
        $(this).select();
    }).on('key', function(e, dt, code) {
        if (code === 13) {
            table_gigi.keys.move('down');
        }
    });
}
function detail_addons_kondisi_fisik(lokasi_fisik,response_array,detail = false){
    $.get('/generate-csrf-token', function(response) {
        $.ajax({
            url: baseurlapi + '/pemeriksaan_fisik/get_kondisi_fisik_gigi',
            type: 'GET',
            headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
            data: {
                _token: response.csrf_token,
                user_id: response_array[0].user_id,
                transaksi_id: response_array[0].transaksi_id,
                lokasi_fisik: lokasi_fisik,
            },
            success: function(response) {
                const data = response.data;
                ['atas', 'bawah'].forEach(posisi => {
                    ['kanan', 'kiri'].forEach(sisi => {
                        for (let i = 1; i <= 8; i++) {
                            if (detail) {
                                $(`#${posisi}_${sisi}_${i}_modal`).text(data[`${posisi}_${sisi}_${i}`]);
                            }else{
                                $(`#${posisi}_${sisi}_${i}`).val(data[`${posisi}_${sisi}_${i}`]);
                            }
                        }
                    });
                });
            },
            error: function(xhr, status, error) {
                return createToast('Kesalahan Pemanggilan Data Lokasi Gigi', 'top-right', xhr.responseJSON.message, 'error', 3000);
            }
        });
    });
}