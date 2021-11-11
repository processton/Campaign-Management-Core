<x-sendportal.text-field name="name" :label="__('Campaign Name')" :value="$campaign->name ?? old('name')" />
<x-sendportal.select-field name="type" :label="__('Compeign Type')" :options="['EMAIL' => 'Email Campaign','SMS' => 'Sms Campaign']" :value="$campaign->type ?? old('type')" />
<x-sendportal.text-field name="subject" :label="__('Email Subject')" :value="$campaign->subject ?? old('subject')" />
<x-sendportal.text-field name="from_name" :label="__('From Name')" :value="$campaign->from_name ?? old('from_name')" />
<x-sendportal.text-field name="from_email" :label="__('From Email')" type="email" :value="$campaign->from_email ?? old('from_email')" />

<x-sendportal.select-field name="template_id" :label="__('Template')" :options="$templates" :value="$campaign->template_id ?? old('template_id')" />

<x-sendportal.select-field name="email_service_id" :label="__('Email Service')" :options="$emailServices->pluck('formatted_name', 'id')" :value="$campaign->email_service_id ?? old('email_service_id')" />
<x-sendportal.select-field name="sms_service_id" :label="__('Sms Service')" :options="$emailServices->pluck('formatted_name', 'id')" :value="$campaign->email_service_id ?? old('email_service_id')" />

<x-sendportal.checkbox-field name="is_open_tracking" :label="__('Track Opens')" value="1" :checked="$campaign->is_open_tracking ?? true" />
<x-sendportal.checkbox-field name="is_click_tracking" :label="__('Track Clicks')" value="1" :checked="$campaign->is_click_tracking ?? true" />

<x-sendportal.textarea-field name="content" :label="__('Content')">{{ $campaign->content ?? old('content') }}</x-sendportal.textarea-field>

<div class="form-group row">
    <div class="offset-sm-3 col-sm-9">
        <a href="{{ route('sendportal.campaigns.index') }}" class="btn btn-light">{{ __('Cancel') }}</a>
        <button type="submit" class="btn btn-primary">{{ __('Save and continue') }}</button>
    </div>
</div>

@include('sendportal::layouts.partials.summernote')

@push('js')
    <script>

        $(function () {
            const smtp = {{
                $emailServices->filter(function ($service) {
                    return $service->type_id === \Sendportal\Base\Models\EmailServiceType::SMTP;
                })
                ->pluck('id')
            }};

            let service_id = $('select[name="email_service_id"]').val();

            toggleTracking(smtp.includes(parseInt(service_id, 10)));

            $('select[name="email_service_id"]').on('change', function () {
              toggleTracking(smtp.includes(parseInt(this.value, 10)));
            });

            $('#id-field-type').on('change', function(e) {
                if(e.target.value == "SMS"){
                    $('.form-group-subject').hide();
                    $('.form-group-from_name').hide();
                    $('.form-group-from_email').hide();
                    $('.form-group-template_id').hide();
                    $('.form-group-email_service_id').hide();
                    $('.form-group-is_open_tracking').hide();
                    $('.form-group-is_click_tracking').hide();
                    
                }else{

                    $('.form-group-subject').show();
                    $('.form-group-from_name').show();
                    $('.form-group-from_email').show();
                    $('.form-group-template_id').show();
                    $('.form-group-email_service_id').show();
                    $('.form-group-is_open_tracking').show();
                    $('.form-group-is_click_tracking').show();
                }
                
            })
        });

        function toggleTracking(disable) {
            let $open = $('input[name="is_open_tracking"]');
            let $click = $('input[name="is_click_tracking"]');

            if (disable) {
                $open.attr('disabled', 'disabled');
                $click.attr('disabled', 'disabled');
            } else {
                $open.removeAttr('disabled');
                $click.removeAttr('disabled');
            }
        }

    </script>
@endpush
