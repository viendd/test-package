<!-- select -->
@php
    $current_value = old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? '';
    $entity_model = $crud->getRelationModel($field['entity'],  - 1);
    $field['allows_null'] = $field['allows_null'] ?? $entity_model::isColumnNullable($field['name']);
    //if it's part of a relationship here we have the full related model, we want the key.
    if (is_object($current_value) && is_subclass_of(get_class($current_value), 'Illuminate\Database\Eloquent\Model') ) {
        $current_value = $current_value->getKey();
    }
    $fields = $crud->getFields();
    $dependenceField = @$fields[$field['field_change']] ?? [];
    if (!isset($field['options'])) {
        $optionsDependence = $dependenceField['model']::all();
    } else {
        $optionsDependence = call_user_func($dependenceField['options'], $dependenceField['model']::query());
    }

    if (!@$entry) {
        if (isset($dependenceField['default']))
        {
            $defaultOption = $optionsDependence->where('id', $dependenceField['default'])->first();
        } else {
            $defaultOption = $optionsDependence->first();
        }

    } else {
        $defaultOption = $optionsDependence->where('id', $entry[$field['field_change']])->first();
    }

    if (!isset($field['options'])) {
        $options = $field['model']::where($field['field_change'], $defaultOption->id)->get();
    } else {
        $options = call_user_func($field['options'], $field['model']::where($field['field_change'], $defaultOption->id));
    }
@endphp

@include('crud::fields.inc.wrapper_start')

<label>{!! $field['label'] !!}</label>
@include('crud::fields.inc.translatable_icon')

<select
    name="{{ $field['name'] }}"
    @include('crud::fields.inc.attributes')
>

    @if ($field['allows_null'])
        <option value="">-</option>
    @endif

    @if (count($options))
        @foreach ($options as $connected_entity_entry)
            @if($current_value == $connected_entity_entry->getKey())
                <option value="{{ $connected_entity_entry->getKey() }}" selected>{{ $connected_entity_entry->{$field['attribute']} }}</option>
            @else
                <option value="{{ $connected_entity_entry->getKey() }}">{{ $connected_entity_entry->{$field['attribute']} }}</option>
            @endif
        @endforeach
    @endif
</select>

{{-- HINT --}}
@if (isset($field['hint']))
    <p class="help-block">{!! $field['hint'] !!}</p>
@endif

@include('crud::fields.inc.wrapper_end')
@push('crud_fields_scripts')
    <script>
        var fieldNameDependence = 'select[name="{{ $field['field_change'] }}"]'
        var fieldName= 'select[name="{{ $field['name'] }}"]'
        var url = '{{ url($field['url']) }}'
        $( document ).ready(function() {
           $(fieldNameDependence).on('change', function (e) {
               var value = $(this).val();
                $.ajax({
                    url: url + '?{{ $field['field_change'] }}=' + value,
                    type: 'GET',
                    dataType: 'json',
                    success: function (res) {
                        $(fieldName).html('');
                        var i;
                        $(fieldName).append('<option value="">-</option>');
                        for (i = 0; i < res.length; i++) {
                            $(fieldName).append('<option value="'+res[i].id+'">'+res[i].{{ $field['attribute'] }}+'</option>');
                        }
                        $(fieldName).val('');
                    }
                })
           })
        });
    </script>
@endpush
