<template>
	<div>
    
    <div v-if="transactions.length > 0">
		<ul class="transacts__items">
			<li class="transacts__item" v-for="item in pageOfItems">
				<div class="transacts__td" v-if="item.fromuser">
					<strong>Поступление от:</strong><br>
					{{ item.fromuser.name }}<br>
					{{ item.fromuser.email }}<br>
					{{ item.fromuser.phone }}<br>
				</div>
				<div class="transacts__td" v-if="item.buyer">
					<strong>Покупатель:</strong><br>
					{{ item.buyer.name }}<br>
					{{ item.buyer.email }}<br>
					{{ item.buyer.phone }}<br>
					<span v-if="item.buyer.parent">
					Пригласил: {{ item.buyer.parent.id }} - {{ item.buyer.parent.name }}
						</span>
				</div>
				<div class="transacts__td" v-if="item.product">
					{{ item.product.title }}
				</div>
				<div class="transacts__td" v-if="item.total < 0">
					Вывод денег
				</div>
				<div class="transacts__td">
					<span v-if="item.buyer && item.buyer.products">
						Полная сумма: {{ findSum(item) }} руб.<br>
					</span>
					<span class="t_sum">Сумма: {{ item.total }} руб. </span><br>
					<span v-if="item.level">Уровень: {{ item.level }}</span><br>
          <strong>{{ item.created_at }}</strong><br>
          </div>
				<div class="transact__td"></div>
			</li>
		</ul>
    <jw-pagination :items="transactions" @changePage="onChangePage" :labels="customLabels"></jw-pagination>
    </div>
		<p v-else>У вас пока нет поступлений</p>
	</div>
</template>

<script>

  const customLabels = {
      first: '<<',
      last: '>>',
      previous: '<',
      next: '>'
  };

  export default {
    name: "transactions",
    data() {
      return {
        transactions: [],
        paginate: ['transactions'],
        pageOfItems: [],
        customLabels
      }
    },
    props: {
      items: ''
    },
    computed: {},
    methods: {

      onChangePage(pageOfItems) {
          this.pageOfItems = pageOfItems;
          document.getElementById('cab_tab').scrollIntoView();
      },

      findSum(item) {
        if (item.product) {
          let id = item.product.id;
          let prod = item.buyer.product_users;
          let user_prod = prod.find(function (item, index, array) {
            return item.id = id;
          });
          if (user_prod) {
            return user_prod.price;
          }
        }

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
      }
    },
    mounted() {
      this.getTransactions();
    }
  }
</script>
