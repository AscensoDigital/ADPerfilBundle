/**
 * Created by claudio on 05-09-16.
 */
$(document).ready(function () {
    $('a[data-toggle="modal"]').on('click', function(){
        var modal_id=$(this).attr('href');
        var url=$(this).data('url');
        var nombre=$(this).data('reporte');
        $(modal_id+'_rep_nombre').html(nombre);
        var now= new Date();
        $(modal_id).find('a').each(function(){
            $(this).attr('href',url + '/' + $(this).data('valor') + '?v=' + now);
        });
    });
});
