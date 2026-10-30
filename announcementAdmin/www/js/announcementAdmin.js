$( document ).ready(function() {
        // Values given by the controller with addJsVariables()
        const settings = JSON.parse(document.getElementById('lizmap-vars').textContent).announcementAdmin;

        //clear all fields
        $(':input','#jforms_announcementAdmin_config')
        .not(':button, :submit, :reset, :hidden')
        .val('')
        .removeAttr('checked')
        .removeAttr('selected');

        //DataTables configuration
        var dtTable = $('#listMsg').DataTable({
            "ajax": settings.urlGetMessages,
            language: DatatablesTranslations,
            dom: 'Bfrtip', 
            autoWidth: false,   
            buttons: [{
                text: settings.labelTruncate,
                action: function ( e, dt, node, config ) {
                    $.get( settings.urlTruncate, function( data ) {
                        dt.ajax.reload();
                    });
                }
            }],
            "columns": [
                { "data": "timestamp"},                
                { "data": "repo" },
                { "data": "proj" },
                { "data": "content" },
                { "data": "display_type" },
                { "data": "permanent" },
                {
                    data: null,
                    render:function (data, type, row) {
                        return `<button data-id="${data}" class="deleteMsg btn btn-danger" id="delete">${settings.labelRemove}</button>`
                }}               
            ],            
            "columnDefs": [{
                //format timestamp date
                targets: 0,                
                render: $.fn.dataTable.render.moment('YYYY-MM-DD HH:mm:ss','DD/MM/YYYY')
            },{
                //Display icone cross (&#x2715;) or tick (&#x2714;) instead of 0,1
                targets: 5,            
                render: function (data, type, row) {
                    return (data === 0) ? '<span>&#x2715;</span>' : '<span>&#x2714;</span>' ;
                }
            },{
                //set column width
                targets:[0,1,2,4,5,6],
                width:'10%'
            }]   
        });   
        
        $('#listMsg tbody').on( 'click', 'button', function () {
            data = dtTable.row( $(this).parents('tr') ).data(); 
            dtTable.row( $(this).parents('tr') ).remove().draw( false );
            $.get( settings.urlDelete, {id:data.id}, function( data ) {  });
        }); 
});