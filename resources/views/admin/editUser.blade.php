@extends('layouts.main')
@section('content')
    <div class="min-h-screen flex flex-col  items-center pt-6 sm:pt-0 bg-gray-100">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
    <div class="container">
       Редактирование курьера
    </div>

        @php
            $locale = App::getLocale();
        @endphp
        <div class="locale-container">
            @if ($locale == 'ru' || $locale == null)
                <a href="{{ url('locale/en') }}">ENG</a>
            @else
                <a href="{{ url('locale/ru') }}">RU</a>
            @endif
        </div>
        @if ($errors->has('custom_error'))
            <div class="alert_danger_errors">
                {{ $errors->first('custom_error') }}
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">
               {{ session('success') }}
            </div>
       @endif
        <form method="POST" action="{{ route('editCourier') }}" id="form_register">
            @csrf
            <input type="hidden" name="id" value="{{ $user->id }}">
            <!-- Phone -->
            <div class="mt-4">
                <x-input-label for="phone" :value="__('custom.Phone')" />
                <input type="text" placeholder="{{__('custom.Phone')}}" name="phone" class="phone_mask" id="phone" value="{{ old('phone', $user->name) }}">
                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            </div>
            <!--First name -->
            <div>
                <x-input-label for="name" :value="__('custom.Name')" />
                <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}"  autofocus autocomplete="first_name" />
                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
            </div>
            <!-- Last name -->
            <div>
                <x-input-label for="surname" :value="__('custom.Surname')" />
                <x-text-input id="surname" class="block mt-1 w-full" type="text" name="surname" value="{{ old('first_name', $user->surname) }}"  autofocus autocomplete="surname" />
                <x-input-error :messages="$errors->get('surname')" class="mt-2" />
            </div>
            <!-- Patronymic -->
            <div>
                <x-input-label for="patronymic" :value="__('custom.Patronymic')" />
                <x-text-input id="patronymic" class="block mt-1 w-full" type="text" name="patronymic" value="{{ old('first_name', $user->patronymic) }}"  autofocus autocomplete="patronymic" />
                <x-input-error :messages="$errors->get('patronymic')" class="mt-2" />
            </div>
            <!-- Role -->
            <div class="mt-4">
                <x-input-label for="role" :value="__('custom.Role')" />
                <select id="role" name="role" class="block mt-1 w-full" onchange="toggleNameFields()">
                    @foreach($statusCourier as $status)
                        <option value="{{ $status->value_status }}" {{ old('role', $user->value_status) == $status->value_status ? 'selected' : '' }}>
                            @if ($locale == 'ru' || $locale == null)
                                {{ $status->status }}
                            @else
                                {{ $status->status_eng }}
                            @endif
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('role')" class="mt-2" />
            </div>
{{--            выбор тарифа--}}
            <div class="mt-4">
                <x-input-label for="workRule" :value="__('custom.WorkRule')" />
                <select id="workRule" name="workRule" class="block mt-1 w-full">
                    @foreach($workRules as $oneRule)
                        <option value="{{ $oneRule->id }}" {{ old('workRule', $user->work_rule_id) == $oneRule->id ? 'selected' : '' }}>
                            @if ($locale == 'ru' || $locale == null)
                                {{ $oneRule->name }} {{ $oneRule->descr_ru }}
                            @else
                                {{ $oneRule->name }} {{ $oneRule->descr_eng }}
                            @endif
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('role')" class="mt-2" />
            </div>
            <!-- Date of birth -->
            <div class="mt-4">
                <x-input-label for="date_of_birth" :value="__('custom.Date of birth')" />
                <input id="date_of_birth" type="text" name="date_of_birth" placeholder="{{__('custom.choose_bd')}}" value="{{ old('date_of_birth', $user->date_of_birth)}}">
                <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
            </div>

            <!-- Водительское удостоверение -->
            <!-- Серия и номер водительского удостоверения -->
            <div class="mt-4 jsDriver">
                <x-input-label for="surname" :value="__('custom.Driver license number')" />
                <x-text-input id="licenceNumber" class="block mt-1 w-full" type="text" name="licenceNumber" value="{{ old('licenceNumber', $user->licenceNumber)}}"  autofocus autocomplete="licenceNumber" placeholder=""
                              oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                              onkeypress="return /[0-9]/.test(event.key)"
                              maxlength="10"
                />
                <x-input-error :messages="$errors->get('licenceNumber')" class="mt-2" />
            </div>
            <!-- Дата выдачи водительского удостоверения-->
            <div class="mt-4 jsDriver">
                <x-input-label for="license_issue" :value="__('custom.Driver license issue date')" />
                <input id="license_issue" type="text" name="license_issue" placeholder="{{__('custom.choose_date')}}" value="{{ old('license_issue', $user->license_issue)}}">
                <x-input-error :messages="$errors->get('license_issue')" class="mt-2" />
            </div>
            <!-- Дата окончания водительского удостоверения-->
            <div class="mt-4 jsDriver">
                <x-input-label for="license_expirated" :value="__('custom.Driver license expiration date')" />
                <input id="license_expirated" type="text" name="license_expirated" placeholder="{{__('custom.choose_date')}}" value="{{ old('license_expirated', $user->license_expirated)}}">
                <x-input-error :messages="$errors->get('license_expirated')" class="mt-2" />
            </div>
            <!-- Страна выдачи удостоверения -->
            <div class="mt-4 jsDriver">
                <x-input-label for="driverCountry" :value="__('custom.driverCountryHead')" />
                <x-input-label for="driverCountry" :value="__('custom.threeLetterCode')" />
                <x-text-input id="driverCountry" class="block mt-1 w-full" type="text" name="driverCountry" value="{{ old('driverCountry', $user->driverCountry) ?? 'rus'}} " maxlength="3"
                              oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"
                              onkeypress="return /[a-zA-Z]/.test(event.key)"
                />
                <x-input-error :messages="$errors->get('driverCountry')" class="mt-2" />
            </div>
            <!-- транспортное средство -->
            <!-- высота ТС -->
            <div class="mt-4 jsDriverGruz">
                <x-input-label for="cargoHoldDimensionsHeight" :value="__('custom.cargoHoldDimensionsHeight')" />
                <select id="cargoHoldDimensionsHeight" name="cargoHoldDimensionsHeight" class="block mt-1 w-full">
                    @for ($i = 90; $i <= 250; $i++)
                        <option value="{{ $i }}" {{ old('cargoHoldDimensionsHeight', $user->cargoHoldDimensionsHeight) == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
                <x-input-error :messages="$errors->get('cargoHoldDimensionsHeight')" class="mt-2" />
            </div>

            <!-- длина ТС -->
            <div class="mt-4 jsDriverGruz">
                <x-input-label for="cargoHoldDimensionsLength" :value="__('custom.cargoHoldDimensionsLength')" />
                <select id="cargoHoldDimensionsLength" name="cargoHoldDimensionsLength" class="block mt-1 w-full">
                    @for ($i = 170; $i <= 601; $i++)
                        <option value="{{ $i }}" {{ old('cargoHoldDimensionsLength',$user->cargoHoldDimensionsLength) == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
                <x-input-error :messages="$errors->get('cargoHoldDimensionsLength')" class="mt-2" />
            </div>

            <!-- ширина ТС -->
            <div class="mt-4 jsDriverGruz">
                <x-input-label for="cargoHoldDimensionsWidth" :value="__('custom.cargoHoldDimensionsWidth')" />
                <select id="cargoHoldDimensionsWidth" name="cargoHoldDimensionsWidth" class="block mt-1 w-full">
                    @for ($i = 96; $i <= 250; $i++)
                        <option value="{{ $i }}" {{ old('cargoHoldDimensionsWidth',$user->cargoHoldDimensionsWidth) == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
                <x-input-error :messages="$errors->get('cargoHoldDimensionsWidth')" class="mt-2" />
            </div>
            <!-- грузоподъёмность ТС -->
            <div class="mt-4 jsDriverGruz">
                <x-input-label for="cargoCapacity" :value="__('custom.cargoCapacity')" />
                <select id="cargoCapacity" name="cargoCapacity" class="block mt-1 w-full">
                    @for ($i = 300; $i <= 6000; $i+= 100)
                        <option value="{{ $i }}" {{ old('cargoCapacity',$user->cargoCapacity) == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
                <x-input-error :messages="$errors->get('cargoCapacity')" class="mt-2" />
            </div>
            <!-- количество грузчиков -->
            <div class="mt-4 jsDriverGruz" >
                <x-input-label for="cargoLoaders" :value="__('custom.cargoLoaders')" />
                <select id="cargoLoaders" name="cargoLoaders" class="block mt-1 w-full">
                    <option value="0" {{ old('cargoLoaders', $user->cargoLoaders) == '0' ? 'selected' : '' }}>0</option>
                    <option value="1" {{ old('cargoLoaders', $user->cargoLoaders) == '1' ? 'selected' : '' }}>1</option>
                    <option value="2" {{ old('cargoLoaders', $user->cargoLoaders) == '2' ? 'selected' : '' }}>2</option>
                </select>
                <x-input-error :messages="$errors->get('cargoLoaders')" class="mt-2" />
            </div>
            <!--номер машины -->
            <div class="mt-4 jsDriver noMoto">
                <x-input-label for="licencePlateNumber" :value="__('custom.licencePlateNumber')" />
                <x-text-input id="licencePlateNumber" class="block mt-1 w-full" type="text" name="licencePlateNumber" value="{{ old('licencePlateNumber', $user->licencePlateNumber) }}"
                              oninput="validateLicencePlate(this)"
                              maxlength="9"
                />
                <img src="{{ asset('images/imgs/numberAuto.png') }}" alt="Пример номера автомобиля" class="mb-4">
                <x-input-error :messages="$errors->get('licencePlateNumber')" class="mt-2" />
            </div>
            <!--Номер свидетельства о регистрации машины -->
            <div class="mt-4 jsDriver noMoto">
                <x-input-label for="registrationCertificate" :value="__('custom.registrationCertificate')" />
                <x-text-input id="registrationCertificate" class="block mt-1 w-full" type="text" name="registrationCertificate" value="{{ old('registrationCertificate', $user->registrationCertificate) }}" maxlength="20"
                              oninput="this.value = this.value.replace(/[^a-zA-Z0-9]/g, '')"
                              onkeypress="return /[a-zA-Z0-9]/.test(event.key)"
                />
                <x-input-error :messages="$errors->get('registrationCertificate')" class="mt-2" />
            </div>
            <!--бренд машины список-->
            <div class="mt-4 jsDriver noMoto">
                <x-input-label for="brandTS" :value="__('custom.brandTS')" />
                <select id="brandTS" name="brandTS" class="block mt-1 w-full" onchange="filterModels()">
                    @foreach($carBrand as $brand)
                        <option value="{{ $brand->id }}" {{ old('brandTS', $user->brandTS_id) == $brand->id ? 'selected' : ($loop->first ? 'selected' : '') }}>
                            {{ $brand->car_brand }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('brandTS')" class="mt-2" />
            </div>
            <!--модель машины список-->
            <div class="mt-4 jsDriver noMoto">
                <x-input-label for="modelTS" :value="__('custom.modelTS')" />
                <select id="modelTS" name="modelTS" class="block mt-1 w-full">
                    <!-- Этот список будет динамически заполнен JavaScript -->
                </select>
                <x-input-error :messages="$errors->get('modelTS')" class="mt-2" />
            </div>
            <!--цвет машины -->
            <div class="mt-4 jsDriver noMoto">
                <x-input-label for="carColor" :value="__('custom.Color')" />
                <select id="carColor" name="carColor" class="block mt-1 w-full">
                    @foreach($carColors as $carColor)
                        <option value="{{ $carColor->id }}" {{ old('carColor', $user->colorAvto_id) == $carColor->id ? 'selected' : '' }}>
                            @if ($locale == 'ru' || $locale == null)
                                {{ $carColor->color_ru }}
                            @else
                                {{ $carColor->color_eng }}
                            @endif
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('role')" class="mt-2" />
            </div>

            <!--год выпуска машины список -->
            <div class="mt-4 jsDriver noMoto">
                <x-input-label for="carManufactureYear" :value="__('custom.carManufactureYear')" />
                <select id="carManufactureYear" name="carManufactureYear" class="block mt-1 w-full">
                    @foreach($yearsManuf as $year)
                        <option value="{{ $year }}" {{ old('carManufactureYear', $user->carManufactureYear) == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('carManufactureYear')" class="mt-2" />
            </div>

            <!--трансмиссия машины -->
            <div class="mt-4 jsDriver noMoto">
                <x-input-label for="Transmission" :value="__('custom.Transmission')" />
                <select id="Transmission" name="Transmission" class="block mt-1 w-full">
                    @foreach($carTransmission as $car)
                        <option value="{{ $car->id }}" {{ old('Transmission', $user->transmission_id) == $car->id ? 'selected' : '' }}>
                            @if ($locale == 'ru' || $locale == null)
                                {{ $car->transmission_ru }}
                            @else
                                {{ $car->transmission_eng }}
                            @endif
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('role')" class="mt-2" />
            </div>
            <!--вин машины -->
            <div class="mt-4 jsDriver noMoto">
                <div class="flex items-center">
                    <x-input-label for="vin" :value="__('custom.vin')" />
                </div>
                <x-text-input id="vin" class="block mt-1 w-full" type="text" name="vin" value=" {{ old('vin', $user->vin) }}"
                              oninput="validateVIN(this)"
                              maxlength="17"
                              placeholder="1HGCM82633A123456"
                />
                <x-input-error :messages="$errors->get('vin')" class="mt-2" />
            </div>
            <!-- Telegram -->
            <div>
                <x-input-label for="telegram" :value="__('custom.Telegram')" />
                <x-text-input id="telegram" class="block mt-1 w-full" type="text" name="telegram" value="{{old('telegram',$user->telegram)}}"
                              oninput="this.value = this.value.replace(/[^a-zA-Z0-9@_()!№$%&?*-|]/g, '')"
                              onkeypress="return /[a-zA-Z0-9@_()!№$%&?*-|]/.test(event.key)"
                />
                <x-input-error :messages="$errors->get('telegram')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-input-label for="email" :value="__('custom.Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" value="{{old('email',$user->email)}}"  autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-primary-button class="ms-4" onclick="registerWithGif()" id="registerButton">
                    {{ __('custom.Save') }}
                </x-primary-button>

            </div>
        </form>

        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div id="registerGif">
                            <img src="{{ asset('images/gif/gif.webp') }}" alt="Registering..." />
                        </div>
                    </div>

                </div>
            </div>
        </div>
        </div>
        </div>
    <script>
        const carData = @json($carBrand);
        function filterModels() {
            const brandSelect = document.getElementById('brandTS');
            const modelSelect = document.getElementById('modelTS');
            const selectedBrandId = brandSelect.value;
            const selectedModelId = @json($user->modelTS_id);// Значение user->modelTS_id из Blade шаблона
            // Очистить список моделей
            modelSelect.innerHTML = '';

            if (selectedBrandId) {
                // Найти выбранный бренд
                const selectedBrand = carData.find(brand => brand.id == selectedBrandId);

                if (selectedBrand && selectedBrand.car_model.length > 0) {
                    // Добавить модели выбранного бренда
                    selectedBrand.car_model.forEach((model, index) => {
                        const option = document.createElement('option');
                        option.value = model.id;
                        option.textContent = model.car_model;
                        // Установить выбранным нужную модель
                        if (model.id === selectedModelId) {
                            option.selected = true;
                        }
                        modelSelect.appendChild(option);
                    });
                }
            }
        }
        // Инициализация моделей для первого бренда при загрузке страницы
        document.addEventListener('DOMContentLoaded', function() {
            const brandSelect = document.getElementById('brandTS');
            const firstBrandId = brandSelect.options[brandSelect.selectedIndex].value;
            if (firstBrandId) {
                filterModels();
            }
        });
        function registerWithGif() {
            // Показать модальное окно
            var myModal = new bootstrap.Modal(document.getElementById('exampleModalCenter'), {
                keyboard: false
            });
            myModal.show();
            document.getElementById("registerButton").disabled = true;
            const form = document.getElementById('form_register');
            form.classList.add('semi-transparent');
            document.getElementById("form_register").submit();
        }
        function toggleNameFields() {
            const role = document.getElementById('role').value;
            var elements = document.getElementsByClassName('jsDriver');
            var displayStyle = (role === 'moto' || role === 'avto' || role === 'gruz') ? 'block' : 'none';
            for (var i = 0; i < elements.length; i++) {
                elements[i].style.display = displayStyle;
            }

            var elementsGruz = document.getElementsByClassName('jsDriverGruz');
            var displayStyleGruz = (role === 'gruz') ? 'block' : 'none';
            for (var i = 0; i < elementsGruz.length; i++) {
                elementsGruz[i].style.display = displayStyleGruz;
            }

            var elementsGruz = document.getElementsByClassName('noMoto');
            var displayStyleMoto = (role === 'moto' || role === 'pesh'|| role === 'velo') ? 'none' : 'block';
            for (var i = 0; i < elementsGruz.length; i++) {
                elementsGruz[i].style.display = displayStyleMoto;
            }

        }

        function validateLicencePlate(input) {
            let value = input.value.toUpperCase(); // Приведение к верхнему регистру
            let newValue = '';

            const regex = /^[А-ЯЁ]?\d{0,3}[А-ЯЁ]{0,2}\d{0,3}$/;

            // Пробегаем по каждому символу введенного значения и формируем новое значение
            for (let i = 0; i < value.length; i++) {
                if (i === 0) {
                    if (/[\u0400-\u04FF]/.test(value[i])) {
                        newValue += value[i];
                    }
                } else if (i >= 1 && i <= 3) {
                    if (/\d/.test(value[i])) {
                        newValue += value[i];
                    }
                } else if (i >= 4 && i <= 5) {
                    if (/[\u0400-\u04FF]/.test(value[i])) {
                        newValue += value[i];
                    }
                } else if (i >= 6 && i <= 8) {
                    if (/\d/.test(value[i])) {
                        newValue += value[i];
                    }
                }
            }

            // Проверка значения на соответствие нужному формату
            if (regex.test(newValue)) {
                input.value = newValue;
            } else {
                input.value = newValue.substring(0, newValue.length - 1);
            }
        }

        function validateVIN(input) {
            let value = input.value.toUpperCase(); // Приведение к верхнему регистру
            const regex = /^[A-HJ-NPR-Z0-9]{0,17}$/; // Разрешены только символы VIN (без I, O, Q)

            // Проверка значения на соответствие нужному формату
            if (regex.test(value)) {
                input.value = value;
            } else {
                input.value = value.substring(0, value.length - 1);
            }
        }

    </script>
    <script type="module">


        // Initialize the form with the correct fields shown/hidden
        document.addEventListener('DOMContentLoaded', toggleNameFields);

        document.addEventListener("DOMContentLoaded", function() {
            // Массив с идентификаторами элементов, к которым нужно применить flatpickr
            var datePickers = ["#date_of_birth", "#license_issue", "#license_expirated"];

            var locale;
            @if ($locale == 'ru' || $locale == null)
                locale = window.RussianLoc;
            @else
                locale = null;
            @endif

            // Цикл для итерации по массиву идентификаторов и применения flatpickr
            for (var i = 0; i < datePickers.length; i++) {
                flatpickr(datePickers[i], {
                    dateFormat: "d-m-Y", // Формат даты: день-месяц-год
                    locale: locale
                });
            }

        });
        document.addEventListener("DOMContentLoaded", function() {
            const phoneInput = document.getElementById("phone");
            if (phoneInput) {
                Inputmask({"mask": "+7 (999) 999-9999"}).mask(phoneInput);
            }
        });
    </script>



@endsection
