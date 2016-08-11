<template>
    <main>
        <navbar></navbar>
        <div class="container">
            <router-view></router-view>
        </div>
    </main>

    <footer class="sticky-footer">
        <div class="container">
            ola sticky footer
        </div>
    </footer>
</template>
<script>
    import Navbar from './components/Layout/Navbar.vue';
    import Events from './components/Events.vue';
    import Event from './components/Event.vue';
    import CreateEvent from './components/Forms/CreateEvent.vue';

    export default {
      components: { Navbar, Events, Event, CreateEvent },
      replace: false,
    }
</script>
