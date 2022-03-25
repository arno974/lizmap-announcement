{jmessage_bootstrap}

<h1>{@announcementAdmin~announcementAdmin.main.title@}</h1>

{formdatafull $form}

<!-- Modify -->
{ifacl2 'lizmap.admin.services.update'}
<div class="form-actions">
    <a class="btn" href="{jurl 'announcementAdmin~config:modify'}">
        {@admin~admin.configuration.button.modify.service.label@}
    </a>
</div>
{/ifacl2}