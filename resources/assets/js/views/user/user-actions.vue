<template>
  <div class="col text-right">
    <div class="btn-group " role="group">
      <button
        v-if="admin"
        title="User Create"
        class="btn btn-sm btn-primary"
        :disabled="action === 'create' ? true : false"
        @click="setAction('create')"
        type="button"
      >
        Create
      </button>
      <button
        v-if="current || admin"
        title="User Update"
        class="btn btn-sm btn-primary"
        :disabled="action === 'create' || action === 'update' ? true : false"
        @click="setAction('update')"
        type="button"
      >
        Edit
      </button>
      <button
        v-if="admin"
        title="User Delete"
        class="btn btn-sm btn-primary"
        :disabled="action === 'create' || action === 'destroy' || current ? true : false"
        @click="setAction('destroy')"
        type="button"
      >
        Delete
      </button>
    </div>
  </div>
</template>
<script>
export default {
  name: 'user-actions',
  props: [
    'action',
    'current',
    'admin'
  ],
  methods: {
    setAction(action){
      this.$emit("setAction",action)
    }
  }
}
</script>

