{{-- Bot trap: humans never see or fill the "website" field; bots usually do.
     form_ts lets the server reject forms submitted faster than a human could. --}}
<div style="position:absolute;left:-10000px;top:auto;width:1px;height:1px;overflow:hidden;" aria-hidden="true">
    <label for="website_{{ $trapId ?? 'f' }}">Leave this field empty</label>
    <input type="text" name="website" id="website_{{ $trapId ?? 'f' }}" value="" tabindex="-1" autocomplete="off">
</div>
<input type="hidden" name="form_ts" value="{{ encrypt(time()) }}">
