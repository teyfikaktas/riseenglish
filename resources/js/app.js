import './bootstrap'

// 1) Flatpickr’ı import edin ve locale’i ayarlayın
import flatpickr from "flatpickr";
import { Turkish } from "flatpickr/dist/l10n/tr.js";
import "flatpickr/dist/flatpickr.min.css";

flatpickr.localize(Turkish);

// 2) Global scope’a atayın —**ALPINE.START’tan ÖNCE**  
window.flatpickr = flatpickr;

// 3) Ardından Alpine’ı başlatın
import Alpine from 'alpinejs'
window.Alpine = Alpine
Alpine.start()
