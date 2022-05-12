<style>
    .mt50 {
        margin-top: 50px;
    }
    .mt10{
        margin-top: 10px;
    }
</style>

<template>
        <div class="tarifs__button mt50">
            <strong>Ваша</strong> реферальная ссылка на продукт: <a :href="link">{{ link }}</a>
            <a @click="onCopy" class="uk-button uk-button-primary mt10">КОПИРОВАТЬ</a>
        </div>
</template>

<script>
    import VueClipboard from 'vue-clipboard2';
    import swal from "sweetalert";
    import Vue from 'vue';
    
    Vue.use(VueClipboard)

    export default {
        name:  'copy-btn',
        props: {
            link: ''
        },
        methods: {
            onCopy() {
                let container = this.$refs.container;
                this.$copyText(this.link, container);
                swal('Ссылка скопирована', {
                    icon: 'success',
                    buttons: false,
                    timer: 1000,
                });
            }
        }
    }
</script>