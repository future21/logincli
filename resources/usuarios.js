$(document).ready(function(){ //cuando el html fue cargado iniciar
   $('#tabla_lista_usuarios').dataTable();


    //a�ado la posibilidad de editar al presionar sobre edit
    $('#tabla_lista_usuarios tbody').on('click','.edit', function(){
        //this = es el elemento sobre el que se hizo click en este caso el link
        //obtengo el id que guardamos en data-id
        var id=$(this).attr('data-id');
        //preparo los parametros
        params={};
        params.id=id;
        params.action="edit2Client";
        $('#popupbox').load('main.php', params,function(){
            $('#block').show();
            $('#popupbox').show();
        })

    })

    $('#tabla_lista_usuarios tbody').on('click','.delete',function(){
        //obtengo el id que guardamos en data-id
        var id=$(this).attr('data-id');
        //preparo los parametros
        params={};
        params.id=id;
        params.action="delete2Client";
        $('#popupbox').load('main.php', params,function(){
            $('#content').load('main.php',{action:"refreshGrid"});
        })
        location.reload(true);


    })

    $(document).on('click','.button',function(){
        params={};
        params.action="new2Client";
        $('#popupbox').load('main.php', params,function(){
            $('#block').show();
            $('#popupbox').show();
        })
    })

    // boton cancelar, uso live en lugar de bind para que tome cualquier boton
    // nuevo que pueda aparecer
    $('#cancel').live('click',function(){
        $('#block').hide();
        $('#popupbox').hide();
    })


     $('#client').live('submit',function(){
        var params={};
        params.action='save2Client';
        params.id=$('#id').val();
        params.cliente=$('#cliente').val();
        params.tiempo=$('#tiempo').val();
        params.email=$('#email').val();
        params.pass=$('#pass').val();
        //params.salt=$('#salt').val();
        //params.created_at=$('#created_at').val();
       Alert("Grabado");
        //Alert($('#adm').val());

        $.post('index.php',params,function(){
            $('#block').hide();
            $('#popupbox').hide();
            $('#content').load('main.php',{action:"refresh2Grid"});
        })
        location.reload(true);
        return false;
    })


})