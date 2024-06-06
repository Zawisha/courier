<x-guest-layout>
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
    <div class="header_reg_text">{{__('custom.registerHeader')}}.</div>
    @if ($errors->has('custom_error'))
        <div class="alert_danger_errors">
            {{ $errors->first('custom_error') }}
        </div>
    @endif
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <!-- Phone -->
        <div class="mt-4">
            <x-input-label for="phone" :value="__('custom.Phone')" />
            <input type="text" placeholder="{{__('custom.Phone')}}" name="phone" class="phone_mask" id="phone" value="{{ old('phone') }}">
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>
        <!--First name -->
        <div>
            <x-input-label for="name" :value="__('custom.Name')" />
            <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')"  autofocus autocomplete="first_name" />
            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
        </div>
        <!-- Last name -->
        <div>
            <x-input-label for="surname" :value="__('custom.Surname')" />
            <x-text-input id="surname" class="block mt-1 w-full" type="text" name="surname" :value="old('surname')"  autofocus autocomplete="surname" />
            <x-input-error :messages="$errors->get('surname')" class="mt-2" />
        </div>
        <!-- Patronymic -->
        <div>
            <x-input-label for="patronymic" :value="__('custom.Patronymic')" />
            <x-text-input id="patronymic" class="block mt-1 w-full" type="text" name="patronymic" :value="old('patronymic')"  autofocus autocomplete="patronymic" />
            <x-input-error :messages="$errors->get('patronymic')" class="mt-2" />
        </div>
        <!-- Role -->
        <div class="mt-4">
            <x-input-label for="role" :value="__('custom.Role')" />
            <select id="role" name="role" class="block mt-1 w-full"  onchange="toggleNameFields()">
                @foreach($statusCourier as $status)
                    <option value="{{ $status->value_status }}" {{ old('role') == $status->value_status ? 'selected' : '' }}>
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
        <div class="mt-4">
            <x-input-label for="workRule" :value="__('custom.WorkRule')" />
            <select id="workRule" name="workRule" class="block mt-1 w-full">
                @foreach($workRules as $oneRule)
                    <option value="{{ $oneRule->id }}">
                        @if ($locale == 'ru' || $locale == null)
                            {{ $oneRule->name }}{{ $oneRule->descr_ru }}
                        @else
                            {{ $oneRule->name }}{{ $oneRule->descr_eng }}
                        @endif
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>
        <!-- Date of birth -->
        <div class="mt-4">
            <x-input-label for="date_of_birth" :value="__('custom.Date of birth')" />
            <input id="date_of_birth" type="text" name="date_of_birth" placeholder="{{__('custom.choose_bd')}}" value="{{ old('date_of_birth')}}">
            <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
        </div>

        <!-- Водительское удостоверение -->
        <!-- Серия и номер водительского удостоверения -->
        <div class="mt-4 jsDriver">
            <x-input-label for="surname" :value="__('custom.Driver license number')" />
            <x-text-input id="licenceNumber" class="block mt-1 w-full" type="text" name="licenceNumber" :value="old('licenceNumber')"  autofocus autocomplete="licenceNumber" placeholder=""
                          oninput="this.value = this.value.replace(/[^a-zA-Z0-9]/g, '')"
                          onkeypress="return /[a-zA-Z0-9]/.test(event.key)"
            />
            <x-input-error :messages="$errors->get('licenceNumber')" class="mt-2" />
        </div>
        <!-- Дата выдачи водительского удостоверения-->
        <div class="mt-4 jsDriver">
            <x-input-label for="license_issue" :value="__('custom.Driver license issue date')" />
            <input id="license_issue" type="text" name="license_issue" placeholder="{{__('custom.choose_date')}}" value="{{ old('license_issue')}}">
            <x-input-error :messages="$errors->get('license_issue')" class="mt-2" />
        </div>
        <!-- Дата окончания водительского удостоверения-->
        <div class="mt-4 jsDriver">
            <x-input-label for="license_expirated" :value="__('custom.Driver license expiration date')" />
            <input id="license_expirated" type="text" name="license_expirated" placeholder="{{__('custom.choose_date')}}" value="{{ old('license_expirated')}}">
            <x-input-error :messages="$errors->get('license_expirated')" class="mt-2" />
        </div>
        <!-- Страна выдачи удостоверения -->
        <div class="mt-4 jsDriver">
            <x-input-label for="driverCountry" :value="__('custom.driverCountryHead')" />
            <x-input-label for="driverCountry" :value="__('custom.threeLetterCode')" />
            <x-text-input id="driverCountry" class="block mt-1 w-full" type="text" name="driverCountry" :value=" old('driverCountry') ?? 'rus' " maxlength="3"
                          oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"
                          onkeypress="return /[a-zA-Z]/.test(event.key)"
            />
            <x-input-error :messages="$errors->get('driverCountry')" class="mt-2" />
        </div>
        <!-- Telegram -->
        <div>
            <x-input-label for="telegram" :value="__('custom.Telegram')" />
            <x-text-input id="telegram" class="block mt-1 w-full" type="text" name="telegram" :value="old('telegram')" />
            <x-input-error :messages="$errors->get('telegram')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('custom.Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"  autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('custom.Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                             autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('custom.Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation"  autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
{{--            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">--}}
{{--                {{ __('Already registered?') }}--}}
{{--            </a>--}}

            <x-primary-button class="ms-4">
                {{ __('custom.Register') }}
            </x-primary-button>
        </div>
    </form>
    <script>
        function toggleNameFields() {
            const role = document.getElementById('role').value;
            var elements = document.getElementsByClassName('jsDriver');
            var displayStyle = (role === 'moto' || role === 'avto' || role === 'gruz') ? 'block' : 'none';

            for (var i = 0; i < elements.length; i++) {
                elements[i].style.display = displayStyle;
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


</x-guest-layout>
