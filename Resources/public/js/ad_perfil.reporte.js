/**
 * Created by claudio on 05-09-15.
 */
$(document).ready(function () {
    $('a[data-toggle="modal"]').on('click', function(){
        var modal_id=$(this).attr('href');
        var url=$(this).data('url');
        $('#rep-nombre').text($(this).data('reporte'));
        $(modal_id).find('a').each(function(){
            $(this).attr('href',url + '/' + $(this).data('valor'));
        });
    });
});
