<x-sendportal.field-wrapper :name="$name" :label="$label">
    <input type="hidden" name="{{ $name }}_country_code" value="{{ $value[0] }}" {{ $attributes->merge(['id' => 'id-field-country-code-' .  str_replace('[]', '', $name), 'class' => 'form-control']) }}>
    <input type="hidden" name="{{ $name }}_area_code" value="{{ $value[1] }}" {{ $attributes->merge(['id' => 'id-field-area-code-' .  str_replace('[]', '', $name), 'class' => 'form-control']) }}>
    <input type="hidden" name="{{ $name }}_number" value="{{ $value[2] }}" {{ $attributes->merge(['id' => 'id-field-number-' .  str_replace('[]', '', $name), 'class' => 'form-control']) }}>
    <input type="text" name="{{ $name }}_widget" value="{{ $value[2] }}" {{ $attributes->merge(['id' => 'id-field-widget-' .  str_replace('[]', '', $name), 'class' => 'form-control']) }}>
    <span {{ $attributes->merge(['id' => 'id-field-error-message-' .  str_replace('[]', '', $name), 'class' => 'text-danger help-block']) }}></span>
</x-sendportal.field-wrapper>

@push('css')
<link
     rel="stylesheet"
     href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css"
   />
   <style>
       .iti.iti--allow-dropdown{
           width: 100%;
       }
   </style>
@endpush

@push('js')
    <script src="https://unpkg.com/libphonenumber-js@1.9.6/bundle/libphonenumber-max.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
    <script>
        const phoneInputWidget = document.querySelector("#{{ 'id-field-widget-' .  str_replace('[]', '', $name) }}");
        const phoneInputField = document.querySelector("#{{ 'id-field-number-' .  str_replace('[]', '', $name) }}");
        const phoneAreaCodeField = document.querySelector("#{{ 'id-field-area-code-' .  str_replace('[]', '', $name) }}");
        const phoneCountryCodeField = document.querySelector("#{{ 'id-field-country-code-' .  str_replace('[]', '', $name) }}");

        const errorMessage = document.querySelector("#{{ 'id-field-error-message-' .  str_replace('[]', '', $name) }}");

        const phoneInput = window.intlTelInput(phoneInputWidget, {
            initialCountry: "pk",
            preferredCountries: ["pk"],
            separateDialCode: true,
            utilsScript:
            "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"
        });
        // phoneInput.localizedCountries({ "pk" : 'Pakistan'});
        phoneInputWidget.addEventListener("change", function() {
            // do something with iti.getSelectedCountryData()
            let countryObject = phoneInput.getSelectedCountryData();

            
            let countryCode = countryObject.iso2.toUpperCase();
            
            if(!phoneInput.isValidNumber()){
                errorMessage.innerHTML = "Invalid number";
                return false;
            }

            try{
                const parsedContactObject = libphonenumber.parsePhoneNumber(phoneInputWidget.value,countryCode)

                let parsedContact = parsedContactObject.format('INTERNATIONAL');

                let contactArray = parsedContact.split(" ");

                phoneCountryCodeField.value = contactArray[0];
                phoneAreaCodeField.value = contactArray[1];
                phoneInputField.value = phoneInputWidget.value;

                console.log(countryObject, phoneInputWidget.value, parsedContact);
                errorMessage.innerHTML = "";

            }catch(error){
                errorMessage.innerHTML = "Invalid number";
                console.error(error);
            }
            
            

            
            
        });
    </script>
@endpush
