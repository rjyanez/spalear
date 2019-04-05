<template>
  <li class="nav-item dropdown notification">
    <a class="nav-link" href="#" role="button" data-toggle="dropdown">
      <span class=" mark icon-sm bg-danger text-white rounded-circle shadow">
        <i>25</i>
      </span>
      <i class="fas fa-bell fa-lg"></i>
    </a>
    <div class="dropdown-menu ml--9">
      <a class="dropdown-item" href="#">Action</a>
      <a class="dropdown-item" href="#">Another action</a>
      <div class="dropdown-divider"></div>
      <a class="dropdown-item" href="#">Something else here</a>
    </div>
  </li>
</template>
<script>
export default {
  name: "notification"
};
</script>

