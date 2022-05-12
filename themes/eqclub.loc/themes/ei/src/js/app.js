import Vue from 'vue'
import '../../../../modules/system/assets/js/framework.js'; 
import UIkit from 'uikit'; 
import Icons from 'uikit/dist/js/uikit-icons'
window.UIkit = UIkit;
import DatePicker from 'vue2-datepicker'
import wait from './components/ei/wait2'
import VueQRCodeComponent from 'vue-qr-generator';
import VueMask from 'v-mask';
import referals from './utils/referal.js';
import JwPagination from 'jw-vue-pagination';
import AccountRegisterComponent from './components/ei/account-register.vue';
import AccountLoginComponent from './components/ei/account-login.vue';
import AccountRestoreComponent from './components/ei/account-restore.vue';
import AccountResetComponent from './components/ei/account-reset.vue';
import AccountRefsComponent from './components/ei/account-refs.vue';
import BuyProductComponent from './components/ei/buy-product.vue';
import WheelComponent from './components/ei/wheel.vue';
import BuyModalComponent from './components/ei/buy-modal.vue';
import BuyModal2Component from './components/ei/buy-modal2.vue';
import EterWebinarComponent from './components/ei/enter-webinar.vue';
import InnerBuyComponent from './components/ei/inner_buy.vue';
import TransactionsComponent from './components/ei/transactions.vue';
import MoneyreqComponent from './components/ei/moneyreq.vue';
import CopyBtnComponent from './components/ei/copy-btn.vue';
import AccountDataComponent from './components/ei/account-data.vue';
import ProductModalComponent from './components/ei/product-modal.vue';



window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

Vue.component('account-register', AccountRegisterComponent);
Vue.component('account-login', AccountLoginComponent);
Vue.component('account-restore', AccountRestoreComponent);
Vue.component('account-reset', AccountResetComponent);
Vue.component('account-refs', AccountRefsComponent);
Vue.component('buy-product', BuyProductComponent);
Vue.component('wheel', WheelComponent);
Vue.component('buy-modal', BuyModalComponent);
Vue.component('buy-modal2', BuyModal2Component);
Vue.component('enter-webinar', EterWebinarComponent);
Vue.component('inner-buy', InnerBuyComponent);
Vue.component('transactions', TransactionsComponent);
Vue.component('moneyreq', MoneyreqComponent);
Vue.component('copy-btn', CopyBtnComponent);
Vue.component('account-data', AccountDataComponent);
Vue.component('product-modal', ProductModalComponent);
Vue.component('wait', wait);
Vue.component('qr-code', VueQRCodeComponent);
Vue.component('jw-pagination', JwPagination);



Vue.use(VueMask);

UIkit.use(Icons);

// Vue.filter('formatDate', function (value) {
//   if (value) {
//     return moment(String(value)).format('D MMMM Y');
//   }
// });

//import 'jquery-google-sheet-to-json';

//import vue2vis from 'vue2vis';
//Vue.component('timeline', vue2vis.Timeline);

//Vue.component('spin', require('./components/spin.vue'));

/*Vue.filter('cifer', (val) => {
  return val.toLocaleString('ru');
});*/

//Vue.component('timeline-component', require('./components/timeline.vue'));

/* Yandex */
let Yacounter = {
  num: '51115889',
  goal: 'ZAYKA'
};

