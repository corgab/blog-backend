<template>
    <div class="py-5">
        <div class="row row-cols-2 g-4">
            <CardContent v-if="posts" v-for="post in posts" :key="post.id" :post="post" />
            <h2 v-else>ciao</h2>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import CardContent from '../CardContent.vue'
export default {
    components: {
        CardContent
    },
    data() {
        return {
            posts: [], // Array per conservare i post
            //   loading: true, // Flag per il caricamento
        }
    },
    methods: {
        fecthPosts() {
            axios.get('http://127.0.0.1:8000/api/posts/', {
                params: {
                    per_page: 6
                }
            }
            ).then(response => {
                this.posts = response.data.data;
                console.log(response.data.data)
                console.log(this.posts)
            })
        }
    },
    mounted() {
        this.fecthPosts()
    }
}
</script>

<style lang="scss" scoped></style>