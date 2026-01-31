@php
    use Illuminate\Support\Str;
    $editId = isset($edit_id) ? $edit_id : null;
    $labelClass = isset($label_class) ? $label_class: '';
    $chosenSize = isset($chosen_size) ? $chosen_size : '100-percent';
    $selectName = Str::snake(Str::singular($modelVariable)) . '_id';
    $isRequired = isset($is_required);

    $selectTitle =   ucwords(implode(" ", preg_split('/(?=[A-Z])/', Str::singular($modelVariable))));
@endphp



    <select id="{{$selectName}}" name="{{$selectName}}" {{ $isRequired ? 'required' : '' }}
    class="tom-select  {{ $isRequired ? 'required' : '' }}"
            data-placeholder="- Select {{$selectTitle}} -">
        <option value=""></option>

        @foreach($$modelVariable as $item)
            <option value="{{$item->id}}" {{request($selectName) == $item->id ? 'selected' : ''}}>{{$item->name}}</option>
        @endforeach
    </select>
