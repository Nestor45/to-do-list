<template>
    <div id="main">
        <Hearder/>
        <div class="content">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
    import Hearder from './Header.vue'
    export default{
        name: 'main-app',
        components: {
            Hearder,
        }
    }
</script>
