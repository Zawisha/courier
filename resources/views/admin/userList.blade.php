@extends('layouts.main')
@section('content')
    <div class="container">
        <h1 class="my-4">Список курьеров</h1>
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
