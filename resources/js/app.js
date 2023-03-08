require('./bootstrap');
require('popper.js');

import Filtro from './filtros';
window.Filtro = Filtro;

import Ajax from './ajax';
window.Ajax = Ajax;

import Tela from './telas';
window.Tela = Tela;

import iziToast from 'izitoast';
window.iziToast = iziToast;

import Input from './input';
window.Input = Input;

import Utils from './utils';
window.Utils = Utils;

import {Helpers} from './helpers';
window.Helpers = Helpers;

/**
 * Altera o proprio Number do nucleo do javascript para incluir a funcao formatMoney para formatar os valores de dinheiro.
 * @param c // casas decimais
 * @param d // separador decimal
 * @param t // separador milhar
 * @returns {string}
 */
Number.prototype.formatMoney = function (c, d, t) {
    var n = this, c = isNaN(c = Math.abs(c)) ? 2 : c, d = d == undefined ? "," : d, t = t == undefined ? "." : t, s = n < 0 ? "-" : "", i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", j = (j = i.length) > 3 ? j % 3 : 0;
    return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};

/**
 * Altera o proprio String do nucleo do javascript para incluir a funcao de replaceall.
 * @param character
 * @param replaceChar
 * @returns {string}
 */
String.prototype.replaceAll = function (character, replaceChar) {
    var word = this.valueOf();

    while (word.indexOf(character) != -1)
        word = word.replace(character, replaceChar);

    return word;
};
