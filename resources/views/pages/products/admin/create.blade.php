<x-app-layout>
    <div class="row justify-content-md-center">
        <div class="col-12 col-md-6 py-3 px-4">
            <div class="card p-4">
                <h2>{{ __('Add new product') }}</h2>
                <form method="POST" action="{{ route('products.admin.store') }}" enctype="multipart/form-data">
                    <x-forms.product-form></x-forms.product-form>
                    <x-primary-button class="w-100">{{ __('Create') }}</x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
