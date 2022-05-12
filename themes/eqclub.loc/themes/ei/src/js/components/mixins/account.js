export const account = {
  data() {
    return {
      errors: '',
      success: ''
    }
  },
  props: {
    url: ''
  },
  methods: {
    checkErrors(data){
      console.log(data);
      let app = this;
      if(data.status === false){
        this.errors = data.message;
        setTimeout(function(){
          app.errors = '';
        }, 2000);
      } else if(data.status === true){
        this.success = data.message;
        setTimeout(function(){
          app.success = '';
        }, 2000);
      } else if(data.X_OCTOBER_REDIRECT){
        window.location.href = data.X_OCTOBER_REDIRECT;
      }
    }
  }
};