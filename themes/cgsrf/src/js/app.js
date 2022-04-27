import request from 'oc-request';
import Alpine from 'alpinejs'

window.Alpine = Alpine

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
                document.location.reload();
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

Alpine.start()
