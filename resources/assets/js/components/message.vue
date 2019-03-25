<template>
<div class="dropdown">
  <button type="button" class="btn float-right btn-default btn-sm" data-toggle="dropdown" >
    Message
  </button>
  <form class="dropdown-menu p-4 mt-6" style="width=5rem">
    <div class="form-group">
      <textarea name="message" rows="10" cols="30" class="form-control" placeholder="make a suggestion"></textarea>
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

