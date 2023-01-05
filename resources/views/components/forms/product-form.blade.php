@props(['product' => null])

@csrf

<div class="mb-4">
    <x-input-label for="name" class="mb-1" :value="__('Name')" />
    <x-text-input id="name" type="text" name="name" :value="old('name') ?? $product?->name" required autofocus />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>

<div class="mb-4">
    <x-input-label for="sku" class="mb-1" :value="__('SKU')" />
    <x-text-input id="sku" type="text" name="sku" :value="old('sku') ?? $product?->sku" required />
    <x-input-error :messages="$errors->get('sku')" class="mt-2" />
</div>

<div class="mb-4">
    <x-input-label for="image" class="mb-1" :value="__('Image')" />
    <input type="file" id="image" name="image" class="form-control" {{ !$product && 'required' }} />
    <x-input-error :messages="$errors->get('image')" class="mt-2" />
</div>

<div class="mb-4">
    <x-input-label for="description" class="mb-1" :value="__('Description')" />
    <x-textarea-input id="description" type="text" name="description" :value="old('description') ?? $product?->description" />
    <x-input-error :messages="$errors->get('description')" class="mt-2" />
</div>
