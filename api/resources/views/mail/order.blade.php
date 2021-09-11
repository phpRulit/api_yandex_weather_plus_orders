<!DOCTYPE html>
<html lang="ru-RU">
<head>
    <meta charset="utf-8">
    <title>Письмо по закрытию заказа...</title>
</head>
<body>

<div>

    Здравствуйте, {{ $data['nameRecipient'] ? $data['nameRecipient'] . ',' : '' }}
    <br>
    <br>
    {{$data['text'] ?? ''}}
    <br>
    <br>
    <b>Состав заказа:</b>
    <br>
    <span style="padding: 0; margin: 0; font-size: 1px; color: white">{{$bothPrice = 0}}</span>
    @foreach ($data['order_products'] as $order_product)
        <div class="row">
            <div class="col-md-10">{{$loop->iteration}}.
                @foreach ($data['products'] as $product)
                    @if ($loop->iteration == $loop->parent->iteration)
                    <span>{{$product->name}}; Количество: {{$order_product->quantity}}; Цена: {{$product->price}}; Общая стоимость: {{$order_product->price}}</span>
                    @endif
                @endforeach
            </div>
        </div>
        <span style="padding: 0; margin: 0; font-size: 1px; color: white">{{$bothPrice += $order_product->price}}</span>
    @endforeach
    <br>
    <br>
    <b>Общая сумма заказа: {{$bothPrice}}</b>

</div>

</body>
</html>
