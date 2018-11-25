$(document).ready(function(){
    const filtroBody=$("#ad_perfil-filtro-body");
    const frm_filtro=$('#ad_perfil-frm-filtros');
    frm_filtro.on('submit',function(){
        filtroBody.children('.alert-danger').remove();
        let error='';
        frm_filtro.find(':input[required="required"]').each(function() {
            let elemento= this;
            ids=elemento.id.split('_');
            ids.splice(0,3);
            if($(elemento).val()===null || $(elemento).val().length===0) {
                error+='<li>Filtro ' + ids.join(' ') + " es obligatorio</li>";
            }
        });
        if(1 === frm_filtro.data('auto-llenado')) {
            frm_filtro.find(':input').each(function () {
                let elemento = this;
                ids = elemento.id.split('_');
                ids.splice(0,3);
                let name = elemento.id + '_' + frm_filtro.data('save-as');
                if (elemento.multiple) {
                    name = name + '_multiple'
                }
                if ($(elemento).val() === null || $(elemento).val().length === 0) {
                    sessionStorage.removeItem(name);
                }
                else if (elemento.id !== 'ad_perfil_filtros__token') {
                    sessionStorage.setItem(name, JSON.stringify($(elemento).val()));
                }
            });
        }
        if(''!==error) {
            let divError='<div></div>';
            $(divError).append(error).addClass('alert alert-danger');
            filtroBody.prepend(divError);
            return false;
        }

        let destino="#" + frm_filtro.data('update');
        let url= frm_filtro.attr('action');
        $('#ad_perfil-btn-filtros').prepend('<i class="fa fa-refresh fa-spin"></i>').prop('disabled', true);
        $.ajax({
            type: 'POST',
            url: url,
            dataType : 'html',
            data: frm_filtro.serialize(),
            success: function(data) {
                $(destino).html(data);
                $('i.fa-spin').remove();
                $('#ad_perfil-btn-filtros').prop('disabled', false);
                if(1 === frm_filtro.data('auto-hidden')) {
                    $('#ad_perfil-collapseFiltro').collapse('hide');
                }
                return true;
            },
            error : function (obj) {
                $('i.fa-spin').remove();
                $('#ad_perfil-btn-filtros').prop('disabled', false);

                let error="Error de Conexion: "+ obj.statusText;
                let divError='<div class="alert alert-danger">'+ error + '</div>';
                filtroBody.prepend(divError);
                return false;
            }
        });
        return false;
    });

    $('#reset').on('click', function(){
        frm_filtro.find(':input').each(function() {
            $(this).val('');
        });
    });

    let filtrar=false;
    let requerido=true;
    frm_filtro.find(':input').each(function() {
        let elemento= this;
        ids = elemento.id.split('_');
        ids.splice(0,3);
        let name = elemento.id + '_' + frm_filtro.data('save-as');
        if (elemento.multiple) {
            name = name + '_multiple'
        }
        if(1 === frm_filtro.data('auto-llenado')) {
            if ($(elemento).val() === null || $(elemento).val().length === 0) {
                let valor = JSON.parse(sessionStorage.getItem(name));
                if (valor != null) {
                    $(elemento).val(valor);
                    if ($(elemento).val()) {
                        $(elemento).change();
                    }
                }
            }
        }
        if($(elemento).attr('required')==='required' && ($(elemento).val()===null || $(elemento).val().length===0)) {
            requerido=false;
        }
        if($(elemento).val()) {
            filtrar=true;
        }
    });

    if(1 === frm_filtro.data('auto-filter') && filtrar && requerido) {
        frm_filtro.submit();
    }
});
