<x-app-layout>
    <div class="row justify-content-md-center">
        <div class="col-12 col-md-10 py-3 px-4">
            <h2>{{ __('Products') }}</h2>
            @if (!$products->count())
                {{ __("No products have been added yet.") }}
            @else
                {!! $products->render() !!}
                <div class="row">
                    @foreach ($products as $product)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="card mb-4">
                            <div class="w-100">
                                <img src="{{ $product->image_url }}" class="rounded-top img-fluid" />
                            </div>
                            <div class="p-3">
                                <div class="d-flex flex-column align-center justify-content-between flex-lg-row">
                                    <h5 class="mb-1 pb-0">{{ $product->name }}</h5>
                                    <h6 class="text-muted">{{ $product->sku }}</h6>
                                </div>
                                {{ $product->description }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                {!! $products->render() !!}
            @endif
        </div>
    </div>
</x-app-layout>
