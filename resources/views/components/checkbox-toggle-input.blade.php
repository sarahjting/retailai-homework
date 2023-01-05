@props(['disabled' => false, 'checked' => false, 'id' => ''])

<div class="form-check form-switch">
    <input
       type="checkbox"
       role="switch"
       id="{{$id}}"
       {{ $checked ? 'checked' : '' }}
       {{ $disabled ? 'disabled' : '' }}
       {{ $attributes->merge(['class' => 'form-check-input']) }}
    >
    @if ($slot)
    <label class="form-check-label" @if ($id) for="{{ $id }} @endif">{{ $slot }}</label>
    @endif
</div>
