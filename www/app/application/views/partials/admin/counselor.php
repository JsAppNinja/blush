<ul class="nav nav-tabs">
  <li class="active"><a href="#profile" data-toggle="tab">Profile Settings</a></li>
  <% if(registration.data) { %>
    <li><a href="#registration" data-toggle="tab">Registration Data</a></li>
  <% } %>
    <li><a href="#customers" data-toggle="tab">Customers</a></li>
</ul>

<div class="tab-content">
  <? include(APPPATH.'views/partials/admin/user/profile.php'); ?>
  <? include(APPPATH.'views/partials/admin/user/registration-data.php'); ?>
  <? include(APPPATH.'views/partials/admin/user/counselor-customers.php'); ?>
</div>