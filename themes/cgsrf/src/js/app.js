import request from 'oc-request';
import Alpine from 'alpinejs'
import persist from '@alpinejs/persist'
import Swiper, { Navigation, Pagination } from 'swiper';
import 'swiper/css';

function jumpto(anchor){
    window.location.href = "#"+anchor;
}

window.Alpine = Alpine

Alpine.data('region_changer', () => ({
    region: null,
    toggle() {
        request.sendData('onSwitchRegion', {
            data: {
                region: this.$el.value,
            },
            success: () => {
                this.$refs.selected.innerText = this.$el.dataset.name;
                document.location.reload();
            },
            loading: '.preloader-selector',
        });
    }
}));


Alpine.data('review_form', () => ({
    show: false,
    message: null,
    modale: true,
    show_step: false,
    submit(event) {
        request.sendForm(event.target, 'onSaveReview', {
            success: (res) => {
                this.message = 'Ваш отзыв успешно отправлен!';
                this.show = true;
                this.show_step = true;
                this.form_hidden = false,
                //jumpto('reviews-links');
                window.setTimeout(() => {
                    this.show = false;
                }, 3000);
            },
            loading: '.preloader-selector',
        });
    }
}));

Alpine.data('contact_form', () => ({
    show: false,
    message: null,
    modale: true,
    submit(event) {
        request.sendForm(event.target, 'onFormSubmit', {
            success: (res) => {
                this.message = 'Ваша форма успешно отправлена!';
                this.show = true;
                this.form_hidden = false,
                window.setTimeout(() => {
                    this.show = false;
                }, 3000);
            },
            loading: '.preloader-selector',
        });
    }
}));

Alpine.plugin(persist)
Alpine.start()

const swiper = new Swiper('.swiper-docs-ji', {
    loop: true,
    slidesPerView: 1,
    modules: [ Navigation, Pagination ],

    navigation: {
      nextEl: '.swiper-docs-ji-button-next',
      prevEl: '.swiper-docs-ji-button-prev',
    },

    
  breakpoints: {
    // when window width is >= 320px
    640: {
      slidesPerView: 2,
      spaceBetween: 20
    },
    // when window width is >= 480px
    768: {
      slidesPerView: 3,
      spaceBetween: 30
    },
    // when window width is >= 640px
    1024: {
        slidesPerView: 4, 
        spaceBetween: 40
    },
    1280: {
      slidesPerView: 5, 
      spaceBetween: 40
    }
  }
});

const swiper_ado = new Swiper('.swiper-docs-ado', {
    loop: true,
    slidesPerView: 1,
    modules: [ Navigation, Pagination ],

    navigation: {
      nextEl: '.swiper-docs-ado-button-next',
      prevEl: '.swiper-docs-ado-button-prev',
    },

    
  breakpoints: {
    // when window width is >= 320px
    640: {
      slidesPerView: 2,
      spaceBetween: 20
    },
    // when window width is >= 480px
    768: {
      slidesPerView: 3,
      spaceBetween: 30
    },
    // when window width is >= 640px
    1024: {
        slidesPerView: 4, 
        spaceBetween: 40
    },
    1280: {
      slidesPerView: 5, 
      spaceBetween: 40
    }
  }
});

const swiper_eco = new Swiper('.swiper-docs-eco', {
    loop: true,
    slidesPerView: 1,
    modules: [ Navigation, Pagination ],

    navigation: {
      nextEl: '.swiper-docs-eco-button-next',
      prevEl: '.swiper-docs-eco-button-prev',
    },

    
  breakpoints: {
    // when window width is >= 320px
    640: {
      slidesPerView: 2,
      spaceBetween: 20
    },
    // when window width is >= 480px
    768: {
      slidesPerView: 3,
      spaceBetween: 30
    },
    // when window width is >= 640px
    1024: {
        slidesPerView: 4, 
        spaceBetween: 40
    },
    1280: {
      slidesPerView: 5, 
      spaceBetween: 40
    }
  }
});