<style>
  .search, .search input {
      /*margin-left: 30px !important;*/
  }
  .search input {
    width: 50% !important;
  }
  .ref_tab {
    display: none;
  }
  .active_tab {
    display: block;
  }
  #checkbox{
    margin-bottom: 50px;
  }
</style>

<template>

<div>

  <input type="checkbox" id="checkbox" v-model="checked">
  <label for="checkbox" v-if="!checked">Списком</label>
  <label for="checkbox" v-if="checked">Деревом</label>

	<div class="ref_tab" :class="{ active_tab: checked }">
		<h5>Количество приглашенных - {{ count }}</h5>
		<v-jstree
			:data="data"
			children-field-name="childrens"
			allow-batch
			whole-row
      
			@item-click="itemClick">
			<template slot-scope="_">
				<div style="display: flex; width: 500px" class="cabinet__refer">
					<i :class="_.vm.themeIconClasses" role="presentation" v-if="!_.model.loading"></i>
					{{_.model.name }} <span>|</span>
					id {{_.model.id }} <span>|</span>
					{{_.model.email }} <span>|</span>
          {{_.model.phone }}
				</div>
			</template>
		</v-jstree>
	</div>

  

	<div class="ref_tab" :class="{ active_tab: !checked }">

    <label class="uk-form-label search">
      <span>Поиск</span>
      <input type="text" placeholder="id, имя или email" class="uk-input" v-model="searchQuery">
    </label>

  
		<ul>
			<li class="transacts__item">
				<div class="transacts__td td_w5 f-z_13">
					<strong>ID</strong>
				</div>

        <div class="transacts__td td_w20 f-z_13">
          Пользователь:<br>
					Имя<br>
          <strong>Дата регистрации</strong>
				</div>

        <div class="transacts__td td_w20 f-z_13">
					Email<br>
          Телефон
				</div>

        <div class="transacts__td td_w20 f-z_13">
          <div>
					Продукт <br>
          <span class="t_sum">Сумма</span><br>
          <strong>Дата покупки</strong>
          </div>
          
				</div>

        <div class="transacts__td td_w20 f-z_13">
          Пригласил:<br>
					id - имя<br>
          Email
				</div>

        <div class="transacts__td td_w5 f-z_13">
					Баланс<br>
				</div>

        <div class="transacts__td td_w5 f-z_13">
					Total<br>
				</div>

        <div class="transacts__td td_w5 f-z_13">
					<strong>Купил</strong>
				</div>
			</li>

      <li v-for="item in pageOfItems" class="transacts__item">
          <div class="transacts__td td_w5">
            <strong>{{ item.id }}</strong>
          </div>

          <div class="transacts__td td_w20">
            {{ item.name }}<br>
            <strong>{{ item.created_at }}</strong>
          </div>

          <div class="transacts__td td_w20 f-z_13">
            {{ item.email }}<br>
            {{ item.phone }}
          </div>

          <div v-if="item.products_buy" class="transacts__td td_w20 f-z_13">
            <div v-for="item_product in item.products_buy">
            {{ item_product.title }} <br>
            <span class="t_sum" v-if="item_product.pivot.price">{{ item_product.pivot.price }} руб.</span><br>
            <strong>{{ item_product.pivot.payment_at }}</strong>
            </div>
            
          </div>

          <div v-if="item.parent_user" class="transacts__td td_w20 f-z_13">
            {{ item.parent_user.id }} - {{ item.parent_user.name }}<br>
            {{ item.parent_user.email }}
          </div>

          <div class="transacts__td td_w5">
            {{ item.balance }} руб.<br>
          </div>

          <div class="transacts__td td_w5">
            {{ item.total }} руб.<br>
          </div>

          <div class="transacts__td td_w5">
            <strong>{{ total(item.products_buy) }} руб.</strong>
          </div>
        </li>
	</ul>

  <jw-pagination :items="resultQuery" @changePage="onChangePage" :labels="customLabels"></jw-pagination>


	</div>

</div>

</template>

<style>

  .f-z_13 {
    font-size: 13px;
  }

@media (min-width: 945px) {
  .td_w5 {
    width: 5%;
  }

  .td_w20 {
    width: 20%;
  }
}

</style>

<script>
  import '@fortawesome/fontawesome-free/css/all.min.css'
  import '@fortawesome/fontawesome-free/js/all'
  import VJstree from 'vue-jstree'


  const customLabels = {
      first: '<<',
      last: '>>',
      previous: '<',
      next: '>'
  };

  export default {
    name: "account-refs",
    data() {
      return {
        searchQuery: null,
        data: [],
        transactions: [],
        data_filter: [],
        count: 0,
        checked: true,
        paginate: ['resultQuery'],
        pageOfItems: [],
        customLabels
      }
    },
    components: {
      VJstree,
    },

    props: {
      items: '',
      children_count: ''
    },
    computed: {
    },

    methods: {

      onChangePage(pageOfItems) {
          this.pageOfItems = pageOfItems;
          document.getElementById('cab_tab').scrollIntoView();
      },

      itemClick(node) {
        //node.model.openChildren();
        //console.log(node);
        //console.log(node.model.text + ' clicked !')
      },

      total(pr) {
          if (!pr) return 0;
          let num = 0;
          pr.forEach(element => {
            num += parseInt(element.pivot.price) || 0;
          });
          return num;
      },

      addKey(arr){
        arr.forEach((element) => {
            this.addKey(element.childrens);
            element.opened = false;
            element.icon = 'fa fa-user';
            //console.log(element);
            this.count += 1;
        });

        return arr;
      },

      getTransactions() {
        let app = this;
        this.$root.wait = true;
        $.request('onGetChildrenPayments', {
          success: function (data) {
            app.$root.wait = false;
            app.transactions = data;
          },
          error: function (err) {
            console.log(err);
          }
        });
      },
      flatten(arr) {
        return arr.reduce((result, current) => {
            if (current.childrens) {
              const childrens = this.flatten(current.childrens);
              //delete current.childrens;
              result.push(current);
              result.push(...childrens);
            } else {
              result.push(current);
            }
            return result;
        }, [])
      }
    },

    mounted() {
      this.data = this.addKey(this.items);
      this.getTransactions();

      let calc_data = this.items;
      calc_data = this.flatten(calc_data);

      this.data_filter = calc_data.sort(function(a, b) {
        return parseInt(b.id) - parseInt(a.id);
      });
    },

    computed: {
      resultQuery(){
        if(this.searchQuery){
        return this.data_filter.filter((item)=>{
          return this.searchQuery.toLowerCase().split(' ').every(v => item.user_datas.toLowerCase().includes(v))
        })
        }else{
          return this.data_filter;
        }
      }
    }

  }
</script>

<style>

.active .page-link {
    background: #57b846;
    color: #fff;
}
</style>