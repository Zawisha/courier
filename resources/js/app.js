import './bootstrap';
import 'jquery-ui/dist/jquery-ui';
import Alpine from 'alpinejs';
import 'jquery-ui/themes/base/datepicker.css';
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";
import { Russian } from "flatpickr/dist/l10n/ru";
window.RussianLoc=Russian
window.Alpine = Alpine;
import jQuery from 'jquery';
window.$ = jQuery;
import Inputmask from "inputmask";


Alpine.start();
