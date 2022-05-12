import { bus } from './../ei/bus.js'
import swal from 'sweetalert'

export const modals = {
  data() {
    return {
      promocode: '',
      promocode_value: 0,
      secret_promo: 0
    }
  },
  props: {},
  methods: {
    checkPromocode() {
      console.log('CheckPromocode');
      //bus.$emit('wait', true);
      let app = this;
      let promocode = this.promocode;
      $.request('onCheckPromocode', {
        data: { promocode },
        success: function (data) {
          console.log(data);
          //bus.$emit('wait', false);
          app.$root.wait = false;
          if (data.amount) {
            swal("Промокод найден", {
              icon: 'success',
              buttons: false,
              timer: 1000,
            });
            app.promocode_value = data.amount;
            app.secret_promo = data.is_secret;
          } else {
            swal("Промокод не найден", {
              icon: 'error',
              buttons: false,
              timer: 1000,
            });
          }
        },
        error: function (err) {
          console.log(err);
        }
      });
    },
    sendform(event) {
      //bus.$emit('wait', true);
      this.$root.warn = true;
      let app = this;
      let form = event.target;
      $(form).request('onModalBuyProduct', {
        success: function (data) {
          console.log(data);
          // bus.$emit('wait', false);
          app.$root.warn = false;
          app.$root.wait = false;
          if (data.hasOwnProperty('status') && data.status == false) {
            app.checkErrors(data);
          }
          if (app.buymodal.pay == 1) {
            if (data.payment) {
              let redirect = data.payment.payUrl;
              window.location.href = redirect;
            } else {
              window.location.href = '/payment-success';
            }
          } else if (app.buymodal.pay == 0 && !app.buymodal.webinar) {
            app.$parent.thanks();
          } else if (app.buymodal.webinar == 1) {
            app.$parent.webinar_access = true;
          }
        },
        error: function (data) {
          app.$root.warn = false;
          app.$root.wait = false;
          if (data.hasOwnProperty('status') && data.status == false) {
            app.checkErrors(data);
          }
          if (app.buymodal.pay == 1 && data.payment) {
            let redirect = data.payment.payUrl;
            window.location.href = redirect;
          }
          if (app.buymodal.pay == 0) {
            app.$parent.thanks();
          }
        }
      });
    },
    setParams(parts) {
      console.log('Fucking Parts!');
      if (parts) {
        let metki = this.metki;

        for (let key in parts) {
          //console.log(key, parts[key]);
          if (parts[key]) {
            let val = parts[key];
            if (key == 'ref') {
              val = val.substr(2);
            }
            metki[key].value = val;
            metki[key].show = false;
          }
        }
      }
    },
    getMetkiFromStorage() {

      return JSON.parse(localStorage.getItem("metki"))
    },
  }
};