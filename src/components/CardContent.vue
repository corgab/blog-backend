<template>
    <div class="col">
        <div class="card">
            <div v-for="img in post.images" class="position-relative">
                <img v-if="img.is_featured == 1" :src="img.link" class="card-img-top img-thumbnail p-0 border-white" alt="...">
                <!-- Immagine preset -->

                <div class="position-absolute d-flex gap-3 tag">
                    <p v-for="tag in post.tags" class="text-body-emphasis">
                        <i class="bi bi-circle-fill"></i> {{ tag.name }} <!-- Colore -->
                    </p>
                </div>
            </div>
            <div class="card-body">
                <div v-if="post.featured" class="card-text"> <!-- da sistemare-->
                    <div class="d-flex fs-6 gap-1 text-body-tertiary">
                        <i class="bi bi-exclamation-circle d-block"></i>
                        <p>Premium</p> <!-- Colore -->

                    </div>
                </div>
                <h5 class="card-title">{{ post.title }}</h5>
                <p class="card-text text-truncate text-body-secondary ">{{ post.content }}</p> <!-- Colore-->
                <p class="card-text">{{ post.reading_time }}</p>
            </div>
            <div class="card-footer bg-body d-flex gap-2">
                <img src="..." alt="user img">
                <p class="fs-6 text-body-secondary ">by {{ post.user.name }} • {{ post.created_date }}</p>

            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'CardContent',
    props: {
        post: {
            type: Object,
            required: true
        }

    }
}
</script>

<style lang="scss" scoped>
.tag {
    bottom: 5%;
    left: 2%;
}

.card {
    --bs-card-border-width: 0,
    --bs-card-spacer-y: 0rem;
    --bs-card-spacer-x: 0rem;
}

.card-footer {
    border-top: 0;
    padding: 2% 0px ;
}

</style>