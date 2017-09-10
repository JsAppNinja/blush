<% if(typeof registration.data != 'undefined') { %>
<div class="tab-pane" id="registration">
    <h4>Registration Information</h4>

    <div class="row">
        <div class="col-lg-4">
            <div class="registration-field">
                <label>School/Occupation:</label>
                <%=registration.data.school_occupation%>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="registration-field">
                <label>Referral Source:</label>
                <%=registration.data.referral%>
                <% if(registration.data.referral_other) { %>
                    : <%=registration.data.referral_other%>
                <% } else if(registration.data.referral_counselor) { %>
                    : <%=registration.data.referral_counselor%>
                <% } %>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="registration-field">
                <label>Preferred Coach: </label>
                <%=registration.data.preferred_coach%>
            </div>
            <div class="registration-field">
                <label>Preferred Coaching Time: </label>
                <p><%=registration.data.preferred_coaching_time%></p>
            </div>
            <div class="registration-field">
                <label>Preferred Coaching Qualifications: </label>
                <p><%=registration.data.coaching_qualifications%></p>
            </div>
        </div>
    </div>

    <hr/>

    <div class="row">
        <div class="col-lg-6">
            <div class="registration-field">
                <label>How is your overall mood?</label>
                <%=registration.data.overall_mood%>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="registration-field">
                <label>What is your attitude towards your physical appearance?</label>
                <%=registration.data.physical_appearance%>
            </div>
        </div>
    </div>

    <hr/>

    <div class="row">
        <div class="col-lg-6">
            <div class="registration-field">
                <label>How do you feel about your relationships?</label>
                <%=registration.data.relationships%>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="registration-field">
                <label>How would you describe your stress level?</label>
                <%=registration.data.stress_level%>
            </div>
        </div>
    </div>

    <hr/>

    <div class="row">
        <div class="col-lg-6">
            <div class="registration-field">
                <label>How optimistic are you for the future?</label>
                <%=registration.data.future_optimistic%>
            </div>
        </div>
    </div>

    <hr/>

    <div class="row">
        <div class="col-lg-5">
            <div class="registration-field">
                <label>Have you seen a counselor before?</label>
                <%=registration.data.counselor_before%>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="registration-field">
                <%=registration.data.counselor_before_more%>
            </div>
        </div>
    </div>

    <hr/>

    <div class="row">
        <div class="col-lg-5">
            <div class="registration-field">
                <label>Do you use drugs or alcohol?</label>
                <%=registration.data.drugs_alcohol%>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="registration-field">
                <%=registration.data.drugs_alcohol_more%>
            </div>
        </div>
    </div>

    <hr/>

    <div class="row">
        <div class="col-lg-5">
            <div class="registration-field">
                <label>Have you had any recent changes in your eating or sleeping habits?</label>
                <%=registration.data.sleeping_changes%>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="registration-field">
                <%=registration.data.sleeping_changes_more%>
            </div>
        </div>
    </div>

    <hr/>

    <div class="row">
        <div class="col-lg-5">
            <div class="registration-field">
                <label>Do you have a medical condition or clinical diagnosis?</label>
                <%=registration.data.medical_diagnosis%>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="registration-field">
                <%=registration.data.medical_diagnosis_more%>
            </div>
        </div>
    </div>

    <hr/>

    <div class="row">
        <div class="col-lg-5">
            <div class="registration-field">
                <label>Have you ever thought about hurting yourself or someone else?</label>
                <%=registration.data.suicide_homicide%>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="registration-field">
                <%=registration.data.suicide_homicide_more%>
            </div>
        </div>
    </div>

    <hr/>

    <div class="row">
        <div class="col-lg-12">
            <div class="registration-field">
                <label>Tell us about your current job (Or if you're a student, about what you're studying!). Why did you choose this field?</label>
                <%=registration.data.pop_culture%>
            </div>
        </div>
    </div>

    <hr/>

    <div class="row">
        <div class="col-lg-12">
            <div class="registration-field">
                <label>What do you like to do in your free time?</label>
                <%=registration.data.interest%>
            </div>
        </div>
    </div>

    <hr/>

    <div class="row">
        <div class="col-lg-12">
            <div class="registration-field">
                <label>What would you attempt if you knew you could not fail?</label>
                <%=registration.data.dream%>
            </div>
        </div>
    </div>

    <hr/>

    <div class="row">
        <div class="col-lg-12">
            <div class="registration-field">
                <label>Tell us about your family.</label>
                <%=registration.data.family%>
            </div>
        </div>
    </div>

    <hr/>

    <div class="row">
        <div class="col-lg-12">
            <div class="registration-field">
                <label>What would you like to focus on while using Blush?</label>
                <%=registration.data.focus%>
            </div>
        </div>
    </div>

    <hr/>

    <div class="row">
        <div class="col-lg-6">
            <div class="registration-field">
                <label>Do you prefer to people watch or get lost in thought?</label>
                <%=registration.data.watch_thought%>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="registration-field">
                <label>Do you prefer to be more practical or more imaginative?</label>
                <%=registration.data.practical_imaginative%>
            </div>
        </div>
    </div>

    <hr/>

    <div class="row">
        <div class="col-lg-6">
            <div class="registration-field">
                <label>Do you prefer to be fair and logical, or sympathetic and personal?</label>
                <%=registration.data.objective_subjective%>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="registration-field">
                <label>Do you prefer to be more flexible &amp; go with the flow or to be scheduled &amp; form opinions?</label>
                <%=registration.data.flow_opinions%>
            </div>
        </div>
    </div>
</div>
<% } %>