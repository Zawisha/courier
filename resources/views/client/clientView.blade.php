@extends('layouts.main')

@section('content')
    <div class="d-flex justify-content-center align-items-center cust_main_form">
        <!-- Форма для ввода данных -->
        <form action="{{ route('checkClient') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="inputField" class="form-label">Введите данные</label>
                <input type="text" class="form-control" id="inputField" name="inputField" required>
            </div>
            <button type="submit" class="btn btn-primary">Отправить</button>
        </form>
    </div>
    <div class="d-flex justify-content-center align-items-center mt-3">
        <!-- Отображение результата, если он есть -->
        @if (session('result')=='no')
            <div class="alert alert-success text-center">
                Можно писать
            </div>
        @endif
        @if (session('result')=='yes')
            <!-- Отображение, если результата нет -->
            <div class="alert alert-danger text-center">
                Нельзя писать
            </div>
        @endif
    </div>
@endsection
