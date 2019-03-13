<template>
  <div class="header pb-8 pt-5 pt-lg-8 d-flex align-items-center" style="background-image: url(/img/teachers-cover.jpg); background-size: cover; background-position: center top;">
    <!-- Mask -->
    <span class="mask bg-gradient-default opacity-8"></span>
    <!-- Header container -->
    <div class="container-fluid d-flex align-items-center">
        <div class="row">
            <div class="col-md-12">
                <h1 class="display-2 text-white">{{ name }}</h1>
                  <p v-if="description" class="text-white mt-0 mb-5">{{ description }}</p>
            </div>
        </div>
    </div>
</div> 
</template>
<script>
export default {
  name: 'header-teacher',
  props: ['name','description']
}
</script>


