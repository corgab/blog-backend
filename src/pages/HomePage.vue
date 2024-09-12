<template>
    <div class="container">
        <!-- <CardContent v-if="posts" v-for="post in posts" :key="post.id" :post="post"/> -->
        <!-- <h2 v-else>Nessun Post...</h2> -->
         <CardHighLights/>
    </div>
</template>

<script>
// import CardContent from "../components/CardContent.vue";
import CardHighLights from '../components/PageComponents/HighLights.vue'

export default {
    components: {
        CardHighLights
    }
}
</script>

<style lang="scss" scoped></style>