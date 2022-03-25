$( document ).ready(function() {
        //clear all fields
        $(':input','#jforms_announcementAdmin_config')
        .not(':button, :submit, :reset, :hidden')
        .val('')
        .removeAttr('checked')
        .removeAttr('selected');

        //add editor to form
        ClassicEditor.create( document.querySelector( '#jforms_announcementAdmin_config_content' ),{
                toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList'],
                heading: {
                    options: [
                        { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                        { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                        { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' }
                    ]
                }
        }).catch( error => {console.error( error );} );
        
        //DataTables configuration
        var dtTable = $('#listMsg').DataTable({
            "ajax": _URLGETMSG,   
            dom: 'Bfrtip', 
            autoWidth: false,   
            buttons: [{
                text: _LABELTRUNCATEMSG,
                action: function ( e, dt, node, config ) {
                    $.get( _URLTRUNCATEMSG, function( data ) {
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
                        return `<button data-id="${data}" class="deleteMsg btn btn-danger" id="delete">${_LABELREMOVEMSG}</button>`
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
            $.get( _URLDELETEMSG, {id:data.id}, function( data ) {  });
        }); 
});