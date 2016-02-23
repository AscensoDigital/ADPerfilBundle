$(document).ready(function(){
    var frm_filtro=$('#ad_perfil-frm-filtros');
    frm_filtro.on('submit',function(){
        var error='';
        frm_filtro.find(':input[required="required"]').each(function() {
            var elemento= this;
            ids=elemento.id.split('_');
            ids.splice(0,3);
            if($(elemento).val()===null || $(elemento).val().length===0) {
                error+='Filtro ' + ids.join(' ') + " es obligatorio\n";
            }
        });
        if(1 === frm_filtro.data('auto-llenado')) {
            frm_filtro.find(':input').each(function () {
                var elemento = this;
                ids = elemento.id.split('_');
                ids.splice(0,3);
                var name = elemento.id + '_' + frm_filtro.data('route');
                if (elemento.multiple) {
                    name = name + '_multiple'
                }
                if ($(elemento).val() === null || $(elemento).val().length === 0) {
                    sessionStorage.removeItem(name);
                }
                else if (elemento.id != 'filtros__token') {
                    sessionStorage.setItem(name, JSON.stringify($(elemento).val()));
                }
            });
        }
        if(''!=error) {
            alert(error);
            return false;
        }

        var destino="#" + frm_filtro.data('update');
        var url= frm_filtro.attr('action');
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
                $('#ad_perfil-collapseFiltro').collapse('hide');
                return true;
            },
            error : function (obj) {
                $('i.fa-spin').remove();
                $('#ad_perfil-btn-filtros').prop('disabled', false);
                alert("Error de Conexion: "+ obj.statusText);
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

    var filtrar=false;
    var requerido=true;
    frm_filtro.find(':input').each(function() {
        var elemento= this;
        ids = elemento.id.split('_');
        ids.splice(0,3);
        var name = elemento.id + '_' + frm_filtro.data('route');
        if (elemento.multiple) {
            name = name + '_multiple'
        }
        if(1 === frm_filtro.data('auto-llenado')) {
            if ($(elemento).val() === null || $(elemento).val().length === 0) {
                var valor = JSON.parse(sessionStorage.getItem(name));
                if (valor != null) {
                    $(elemento).val(valor);
                    if ($(elemento).val()) {
                        $(elemento).change();
                    }
                }
            }
        }
        if($(elemento).attr('required')=='required' && ($(elemento).val()===null || $(elemento).val().length===0)) {
            requerido=false;
        }
        if($(elemento).val()) {
            filtrar=true;
        }
    });

    if(1 === frm_filtro.data('auto-filter') && filtrar && requerido) {
        frm_filtro.submit();
    }
    $('.fecha').datepicker({
        showOtherMonths: true,
        selectOtherMonths: true,
        dateFormat: "yy-mm-dd"
    });
});
