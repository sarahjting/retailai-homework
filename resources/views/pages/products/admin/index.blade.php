<x-app-layout>
    <div class="row justify-content-md-center">
        <div class="col-12 col-md-10 col-lg-8 py-3 px-4">
            <div class="card p-4">
                <h2>{{ __('Products admin dashboard') }}</h2>

                @can(\App\Enums\PermissionEnum::PRODUCTS_CREATE->value)
                <a href="{{route('products.admin.create')}}" class="btn btn-primary mb-3">
                    {{ __("Create product") }}
                </a>
                @endcan

                @can(\App\Enums\PermissionEnum::PRODUCTS_READ->value)
                @if (!$products->count())
                    {{ __("No products have been added yet.") }}
                @else
                    {!! $products->render() !!}
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <td></td>
                            <td>{{ __("SKU") }}</td>
                            <td>{{ __("Name") }}</td>
                        </tr>
                        </thead>
                        @foreach ($products as $product)
                            <tr>
                                <td style="width: 100px;">
                                    <img src="{{ $product->image_url }}" class="img-fluid" />
                                </td>
                                <td style="vertical-align: middle">
                                    @can(\App\Enums\PermissionEnum::PRODUCTS_UPDATE->value)
                                        <a href="{{ route('products.admin.edit', ['product' => $product]) }}">
                                            {{ $product->sku }}
                                        </a>
                                    @else
                                        {{ $product->sku }}
                                    @endcan
                                </td>
                                <td style="vertical-align: middle">
                                    {{ $product->name }}
                                </td>
                            </tr>
                        @endforeach
                    </table>
                    {!! $products->render() !!}
                @endif
                @endcan
            </div>
        </div>
    </div>
</x-app-layout>
