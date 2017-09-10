<form action="" method="post"  class="std-form">
    <div class="row">
        <div class="col-lg-12 title">
            <h3><strong>Your</strong> Personality</h3>
        </div>
    </div>

    <div class="row">
        <div class="choices choices-2 col-lg-12">
            <label>Do you prefer to people watch or get lost in thought?</label>

            <div class="btn-group" data-toggle="buttons">
                <label class="btn">
                    <input type="radio" name="watch_thought" value="People Watch"> People Watch
                </label>
                <label class="btn last">
                    <input type="radio" name="watch_thought" value="Get Lost In Thought"> Get Lost In Thought
                </label>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="choices choices-2 col-lg-12">
            <label>Do you prefer to be more practical or more imaginative?</label>

            <div class="btn-group" data-toggle="buttons">
                <label class="btn">
                    <input type="radio" name="practical_imaginative" value="Practical"> Practical
                </label>
                <label class="btn last">
                    <input type="radio" name="practical_imaginative" value="Imaginative"> Imaginative
                </label>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="choices choices-2 col-lg-12">
            <label>Do you prefer to be fair and logical, or sympathetic and personal?</label>

            <div class="btn-group" data-toggle="buttons">
                <label class="btn">
                    <input type="radio" name="objective_subjective" value="Fair &amp; Rational"> Fair &amp; Rational
                </label>
                <label class="btn last">
                    <input type="radio" name="objective_subjective" value="Sympathetic &amp; Personal"> Sympathetic &amp; Personal
                </label>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="choices choices-2 col-lg-12">
            <label>Do you prefer to be more open-minded and flexible, or to be opinionated and scheduled?</label>

            <div class="btn-group" data-toggle="buttons">
                <label class="btn">
                    <input type="radio" name="flow_opinions" value="Flexible &amp; go with the Flow"> Flexible &amp; go with the Flow
                </label>
                <label class="btn last">
                    <input type="radio" name="flow_opinions" value="Scheduled &amp; Form Opinions"> Scheduled &amp; Form Opinions
                </label>
            </div>
        </div>
    </div>


    <div class="row">
        <input type="hidden" name="step" value="5"/>

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