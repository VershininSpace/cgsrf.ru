import request from 'oc-request';
import Alpine from 'alpinejs'
import persist from '@alpinejs/persist'

window.Alpine = Alpine

Alpine.data('region_changer', () => ({
    region: null,
    toggle() {
        console.log(this.$el.target);
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
    submit(event) {
        console.log(event.target);
        request.sendForm(event.target, 'onSaveReview', {
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

Alpine.plugin(persist)
Alpine.start()