const app = new Vue({
  el: '#app',
  data: {
    loaded: false,
    ref: false,
    wait: false,
    warn: false,
    webinar_access: false,
    modal: {
      title: "Заказать звонок",
      button: 'Отправить',
      theme: 'Заявка',
      vopr: false,
    },
    modalregister: {
      title: "Зарегестрироваться",
      button: 'Отправить',
      theme: 'Заявка', 
      vopr: false,
    },
    paymodal: {
      kurs: '',
      pay: '',
      title: '',
      product: ''
    },
    flashmes: {
      'type': '',
      'message': ''
    },
    franch: {
      buy: false,
      email: '',
      re_email: ''
    },
    confid: true,
    currentCity: {},
    selectCity: false
  },

  computed: {},
  methods: {
    buymodal(pay = 1, kurs = 'first', title = 'Купить билет на курс', product = null) {
      this.paymodal.pay = pay;
      this.paymodal.kurs = kurs;
      this.paymodal.title = title;
      this.paymodal.product = product;
    },
    setModal(title = 'Заявка', theme = 'Заявка', button = 'Отправить', vopr = false, bron = false) {
      let modal = {
        title,
        button,
        theme,
        vopr,
        bron
      };
      this.modal = modal;
      //console.log(modal);
    },

    counter() {
      if (typeof (window['yaCounter' + Yacounter.num]) !== "undefined") {
        console.log('Счетчик сработал');
        window['yaCounter' + Yacounter.num].reachGoal(Yacounter.goal);
      } else {
        console.log('Счетчик неопределен');
      }
    },
    sendForm(event) {
      let fd = {};
      let form = $(event.target);
      form.serializeArray().forEach(function (element) {
        fd[element.name] = element.value;
      });

      let app = this;
      let formdata = {
        form: fd
      };


      console.log(fd);
      $.request('onSend', {
        data: fd,
        success: function (data) {
          //... do something ...
          console.log(data);
          //this.success(data);
        },
        error: function (err) {
          console.log(err);
        }
      })

    },
    thanks() {
      UIkit.modal('#thanksmodal').show();
    },
    autoModal() {
      let app = this;
      $('.js-modal').on('click', function () {
        let text = $(this).text();
        let modal = {
          title: text,
          button: 'Отправить',
          theme: text
        };
        app.modal = modal;
        UIkit.modal('#ordermodal').show();
      });
    },
    openLightbox(el) {
      UIkit.lightbox(el).show();
    },
    maps() {
      let map = L.map('mapblock').setView([55.79788757, 49.11057300], 13);

      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: ''
      }).addTo(map);

      L.marker([55.79788757, 49.11057300]).addTo(map)
      .bindPopup('Офис компании Матирос')
      .openPopup();

      map.scrollWheelZoom.disable();

      map.on('click', function () {
        if (!map.scrollWheelZoom.enabled()) {
          map.scrollWheelZoom.enable();
        }
      });
      map.on('mouseout', function () {
        map.scrollWheelZoom.disable();
      });
      if ($(window).width() > 768) {
        map.panBy(new L.Point(-250, 0));
      } else {
        //map.panBy(new L.Point(-250, 0));
      }
    },
    appInit() {
      $('.inp__input').each(function () {
        //console.log($(this));
        $(this).on('blur', function () {
          if ($(this).val().trim() != "") {
            $(this).addClass('has-val');
          }
          else {
            $(this).removeClass('has-val');
          }
        })
      });

      let url_vars = this.getUrlVars(['ref']);
      if ('tarif' in url_vars) {
        axios.get('/product/get_uri/' + url_vars.tarif)
          .then(function (response) {
            window.location.href = '/tarifs/' + response.data;
          })
          .catch(function (error) {
            console.log(error);
          });
      }
    },
    clearUserCache() {
      $.request('onClearUserCache', {
        data: {},
        success: function (data) {
          console.log(data);
        },
        error: function (err) {
          console.log(err);
        }
      });
    },
    testPercent() {
      $.request('onTestPercent', {
        data: {},
        success: function (data) {
          console.log(data);
        },
        error: function (err) {
          console.log(err);
        }
      });
    },
    lkopen() {
      UIkit.dropdown('#ui-dropdown').hide();
      app.wait = true;
    },
    /** Работа с метками */
    getUrlVars(except = ['tarif']) {
      return referals.getUrlVars(except);
    },
    getMetkiFromStorage() {
      return referals.getMetkiFromStorage(); 
    },
    /** AJAXES */
    ajaxes() {
      let app = this;
      $(window).on('ajaxBeforeSend', function() {
        app.wait = true;
        console.log('ajaxBeforeSend');
      });

      $(window).on('ajaxUpdateComplete', function(event, context, data) {
        app.wait = false;
        console.log('ajaxUpdateComplete');
        console.log(data);
        let status = "warning";
        if (data.status) status = "success";
        if (data.message){
          swal(data.message, "", status);
        }
      });

      $(window).on('ajaxError', function(event, context, data) {
        app.wait = false;
        console.log('ajaxError');;
        console.log(data);
        if(data.message){
          swal(data.message, "", "warning");
        }
      });

      $(window).on('ajaxComplete', function() {
        app.wait = false;
        console.log('ajaxComplete');
      });

      $(window).on('ajaxSuccess', function(event, context, data, status) {
        app.wait = false;
        console.log('ajaxSuccess');
        //console.log(event, context, data);
      });

      $(window).on('ajaxConfirmMessage', function(event, message) {
        // Stop the default confirm dialog
        //console.log(event, message);
        app.wait = false;
        console.log('ajaxConfirmMessage');
        event.preventDefault();

        swal({
          title: message,
          //text: "Once deleted, you will not be able to recover this imaginary file!",
          icon: "warning",
          buttons: ['Отмена', 'Да'],
          dangerMode: true,
        })
        .then((choise) => {
          //console.log(choise);
          if (choise) {
            event.promise.resolve();
          } else {
            event.promise.reject();
          }
        });

        return true;
      });

    },
  },
  // !!components: { DatePicker, wait },
  created() {
    referals.getMetki();
    window.lkopen = this.lkopen;
  },
  mounted() {
    this.ajaxes();
    this.appInit();
    //this.autoModal();

  }
});