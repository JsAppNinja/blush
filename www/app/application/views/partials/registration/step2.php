<form action="" method="post"  class="std-form">
    <div class="row">
        <div class="col-lg-12 title">
            <h3><strong>How Are You Feeling?</strong> Please Select Below</h3>
        </div>
    </div>
    <div class="row">
        <div class="choices choices-3 col-lg-12">
            <label>How is your overall mood?</label>

            <div class="btn-group" data-toggle="buttons">
                <label class="btn">
                    <input type="radio" name="overall_mood" value="Bummed"> Bummed.
                </label>
                <label class="btn">
                    <input type="radio" name="overall_mood" value="I'm ok. Whatever"> I'm ok.  Whatever.
                </label>
                <label class="btn last">
                    <input type="radio" name="overall_mood" value="PUMPED!"> PUMPED!
                </label>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="choices choices-3 col-lg-12">
            <label>How do you feel about your physical appearance?</label>

            <div class="btn-group" data-toggle="buttons">
                <label class="btn">
                    <input type="radio" name="physical_appearance" value="Ew."> Ew.
                </label>
                <label class="btn">
                    <input type="radio" name="physical_appearance" value="Reluctant acceptance"> Reluctant acceptance
                </label>
                <label class="btn last">
                    <input type="radio" name="physical_appearance" value="Love It."> Love It.
                </label>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="choices choices-3 col-lg-12">
            <label>How do you feel about your relationships?</label>

            <div class="btn-group" data-toggle="buttons">
                <label class="btn">
                    <input type="radio" name="relationships" value="Not so hot. Help"> Not so hot. Help.
                </label>
                <label class="btn">
                    <input type="radio" name="relationships" value="Some are good & some suck"> Some are good & some suck
                </label>
                <label class="btn last">
                    <input type="radio" name="relationships" value="Craze amaze."> Craze amaze.
                </label>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="choices choices-3 col-lg-12">
            <label>How would you describe your stress level?</label>

            <div class="btn-group" data-toggle="buttons">
                <label class="btn">
                    <input type="radio" name="stress_level" value="So stressed. Life is a mess"> So stressed. Life is a mess
                </label>
                <label class="btn">
                    <input type="radio" name="stress_level" value="Pretty Standard"> Pretty Standard
                </label>
                <label class="btn last">
                    <input type="radio" name="stress_level" value="Stress free-WOOP!"> Stress free-WOOP!
                </label>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="choices choices-3 col-lg-12">
            <label>How excited are you for the future?</label>

            <div class="btn-group" data-toggle="buttons">
                <label class="btn">
                    <input type="radio" name="future_optimistic" value="Not at all, I'm scared"> Not at all, I'm scared
                </label>
                <label class="btn">
                    <input type="radio" name="future_optimistic" value="Nervous. I'm not sure"> Nervous. I'm not sure
                </label>
                <label class="btn last">
                    <input type="radio" name="future_optimistic" value="Building my Empire"> Building my Empire
                </label>
            </div>

        </div>
    </div>

    <div class="row">
        <input type="hidden" name="step" value="2"/>

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