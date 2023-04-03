{{-- @extends('templates.development') --}}
@extends('templates.portal')
@section('content')
<div class="reportsContent">
    <h1>{{ __('Reports') }}</h1>
    <p>{{ __('Make your selection to generate a report') }}.</p>
    <form action="{{ url('validate-report') }}" method="POST">
        @csrf
        <table>
            <tr>
                <td>{{ __('Period') }}</td>
                <td>
                    <div id="dateRange">
                        <input type="text" name="start" value="{{ old('start') }}">
                        <span>{{ __('to') }}</span>
                        <input type="text" name="end" value="{{ old('end') }}">  
                    </div>
                </td>
            </tr>
            <tr>
                <td>{{ __('Customer') }}</td>
                <td>
                    {{-- <input type="checkbox" name="clientCheck"> --}}
                    <select name="client">
                        <option value="">- {{ __('Select') }} -</option>
                        @foreach ($data['clients'] as $client)
                        <option value="{{ $client->klantCode }}" @if(old('client') == $client->klantCode){{ 'selected' }}@endif>{{ $client->naam }}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr>
                <td>{{ __('User') }}</td>
                <td>
                    {{-- <input type="checkbox" name="userCheck"> --}}
                    <select name="user" data-old="{{ old('user') }}">
                        <option value="">- {{ __('Select a customer first') }} -</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>{{ __('Report') }} type</td>
                <td>
                    <input type="radio" name="reportType" value="orders" id="ordersRef" @if(old('reportType') == 'orders'){{ 'checked' }}@endif><label for="ordersRef">Orders</label><br />
                    <input type="radio" name="reportType" value="total_orders" id="total_ordersRef" @if(old('reportType') == 'total_orders'){{ 'checked' }}@endif><label for="total_ordersRef">{{ __('Total') }} orders</label><br />
                    <input type="radio" name="reportType" value="total_products" id="total_productsRef" @if(old('reportType') == 'total_products'){{ 'checked' }}@endif><label for="total_productsRef">{{ __('Total') }} {{ strtolower(__('Products')) }}</label>
                </td>
            </tr>
        </table>
        <p><button type="submit" name="generateType" value="view">{{ __('Generate') }} {{ strtolower(__('Report')) }}</button></p>
        <p><button type="submit" name="generateType" value="pdf" disabled>{{ __('Export to PDF') }}</button></p>
        <p><button type="submit" name="generateType" value="csv" disabled>{{ __('Export to CSV') }}</button></p>
    </form>

    {{-- @if (isset($report))
    {{ print_r($report) }}
    @endif --}}
    <div class="reportWrapper"></div>

</div>
@endsection
@section('extra_head')
    <link rel="stylesheet" href="{{ asset('css/datepicker.min.css') }}">
    <script src="{{ asset('js/datepicker-full.min.js') }}"></script>
@endsection
@section('before_closing_body_tag')
<script>
    @if(session('message') && session('generate_type'))
    const generateReport = true;
    const typeOfGeneration = "{{ session('generate_type') }}";
    @else
    const generateReport = false;
    const typeOfGeneration = false;
    @endif
    const range = document.getElementById('dateRange');
    const start = document.querySelector('[name=start]');
    const end = document.querySelector('[name=end]');
    const clientSelect = document.querySelector('[name=client]');
    const userSelect = document.querySelector('[name=user]');
    const reportTypeRadios = document.querySelectorAll('[name=reportType]');
    const oldUserValue = userSelect.dataset.old;
    const reportResult = document.querySelector('.reportWrapper');
    const pdfBtn = document.querySelector('[value=pdf]');
    const csvBtn = document.querySelector('[value=csv]');

    const viewBtn = document.querySelector('[value=view]'); //
    const generateBtns = document.querySelectorAll('[name=generateType]'); //

    populateUserSelect();

    const rangepicker = new DateRangePicker(range, {
        format: 'dd-mm-yyyy'
    });

    if(generateReport) {
        let reportTypeValue = false;
        reportTypeRadios.forEach(radio => {
            if(radio.checked) reportTypeValue = radio.value;
        });

        axios.post('{{ url('/ajax/generate-report') }}', {
                startDate:start.value,
                endDate:end.value,
                klantCode:clientSelect.value,
                userId:oldUserValue,
                reportType:reportTypeValue,
                generateTypeValue:typeOfGeneration,
            })
            .then(function (response) {
                // handle success
                // console.log(response.data);
                // reportResult.innerHTML = '<p><button type="submit">{{ __('Export to PDF') }}</button></p>'
                reportResult.innerHTML = response.data;
                pdfBtn.disabled = false;
                csvBtn.disabled = false;
                showMessage('success',"<p>{{ __('Report generated successfully') }}</p>")
                openExportFile();
            })
            .catch(function (error) {
                // handle error
                console.log(error);
            })
            .finally(function () {
                // always executed
            });
    }

    clientSelect.addEventListener('change', () => {
        resetElementStates();
        populateUserSelect();
    });
    userSelect.addEventListener('change', () => {
        resetElementStates();
    });
    start.addEventListener('blur', () => {
        resetElementStates();
    });
    end.addEventListener('blur', () => {
        resetElementStates();
    });
    reportTypeRadios.forEach(radio => {
        radio.addEventListener('change', () => {
            resetElementStates();
        });
    });

    function resetElementStates() {
        reportResult.innerHTML = '';
        pdfBtn.disabled = true;
        csvBtn.disabled = true;
    }
    
    function populateUserSelect() {
        if(clientSelect.value) {
            userSelect.innerHTML = '';
            
            axios.get('{{ url('/ajax/users') }}' + '/' +  clientSelect.value)
                .then(function (response) {
                    // handle success
                    // console.log(response.data);
                    if(response.data.length) {
                        let defaultOption = document.createElement('option');
                        let defaultOptionText = document.createTextNode("- {{ __('Select') }}  -");
                        defaultOption.value = '';
                        defaultOption.appendChild(defaultOptionText);
                        userSelect.appendChild(defaultOption);
                        response.data.forEach(element => {
                            let newOption = document.createElement('option');
                            let newOptionText = document.createTextNode(element.name);
                            newOption.value = element.hulshoff_user_id;
                            if(element.hulshoff_user_id == oldUserValue) {
                                newOption.selected = true;
                                newOption.setAttribute('selected', true);
                            }
                            newOption.appendChild(newOptionText);
                            userSelect.appendChild(newOption);
                        });
                    }
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
                .finally(function () {
                    // always executed
                });
        }
    }

    function openExportFile() {
        const resultTable = reportResult.querySelector('table');
        if(resultTable && resultTable.dataset.exportfile) {
            window.open(resultTable.dataset.exportfile, '_blank').focus();
        }
    }
</script>
@endsection