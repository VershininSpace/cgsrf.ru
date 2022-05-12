/** Работа с метками */


function getUrlVars(except = ['tarif']) {
    let vars = {};
    let parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function (m, key, value) {
      if(!except.includes(key)) vars[key] = decodeURIComponent(value);
    });

    return vars;
}
function getMetki() {
    let parts = getUrlVars();

    let metki = {};
    if (Object.keys(parts).length == 0) {
      console.log('меток нет, проверим в хранилище');
      metki = getMetkiFromStorage();
    } else {
      console.log('метки есть, запишем их');
      metki = parts;
      this.storageMetki(parts);
    }
}
 
function storageMetki(obj) {
    console.log('Бережно сохраняем наши метки');
    var sObj = JSON.stringify(obj);
    localStorage.setItem("metki", sObj);
}

function getMetkiFromStorage() {
    let metki = localStorage.getItem("metki");
    console.log('Проверяем, есть ли метки: ', metki);

    if (metki != null)
      return metki;
    return {};
}

function isReferal() {
    return Object.keys(getMetkiFromStorage()).length
}

export default {getUrlVars, getMetki, storageMetki, getMetkiFromStorage, isReferal};