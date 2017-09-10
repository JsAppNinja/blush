<form action="" method="post"  class="std-form">
    <div class="row">
        <div class="col-lg-12 title">
            <h3><strong>Question Time!</strong> Tell Us About Yourself! </h3>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 choices">
            <label class="two-tone"><strong>Work/School question:</strong> Tell us about your current job (Or if you're a student, about what you're studying!). Why did you choose this field?</label>
            <textarea class="form-control short" id="pop_culture" name="pop_culture" placeholder="Text here"><%=registration.pop_culture%></textarea>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 choices">
            <label class="two-tone"><strong>Interest question:</strong> What do you like to do in your free time?</label>
            <textarea class="form-control short" id="interest" name="interest" placeholder="Text here"><%=registration.interest%></textarea>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 choices">
            <label class="two-tone"><strong>Dream question:</strong> What would you attempt if you knew you could not fail?</label>
            <textarea class="form-control short" id="dream" name="dream" placeholder="Text here"><%=registration.dream%></textarea>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 choices">
            <label class="two-tone"><strong>Family question:</strong> Tell us about your family!</label>
            <textarea class="form-control short" id="family" name="family" placeholder="Text here"><%=registration.family%></textarea>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 choices">
            <label>What would you like to focus on while using Blush?</label>
            <textarea class="form-control short" id="focus" name="focus" placeholder="Text here"><%=registration.focus%></textarea>
        </div>
    </div>


    <div class="row">
        <input type="hidden" name="step" value="4"/>

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