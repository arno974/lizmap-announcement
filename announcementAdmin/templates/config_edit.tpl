{jmessage_bootstrap}
<h1>{@announcementAdmin~announcementAdmin.main.title@}</h1>
{jmessage} 

<ul class="nav nav-tabs">
  <li class="active">
    <a href="#add-message" data-toggle="tab">{@announcementAdmin~announcementAdmin.form.main.label@}</a>
  </li>
  <li>
    <a href="#view-message" data-toggle="tab">{@announcementAdmin~announcementAdmin.list.messages@}</a>
  </li>
</ul>
 
<div class="tab-content">
  <div class="tab-pane active" id="add-message">
    {formfull $form, 'announcementAdmin~config:save', array(), 'htmlbootstrap'}
    <div>
      <a class="btn" href="{jurl 'announcementAdmin~config:edit'}">{@admin~admin.configuration.button.back.label@}</a>
    </div>
  </div>
  <div class="tab-pane" id="view-message">
    <table id="listMsg" class="table table-striped table-bordered" style="width:100%">
      <thead>
        <tr>
          <th>{@announcementAdmin~announcementAdmin.form.input.dateMsg@}</th>
          <th>{@announcementAdmin~announcementAdmin.form.input.repository@}</th>          
          <th>{@announcementAdmin~announcementAdmin.form.input.project@}</th>
          <th>{@announcementAdmin~announcementAdmin.form.input.message@}</th>
          <th>{@announcementAdmin~announcementAdmin.form.input.display_type.label@}</th>
          <th>{@announcementAdmin~announcementAdmin.list.label.permanent@}</th>
          <th>{@announcementAdmin~announcementAdmin.list.label.removerow@}</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

