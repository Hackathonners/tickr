<template>
  <nav class="navbar">
    <div class="container">
      <div class="navbar-header">
        <a class="navbar-brand" v-link="{ name: 'events' }">
          <img alt="Brand" height="34" src="/images/logo.svg">
        </a>
      </div>
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            <div class="profile-info">
              <div class="profile-info-username">Francisco Neves <span class="caret"></span></div>
              <div class="small text-muted profile-info-email">fntneves@gmail.com</div>
            </div>
          </a>
          <ul class="dropdown-menu">
            <li><a href="#">Action</a></li>
            <li><a href="#">Another action</a></li>
            <li><a href="#">Something else here</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="#">Separated link</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
</template>
