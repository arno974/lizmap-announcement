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
            _URLGETLASTINFO, repoProjectParams, function(data) {    
                let repoContent = null;
                let projContent = null;
                let repoDisplayType = null;
                let projDisplayType = null; 

                if(Object.keys(data).length>0){ 
                    
                    let bootstrapAlert = `<div id="lizmap-announcement-alert" class="alert" role="alert">
                                                <button type="button" class="close" data-dismiss="alert">&times;</button> 
                                                <p id="lizmap-announcement-repo"></p> 
                                                <p id="lizmap-announcement-project" class="mb-0"></p> 
                                            </div>`;
                    let boostrapModal =  `<div id="lizmap-announcement-modal" class="modal hide fade">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                    <h3>${_MODALHEADER}</h3></div><div class="modal-body">
                                                    <p id="lizmap-announcement-repo"></p>
                                                    <p id="lizmap-announcement-project" class="mb-0"></p>
                                                </div>
                                            </div>`;             
                    if('repo' in data){
                        if ('content' in data.repo){
                            repoContent = data.repo.content;
                        }                        
                        if('display_type' in data.repo && 'display_type' != null){
                            repoDisplayType = data.repo.display_type;
                        }else{
                            repoDisplayType = 'alert';
                        }                                           
                    }                    
                    if('project' in data){                        
                        if ('content' in data.project){
                            projContent = data.project.content;
                        }
                        if('display_type' in data.project && 'display_type' != null){
                            projDisplayType = data.project.display_type;
                        }else{
                            projDisplayType = 'alert';
                        }                                                                  
                    }
                    if ( repoDisplayType === projDisplayType){   
                        if( repoDisplayType === 'modal' ){
                            $("body").append(boostrapModal);
                            $("#lizmap-announcement-modal #lizmap-announcement-repo").html(repoContent); 
                            $("#lizmap-announcement-modal #lizmap-announcement-project").html(projContent); 
                            $('#lizmap-announcement-modal').modal('show');                            
                        }else{
                            //alert display
                            $("#map-content").append(bootstrapAlert);
                            $("#lizmap-announcement-alert #lizmap-announcement-repo").html(repoContent); 
                            $("#lizmap-announcement-alert #lizmap-announcement-project").html(projContent);  
                        }
                        $("#lizmap-announcement-project").prepend('<hr>');
                    }else{                 
                        if( repoDisplayType === 'modal'){
                            $("body").append(boostrapModal);
                            $("#lizmap-announcement-modal #lizmap-announcement-repo").html(repoContent); 
                            $('#lizmap-announcement-modal').modal('show');
                        }else{
                            $("#map-content").append(bootstrapAlert);
                            $("#lizmap-announcement-alert #lizmap-announcement-repo").html(repoContent);                            
                        }
                        if( projDisplayType === 'modal'){
                            $("body").append(boostrapModal);
                            $("#lizmap-announcement-modal #lizmap-announcement-project").html(projContent);
                            $('#lizmap-announcement-modal').modal('show');
                        }else{
                            $("#map-content").append(bootstrapAlert);
                            $("#lizmap-announcement-alert #lizmap-announcement-project").html(projContent);                            
                        }                        
                    }                  
                };   
                //Event on close
                $('#lizmap-announcement-alert').bind('closed', function () {
                    if(projDisplayType === 'alert'){
                        $.get(_SETUSERCLOSEINFO, repoProjectParams, function(data) { })
                     }else{
                        let _repoProjectParams = repoProjectParams;
                        _repoProjectParams.project = null;                         
                        $.get(_SETUSERCLOSEINFO, _repoProjectParams, function(data) { });  
                     }
                });

                $(document).on('hide.bs.modal','#lizmap-announcement-modal', function () {
                    if(projDisplayType === 'modal'){
                        $.get(_SETUSERCLOSEINFO, repoProjectParams, function(data) { })
                     }else{                        
                        let _repoProjectParams = repoProjectParams;
                        _repoProjectParams.project = null;                   
                        $.get(_SETUSERCLOSEINFO, _repoProjectParams, function(data) { });  
                     }                      
                });                                  
            },'json'
        );
    }
});