<div class="col-lg-12">
    <div id="availability-calendar" class="calendar"></div>
    <div id="event"></div>
</div>

<div class="modal fade" id="availability-calendar-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Availability</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-4 control-label">I will be...</label>
                        <div class="col-sm-8">
                            <select name="is_available" class="form-control" data-rule-required="true">
                                <option value="-1">Unavailable</option>
                                <option value="1">Available</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-4 col-sm-8">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" class="is_all_day" value="1"> All Day
                                    <input type="hidden" class="is_all_day_val" name="is_all_day"/>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-group-date">
                        <label class="col-sm-4 control-label">Start Time</label>
                        <div class="col-sm-8">
                            <?=form_hour('start_time', '', 'class="form-control"  data-rule-required="true"')?>
                        </div>
                    </div>

                    <div class="form-group form-group-date">
                        <label class="col-sm-4 control-label">End Time</label>
                        <div class="col-sm-8">
                            <?=form_hour('end_time', '', 'class="form-control"  data-rule-required="true"')?>
                        </div>
                    </div>

                    <input type="hidden" name="date"/>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-save-availability-calendar" data-loading-text="Saving...">Save</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->