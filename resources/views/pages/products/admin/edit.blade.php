<x-app-layout>
    <div class="row justify-content-md-center">
        <div class="col-12 col-md-6 py-3 px-4">
            <div class="card p-4 mb-4">
                <h2>{{ __('Update product:') }} {{ $product->name }}</h2>
                <img src="{{ $product->image_url }}" class="img-fluid mb-4"/>
                <form method="POST" action="{{ route('products.admin.update', ['product' => $product]) }}" enctype="multipart/form-data">
                    @method('PUT')
                    <x-forms.product-form :product="$product"></x-forms.product-form>
                    <x-primary-button class="w-100">{{ __('Update') }}</x-primary-button>
                </form>
            </div>
            <div class="card p-4">
                <h2>{{ __('Delete product') }}</h2>
                <form method="POST" action="{{ route('products.admin.delete', ['product' => $product]) }}">
                    @method('DELETE')
                    @csrf
                    <x-danger-button class="w-100">{{ __('Delete') }}</x-danger-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
