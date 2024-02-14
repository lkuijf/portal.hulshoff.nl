<select name="language" class="lanSelect">
    <option value="nl"@if(app()->getLocale() == 'nl'){{ ' selected' }}@endif>🇳🇱&emsp;Nederlands</option>
    <option value="en"@if(app()->getLocale() == 'en'){{ ' selected' }}@endif>🇺🇸&emsp;English</option>
</select>