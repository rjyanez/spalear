<template>
<div class="dropdown dropright">
  <button type="button" class="btn float-right btn-default btn-sm" data-toggle="dropdown" >
    Message
  </button>
  <form class="dropdown-menu p-4" style="min-width: 20rem">
    <div class="form-group">
      <textarea name="message" rows="10" cols="30" class="form-control" placeholder="Make a suggestion"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Send</button>
  </form>
</div>
</template>
<script>
export default {
  name: 'message',
  computed: {
    currentUser() {
      return this.$store.getters.currentUser;
    },
  },
  props: [
    'related'
  ],
  data(){
    return {      
      userRelated:{
        type: Object,
        default : ()=> this.related
      },
    }

  }
}
</script>

