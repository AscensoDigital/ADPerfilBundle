/**
 * Created by claudio on 05-09-16.
 */
$(document).ready(function () {
    $('a[data-toggle="modal"]').on('click', function(){
        var modal_id=$(this).attr('href');
        var url=$(this).data('url');
        $(modal_id).find('a').each(function(){
            $(this).attr('href',url + '/' + $(this).data('valor'));
        });
        $('#rep-nombre').text($(this).data('reporte'));
    });
});
