import request from 'oc-request';
import Alpine from 'alpinejs'

window.Alpine = Alpine


window.isNumberKey = function (evt) {
    console.log(false);
}

// Alpine.data('region_changer', () => ({
//     region: null,
//     toggle() {
//         console.log(this.$el.dataset.code);
//         request.sendData('onSwitchRegion', {
//             data: {
//                 region: this.$el.dataset.code,
//             },
//             success: () => {
//                 this.$refs.selected.innerText = this.$el.innerText;
//             },
//             loading: '.preloader-selector',
//         });
//     }
// }));

Alpine.data('region_changer', () => ({
    region: null,
    toggle() {
        console.log(this.$el.value);
        request.sendData('onSwitchRegion', {
            data: {
                region: this.$el.value,
            },
            success: () => {
                this.$refs.selected.innerText = this.$el.dataset.name;
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
        console.log(event.target);
        request.sendForm(event.target, 'onFormSubmit', {
            success: (res) => {
                console.log(res);
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

// var region_changers = document.querySelectorAll(".region_change_item");


// region_changers.forEach(e => {
//     e.onclick = function() {
//         console.log(e.dataset.code)
//         request.sendData('onSwitchRegion', {
//             data: {
//                 region: e.dataset.code,
//             },
//             // update: { 'product/slider/slider-ajax': '.slider-ajax-wrapper' },

//             loading: '.preloader-selector',
//         });
//     };
// });


Alpine.start()
