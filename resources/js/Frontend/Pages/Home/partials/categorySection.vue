<template>
    <section class="container my-5">
        <div class="row">
            <!-- Browse by Make -->
            <div class="col-md-6 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2>Browse by Make</h2>
                    <Link :href="route('make')">Show All →</Link>
                </div>

                <div class="row text-center">
                    <div
                        v-for="make in bikeManufactures"
                        :key="make.id"
                        class="col-6 col-md-3 mb-3"
                    >
                        <Link
                            :href="route('brows.product', { manufacture: make.id })"
                            class="text-decoration-none text-dark"
                        >
                            <img
                                v-if="make.media && make.media.length"
                                :src="make.media[0].original_url"
                                :alt="make.name"
                                class="category-image"
                            />
                            <p>{{ make.name }}</p>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Browse by Model -->
            <div class="col-md-6 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2>Browse by Model</h2>
                    <Link :href="route('model')">Show All →</Link>
                </div>

                <div class="row text-center">
                    <div
                        v-for="model in bikeModel"
                        :key="model.id"
                        class="col-6 col-md-3 mb-3"
                    >
                        <Link
                            :href="route('brows.product', { model: model.id })"
                            class="text-decoration-none text-dark"
                        >
                            <img
                                v-if="model.media && model.media.length"
                                :src="model.media[0].original_url"
                                :alt="model.name"
                                class="category-image"
                            />
                            <p>{{ model.name }}</p>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";

export default {
    components: {
        Link,
    },

    props: {
        bikeManufactures: {
            type: Array,
            default: () => [],
        },
        bikeModel: {
            type: Array,
            default: () => [],
        },
    },
};
</script>

<style scoped>
.category-image {
    width: 100%;
    height: 100px;
    object-fit: contain;
}

h2 {
    font-size: 1.4rem;
}

p {
    margin-top: 8px;
    font-weight: 600;
}
</style>