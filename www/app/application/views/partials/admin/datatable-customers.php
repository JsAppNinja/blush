<div class="filter-bar row">
    <form method="post">

        <div class="col-lg-2">
            <label>Coach:</label>
        </div>
        <div class="col-lg-4">
            <select id="counselor_id" name="counselor_id" class="form-control">
                <option selected="selected" value=""></option>
                <option value="-1">Unassigned</option>
                <? $counselors = $this->User->get_counselors() ?>
                <? foreach ($counselors as $counselor) { ?>
                    <option value="<?= $counselor->id ?>"><?= $counselor->firstname." ".$counselor->lastname ?></option>
                <? } ?>
            </select>
        </div>
        <div class="clearfix"></div>
    </form>
</div>
<div class="table-responsive">
    <table id="datatable" class="table">
        <thead>
        <tr>
            <% _.each(columns, function (column, index) { %>
                <th><%= column.title %></th>
            <% }) %>
        </tr>
        </thead>
    </table>
</div>