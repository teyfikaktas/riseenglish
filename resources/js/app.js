import './bootstrap'

// 1) Flatpickr'ı import edin ve locale'i ayarlayın
import flatpickr from "flatpickr";
import { Turkish } from "flatpickr/dist/l10n/tr.js";
import "flatpickr/dist/flatpickr.min.css";
import Phaser from 'phaser';

flatpickr.localize(Turkish);

// 2) Global scope'a atayın —**ALPINE.START'tan ÖNCE**  
window.flatpickr = flatpickr;
window.Phaser = Phaser;

// Game dosyasını import et (tek dosya olarak)
import './game.js';

// 3) Ardından Alpine'ı başlatın
import Alpine from 'alpinejs'
window.Alpine = Alpine
Alpine.start()