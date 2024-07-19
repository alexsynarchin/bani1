<div>
    <label>Имя: </label>
    <span>{{$order->client->name}}</span>
</div>
<div>
    <label>Телефон: </label>
    <span>{{$order->client->phone}}</span>
</div>
<div>
    <label>Сумма бронирования: </label>
    <span>{{$order->price}}</span>
</div>
<div>
    <label>Дата: </label>
    <span>{{$order['reservations']['0']['date']}}</span>
</div>
<div>
    <label>Начало бронировани: </label>
    <span>{{$order['reservations']['0']['start_time']}}</span>
</div>
<div>
    <label>Окончание бронирования: </label>
    <span>{{$order['reservations']['0']['end_time']}}</span>
</div>
<div>
    @foreach($order->reservations as $reservation)
        @if($reservation->reservationable_type === 'App\\Models\\Place')
        Место
        @else
        Кабинка
        @endif
        {{$reservation-> reservationable->number}}<br>
    @endforeach
</div>

