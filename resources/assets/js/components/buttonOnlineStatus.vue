<template>
	<span 
        class="rounded-circle btn btn-sm " 
        :class="{'btn-success': online,'btn-light': !online }"
        :title="(online)? 'Online' : 'Offline'"
      >
        <i class="far fa-circle"></i>
    </span>
</template>
<script>
export default {
    name: 'button-online-status',
    props: {
      online: false
    },
}
 </script>