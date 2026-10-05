<template>
    <section class="about-banner">
        <div class="about-text">
            <h1>test Us</h1>

            <p>
                test
                test
            </p>

            <a :href="route('about-us')" class="read-more">
                Read More
            </a>
        </div>

        <div class="about-image">
            <img src="/images/67872.jpg" alt="Motorcycle parts" />
        </div>
    </section>
</template>

<style scoped>
.about-banner {
    display: flex;
    align-items: center;
    background: #222;
    color: white;
}

.about-text,
.about-image {
    width: 50%;
}

.about-text {
    padding: 40px;
}

.about-text h1 {
    font-size: 40px;
}

.about-image img {
    display: block;
    width: 100%;
    height: 300px;
    object-fit: cover;
}

.read-more {
    display: inline-block;
    margin-top: 12px;
    padding: 10px 20px;
    border-radius: 4px;
    background: #333;
    color: white;
    text-decoration: none;
}

@media (max-width: 767px) {
    .about-banner {
        flex-direction: column;
    }

    .about-text,
    .about-image {
        width: 100%;
    }
}
</style>