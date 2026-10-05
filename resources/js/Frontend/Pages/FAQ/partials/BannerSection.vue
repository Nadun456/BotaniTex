```vue
<template>
    <section>
        <div class="hero">
            <h1 class="text-light fs-1 fw-semibold text-uppercase">
                faq
            </h1>

            <h2 class="text-light fs-4 fw-semibold text-uppercase">
                demo website
            </h2>
        </div>
    </section>
</template>

<script>
export default {
    components: {},
};
</script>

<style scoped>
.hero {
    background: url("https://via.placeholder.com/1200x600")
        no-repeat center center;
    justify-content: center;
    background-size: cover;
    flex-direction: column;
    align-items: center;
    display: flex;
    height: 50vh;
    width: 100%;
}

.down {
    text-align: center;
    position: absolute;
    opacity: 0.6;
    height: 40px;
    width: 100%;
    bottom: 0;
}

.form-control:focus {
    border-color: #000;
    box-shadow: none;
}
</style>
```
