<template>
<div class="dropdown dropright">
  <button type="button" class="btn float-right btn-default btn-sm" data-toggle="dropdown" ref="button" @click="message = ''">
    <i class="fas fa-paper-plane"></i> Send Message
  </button>
  <form class="dropdown-menu p-4" style="min-width: 20rem" @submit.prevent="submit">
    <div class="form-group">
      <textarea v-model="message" name="message" rows="10" cols="30" class="form-control" placeholder="Make a suggestion"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Send</button>
  </form>
</div>
</template>
<script>
import {addJsonToFormData} from './../helpers/general'

export default {
  name: 'button-message',
  computed: {
    currentUser() {
      return this.$store.getters.currentUser;
    },
  },
  data(){
    return {
      message: ''
    }
  },
  props: [
    'to',
    'action'
  ],
  methods: {
    submit(){
      this.$store.dispatch("sendPost", { 
        url: this.action, 
        data: addJsonToFormData({ 
          from: this.currentUser.id,
          to: this.to,
          message: this.message
        }), 
        auth: true 
      }).then(res => {
        if (res) {
          this.$refs.button.click()         
          this.$toasted.success(res.message)
          this.$emit('submitMessage')
        } else {
          this.$toasted.error("Something went wrong, please try again.")
        }
      });
     
    }
  },
}
</script>

