<form action="" method="post"  class="std-form">
    <div class="row">
        <div class="col-lg-12 title">
            <h3><strong>Counseling</strong> History</h3>
        </div>
    </div>

    <div class="row">
        <div class="choices choices-2 yes-no-more col-lg-12">
            <label>Have you seen a counselor before?</label>

            <div class="btn-group" data-toggle="buttons">
                <label class="btn">
                    <input type="radio" name="counselor_before" value="Yes"> Yes
                </label>
                <label class="btn last">
                    <input type="radio" name="counselor_before" value="No"> No
                </label>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="choices choices-2 more col-lg-12 counselor_before" style="display:none">
            <span class="if_yes">(IF YES)</span>
            <textarea class="form-control" id="counselor_before_more" placeholder="Tell us about it." name="counselor_before_more"><%=registration.counselor_before_more%></textarea>
        </div>
    </div>



    <div class="row">
        <div class="choices choices-2 yes-no-more col-lg-12">
            <label>Do you use drugs or alcohol?</label>

            <div class="btn-group" data-toggle="buttons">
                <label class="btn">
                    <input type="radio" name="drugs_alcohol" value="Yes"> Yes
                </label>
                <label class="btn last">
                    <input type="radio" name="drugs_alcohol" value="No"> No
                </label>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="choices choices-2 more col-lg-12 drugs_alcohol" style="display:none">
            <span class="if_yes">(IF YES)</span>
            <textarea class="form-control" id="drugs_alcohol_more" placeholder="Tell us about it." name="drugs_alcohol_more"><%=registration.drugs_alcohol_more%></textarea>
        </div>
    </div>



    <div class="row">
        <div class="choices choices-2 yes-no-more col-lg-12">
            <label>Have you had any recent changes in your eating or sleeping habits?</label>

            <div class="btn-group" data-toggle="buttons">
                <label class="btn">
                    <input type="radio" name="sleeping_changes" value="Yes"> Yes
                </label>
                <label class="btn last">
                    <input type="radio" name="sleeping_changes" value="No"> No
                </label>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="choices choices-2 more col-lg-12 sleeping_changes" style="display:none">
            <span class="if_yes">(IF YES)</span>
            <textarea class="form-control" id="sleeping_changes_more" placeholder="Tell us about it." name="sleeping_changes_more"><%=registration.sleeping_changes_more%></textarea>
        </div>
    </div>



    <div class="row">
        <div class="choices choices-2 yes-no-more col-lg-12">
            <label>Do you have any medical conditions or have you ever been clinically diagnosed?</label>

            <div class="btn-group" data-toggle="buttons">
                <label class="btn">
                    <input type="radio" name="medical_diagnosis" value="Yes"> Yes
                </label>
                <label class="btn last">
                    <input type="radio" name="medical_diagnosis" value="No"> No
                </label>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="choices choices-2 more col-lg-12 medical_diagnosis" style="display:none">
            <span class="if_yes">(IF YES)</span>
            <textarea class="form-control" id="medical_diagnosis_more" placeholder="Tell us about it." name="medical_diagnosis_more"><%=registration.medical_diagnosis_more%></textarea>
        </div>
    </div>



    <div class="row">
        <div class="choices choices-2 yes-no-more col-lg-12">
            <label>Have you ever thought about hurting yourself or someone else? </label>

            <div class="btn-group" data-toggle="buttons">
                <label class="btn">
                    <input type="radio" name="suicide_homicide" value="Yes"> Yes
                </label>
                <label class="btn last">
                    <input type="radio" name="suicide_homicide" value="No"> No
                </label>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="choices choices-2 more col-lg-12 suicide_homicide" style="display:none">
            <span class="if_yes">(IF YES)</span>
            <textarea class="form-control" id="suicide_homicide_more" placeholder="Tell us about it." name="suicide_homicide_more"><%=registration.suicide_homicide_more%></textarea>
        </div>
    </div>


    <div class="row">
        <input type="hidden" name="step" value="3"/>

        <div class="col-lg-12 submit-container">
            <button class="btn btn-primary previous pull-left btn-md"><i class="glyphicon glyphicon-chevron-left"></i> Previous</button>
            <button class="btn btn-primary next pull-right btn-md" data-loading-text="Saving...">Next <i class="glyphicon glyphicon-chevron-right"></i></button>
            <button class="btn btn-primary save pull-right btn-md" data-loading-text="Saving...">Save</button>
            <div class="alert alert-success pull-right" style="display:none"></div>
            <div class="alert alert-danger pull-right" style="display:none"></div>
            <div class="clearfix"></div>
        </div>
    </div>
</form>