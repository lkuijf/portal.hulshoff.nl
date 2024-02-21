@php
$filterSelectedOptionToDisplay = $filter_selected_option;
if(app()->getLocale() == 'en' && $enTranslation = config('hulshoff.productgroup_translations.' . $filterSelectedOptionToDisplay)) {
    $filterSelectedOptionToDisplay = $enTranslation;
}
@endphp
<p class="activeFilter"><span>{{ $filter_name }}:&nbsp;{{ $filterSelectedOptionToDisplay }}</span><a href="#">&nbsp;</a></p>