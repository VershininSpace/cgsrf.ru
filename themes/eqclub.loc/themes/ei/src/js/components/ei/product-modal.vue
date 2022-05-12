<template>
  <div class="uk-modal-full" id="product-modal" uk-modal="">
    <div class="uk-modal-dialog">
        <button class="uk-modal-close-full uk-close-large" type="button" uk-close=""></button>
        <div class="modalmenu">
        <h2 class="uk-modal-title">Регистрация</h2>
          <div v-if="isReferal">
            <p>Для получения доступа к продукту необходимо заполнить форму или <a href="/login">ВОЙТИ</a></p>
            
            <form data-request="onModalRegister">
                
                <input placeholder="Как к вам обращаться*" type="text" required="required" name="name" class="uk-input"> 
                <input placeholder="Email* " required="required" type="email" name="email" class="uk-input"> 
                <input placeholder="Телефон* " required="required" type="text" name="phone" class="uk-input"> 
                <div class="confid"><label><input type="checkbox"><span>Я согласен с</span>
                <a uk-toggle="target: #politicamodal">политикой конфиденциальности</a></label></div> 
                <button type="submit" class="uk-button uk-button-primary uk-width-1-1">Отправить</button></form>
            </form>
          </div>
          <div v-else>
              Пройти дальше можно только по рекомендации члена клуба. Не знаете где её взять?
              <a href="/send" class="uk-button uk-button-text">Напишите нам</a>
          </div>
        </div>
    </div>
  </div>
</template>

<script>
import { bus } from './bus.js'
import referals from '../../utils/referal.js';

  export default {
    name: "product-modal",
    methods: {
    },
    computed: {
      isReferal: function() {
        return referals.isReferal();
      }
    },
  }
</script>