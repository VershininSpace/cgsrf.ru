<template>
	<div>
		<h5>Количество приглашенных - {{ children_count }}</h5>
		<v-jstree
			:data="items"
			children-field-name="childrens"
			allow-batch
			whole-row
      
			@item-click="itemClick">
			<template slot-scope="_">
				<div style="display: flex; width: 500px" class="cabinet__refer">
					<i :class="_.vm.themeIconClasses" role="presentation" v-if="!_.model.loading"></i>
					{{_.model.name }} <span>|</span>
					id {{_.model.id }} <span>|</span>
					{{_.model.email }}
				</div>
			</template>
		</v-jstree>
	</div>
</template>

<script>
  import '@fortawesome/fontawesome-free/css/all.min.css'
  import '@fortawesome/fontawesome-free/js/all'
  import VJstree from 'vue-jstree'

  export default {
    name: "account-refs",
    data() {
      return {
        data: []
      }
    },
    components: {
      VJstree
    },
    props: {
      items: '',
      children_count: ''
    },
    computed: {},

    methods: {
      itemClick(node) {
        //node.model.openChildren();
        //console.log(node);
        //console.log(node.model.text + ' clicked !')
      }
    }
  }
</script>

<style>

</style>