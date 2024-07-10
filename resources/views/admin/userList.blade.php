@extends('layouts.main')
@section('content')
    <div class="container">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <h1 class="my-4">Список курьеров</h1>
        @can('admin perm')
        <div class="container">
            <div class="d-flex align-items-center">
                <span>Отправка в яндекс:</span>
                <div class="form-check ms-2">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" {{ $isChecked ? 'checked' : '' }}>
                </div>
            </div>
        </div>
        @endcan
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="thead-dark">
                <tr>
                    <th>id</th>
                    <th>Телефон</th>
                    <th>Имя Фамилия</th>
                    <th>Email</th>
                    <th>Роль</th>
                    <th>Условия</th>
                    <th>телеграм</th>
                    <th>Машина</th>
                    <th>Дата регистрации</th>
                    <th>Редактировать</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($couriers as $courier)
                    <tr>
                        <td>{{ $courier->user_id }}</td>
                        <td>{{ $courier->name }}</td>
                        <td>{{ $courier->first_name }}{{ $courier->surname }}</td>
                        <td>{{ $courier->email }}</td>
                        <td>{{ $courier->role_status }}</td>
                        <td>{{ $courier->work_rule_name }}</td>
                        <td>{{ $courier->telegram }}</td>
                        <td>{{ $courier->car_brand }}</td>
                        <td>{{ $courier->created_data }}</td>
                        <td>
                            <a href="{{ url('/edit-user', ['id' => $courier->user_id]) }}">
                                Редактировать
                            </a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <!-- Пагинация -->
        <div class="d-flex justify-content-center">
            {{ $couriers->links() }}
        </div>
    </div>
@endsection
<script type="module">
    $(document).ready(function() {
        $('#flexCheckDefault').change(function() {
            let isChecked = $(this).is(':checked');
            if(isChecked)
            {
                isChecked=1;
            }
            else
            {
                isChecked=0;
            }
            $.ajax({
                url: "/send_to_yandex_change", // маршрут для обработки запроса на сервере
                type: 'post',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    isChecked: isChecked
                },
                success: function(response) {
                    console.log(response.message);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });
    });
</script>