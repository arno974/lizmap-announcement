lizMap.events.on({
    'uicreated': function(e) {
        var repoProjectParams =  {
            repository: lizUrls.params.repository,
            project: lizUrls.params.project,
        };

        if( $('#info-user-login').length ){
            if( $('#info-user-login').text() ){
                repoProjectParams["user"] = $('#info-user-login').text();
            }
        };

        $.get(
            announcementConfig.urlGetLastInfo, repoProjectParams, function(data) {
                // Messages received, with 'alert' as default display type
                const messages = ['repo', 'project']
                    .filter(scope => scope in data)
                    .map(scope => ({
                        scope: scope,
                        content: data[scope].content,
                        type: data[scope].display_type === 'modal' ? 'modal' : 'alert',
                    }));

                const alerts = messages.filter(m => m.type === 'alert');
                const modals = messages.filter(m => m.type === 'modal');

                // The project is sent only if its message was in the closed block,
                // otherwise the close is recorded for the repository message
                const recordClose = function (closedMessages) {
                    const params = {...repoProjectParams};
                    if (!closedMessages.some(m => m.scope === 'project')) {
                        params.project = '';
                    }
                    $.get(announcementConfig.urlSetCloseInfo, params);
                };

                const contentOf = list => list.map(m => m.content).join('<hr>');

                if (alerts.length) {
                    const alert = $(`<div id="lizmap-announcement-alert" class="alert" role="alert">
                                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                                        <div class="lizmap-announcement-content"></div>
                                    </div>`);
                    alert.find('.lizmap-announcement-content').html(contentOf(alerts));
                    alert.on('closed', () => recordClose(alerts));
                    $('#map-content').append(alert);
                }

                if (modals.length) {
                    const modal = $(`<div id="lizmap-announcement-modal" class="modal hide fade">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h3></h3>
                                        </div>
                                        <div class="modal-body"></div>
                                    </div>`);
                    modal.find('h3').text(announcementConfig.modalHeader);
                    modal.find('.modal-body').html(contentOf(modals));
                    // Bootstrap 2 event, 'hide.bs.modal' only exists since Bootstrap 3
                    modal.on('hidden', () => recordClose(modals));
                    $('body').append(modal);
                    modal.modal('show');
                }
            },'json'
        );
    }
});
