import './bootstrap';
import Alpine from 'alpinejs'
window.Alpine = Alpine
import flatpickr from "flatpickr";
import { Turkish } from "flatpickr/dist/l10n/tr.js";
import "flatpickr/dist/flatpickr.min.css";

flatpickr.localize(Turkish);
Alpine.start()
window.flatpickr = flatpickr;
