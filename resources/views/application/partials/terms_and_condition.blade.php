<div class="row">
    <div class="card m-3 border-left-info">
        <div class="card-body">
            <p class="font-italic font-weight-bold text-info" style="text-indent: 50px;">
                <input class="form-check-input" name="agree_terms" type="checkbox" value="1" id="agree_terms">   
                I hereby certify that I have read and fully understood all instructions regarding my application for admission at {{ ($configuration) ? $configuration->name : '' }}, and the information supplied in this application and the documents supporting it are correct and complete. I understand that incomplete or inaccurate information could be prejudicial to my admission and retention. If accepted as a student of {{ ($configuration) ? $configuration->name : '' }}, I agree to abide by all policies and regulations.
            </p>
            <p class="font-italic font-weight-bold text-info" style="text-indent: 50px;">
                Credentials and information in support of this application become the property of {{ ($configuration) ? $configuration->name : '' }}. Documents submitted are not returnable to the applicant. Misrepresentation of information requestedin this application form and its subsequent attachments will be considered sufficient reason for refusal of admission and exclusion from the admission process.
            </p>
            @error('agree_terms')
                <p class="text-danger text-xs mt-1">{{$message}}</p>
            @enderror
            <div id="error_agree_terms" class="errors"></div>
        </div>
    </div>
</div>