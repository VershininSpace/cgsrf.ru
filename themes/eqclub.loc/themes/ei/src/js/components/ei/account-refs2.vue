<template>
	<Tree id="my-tree-id" :custom-options="myCustomOptions" :custom-styles="myCustomStyles" :nodes="treeDisplayData"></Tree>
</template>

<script>
  import Tree from 'vuejs-tree'
  export default {
    name: "account-refs",
    data() {
      return {
        treeDisplayData: [
          {
            text: 'Root 1',
            nodes: [
              {
                text: 'Child 1',
                nodes: [
                  {
                    text: 'Grandchild 1'
                  },
                  {
                    text: 'Grandchild 2'
                  }
                ]
              },
              {
                text: 'Child 2'
              }
            ]
          },
          {
            text: 'Root 2'
          }
        ]
      }
    },
    props: {
      items: ''
    },
    components: {
      'Tree': Tree
    },
    computed: {
      myCustomStyles() {
        return {
          tree: {
            height: 'auto',
            maxHeight: '300px',
            overflowY: 'visible',
            display: 'inline-block'
          },
          row: {
            width: '500px',
            cursor: 'pointer',
            child: {
              height: '35px'
            }
          },
          addNode: {
            class: 'custom_class',
            style: {
              color: '#007AD5'
            }
          },
          editNode: {
            class: 'custom_class',
            style: {
              color: '#007AD5'
            }
          },
          deleteNode: {
            class: 'custom_class',
            style: {
              color: '#EE5F5B'
            }
          },
          selectIcon: {
            class: 'custom_class',
            style: {
              color: '#007AD5'
            },
            active: {
              class: 'custom_class',
              style: {
                color: '#2ECC71'
              }
            }
          },
          text: {
            style: {},
            active: {
              style: {
                'font-weight': 'bold',
                color: '#2ECC71'
              }
            }
          }
        };
      },
      myCustomOptions() {
        return {
          treeEvents: {
            expanded: {
              state: true,
              fn: null,
            },
            collapsed: {
              state: false,
              fn: null,
            },
            selected: {
              state: false,
              fn: null,
            },
            checked: {
              state: true,
              fn: this.myCheckedFunction,
            }
          },
          events: {
            expanded: {
              state: true,
              fn: null,
            },
            selected: {
              state: false,
              fn: null,
            },
            checked: {
              state: false,
              fn: null,
            },
            editableName: {
              state: false,
              fn: null,
              calledEvent: null,
            }
          },
          addNode: { state: false, fn: null, appearOnHover: false },
          editNode: { state: true, fn: null, appearOnHover: true },
          deleteNode: { state: true, fn: null, appearOnHover: true },
          showTags: true,
        };
      }
    },
    methods: {
      getTree: function(treeId) {
        for (let i = 0; i < this.$children.length; i++) {
          if (this.$children[i].$props.id == treeId) return this.$children[i]
        }
      }
    }
  }
</script>

<style>

</style>